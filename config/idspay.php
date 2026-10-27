<?php

return [
    /*
    |--------------------------------------------------------------------------
    | IDSPay API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for integrating with IDSPay Services API engine.
    |
    */

    'base_url' => env('IDSPAY_BASE_URL', 'https://javabackend.idspay.in'),

    'api_id' => env('IDSPAY_API_ID', 'APID3597'),

    'api_key' => env('IDSPAY_API_KEY', '679d36c9-1897-41ca-aabb-8549842c33c7'),

    'secret_key' => env('IDSPAY_SECRET_KEY', ''),

    'token_id' => env('IDSPAY_TOKEN_ID', 'ZvFfehk2E2JNyenRuGKyDBfSAIQHJ9lN'),

    'timeout' => (int) env('IDSPAY_TIMEOUT', 30),

    /*
    | Set to true to simulate IDSPay responses in local sandbox when live
    | credentials are not yet configured or for test suite execution.
    */
    'mock_mode' => (bool) env('IDSPAY_MOCK_MODE', false),
];
