<?php

return [

    'manifest_path' => public_path('build/manifest.json'),

    'hot_file' => public_path('hot'),

    'dev_server' => [
        'url' => env('VITE_DEV_SERVER_URL', 'http://localhost:5173'),
    ],

    'build_directory' => 'build',

    'asset_url' => env('ASSET_URL'),
];
