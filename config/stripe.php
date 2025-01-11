<?php

return [
    'mode' => env('STRIPE_MODE', 'test'), // Define o modo padrão
    'debug' => env('STRIPE_DEBUG', false),
    'keys' => [
        'public' => env('STRIPE_PUBLIC_KEY'),
        'secret' => env('STRIPE_SECRET_KEY'),
    ],
    'test' => [
        'public_key' => env('STRIPE_TEST_PUBLIC_KEY'),
        'secret_key' => env('STRIPE_TEST_SECRET_KEY'),
    ],
    'production' => [
        'public_key' => env('STRIPE_PUBLIC_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
    ],
];
