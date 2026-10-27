<?php

use App\Http\Controllers\Api\ApiServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| API portal endpoints for franchise and child system integration.
| All service routing is protected by 'api.auth' (X-API-KEY / Bearer Token).
|
*/

Route::prefix('v1')->middleware('api.auth')->group(function () {

    // General Service Execution Endpoint
    Route::post('/service/execute/{serviceCode?}', [ApiServiceController::class, 'executeService'])
        ->name('api.service.execute');

    // Direct Named Convenience Service Endpoints (Updated 6 Services)
    Route::post('/service/dl-verification-plus', function (Request $request, ApiServiceController $controller) {
        return $controller->executeService($request, 'dl_verification_plus_srv2');
    })->name('api.service.dl_verification_plus');

    Route::post('/service/pan-details-v2', function (Request $request, ApiServiceController $controller) {
        return $controller->executeService($request, 'pan_details_v2');
    })->name('api.service.pan_details_v2');

    Route::post('/service/pan-details-v1', function (Request $request, ApiServiceController $controller) {
        return $controller->executeService($request, 'pan_details_v1');
    })->name('api.service.pan_details_v1');

    Route::post('/service/aadhar-to-pan', function (Request $request, ApiServiceController $controller) {
        return $controller->executeService($request, 'aadhar_to_pan');
    })->name('api.service.aadhar_to_pan');

    Route::post('/service/rc-to-mobile', function (Request $request, ApiServiceController $controller) {
        return $controller->executeService($request, 'rc_to_mobile_v3');
    })->name('api.service.rc_to_mobile');

    Route::post('/service/voter-id-verification', function (Request $request, ApiServiceController $controller) {
        return $controller->executeService($request, 'voter_id_verification_srv2');
    })->name('api.service.voter_id_verification');

    // Legacy Route Aliases for Compatibility
    Route::post('/service/pan-aadhaar', function (Request $request, ApiServiceController $controller) {
        $code = \App\Models\Service::where('service_code', 'aadhar_to_pan')->exists() ? 'aadhar_to_pan' : 'pan_aadhaar';
        return $controller->executeService($request, $code);
    })->name('api.service.pan_aadhaar');

    Route::post('/service/rc-lookup', function (Request $request, ApiServiceController $controller) {
        $code = \App\Models\Service::where('service_code', 'rc_to_mobile_v3')->exists() ? 'rc_to_mobile_v3' : 'rc_lookup';
        return $controller->executeService($request, $code);
    })->name('api.service.rc_lookup');

    Route::post('/service/voter-id', function (Request $request, ApiServiceController $controller) {
        $code = \App\Models\Service::where('service_code', 'voter_id_verification_srv2')->exists() ? 'voter_id_verification_srv2' : 'voter_id';
        return $controller->executeService($request, $code);
    })->name('api.service.voter_id');

    Route::post('/service/driving-license', function (Request $request, ApiServiceController $controller) {
        $code = \App\Models\Service::where('service_code', 'dl_verification_plus_srv2')->exists() ? 'dl_verification_plus_srv2' : 'driving_license';
        return $controller->executeService($request, $code);
    })->name('api.service.driving_license');

    // Wallet & Balance Information
    Route::get('/wallet/balance', [ApiServiceController::class, 'getWalletBalance'])
        ->name('api.wallet.balance');

    // Catalog Services List
    Route::get('/services', [ApiServiceController::class, 'listServices'])
        ->name('api.services.catalog');
});
