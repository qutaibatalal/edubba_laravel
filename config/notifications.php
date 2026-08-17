<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default channels
    |--------------------------------------------------------------------------
    | Order of fallback: push -> whatsapp -> sms.
    */
    'default_channels' => ['push', 'whatsapp', 'sms'],

    'rate_limit' => [
        'per_second' => (int) env('NOTIF_RATE_PER_SECOND', 10),
        'daily_cap' => (int) env('NOTIF_DAILY_CAP', 5000),
    ],

    'sms' => [
        'provider' => env('SMS_PROVIDER', 'mock'), // mock | twilio | unifonic | iraqsms
        'from' => env('SMS_FROM', 'EDUBBA'),
        'twilio' => [
            'sid' => env('TWILIO_SID'),
            'token' => env('TWILIO_TOKEN'),
            'from' => env('TWILIO_FROM'),
        ],
        'unifonic' => [
            'app_sid' => env('UNIFONIC_APP_SID'),
            'from' => env('UNIFONIC_FROM', 'EDUBBA'),
        ],
        'iraqsms' => [
            'username' => env('IRAQ_SMS_USERNAME'),
            'password' => env('IRAQ_SMS_PASSWORD'),
            'sender' => env('IRAQ_SMS_SENDER', 'EDUBBA'),
        ],
    ],

    'whatsapp' => [
        'provider' => env('WHATSAPP_PROVIDER', 'mock'), // mock | meta
        'meta' => [
            'token' => env('WHATSAPP_META_TOKEN'),
            'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
            'api_version' => env('WHATSAPP_API_VERSION', 'v20.0'),
        ],
    ],

    'fcm' => [
        'project_id' => env('FCM_PROJECT_ID'),
        'service_account' => env('FCM_SERVICE_ACCOUNT_JSON'), // path to credentials file
        'max_retries' => (int) env('FCM_MAX_RETRIES', 3),
    ],
];
