<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'paytm' => [
        'environment' => env('PAYTM_ENVIRONMENT', 'production'),
        'merchant_id' => env('PAYTM_MERCHANT_ID'),
        'merchant_key' => env('PAYTM_MERCHANT_KEY'),
        'website' => env('PAYTM_WEBSITE', 'DEFAULT'),
        'industry_type' => env('PAYTM_INDUSTRY_TYPE', 'Retail'),
        'channel_id_web' => env('PAYTM_CHANNEL_ID_WEB', 'WEB'),
        'channel_id_wap' => env('PAYTM_CHANNEL_ID_WAP', 'WAP'),
        'callback_url' => env('PAYTM_CALLBACK_URL'),
        'franchise_fee' => env('FRANCHISE_FEE', '100.00'),
    ],

    'apnacsc' => [
        'upiuid'       => env('APNACSC_UPIUID'),
        'token'        => env('APNACSC_TOKEN'),
        'secret'       => env('APNACSC_SECRET'),
        'url'          => env('APNACSC_URL', 'https://apnacsc.online/order/process'),
        'callback_url' => env('APNACSC_CALLBACK_URL'),
        'test_mode'    => env('APNACSC_TEST_MODE', false),
    ],

    'rechpay' => [
        'url'         => env('RECHPAY_URL', 'https://apnacsc.online/order/process'),
        'token'       => env('RECHPAY_TOKEN', env('APNACSC_TOKEN')),
        'secret'      => env('RECHPAY_SECRET', env('APNACSC_SECRET')),
        'environment' => env('RECHPAY_ENVIRONMENT', 'PROD'),
    ],

];