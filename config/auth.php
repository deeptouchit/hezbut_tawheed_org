<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [

        // Admin / Staff / Delivery Man (Users table) - Default web guard
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Admin Guard (Explicitly for admin panel)
        'admin' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Customer Login (Customers table)
        'customer' => [
            'driver' => 'session',
            'provider' => 'customers',
        ],

        // API Guard for customers
        'customer-api' => [
            'driver' => 'sanctum',
            'provider' => 'customers',
        ],

        // API Guard for admin
        'admin-api' => [
            'driver' => 'sanctum',
            'provider' => 'users',
        ],

        // Delivery Man Guard
        'delivery_man' => [
            'driver' => 'session',
            'provider' => 'delivery_men',
        ],

        // API Guard for delivery men
        'delivery_man-api' => [
            'driver' => 'sanctum',
            'provider' => 'delivery_men',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [

        // Admin / Staff / Delivery Man
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // Customers
        'customers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],

        // Delivery Men
        'delivery_men' => [
            'driver' => 'eloquent',
            'model' => App\Models\DeliveryMan::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Reset
    |--------------------------------------------------------------------------
    */

    'passwords' => [

        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'customers' => [
            'provider' => 'customers',
            'table' => 'customer_password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => 10800,

];
