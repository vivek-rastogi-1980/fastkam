<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WalletController extends Controller
{
    private const RECHPAY_IV = '@@@@&&&&####$$$$';

    // Production minimum. Testing ke time 1 kar sakte ho.
    private const MIN_AMOUNT = 1;

    /**
     * Display the Add Wallet Balance / Recharge Wallet page.
     * Payment ke baad callback yahin signed URL ke saath redirect karta hai.
     */
    public function addBalance(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        $presetAmounts = [
            1  => ['amount' => 1,  'label' => 'Basic'],
            200  => ['amount' => 200,  'label' => 'Popular'],
            500  => ['amount' => 500,  'label' => 'Value'],
            1000 => ['amount' => 1000, 'label' => 'Best Value'],
            2000 => ['amount' => 2000, 'label' => 'Premium'],
            5000 => ['amount' => 5000, 'label' => 'Enterprise'],
        ];

        // Payment result (sirf valid signed URL se)
        $paymentResult = null;
        if ($request->query('payment') && $request->hasValidSignature()) {
            $paymentResult = [
                'status'  => $request->query('payment') === 'success' ? 'success' : 'failed',
                'message' => (string) $request->query('msg', ''),
                'order'   => (string) $request->query('order', ''),
            ];
        }

        return view('wallet.add_balance', [
            'user'          => $user,
            'presetAmounts' => $presetAmounts,
            'minAmount'     => self::MIN_AMOUNT,
            'paymentResult' => $paymentResult,
        ]);
    }

    /**
     * Create order and redirect to ApnaCSC payment gateway.
     */
    public function processPayment(Request $request): View|RedirectResponse
    {
        $min = self::MIN_AMOUNT;

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:' . $min],
            'mobile' => ['nullable', 'string', 'max:20'],
        ], [
            'amount.min'      => "Minimum recharge amount is ₹{$min}.",
            'amount.required' => 'Please enter or select a recharge amount.',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $amount = (float) $validated['amount'];
        $txnAmountStr = ((int) $amount == $amount) ? (string) ((int) $amount) : number_format($amount, 2, '.', '');

        $mobile = substr(preg_replace('/\D/', '', (string) ($request->input('mobile') ?: $user->phone)), -10);
        if (!preg_match('/^[6-9][0-9]{9}$/', $mobile)) {
            return redirect()->back()->withInput()
                ->withErrors(['mobile' => 'Please enter a valid 10 digit mobile number.']);
        }
        if (empty($user->phone)) {
            try {
                $user->phone = $mobile;
                $user->save();
            } catch (\Throwable $e) {
                Log::notice('Could not save user phone: ' . $e->getMessage());
            }
        }
        $email = !empty($user->email) ? $user->email : 'support@fastkam.in';

        $orderId = 'ORD' . now()->format('YmdHis') . 'U' . $user->id . Str::upper(Str::random(5));

        $order = [
            'user_id'    => $user->id,
            'order_id'   => $orderId,
            'amount'     => $amount,
            'created_at' => now()->toDateTimeString(),
        ];
        Cache::put('apnacsc_order_' . $orderId, $order, now()->addHours(24));

        if (config('services.apnacsc.test_mode') && app()->environment('local')) {
            $this->creditWallet($order, 'TEST_' . strtoupper(uniqid()), 'SUCCESS', 'Test mode', []);
            return redirect()->back()->with('success', 'Test recharge credited.');
        }

        $cfg = config('services.apnacsc');
        abort_if(
            empty($cfg['upiuid']) || empty($cfg['token']) || empty($cfg['secret']),
            500,
            'Payment gateway is not configured.'
        );

        // FIX: hamesha apne route se URL banao (.env me /public wala galat URL ignore).
        $callbackUrl = route('wallet.payment.callback');
        if (app()->environment('production')) {
            $callbackUrl = preg_replace('/^http:/', 'https:', $callbackUrl);
        }
        $callbackUrl .= (str_contains($callbackUrl, '?') ? '&' : '?') . 'orderId=' . urlencode($orderId);

        $params = [
            'upiuid'       => $cfg['upiuid'],
            'token'        => $cfg['token'],
            'orderId'      => $orderId,
            'txnAmount'    => $txnAmountStr,
            'txnNote'      => $orderId,
            'callback_url' => $callbackUrl,
            'cust_Mobile'  => $mobile,
            'cust_Email'   => $email,
        ];
        $params['checksum'] = self::generateSignature($params, $cfg['secret']);

        Log::info('ApnaCSC Payment Initiated', [
            'user_id'      => $user->id,
            'order_id'     => $orderId,
            'amount'       => $amount,
            'txnAmount'    => $txnAmountStr,
            'mobile_tail'  => substr($mobile, -4),
            'callback_url' => $callbackUrl,
        ]);

        return view('wallet.redirect', [
            'apnacscParams' => $params,
            'processUrl'    => $cfg['url'],
            'orderId'       => $orderId,
            'amount'        => $amount,
            'user'          => $user,
        ]);
    }

    /**
     * Callback from ApnaCSC.
     *
     * IMPORTANT: Yeh route session/cookie middleware ke bina chalna chahiye
     * (routes/web.php dekho). Isme view render nahi hota, sirf redirect hota hai,
     * taaki user ke login session ko koi touch na kare.
     */
    public function paymentCallback(Request $request): JsonResponse|RedirectResponse
    {
        Log::info('ApnaCSC callback raw', [
            'method'     => $request->method(),
            'query'      => $request->query(),
            'status'     => $request->input('status'),
            'message'    => $request->input('message'),
            'has_hash'   => $request->filled('hash'),
        ]);

        $secret = (string) config('services.apnacsc.secret');
        $status = strtoupper((string) $request->input('status'));

        $fail = function (string $message, ?string $orderId = null) use ($request) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'FAILED', 'message' => $message], 400);
            }
            return $this->resultRedirect('failed', $message, $orderId);
        };

        if ($status !== 'SUCCESS' || !$request->filled('hash') || $secret === '') {
            Log::warning('ApnaCSC callback: not success', ['status' => $status]);
            return $fail(
                (string) ($request->input('message') ?: 'Payment was not completed.'),
                (string) $request->query('orderId', '')
            );
        }

        $decrypted = self::hashDecrypt((string) $request->input('hash'), $secret);
        $payload = $decrypted !== false ? json_decode($decrypted, true) : null;

        Log::info('ApnaCSC callback payload', ['payload' => $payload]);

        if (!is_array($payload)
            || ($payload['txnStatus'] ?? null) !== 'TXN_SUCCESS'
            || empty($payload['payee_vpa'])) {
            return $fail('Payment could not be verified.', (string) $request->query('orderId', ''));
        }

        $orderId = (string) ($payload['sender_note'] ?? '');
        $order = $orderId !== '' ? Cache::get('apnacsc_order_' . $orderId) : null;

        if (!$order) {
            $alreadyCredited = $orderId !== '' && WalletTransaction::where('reference_id', $orderId)
                ->where('type', 'credit')->exists();

            if ($alreadyCredited) {
                return $request->expectsJson()
                    ? response()->json(['status' => 'SUCCESS', 'message' => 'Already credited.'])
                    : $this->resultRedirect('success', 'Wallet already recharged.', $orderId);
            }

            Log::critical('ApnaCSC: verified payment but order not found - manual reconcile needed', [
                'order_id' => $orderId,
                'payload'  => $payload,
            ]);
            return $fail('Payment received but order not found. Please contact support with Order ID: ' . $orderId, $orderId);
        }

        $paidAmount = (float) ($payload['txnAmount'] ?? 0);
        if (abs($paidAmount - (float) $order['amount']) > 0.01) {
            Log::critical('ApnaCSC: amount mismatch', [
                'order_id' => $orderId,
                'paid'     => $paidAmount,
                'expected' => $order['amount'],
            ]);
            return $fail('Amount mismatch. Please contact support.', $orderId);
        }

        $txnId = (string) ($payload['txnId'] ?? $orderId);
        $this->creditWallet($order, $txnId, 'SUCCESS', 'Payment verified', $payload);

        if ($request->expectsJson()) {
            return response()->json(['status' => 'SUCCESS', 'message' => 'Wallet recharge credited successfully.']);
        }

        return $this->resultRedirect('success', 'Wallet recharged successfully.', $orderId);
    }

    /**
     * Signed URL par redirect (session ki zarurat nahi). Browser normal GET se
     * wapas aata hai, isliye login cookie sahi chali jaati hai.
     */
    private function resultRedirect(string $status, string $message, ?string $orderId = null): RedirectResponse
    {
        $url = URL::signedRoute('wallet.add_balance', [
            'payment' => $status,
            'msg'     => Str::limit($message, 150, ''),
            'order'   => $orderId ?: '',
        ]);

        if (app()->environment('production')) {
            // signed URL https par hi bana rahe, scheme force mat badlo (signature toot jayega)
            // APP_URL=https://fastkam.in hona chahiye.
        }

        return redirect()->to($url);
    }

    /**
     * Local-only simulation for development.
     */
    public function simulateSuccess(Request $request): RedirectResponse
    {
        abort_unless(app()->environment('local'), 403, 'Local environment only.');

        $user = Auth::user();
        $amount = (float) $request->input('amount', 1);

        $this->creditWallet([
            'user_id'  => $user->id,
            'order_id' => 'SIM' . now()->format('YmdHis') . 'U' . $user->id . Str::upper(Str::random(5)),
            'amount'   => $amount,
        ], 'SIM_' . strtoupper(uniqid()), 'SUCCESS', 'Local simulation', []);

        return redirect()->back()->with('success', 'Simulated recharge credited.');
    }

    /**
     * Idempotent wallet credit. Calling twice for the same order never double credits.
     */
    private function creditWallet(array $order, string $txnId, string $status, string $message, array $raw): bool
    {
        return DB::transaction(function () use ($order, $txnId, $status, $message, $raw) {
            $user = User::whereKey($order['user_id'])->lockForUpdate()->firstOrFail();

            $already = WalletTransaction::where('reference_id', $order['order_id'])
                ->where('type', 'credit')
                ->exists();
            if ($already) {
                return false;
            }

            $amount  = (float) $order['amount'];
            $opening = (float) $user->wallet_balance;
            $closing = $opening + $amount;

            $user->wallet_balance = $closing;
            $user->save();

            WalletTransaction::create([
                'user_id'         => $user->id,
                'transaction_id'  => 'TXN_APNA_' . strtoupper(uniqid()),
                'type'            => 'credit',
                'amount'          => $amount,
                'opening_balance' => $opening,
                'closing_balance' => $closing,
                'description'     => "Wallet recharge via ApnaCSC UPI: Added {$amount} Tokens (1 INR = 1 Token)",
                'reference_id'    => $order['order_id'],
                'metadata'        => [
                    'gateway'         => 'apnacsc',
                    'gateway_txn_id'  => $txnId,
                    'gateway_status'  => $status,
                    'gateway_message' => $message,
                    'raw_response'    => $raw,
                ],
            ]);

            Cache::forget('apnacsc_order_' . $order['order_id']);

            Log::info('Wallet credited', [
                'user_id'  => $user->id,
                'amount'   => $amount,
                'order_id' => $order['order_id'],
            ]);

            return true;
        });
    }

    // ------------------------------------------------------------------
    // RechPay checksum helpers (AES-128-CBC + SHA256)
    // ------------------------------------------------------------------

    private static function generateSignature(array $params, string $key): string
    {
        $salt = self::randomSalt(4);
        $hash = self::calculateHash(self::paramsToString($params), $salt);

        return openssl_encrypt($hash, 'AES-128-CBC', html_entity_decode($key), 0, self::RECHPAY_IV);
    }

    private static function hashDecrypt(string $bundle, string $password): string|false
    {
        $parts = explode(':', $bundle);
        if (count($parts) !== 3) {
            return false;
        }
        [$iv, $salt, $encrypted] = $parts;
        $key = hash('sha256', sha1($password) . $salt);

        return @openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
    }

    private static function paramsToString(array $params): string
    {
        ksort($params);
        $params = array_map(
            fn ($v) => ($v !== null && strtolower((string) $v) !== 'null') ? (string) $v : '',
            $params
        );

        return implode('|', $params);
    }

    private static function calculateHash(string $params, string $salt): string
    {
        return hash('sha256', $params . '|' . $salt) . $salt;
    }

    private static function randomSalt(int $length): string
    {
        $chars = '9876543210ZYXWVUTSRQPONMLKJIHGFEDCBAabcdefghijklmnopqrstuvwxyz!@#$&_';
        $out = '';
        for ($i = 0; $i < $length; $i++) {
            $out .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $out;
    }
}