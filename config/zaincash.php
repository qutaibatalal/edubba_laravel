<?php

return [
    'sandbox' => env('ZAINCASH_SANDBOX', true),

    'merchant_id' => env('ZAINCASH_MERCHANT_ID'),
    'merchant_secret' => env('ZAINCASH_MERCHANT_SECRET'),
    'msisdn' => env('ZAINCASH_MSISDN'), // merchant mobile number
    'iqn' => env('ZAINCASH_IQN'), // Iraqi National ID of the merchant
    'redirect_uri' => env('ZAINCASH_REDIRECT_URI', env('APP_URL').'/payments/zaincash/callback'),

    'sandbox_url' => env('ZAINCASH_SANDBOX_URL', 'https://test.zaincash.iq/'),
    'production_url' => env('ZAINCASH_PRODUCTION_URL', 'https://api.zaincash.iq/'),
];
