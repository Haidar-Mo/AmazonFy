<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', '*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['https://amazonfy.bazarsy.shop'],

    // Optional pattern for subdomains
    // 'allowed_origins_patterns' => ['/^https:\/\/.*\.bazarsy\.shop$/'],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
