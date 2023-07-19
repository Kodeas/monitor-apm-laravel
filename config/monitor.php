<?php

return [
    'key' => env("MONITOR_API_KEY"),

    'namespace' => 'App\\',

    'environment' => env('MONITOR_ENV', env('APP_ENV')),

    'loggers' => [
        'requests' => true,

        'git' => true,

        'request_data' => true,

        'user' => true,

        'executed_sql' => true,
    ],

    'logger_details' => [
        'user_fields' => [
            'id', 'name'
        ]
    ]
];
