<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Envato API Configuration
    |--------------------------------------------------------------------------
    |
    | Used to verify purchase codes, get buyer details, and handle OAuth login.
    |
    */

    'personal_token' => env('ENVATO_PERSONAL_TOKEN', ''),

    'client_id' => env('ENVATO_CLIENT_ID', ''),
    
    'client_secret' => env('ENVATO_CLIENT_SECRET', ''),

    'redirect_uri' => env('ENVATO_REDIRECT_URI', 'http://localhost/auth/envato/callback'),
];
