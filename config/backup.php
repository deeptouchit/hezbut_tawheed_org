<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Backup Storage Configuration
    |--------------------------------------------------------------------------
    */
    'disk' => env('BACKUP_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Backup Retention Days
    |--------------------------------------------------------------------------
    */
    'retention_days' => env('BACKUP_RETENTION_DAYS', 30),

    /*
    |--------------------------------------------------------------------------
    | Backup Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'enabled' => env('BACKUP_NOTIFICATIONS', true),
        'mail' => env('BACKUP_NOTIFICATION_EMAIL'),
        'slack' => env('BACKUP_NOTIFICATION_SLACK_WEBHOOK'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Backup Configuration
    |--------------------------------------------------------------------------
    */
    'database' => [
        'enabled' => true,
        'chunk_size' => 5000,
        'compress' => true,
        'exclude_tables' => [
            'migrations',
            'failed_jobs',
            'sessions',
            'cache',
            'cache_locks'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Files Backup Configuration
    |--------------------------------------------------------------------------
    */
    'files' => [
        'enabled' => true,
        'paths' => [
            storage_path('app/public'),
            storage_path('app/uploads'),
        ],
        'exclude' => [
            '*.tmp',
            '*.cache',
            'thumbnails/*'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Advanced Options
    |--------------------------------------------------------------------------
    */
    'advanced' => [
        'encryption_enabled'  => env('BACKUP_ENCRYPTION', false),
        'encryption_key'      => env('BACKUP_ENCRYPTION_KEY'),
        'parallel_processing' => env('BACKUP_PARALLEL', false),
        'max_execution_time'  => env('BACKUP_MAX_EXECUTION_TIME', 3600),
        'memory_limit'        => env('BACKUP_MEMORY_LIMIT', '2048M')
    ]
];
