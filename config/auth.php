<?php

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],
    'guards' => [
        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
    ],
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\User::class,
        ]
    ],
    'oauth' => [
        'client_id' => intval(env('OAUTH_CLIENT_ID', null)),
        'client_secret' => env('OAUTH_CLIENT_SECRET', null),
        'grant_type' => env('OAUTH_GRANT_TYPE', null),
    ]
];
