<?php

return [
    'key' => env("MONITOR_API_KEY"),

    'namespace' => 'App\\',

    'environment' => env('MONITOR_ENV', env('APP_ENV')),

    'notify_environments' => empty(env('MONITOR_NOTIFY_ENVIRONMENTS')) ? null : explode(',', str_replace(' ', '', env('MONITOR_NOTIFY_ENVIRONMENTS'))),

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
