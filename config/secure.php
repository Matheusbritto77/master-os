<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HTTPS Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration controls the HTTPS behavior of your application.
    | Set these values based on your environment.
    |
    */

    'force_https' => env('FORCE_HTTPS', false),

    /*
    |--------------------------------------------------------------------------
    | Secure Cookies Configuration
    |--------------------------------------------------------------------------
    |
    | Configure secure cookies based on environment.
    | In production, cookies should be secure.
    | In development, they can be insecure for easier testing.
    |
    */

    'secure_cookies' => env('SECURE_COOKIES', false),

    /*
    |--------------------------------------------------------------------------
    | HSTS Configuration
    |--------------------------------------------------------------------------
    |
    | HTTP Strict Transport Security (HSTS) configuration.
    | Only enable in production environments.
    |
    */

    'hsts' => [
        'enabled' => env('HSTS_ENABLED', false),
        'max_age' => env('HSTS_MAX_AGE', 31536000), // 1 year
        'include_subdomains' => env('HSTS_INCLUDE_SUBDOMAINS', true),
        'preload' => env('HSTS_PRELOAD', false),
    ],

]; 