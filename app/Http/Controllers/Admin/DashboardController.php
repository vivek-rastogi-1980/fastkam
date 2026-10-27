<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiLog;
use App\Models\FranchiseServicePrice;
use App\Models\Service;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * "Our Services" catalog page.
     * Displays responsive cards and execution details for all 6 IDSPay integrated services.
     */
    public function services(): View
    {
        $services = Service::orderBy('id', 'asc')->get();
        $user = Auth::user();

        // If franchise user, resolve any custom configured service prices and exempt from GST
        if ($user && $user->isFranchise()) {
            $customPrices = FranchiseServicePrice::where('user_id', $user->id)
                ->pluck('price', 'service_id');

            foreach ($services as $service) {
                $service->effective_cost = isset($customPrices[$service->id])
                    ? (float) $customPrices[$service->id]
                    : (float) $service->default_franchise_price;
                $service->gst_rate = 0.00;
                $service->gst_amount = 0.00;
                $service->total_cost = (float) $service->effective_cost;
            }
            $gstRate = 0.00;
            $gstEnabled = false;
        } else {
            foreach ($services as $service) {
                $service->effective_cost = (float) $service->super_admin_cost;
            }

            $gstRate = SystemSetting::getGstRate();
            $gstEnabled = SystemSetting::isGstEnabled();

            foreach ($services as $service) {
                $base = (float) $service->effective_cost;
                $gstAmount = ($gstEnabled && $gstRate > 0) ? round($base * ($gstRate / 100), 2) : 0.00;
                $service->gst_rate = $gstRate;
                $service->gst_amount = $gstAmount;
                $service->total_cost = round($base + $gstAmount, 2);
            }
        }

        $stats = [
            'total_services' => $services->count(),
            'active_services' => $services->where('is_active', true)->count(),
            'total_franchises' => User::where('role', 'franchise')->count(),
            'api_calls_today' => ($user->role === 'franchise')
                ? ApiLog::where('user_id', $user->id)->whereDate('created_at', today())->count()
                : ApiLog::whereDate('created_at', today())->count(),
        ];

        return view('admin.services.index', compact('services', 'user', 'stats', 'gstRate', 'gstEnabled'));
    }

    /**
     * Show Update Password form.
     */
    public function editPassword(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $currentPassword = $user->plain_password 
            ?? session('current_plain_password') 
            ?? (Hash::check('password123', $user->password) ? 'password123' : (Hash::check('password', $user->password) ? 'password' : (Hash::check('franchise123', $user->password) ? 'franchise123' : (Hash::check('admin123', $user->password) ? 'admin123' : ''))));

        return view('admin.profile.password', compact('user', 'currentPassword'));
    }

    /**
     * Process Update Password request.
     * Validates current password and enforces new password requirements.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.current_password' => 'Your current password does not match our records.',
            'password.different' => 'New password must be different from your current password.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->plain_password = $request->password;
        $user->save();
        session(['current_plain_password' => $request->password]);

        return redirect()->route('admin.password.edit')
            ->with('success', 'Your password has been updated securely.');
    }

    /**
     * Franchise Accounts Management.
     * Admin view to inspect and create franchise child accounts.
     */
    public function franchises(Request $request): View
    {
        $statusFilter = $request->get('status', 'all');

        $query = User::where('role', 'franchise')
            ->withCount(['apiLogs', 'walletTransactions'])
            ->latest();

        if ($statusFilter === 'active') {
            $query->where('status', 'active');
        } elseif ($statusFilter === 'inactive') {
            $query->where(function ($q) {
                $q->where('status', 'inactive')
                  ->orWhere('status', '!=', 'active');
            });
        } elseif ($statusFilter === 'pending_payment') {
            $query->where('status', 'pending_payment');
        }

        $franchises = $query->paginate(15)->appends($request->query());

        $counts = [
            'all' => User::where('role', 'franchise')->count(),
            'active' => User::where('role', 'franchise')->where('status', 'active')->count(),
            'inactive' => User::where('role', 'franchise')->where('status', '!=', 'active')->count(),
        ];

        $services = Service::orderBy('id', 'asc')->get();

        return view('admin.franchises.index', compact('franchises', 'services', 'counts', 'statusFilter'));
    }

    /**
     * Toggle or update franchise account status (active / inactive).
     */
    public function toggleFranchiseStatus(Request $request, User $franchise): JsonResponse|RedirectResponse
    {
        $request->validate([
            'status' => ['nullable', 'string', 'in:active,inactive,pending_payment,suspended'],
        ]);

        if ($request->filled('status')) {
            $newStatus = $request->status;
        } else {
            $newStatus = ($franchise->status === 'active') ? 'inactive' : 'active';
        }

        $franchise->status = $newStatus;
        $franchise->save();

        $statusLabel = ucfirst(str_replace('_', ' ', $franchise->status));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $franchise->status,
                'status_label' => $statusLabel,
                'message' => "Franchise '{$franchise->name}' is now {$statusLabel}.",
            ]);
        }

        return redirect()->back()->with('success', "Franchise '{$franchise->name}' status changed to {$statusLabel}.");
    }

    /**
     * Generate signed URL for franchise registration.
     */
    public function generateInvite(Request $request): RedirectResponse
    {
        $url = \Illuminate\Support\Facades\URL::signedRoute('franchise.register');
        return redirect()->back()->with('success', 'Invite URL generated successfully!')->with('invite_url', $url);
    }

    /**
     * Store new Franchise with personal details and generate API credentials.
     * Automatically assigns default prices (managed in Main Administrator Panel) for each service.
     */
    public function storeFranchise(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'dob' => ['nullable', 'date'],
            'gov_id_type' => ['nullable', 'string', 'in:aadhaar,pan,voter_id,passport'],
            'gov_id_number' => ['nullable', 'string', 'max:50'],
            'initial_tokens' => ['nullable', 'numeric', 'min:0'],
            'prices' => ['nullable', 'array'],
            'prices.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        $apiKey = 'vle_live_' . Str::random(32);
        $apiSecret = 'sec_' . Str::random(40);
        $initialTokens = (float) ($validated['initial_tokens'] ?? 0.00);

        $franchise = User::create([
            'name' => $validated['name'],
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'plain_password' => $validated['password'],
            'role' => 'franchise',
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'gov_id_type' => $validated['gov_id_type'] ?? null,
            'gov_id_number' => $validated['gov_id_number'] ?? null,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'wallet_balance' => $initialTokens,
            'status' => 'active',
        ]);

        // When a new franchise is added, default prices for each service are applicable and assigned at creation
        $services = Service::all();
        foreach ($services as $service) {
            $servicePrice = (isset($validated['prices'][$service->id]) && is_numeric($validated['prices'][$service->id]))
                ? (float) $validated['prices'][$service->id]
                : (float) $service->default_franchise_price;

            FranchiseServicePrice::create([
                'user_id' => $franchise->id,
                'service_id' => $service->id,
                'price' => $servicePrice,
            ]);
        }

        if ($initialTokens > 0) {
            WalletTransaction::create([
                'user_id' => $franchise->id,
                'transaction_id' => 'TXN_INIT_' . strtoupper(uniqid()),
                'type' => 'credit',
                'amount' => $initialTokens,
                'opening_balance' => 0.00,
                'closing_balance' => $initialTokens,
                'description' => "Initial onboarding wallet token allocation ({$initialTokens} Tokens)",
                'reference_id' => 'ADMIN_ALLOC_' . time(),
                'metadata' => ['allocated_by' => Auth::id()],
            ]);
        }

        return redirect()->route('admin.franchises')
            ->with('success', "Franchise '{$franchise->name}' created successfully with default service prices! API Key: {$apiKey}");
    }

    /**
     * API Transaction & Execution Logs.
     */
    public function logs(Request $request): View
    {
        $user = Auth::user();
        $query = ApiLog::with(['user', 'service'])->latest();

        // If franchise user, strictly scope to their own API logs
        if ($user->role === 'franchise') {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('service_code')) {
            $query->where('service_code', $request->service_code);
        }

        // Date Range Filtering (supports '2026-11-02 to 2026-11-05', '2nd Nov 26 to 5 Nov 26', separate from/to dates, or today)
        $fromDate = null;
        $toDate = null;
        $parsedFrom = null;
        $parsedTo = null;
        $dateRangeText = null;
        $dateRangeValue = null;

        if ($request->filled('date_range')) {
            $rangeStr = trim($request->date_range);
            if (str_contains($rangeStr, ' to ')) {
                $parts = explode(' to ', $rangeStr);
                $fromDate = trim($parts[0]);
                $toDate = trim($parts[1]);
            } elseif (str_contains($rangeStr, ' - ')) {
                $parts = explode(' - ', $rangeStr);
                $fromDate = trim($parts[0]);
                $toDate = trim($parts[1]);
            } else {
                $fromDate = $rangeStr;
                $toDate = $rangeStr;
            }
        } elseif ($request->filled('from_date') || $request->filled('to_date')) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
        } elseif ($request->filled('today') || $request->has('today')) {
            $fromDate = today()->toDateString();
            $toDate = today()->toDateString();
        }

        if ($fromDate) {
            try {
                $parsedFrom = \Carbon\Carbon::parse($fromDate)->startOfDay();
                $query->where('created_at', '>=', $parsedFrom);
            } catch (\Exception $e) {
                // Ignore parse errors gracefully
            }
        }

        if ($toDate) {
            try {
                $parsedTo = \Carbon\Carbon::parse($toDate)->endOfDay();
                $query->where('created_at', '<=', $parsedTo);
            } catch (\Exception $e) {
                // Ignore parse errors gracefully
            }
        }

        if ($parsedFrom && $parsedTo) {
            $dateRangeValue = $parsedFrom->format('Y-m-d') . ' to ' . $parsedTo->format('Y-m-d');
            if ($parsedFrom->isSameDay($parsedTo)) {
                $dateRangeText = $parsedFrom->isToday() ? 'Today (' . $parsedFrom->format('jS M y') . ')' : $parsedFrom->format('jS M y');
            } else {
                $dateRangeText = $parsedFrom->format('jS M y') . ' to ' . $parsedTo->format('jS M y');
            }
        } elseif ($parsedFrom) {
            $dateRangeValue = $parsedFrom->format('Y-m-d');
            $dateRangeText = 'From ' . $parsedFrom->format('jS M y');
        } elseif ($parsedTo) {
            $dateRangeValue = $parsedTo->format('Y-m-d');
            $dateRangeText = 'Up to ' . $parsedTo->format('jS M y');
        }

        $logs = $query->paginate(20)->withQueryString();
        $services = Service::all();

        return view('admin.logs.index', compact(
            'logs',
            'services',
            'user',
            'dateRangeValue',
            'dateRangeText',
            'parsedFrom',
            'parsedTo'
        ));
    }

    /**
     * Recharge Franchise Wallet Tokens (1 INR = 1 Token).
     */
    public function rechargeWallet(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'franchise_id' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'reference_id' => ['nullable', 'string', 'max:100'],
        ]);

        /** @var User $franchise */
        $franchise = User::findOrFail($validated['franchise_id']);
        $amount = (float) $validated['amount'];

        $openingBalance = (float) $franchise->wallet_balance;
        $closingBalance = $openingBalance + $amount;

        $franchise->wallet_balance = $closingBalance;
        $franchise->save();

        WalletTransaction::create([
            'user_id' => $franchise->id,
            'transaction_id' => 'TXN_REC_' . strtoupper(uniqid()),
            'type' => 'credit',
            'amount' => $amount,
            'opening_balance' => $openingBalance,
            'closing_balance' => $closingBalance,
            'description' => "Wallet recharge: Added {$amount} Tokens (1 INR = 1 Token)",
            'reference_id' => $validated['reference_id'] ?? 'PGW_' . strtoupper(uniqid()),
            'metadata' => ['recharged_by' => Auth::id()],
        ]);

        return back()->with('success', "Successfully credited {$amount} Tokens to {$franchise->name}'s wallet.");
    }

    /**
     * View or get JSON of services cost for a specific franchise.
     */
    public function franchiseServicesCost(Request $request, User $franchise): View|JsonResponse
    {
        $services = Service::orderBy('id', 'asc')->get();
        $customPrices = FranchiseServicePrice::where('user_id', $franchise->id)->pluck('price', 'service_id');

        $servicesData = $services->map(function ($service) use ($customPrices) {
            $custom = $customPrices[$service->id] ?? null;
            return [
                'id' => $service->id,
                'name' => $service->name,
                'service_code' => $service->service_code,
                'category' => $service->category,
                'description' => $service->description,
                'default_price' => (float) $service->default_franchise_price,
                'custom_price' => $custom !== null ? (float) $custom : null,
                'effective_price' => $custom !== null ? (float) $custom : (float) $service->default_franchise_price,
            ];
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'SUCCESS',
                'franchise' => [
                    'id' => $franchise->id,
                    'name' => $franchise->name,
                    'full_name' => $franchise->full_name,
                    'email' => $franchise->email,
                ],
                'services' => $servicesData,
            ]);
        }

        return view('admin.franchises.services_cost', [
            'franchise' => $franchise,
            'services' => $services,
            'customPrices' => $customPrices,
        ]);
    }

    /**
     * Update services cost / prices for a franchise.
     */
    public function updateFranchiseServicesCost(Request $request, User $franchise): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'prices' => ['required', 'array'],
            'prices.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        foreach ($validated['prices'] as $serviceId => $price) {
            if (!Service::where('id', $serviceId)->exists()) {
                continue;
            }

            if ($price !== null && $price !== '') {
                FranchiseServicePrice::updateOrCreate(
                    [
                        'user_id' => $franchise->id,
                        'service_id' => $serviceId,
                    ],
                    [
                        'price' => (float) $price,
                    ]
                );
            } else {
                // If blank/null, remove custom price override to revert to default catalog cost
                FranchiseServicePrice::where('user_id', $franchise->id)
                    ->where('service_id', $serviceId)
                    ->delete();
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Services cost updated successfully for {$franchise->name}.",
            ]);
        }

        return redirect()->route('admin.franchises')
            ->with('success', "Services cost updated successfully for {$franchise->name}.");
    }

    /**
     * Super Admin: View all services and their default franchise prices (applicable for new franchises only).
     */
    public function servicesPricing(Request $request): View|JsonResponse
    {
        $services = Service::with(['franchisePrices.user'])
            ->withCount('franchisePrices')
            ->orderBy('id', 'asc')
            ->get();

        $stats = [
            'total_services' => $services->count(),
            'active_services' => $services->where('is_active', true)->count(),
            'avg_price' => $services->count() > 0 ? round((float) $services->avg('default_franchise_price'), 2) : 0.00,
            'min_price' => $services->count() > 0 ? (float) $services->min('default_franchise_price') : 0.00,
            'max_price' => $services->count() > 0 ? (float) $services->max('default_franchise_price') : 0.00,
            'total_overrides' => FranchiseServicePrice::count(),
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'SUCCESS',
                'stats' => $stats,
                'services' => $services->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'name' => $s->name,
                        'service_code' => $s->service_code,
                        'category' => $s->category,
                        'description' => $s->description,
                        'default_franchise_price' => (float) $s->default_franchise_price,
                        'super_admin_cost' => (float) $s->super_admin_cost,
                        'token_cost' => (float) $s->default_franchise_price,
                        'is_active' => (bool) $s->is_active,
                        'endpoint_path' => $s->endpoint_path,
                        'franchise_overrides_count' => $s->franchise_prices_count,
                        'updated_at' => $s->updated_at?->toIso8601String(),
                    ];
                }),
            ]);
        }

        return view('admin.services.pricing', compact('services', 'stats'));
    }

    /**
     * Super Admin: Bulk update default franchise prices (applicable for new franchises only).
     */
    public function updateServicesPricing(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'prices' => ['required', 'array'],
            'prices.*' => ['required', 'numeric', 'min:0'],
        ]);

        $updatedCount = 0;
        foreach ($validated['prices'] as $serviceId => $price) {
            $service = Service::find($serviceId);
            if ($service) {
                $service->default_franchise_price = (float) $price;
                $service->token_cost = (float) $price; // Backward compatibility for franchise catalog price

                // Super Admin cost strictly retains IDSPay upstream gateway cost
                if (empty($service->super_admin_cost) || (float)$service->super_admin_cost <= 0) {
                    $service->super_admin_cost = Service::IDSPAY_DEFAULT_PRICES[$service->service_code] ?? (float) $service->super_admin_cost;
                }

                $service->save();
                $updatedCount++;
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Successfully updated default prices for {$updatedCount} services (franchise level).",
                'updated_count' => $updatedCount,
            ]);
        }

        return redirect()->route('admin.services.pricing')
            ->with('success', "Successfully updated default pricing for {$updatedCount} services (franchise level).");
    }

    /**
     * Super Admin: Update default price for a single service (applicable for franchises).
     */
    public function updateSingleServicePrice(Request $request, Service $service): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'token_cost' => ['nullable', 'numeric', 'min:0'],
            'default_franchise_price' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $oldPrice = (float) $service->default_franchise_price;
        $newPrice = (float) ($validated['default_franchise_price'] ?? $validated['token_cost'] ?? $validated['price'] ?? 0);

        $service->default_franchise_price = $newPrice;
        $service->token_cost = $newPrice; // Backward compatibility

        // Super Admin cost strictly retains IDSPay upstream gateway cost
        if (empty($service->super_admin_cost) || (float)$service->super_admin_cost <= 0) {
            $service->super_admin_cost = Service::IDSPAY_DEFAULT_PRICES[$service->service_code] ?? (float) $service->super_admin_cost;
        }

        $service->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Default price for '{$service->name}' updated to ₹" . number_format($newPrice, 2) . " Tokens (new franchises only).",
                'service' => [
                    'id' => $service->id,
                    'name' => $service->name,
                    'service_code' => $service->service_code,
                    'old_token_cost' => $oldPrice,
                    'token_cost' => $newPrice,
                    'default_franchise_price' => $newPrice,
                ],
            ]);
        }

        return redirect()->back()
            ->with('success', "Default price for '{$service->name}' updated to ₹" . number_format($newPrice, 2) . " Tokens (new franchises only).");
    }

    /**
     * Super Admin Dedicated Pricing Section: View all services and Super Admin purchase / base costs.
     */
    public function superAdminPricing(Request $request): View|JsonResponse
    {
        $services = Service::orderBy('id', 'asc')->get();
        $superAdmin = Auth::user();

        $stats = [
            'total_services' => $services->count(),
            'active_services' => $services->where('is_active', true)->count(),
            'avg_admin_cost' => $services->count() > 0 ? round((float) $services->avg('super_admin_cost'), 2) : 0.00,
            'min_admin_cost' => $services->count() > 0 ? (float) $services->min('super_admin_cost') : 0.00,
            'max_admin_cost' => $services->count() > 0 ? (float) $services->max('super_admin_cost') : 0.00,
            'admin_wallet_balance' => (float) ($superAdmin->wallet_balance ?? 0.00),
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'SUCCESS',
                'stats' => $stats,
                'services' => $services->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'name' => $s->name,
                        'service_code' => $s->service_code,
                        'category' => $s->category,
                        'description' => $s->description,
                        'super_admin_cost' => (float) $s->super_admin_cost,
                        'default_franchise_price' => (float) $s->default_franchise_price,
                        'is_active' => (bool) $s->is_active,
                        'endpoint_path' => $s->endpoint_path,
                        'updated_at' => $s->updated_at?->toIso8601String(),
                    ];
                }),
            ]);
        }

        return view('admin.services.super_admin_pricing', compact('services', 'stats', 'superAdmin'));
    }

    /**
     * Super Admin Dedicated Pricing Section: Bulk update Super Admin base costs.
     */
    public function updateSuperAdminPricing(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'prices' => ['required', 'array'],
            'prices.*' => ['required', 'numeric', 'min:0'],
        ]);

        $updatedCount = 0;
        foreach ($validated['prices'] as $serviceId => $price) {
            $service = Service::find($serviceId);
            if ($service) {
                $service->super_admin_cost = (float) $price;
                $service->save();
                $updatedCount++;
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Successfully updated Super Admin pricing for {$updatedCount} services.",
                'updated_count' => $updatedCount,
            ]);
        }

        return redirect()->route('admin.services.super_admin_pricing')
            ->with('success', "Successfully updated Super Admin pricing for {$updatedCount} services.");
    }

    /**
     * Super Admin Dedicated Pricing Section: Update single service Super Admin base cost.
     */
    public function updateSingleSuperAdminPrice(Request $request, Service $service): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'super_admin_cost' => ['nullable', 'numeric', 'min:0'],
            'token_cost' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $newPrice = (float) ($validated['super_admin_cost'] ?? $validated['token_cost'] ?? $validated['price'] ?? 0);
        $oldPrice = (float) $service->super_admin_cost;

        $service->super_admin_cost = $newPrice;
        $service->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'SUCCESS',
                'message' => "Super Admin price for '{$service->name}' updated to ₹" . number_format($newPrice, 2) . " Tokens.",
                'service' => [
                    'id' => $service->id,
                    'name' => $service->name,
                    'service_code' => $service->service_code,
                    'old_super_admin_cost' => $oldPrice,
                    'super_admin_cost' => $newPrice,
                ],
            ]);
        }

        return redirect()->route('admin.services.super_admin_pricing')
            ->with('success', "Super Admin price for '{$service->name}' updated to ₹" . number_format($newPrice, 2) . " Tokens.");
    }

    /**
     * Super Admin: View GST Settings & Configuration.
     */
    public function gstSettings(Request $request): View|JsonResponse
    {
        $gstRate = SystemSetting::getGstRate();
        $rawGstRate = (float) SystemSetting::get('gst_rate_percent', 18.00);
        $gstEnabled = SystemSetting::isGstEnabled();
        $services = Service::orderBy('id', 'asc')->get();

        // Calculate GST sample simulation for all services
        $simulation = $services->map(function ($service) use ($gstRate) {
            $base = (float) $service->token_cost;
            $gst = $gstRate > 0 ? round($base * ($gstRate / 100), 2) : 0.00;
            return [
                'id' => $service->id,
                'name' => $service->name,
                'service_code' => $service->service_code,
                'category' => $service->category,
                'base_cost' => $base,
                'gst_amount' => $gst,
                'total_cost' => round($base + $gst, 2),
            ];
        });

        // Fetch recent GST wallet deductions
        $recentTransactions = WalletTransaction::with('user')
            ->where(function ($q) {
                $q->where('description', 'like', '%GST%')
                  ->orWhere('description', 'like', '%tax%');
            })
            ->latest()
            ->take(15)
            ->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'SUCCESS',
                'gst_rate_percent' => $rawGstRate,
                'effective_gst_rate' => $gstRate,
                'gst_enabled' => $gstEnabled,
                'services_simulation' => $simulation,
            ]);
        }

        return view('admin.settings.gst', compact(
            'gstRate',
            'rawGstRate',
            'gstEnabled',
            'services',
            'simulation',
            'recentTransactions'
        ));
    }

    /**
     * Super Admin: Update GST Rate and configuration.
     */
    public function updateGstSettings(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'gst_rate_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'gst_enabled' => ['nullable'],
        ]);

        $gstRate = round((float) $validated['gst_rate_percent'], 2);
        // If checkbox is checked, it will be sent as '1' or 'on', or if boolean passed
        $gstEnabled = $request->has('gst_enabled') && ($request->input('gst_enabled') === '1' || $request->input('gst_enabled') === 'on' || $request->input('gst_enabled') === true || $request->input('gst_enabled') === 'true');

        SystemSetting::set('gst_rate_percent', $gstRate, 'float', 'tax', 'GST rate percentage added to service price during wallet deduction');
        SystemSetting::set('gst_enabled', $gstEnabled ? '1' : '0', 'boolean', 'tax', 'Master switch to enable/disable GST deduction');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'SUCCESS',
                'message' => "GST settings updated successfully. Active rate: {$gstRate}% (" . ($gstEnabled ? 'Enabled' : 'Disabled') . ").",
                'gst_rate_percent' => $gstRate,
                'gst_enabled' => $gstEnabled,
            ]);
        }

        return redirect()->route('admin.settings.gst')
            ->with('success', "GST Settings updated successfully! Current GST rate: {$gstRate}% (" . ($gstEnabled ? 'Active' : 'Disabled') . "). All service wallet deductions will now apply this rate.");
    }

    /**
     * Super Admin: Single-Page Unified Reporting Dashboard.
     * Consolidates high-level KPIs, financial flows, franchise consumption leaderboard,
     * service operational metrics, time-series traffic trends, and real-time audit alerts.
     */
    public function reports(Request $request): View|JsonResponse
    {
        $admin = Auth::user();

        // 1. Time-Range Resolution
        $rangePreset = $request->input('preset', 'last30');
        $fromDate = null;
        $toDate = null;
        $parsedFrom = null;
        $parsedTo = null;
        $dateRangeText = 'Last 30 Days';
        $dateRangeValue = null;

        if ($request->filled('date_range')) {
            $rangeStr = trim($request->date_range);
            if (str_contains($rangeStr, ' to ')) {
                $parts = explode(' to ', $rangeStr);
                $fromDate = trim($parts[0]);
                $toDate = trim($parts[1]);
            } elseif (str_contains($rangeStr, ' - ')) {
                $parts = explode(' - ', $rangeStr);
                $fromDate = trim($parts[0]);
                $toDate = trim($parts[1]);
            } else {
                $fromDate = $rangeStr;
                $toDate = $rangeStr;
            }
        } elseif ($request->filled('from_date') || $request->filled('to_date')) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
        } elseif ($rangePreset === 'today') {
            $fromDate = today()->toDateString();
            $toDate = today()->toDateString();
            $dateRangeText = 'Today (' . today()->format('jS M Y') . ')';
        } elseif ($rangePreset === 'yesterday') {
            $fromDate = today()->subDay()->toDateString();
            $toDate = today()->subDay()->toDateString();
            $dateRangeText = 'Yesterday (' . today()->subDay()->format('jS M Y') . ')';
        } elseif ($rangePreset === 'last7') {
            $fromDate = today()->subDays(6)->toDateString();
            $toDate = today()->toDateString();
            $dateRangeText = 'Last 7 Days (' . today()->subDays(6)->format('jS M') . ' - ' . today()->format('jS M Y') . ')';
        } elseif ($rangePreset === 'month') {
            $fromDate = today()->startOfMonth()->toDateString();
            $toDate = today()->toDateString();
            $dateRangeText = 'This Month (' . today()->format('F Y') . ')';
        } elseif ($rangePreset === 'all') {
            $fromDate = null;
            $toDate = null;
            $dateRangeText = 'All Time';
        } else {
            // Default: Last 30 Days
            $fromDate = today()->subDays(29)->toDateString();
            $toDate = today()->toDateString();
            $dateRangeText = 'Last 30 Days';
        }

        if ($fromDate) {
            try {
                $parsedFrom = \Carbon\Carbon::parse($fromDate)->startOfDay();
            } catch (\Exception $e) {}
        }
        if ($toDate) {
            try {
                $parsedTo = \Carbon\Carbon::parse($toDate)->endOfDay();
            } catch (\Exception $e) {}
        }

        if ($parsedFrom && $parsedTo) {
            $dateRangeValue = $parsedFrom->format('Y-m-d') . ' to ' . $parsedTo->format('Y-m-d');
            if ($parsedFrom->isSameDay($parsedTo)) {
                $dateRangeText = $parsedFrom->isToday() ? 'Today (' . $parsedFrom->format('jS M Y') . ')' : $parsedFrom->format('jS M Y');
            } else {
                $dateRangeText = $parsedFrom->format('jS M Y') . ' to ' . $parsedTo->format('jS M Y');
            }
        } elseif ($parsedFrom) {
            $dateRangeValue = $parsedFrom->format('Y-m-d');
            $dateRangeText = 'From ' . $parsedFrom->format('jS M Y');
        } elseif ($parsedTo) {
            $dateRangeValue = $parsedTo->format('Y-m-d');
            $dateRangeText = 'Up to ' . $parsedTo->format('jS M Y');
        }

        // Base Query with filters
        $logsQuery = ApiLog::query();
        $txnsQuery = WalletTransaction::query();

        if ($parsedFrom) {
            $logsQuery->where('created_at', '>=', $parsedFrom);
            $txnsQuery->where('created_at', '>=', $parsedFrom);
        }
        if ($parsedTo) {
            $logsQuery->where('created_at', '<=', $parsedTo);
            $txnsQuery->where('created_at', '<=', $parsedTo);
        }

        // Optional Franchise Filter
        $selectedFranchiseId = $request->input('franchise_id');
        if ($request->filled('franchise_id')) {
            $logsQuery->where('user_id', $selectedFranchiseId);
            $txnsQuery->where('user_id', $selectedFranchiseId);
        }

        // Optional Service Filter
        $selectedServiceCode = $request->input('service_code');
        if ($request->filled('service_code')) {
            $logsQuery->where('service_code', $selectedServiceCode);
        }

        // 2. High-Level KPI Aggregations
        $totalLogs = (clone $logsQuery)->count();
        $successLogs = (clone $logsQuery)->where('status', 'SUCCESS')->count();
        $failedLogs = (clone $logsQuery)->where('status', '!=', 'SUCCESS')->count();
        $insufficientFundsLogs = (clone $logsQuery)->where('status', 'INSUFFICIENT_FUNDS')->count();
        $successRate = $totalLogs > 0 ? round(($successLogs / $totalLogs) * 100, 1) : 100.0;

        $totalTokensDeducted = (float) (clone $logsQuery)->sum('tokens_deducted');
        $avgLatency = (clone $logsQuery)->whereNotNull('execution_time_ms')->avg('execution_time_ms');
        $avgLatency = $avgLatency ? round((float) $avgLatency, 1) : 0;

        // GST Statistics
        $gstRate = SystemSetting::getGstRate();
        $gstEnabled = SystemSetting::isGstEnabled();
        $gstCollectedEstimated = ($gstEnabled && $gstRate > 0) ? round($totalTokensDeducted * ($gstRate / 100), 2) : 0.00;

        // Franchise ecosystem KPIs
        $totalFranchises = User::where('role', 'franchise')->count();
        $activeFranchises = User::where('role', 'franchise')->where('status', 'active')->count();
        $totalFranchiseWalletFloat = (float) User::where('role', 'franchise')->sum('wallet_balance');
        $adminWalletBalance = (float) ($admin->wallet_balance ?? 0);

        // Wallet recharges in period
        $totalRechargeTokens = (float) (clone $txnsQuery)->where('type', 'credit')->sum('amount');
        $rechargeCount = (clone $txnsQuery)->where('type', 'credit')->count();

        // 3. Time Series Data for Chart.js (Daily traffic & volume)
        $trafficTimeline = (clone $logsQuery)
            ->selectRaw("DATE(created_at) as log_date, 
                         COUNT(*) as total_calls, 
                         SUM(CASE WHEN status = 'SUCCESS' THEN 1 ELSE 0 END) as success_count,
                         SUM(CASE WHEN status != 'SUCCESS' THEN 1 ELSE 0 END) as failed_count,
                         SUM(tokens_deducted) as tokens_spent")
            ->groupBy('log_date')
            ->orderBy('log_date', 'asc')
            ->get();

        $chartLabels = [];
        $chartSuccessData = [];
        $chartFailedData = [];
        $chartTokensData = [];

        foreach ($trafficTimeline as $point) {
            $dt = \Carbon\Carbon::parse($point->log_date);
            $chartLabels[] = $dt->format('d M');
            $chartSuccessData[] = (int) $point->success_count;
            $chartFailedData[] = (int) $point->failed_count;
            $chartTokensData[] = round((float) $point->tokens_spent, 2);
        }

        // If timeline is empty, fallback
        if (empty($chartLabels)) {
            $chartLabels = [now()->format('d M')];
            $chartSuccessData = [0];
            $chartFailedData = [0];
            $chartTokensData = [0];
        }

        // 4. Service-by-Service Breakdown
        $allServices = Service::orderBy('id', 'asc')->get();
        $servicesBreakdown = [];
        $pieServiceLabels = [];
        $pieServiceCalls = [];
        $pieServiceTokens = [];

        foreach ($allServices as $service) {
            $srvLogs = (clone $logsQuery)->where('service_code', $service->service_code);
            $srvCount = (clone $srvLogs)->count();
            $srvSuccess = (clone $srvLogs)->where('status', 'SUCCESS')->count();
            $srvFailed = $srvCount - $srvSuccess;
            $srvTokens = (float) (clone $srvLogs)->sum('tokens_deducted');
            $srvAvgLat = (clone $srvLogs)->whereNotNull('execution_time_ms')->avg('execution_time_ms');

            $rate = $srvCount > 0 ? round(($srvSuccess / $srvCount) * 100, 1) : 100.0;

            $servicesBreakdown[] = [
                'service' => $service,
                'total_calls' => $srvCount,
                'success_calls' => $srvSuccess,
                'failed_calls' => $srvFailed,
                'success_rate' => $rate,
                'tokens_spent' => $srvTokens,
                'avg_latency' => $srvAvgLat ? round((float) $srvAvgLat, 1) : 0,
            ];

            if ($srvCount > 0) {
                $pieServiceLabels[] = $service->name;
                $pieServiceCalls[] = $srvCount;
                $pieServiceTokens[] = $srvTokens;
            }
        }

        // 5. Franchise Consumption Leaderboard
        $franchises = User::where('role', 'franchise')->get();
        $franchiseLeaderboard = [];

        foreach ($franchises as $franchise) {
            $fLogs = (clone $logsQuery)->where('user_id', $franchise->id);
            $fTotalCalls = (clone $fLogs)->count();
            $fSuccessCalls = (clone $fLogs)->where('status', 'SUCCESS')->count();
            $fFailedCalls = $fTotalCalls - $fSuccessCalls;
            $fTokensSpent = (float) (clone $fLogs)->sum('tokens_deducted');
            $fRecharges = WalletTransaction::where('user_id', $franchise->id)
                ->where('type', 'credit')
                ->sum('amount');
            $fCustomPricingCount = FranchiseServicePrice::where('user_id', $franchise->id)->count();

            $franchiseLeaderboard[] = [
                'franchise' => $franchise,
                'wallet_balance' => (float) $franchise->wallet_balance,
                'total_calls' => $fTotalCalls,
                'success_calls' => $fSuccessCalls,
                'failed_calls' => $fFailedCalls,
                'tokens_spent' => $fTokensSpent,
                'total_recharges' => (float) $fRecharges,
                'custom_prices_count' => $fCustomPricingCount,
            ];
        }

        // Sort leaderboard by tokens spent desc
        usort($franchiseLeaderboard, fn($a, $b) => $b['tokens_spent'] <=> $a['tokens_spent']);

        // 6. Recent Operational Exceptions & Troubleshooting Feed
        $recentExceptions = ApiLog::with(['user', 'service'])
            ->where('status', '!=', 'SUCCESS')
            ->latest()
            ->take(8)
            ->get();

        if ($recentExceptions->isEmpty()) {
            $recentExceptions = ApiLog::with(['user', 'service'])
                ->latest()
                ->take(8)
                ->get();
        }

        // 7. Recent Wallet Recharges Audit Feed
        $recentRecharges = WalletTransaction::with('user')
            ->where('type', 'credit')
            ->latest()
            ->take(8)
            ->get();

        $kpis = [
            'total_logs' => $totalLogs,
            'success_logs' => $successLogs,
            'failed_logs' => $failedLogs,
            'insufficient_funds_logs' => $insufficientFundsLogs,
            'success_rate' => $successRate,
            'total_tokens_deducted' => $totalTokensDeducted,
            'avg_latency' => $avgLatency,
            'total_franchises' => $totalFranchises,
            'active_franchises' => $activeFranchises,
            'total_wallet_float' => $totalFranchiseWalletFloat,
            'admin_wallet_balance' => $adminWalletBalance,
            'total_recharge_tokens' => $totalRechargeTokens,
            'recharge_count' => $rechargeCount,
            'gst_rate' => $gstRate,
            'gst_enabled' => $gstEnabled,
            'gst_collected_estimated' => $gstCollectedEstimated,
        ];

        $chartData = [
            'labels' => $chartLabels,
            'success' => $chartSuccessData,
            'failed' => $chartFailedData,
            'tokens' => $chartTokensData,
            'pie_labels' => $pieServiceLabels,
            'pie_calls' => $pieServiceCalls,
            'pie_tokens' => $pieServiceTokens,
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'SUCCESS',
                'date_range' => [
                    'text' => $dateRangeText,
                    'value' => $dateRangeValue,
                    'from' => $parsedFrom?->toDateString(),
                    'to' => $parsedTo?->toDateString(),
                ],
                'kpis' => $kpis,
                'chart_data' => $chartData,
                'services_breakdown' => $servicesBreakdown,
                'franchise_leaderboard' => $franchiseLeaderboard,
            ]);
        }

        return view('admin.reports.index', compact(
            'kpis',
            'chartData',
            'servicesBreakdown',
            'franchiseLeaderboard',
            'recentExceptions',
            'recentRecharges',
            'allServices',
            'franchises',
            'dateRangeText',
            'dateRangeValue',
            'rangePreset',
            'selectedFranchiseId',
            'selectedServiceCode'
        ));
    }
}

