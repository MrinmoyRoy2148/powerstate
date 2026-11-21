<?php

return [
    'initial_admin' => [
        'name' => env('ADMIN_NAME'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],
    'registration_key' => env('ADMIN_REGISTRATION_KEY'),
];
