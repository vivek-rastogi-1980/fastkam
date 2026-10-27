<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\PaytmChecksum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FranchiseRegistrationController extends Controller
{
    /**
     * Show the franchise registration form.
     */
    public function showForm(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'This invite link is invalid or has expired.');
        }

        return view('auth.franchise_register');
    }

    /**
     * Handle the registration form submission and initiate payment with Paytm.
     */
    public function submitForm(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'This invite link is invalid or has expired.');
        }

        // Validate the request with the specific password policy
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:15'],
            'dob' => ['nullable', 'date'],
            'gov_id_type' => ['nullable', 'string', 'max:50'],
            'gov_id_number' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z].*[A-Z]/', // At least 2 capital letters
                'regex:/[\W_]/', // At least 1 special character
                'confirmed'
            ],
        ], [
            'password.regex' => 'The password must contain at least two uppercase letters and one special character.',
        ]);

        // Create the user with 'pending_payment' status
        $user = User::create([
            'name' => $request->name,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'dob' => $request->filled('dob') ? $request->dob : null,
            'gov_id_type' => $request->filled('gov_id_type') ? $request->gov_id_type : null,
            'gov_id_number' => $request->filled('gov_id_number') ? $request->gov_id_number : null,
            'address' => $request->filled('address') ? $request->address : null,
            'password' => Hash::make($request->password),
            'role' => 'franchise',
            'status' => 'pending_payment',
            'wallet_balance' => 0,
            'api_key' => 'live_pk_' . Str::random(40),
            'api_secret' => 'live_sk_' . Str::random(60),
        ]);

        $user->ensureDefaultServicePrices();

        // Paytm Payment Gateway Configuration
        $mid = config('services.paytm.merchant_id', env('PAYTM_MERCHANT_ID', 'GCoMIo80985644180515'));
        $merchantKey = config('services.paytm.merchant_key', env('PAYTM_MERCHANT_KEY', 'IWUX2@WT_eM6w%P&'));
        $website = config('services.paytm.website', env('PAYTM_WEBSITE', 'DEFAULT'));
        $industryType = config('services.paytm.industry_type', env('PAYTM_INDUSTRY_TYPE', 'Retail'));
        $channelId = config('services.paytm.channel_id_web', env('PAYTM_CHANNEL_ID_WEB', 'WEB'));
        
        $callbackUrl = config('services.paytm.callback_url');
        if (empty($callbackUrl) || (app()->environment('production') && str_contains($callbackUrl, 'localhost'))) {
            $callbackUrl = route('franchise.payment.callback');
        }
        if ((app()->environment('production') || request()->isSecure() || str_starts_with((string) config('app.url'), 'https://')) && str_starts_with($callbackUrl, 'http://')) {
            $callbackUrl = 'https://' . substr($callbackUrl, 7);
        }

        $orderId = 'ORD_' . time() . '_' . $user->id;
        $txnAmount = config('services.paytm.franchise_fee', env('FRANCHISE_FEE', '100.00'));

        $paytmParams = [
            'MID' => trim($mid),
            'WEBSITE' => trim($website),
            'INDUSTRY_TYPE_ID' => trim($industryType),
            'CHANNEL_ID' => trim($channelId),
            'ORDER_ID' => $orderId,
            'CUST_ID' => 'CUST_' . $user->id,
            'TXN_AMOUNT' => number_format((float) $txnAmount, 2, '.', ''),
            'CALLBACK_URL' => $callbackUrl,
        ];

        // Format clean 10-digit mobile number if available
        if (!empty($user->phone)) {
            $cleanedPhone = preg_replace('/[^0-9]/', '', $user->phone);
            if (strlen($cleanedPhone) >= 10) {
                $paytmParams['MOBILE_NO'] = substr($cleanedPhone, -10);
            }
        }

        // Include email if available
        if (!empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            $paytmParams['EMAIL'] = trim($user->email);
        }

        // Generate checksum signature with merchant key
        $checksum = PaytmChecksum::generateSignature($paytmParams, $merchantKey);
        $paytmParams['CHECKSUMHASH'] = $checksum;

        $environment = config('services.paytm.environment', env('PAYTM_ENVIRONMENT', 'production'));
        $txnUrl = $environment === 'production'
            ? 'https://securegw.paytm.in/order/process'
            : 'https://securegw-stage.paytm.in/order/process';

        // Store order details in session for cross-verification
        session([
            'registration_order_id' => $orderId,
            'registration_user_id' => $user->id,
            'registration_txn_amount' => $txnAmount,
        ]);

        return view('auth.franchise_payment_redirect', compact('txnUrl', 'paytmParams'));
    }

    /**
     * Handle payment callback from Paytm Payment Gateway.
     */
    public function paymentCallback(Request $request)
    {
        $paytmParams = $request->all();
        Log::info('Paytm Payment Callback Received', $paytmParams);

        $checksum = $request->input('CHECKSUMHASH', '');
        $merchantKey = config('services.paytm.merchant_key', env('PAYTM_MERCHANT_KEY', 'IWUX2@WT_eM6w%P&'));

        $isValidChecksum = false;
        if (!empty($checksum)) {
            $isValidChecksum = PaytmChecksum::verifySignature($paytmParams, $merchantKey, $checksum);
        }

        $status = $request->input('STATUS');
        $respCode = $request->input('RESPCODE');
        $orderId = $request->input('ORDERID');
        $respMsg = $request->input('RESPMSG', 'Payment transaction failed or cancelled.');

        Log::info('Paytm Verification Result', [
            'order_id' => $orderId,
            'status' => $status,
            'resp_code' => $respCode,
            'checksum_valid' => $isValidChecksum,
        ]);

        if ($isValidChecksum && $status === 'TXN_SUCCESS' && $respCode === '01') {
            // Find user associated with this order
            $userId = session('registration_user_id');

            if (!$userId && $orderId) {
                $parts = explode('_', $orderId);
                $userId = end($parts);
            }

            $user = null;
            if ($userId) {
                $user = User::find($userId);
                if ($user) {
                    if ($user->status === 'pending_payment') {
                        $user->status = 'active';
                        $user->save();
                    }

                    // Log the user in
                    auth()->login($user);
                }
            }

            return view('auth.franchise_payment_success', [
                'user' => $user,
                'paytmParams' => $paytmParams,
                'orderId' => $orderId,
                'txnId' => $request->input('TXNID'),
                'txnAmount' => $request->input('TXNAMOUNT', '100.00'),
                'txnDate' => $request->input('TXNDATE'),
                'paymentMode' => $request->input('PAYMENTMODE'),
                'bankName' => $request->input('BANKNAME'),
                'respMsg' => $respMsg,
            ]);
        }

        // Handle Payment Failure
        $userId = session('registration_user_id');
        if (!$userId && $orderId) {
            $parts = explode('_', $orderId);
            $userId = end($parts);
        }
        $user = $userId ? User::find($userId) : null;

        return view('auth.franchise_payment_failed', [
            'user' => $user,
            'paytmParams' => $paytmParams,
            'orderId' => $orderId,
            'status' => $status,
            'respCode' => $respCode,
            'respMsg' => $respMsg,
            'checksumValid' => $isValidChecksum,
        ]);
    }
}
