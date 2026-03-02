<?php

return [

    'paths' => ['api/*'],

    'allowed_methods' => ['GET', 'POST', 'OPTIONS'],

    'allowed_origins' => [
        'https://elitedigitalagency.net',
        'https://www.elitedigitalagency.net',
        'https://portal.elitedigitalagency.net',
        'http://localhost:3000', // For local development
        // Add any other frontend domains here
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Content-Type', 'Accept', 'Authorization', 'X-Requested-With'],

    'exposed_headers' => [],

    'max_age' => 86400,

    'supports_credentials' => false,

];