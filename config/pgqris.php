<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PGQRIS Store Key & Base Endpoint
    |--------------------------------------------------------------------------
    */
    'store_key' => env('PGQRIS_STORE_KEY', ''),
    'base_url' => env('PGQRIS_BASE_URL', 'https://rest.pgqris.com'),
    'timeout' => env('PGQRIS_TIMEOUT', 30),
];
