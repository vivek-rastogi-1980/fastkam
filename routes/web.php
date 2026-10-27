<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - a4Digital Admin Portal
|--------------------------------------------------------------------------
*/

// Authentication Routes (Guests only; authenticated users are redirected to services)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // Forgot & Reset Password Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public FastKam.in Home Page
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.services');
    }
    return view('home');
})->name('home');

Route::get('/home', function () {
    if (Auth::check()) {
        return redirect()->route('admin.services');
    }
    return view('home');
});

// Privacy Policy & Service Information Page
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy.policy');

// Terms & Conditions Page
Route::get('/terms-and-conditions', function () {
    return view('terms-and-conditions');
})->name('terms.conditions');

Route::get('/terms', function () {
    return view('terms-and-conditions');
});

// Refund & Cancellation Policy Page
Route::get('/refund-policy', function () {
    return view('refund-policy');
})->name('refund.policy');

Route::get('/refund-and-cancellation-policy', function () {
    return view('refund-policy');
});

// Authenticated Admin & Franchise Portal Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.reports');
        }
        return redirect()->route('admin.services');
    })->name('dashboard');

    // Requirement 3: "Our Services" page
    Route::get('/services', [DashboardController::class, 'services'])->name('admin.services');

    // Wallet Balance & Token Recharge Routes (ApnaCSC Gateway)
    Route::get('/wallet/add-balance', [WalletController::class, 'addBalance'])->name('wallet.add_balance');
    Route::get('/wallet/add-token', function () {
        return redirect()->route('wallet.add_balance');
    });
    Route::post('/wallet/process-payment', [WalletController::class, 'processPayment'])->name('wallet.process_payment');
    Route::get('/wallet/simulate-success', [WalletController::class, 'simulateSuccess'])->name('wallet.simulate_success');

    // Requirement 3: "Update Password" form & action
    Route::get('/password/edit', [DashboardController::class, 'editPassword'])->name('admin.password.edit');
    Route::post('/password/update', [DashboardController::class, 'updatePassword'])->name('admin.password.update');

    // API Transaction Logs (Admin views all logs, Franchise views only their own logs)
    Route::get('/logs', [DashboardController::class, 'logs'])->name('admin.logs');

    // Franchise Management (Admin privileged)
    Route::middleware('admin')->group(function () {
        // Super Admin Unified Reporting Dashboard (Single-Page Insights)
        Route::get('/reports', [DashboardController::class, 'reports'])->name('admin.reports');
        Route::get('/reporting-dashboard', function () {
            return redirect()->route('admin.reports');
        });

        Route::get('/franchises', [DashboardController::class, 'franchises'])->name('admin.franchises');
        Route::get('/franchises/invite', [DashboardController::class, 'generateInvite'])->name('admin.franchises.invite');
        Route::post('/franchises', [DashboardController::class, 'storeFranchise'])->name('admin.franchises.store');
        Route::post('/franchises/recharge', [DashboardController::class, 'rechargeWallet'])->name('admin.franchises.recharge');
        Route::get('/franchises/{franchise}/services-cost', [DashboardController::class, 'franchiseServicesCost'])->name('admin.franchises.services_cost');
        Route::post('/franchises/{franchise}/services-cost', [DashboardController::class, 'updateFranchiseServicesCost'])->name('admin.franchises.services_cost.update');
        Route::post('/franchises/{franchise}/toggle-status', [DashboardController::class, 'toggleFranchiseStatus'])->name('admin.franchises.toggle_status');

        // Super Admin Global Service Pricing Management (Default Price for New Franchises Only)
        Route::get('/services/pricing', [DashboardController::class, 'servicesPricing'])->name('admin.services.pricing');
        Route::get('/services-pricing', function () {
            return redirect()->route('admin.services.pricing');
        });
        Route::post('/services/pricing', [DashboardController::class, 'updateServicesPricing'])->name('admin.services.pricing.update');
        Route::post('/services/pricing/{service}', [DashboardController::class, 'updateSingleServicePrice'])->name('admin.services.pricing.single');

        // Super Admin Dedicated Service Pricing Section (Base Cost for Super Admin)
        Route::get('/services/super-admin-pricing', [DashboardController::class, 'superAdminPricing'])->name('admin.services.super_admin_pricing');
        Route::get('/super-admin-pricing', function () {
            return redirect()->route('admin.services.super_admin_pricing');
        });
        Route::post('/services/super-admin-pricing', [DashboardController::class, 'updateSuperAdminPricing'])->name('admin.services.super_admin_pricing.update');
        Route::post('/services/super-admin-pricing/{service}', [DashboardController::class, 'updateSingleSuperAdminPrice'])->name('admin.services.super_admin_pricing.single');

        // Super Admin GST Tax Rate Settings
        Route::get('/settings/gst', [DashboardController::class, 'gstSettings'])->name('admin.settings.gst');
        Route::post('/settings/gst', [DashboardController::class, 'updateGstSettings'])->name('admin.settings.gst.update');
    });
});


// Franchise Registration & Payment Gateway Routes (Publicly accessible but validated by signature)
Route::get('/franchise/register', [App\Http\Controllers\FranchiseRegistrationController::class, 'showForm'])->name('franchise.register')->middleware('signed');
Route::post('/franchise/register', [App\Http\Controllers\FranchiseRegistrationController::class, 'submitForm'])->name('franchise.register.submit')->middleware('signed');
Route::any('/franchise/payment-callback', [App\Http\Controllers\FranchiseRegistrationController::class, 'paymentCallback'])->name('franchise.payment.callback');

// ApnaCSC Payment Gateway Callbacks (Publicly accessible webhook/return endpoints)
// Session/cookie middleware hata diya taaki gateway ka cross-site callback user ka login session overwrite na kare.
Route::any('/wallet/payment-callback', [WalletController::class, 'paymentCallback'])
    ->name('wallet.payment.callback')
    ->withoutMiddleware([
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ]);

Route::any('/txnResult', [WalletController::class, 'paymentCallback'])
    ->name('wallet.txn_result')
    ->withoutMiddleware([
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ]);

