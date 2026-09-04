<?php

return [
    'default' => env('CACHE_STORE', 'file'),

    'stores' => [
        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],
        'database' => [
            'driver' => 'database',
            'table' => env('CACHE_TABLE', 'cache'),
            'connection' => env('CACHE_DB_CONNECTION'),
            'lock_connection' => env('CACHE_LOCK_DB_CONNECTION'),
        ],
        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],
        'null' => [
            'driver' => 'null',
        ],
    ],

    'prefix' => env('CACHE_PREFIX', 'turkelitemedcare_cache_'),
];
