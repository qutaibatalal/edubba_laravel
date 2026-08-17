<?php

return [
    'sandbox' => env('QICARD_SANDBOX', true),

    'terminal_id' => env('QICARD_TERMINAL_ID'),
    'username' => env('QICARD_USERNAME'),
    'password' => env('QICARD_PASSWORD'),
    'merchant_key' => env('QICARD_MERCHANT_KEY'),
    'redirect_uri' => env('QICARD_REDIRECT_URI', env('APP_URL').'/payments/qicard/callback'),

    'sandbox_url' => env('QICARD_SANDBOX_URL', 'https://sbs.qicard.com/'),
    'production_url' => env('QICARD_PRODUCTION_URL', 'https://sbs.qicard.com/'),
];
