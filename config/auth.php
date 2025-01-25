<?php

return [
    'defaults'         => [
        'guard'     => 'web',
        'passwords' => 'users',
    ],
    'guards'           => [
        'web'   => [
            'driver'   => 'session',
            'provider' => 'users',
        ],
        // Tambahkan guard admin
        'admin' => [
            'driver'   => 'session',
            'provider' => 'admins',  // sesuaikan dengan provider yang akan kita buat
        ],
    ],
    'providers'        => [
        'users'  => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
            'table'  => 'putri_user',
        ],
        // Tambahkan provider untuk admin
        'admins' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Admin::class,
        ],
    ],
    'passwords'        => [
        'users'  => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
        'admins' => [
            'provider' => 'admins',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],
    'password_timeout' => 10800,
];
