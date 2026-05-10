<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    | These are the routes where CORS headers should be applied. For Sanctum,
    | you must include "sanctum/csrf-cookie" as well as API routes.
    |
    */

    'paths' => [
        'api/*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    |
    | You can specify the HTTP methods allowed. Setting "*" allows all methods.
    |
    */

    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | Define which domains can access your API. For Vue (Vite), use
    | http://localhost:5173 in dev. For production, add your domain here.
    |
    */

    'allowed_origins' => [
        'http://localhost:5173', // Vue dev server
        'http://127.0.0.1:5173',
        // 'https://your-production-domain.com', // add prod domain
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins Patterns
    |--------------------------------------------------------------------------
    |
    | Use this if you need wildcards (e.g. subdomains). Leave empty if not needed.
    |
    */

    'allowed_origins_patterns' => [],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    |
    | Define which headers are allowed. "*" allows all headers.
    |
    */

    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    |
    | These headers are exposed to the browser. Leave empty unless needed.
    |
    */

    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    |
    | How long (in seconds) the results of a preflight request can be cached.
    |
    */

    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    |
    | Must be true for Sanctum, since it uses cookies for authentication.
    |
    */

    'supports_credentials' => true,

];
