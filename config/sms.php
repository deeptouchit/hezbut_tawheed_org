<?php

return [
    'default' => env('SMS_DEFAULT_PROVIDER', 'bulk_sms'),

    'providers' => [
        'bulk_sms' => [
            'driver' => 'bulk_sms',
            'api_key' => env('BULK_SMS_API_KEY'),
            'api_url' => env('BULK_SMS_API_URL'),
            'balance_api' => env('BULK_SMS_BALANCE_API'),
            'sender_id' => env('BULK_SMS_SENDER_ID'),
            'timeout' => 30,
            'retry_times' => 3,
            'retry_milliseconds' => 100,
        ],
    ],

    'test_number' => env('SMS_TEST_NUMBER', '8801XXXXXXXXX'),
];
