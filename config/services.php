<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Bulk SMS Gateway
    |--------------------------------------------------------------------------
    |
    | Configuration for Bulk SMS Gateway Service
    |
    */
    'bulk_sms' => [
        'api_key'            => env('BULK_SMS_API_KEY'),
        'api_url'            => env('BULK_SMS_API_URL', 'http://139.99.39.237/api/smsapi'),
        'balance_api'        => env('BULK_SMS_BALANCE_API', 'http://139.99.39.237/api/getBalanceApi'),
        'sender_id'          => env('BULK_SMS_SENDER_ID'),
        'timeout'            => env('SMS_TIMEOUT', 30),
        'retry_times'        => env('SMS_RETRY_TIMES', 3),
        'retry_milliseconds' => env('SMS_RETRY_MS', 100),
        'test_number'        => env('SMS_TEST_NUMBER', '8801XXXXXXXXX'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Login Configuration
    |--------------------------------------------------------------------------
    */
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI'),
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect'      => env('FACEBOOK_REDIRECT_URI'),
    ],

];
