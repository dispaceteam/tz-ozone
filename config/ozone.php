<?php

return [

    'client_id' => env('OZONE_CLIENT_ID', '836'),
    'api_key' => env('OZONE_API_KEY', '0296d4f2-70a1-4c09-b507-904fd05567b9'),
    'host' => env('OZONE_HOST', 'http://cb-api.ozonru.me/'),

    'products' => [
        'status' => [
            'failed' => ['failed_moderation', 'failed_validation', 'failed'],
            'in_progress' => ['sent', 'processing', 'moderating', 'pending'],
            'success' => ['processed', 'imported']
        ],
        'check_cron' => env('OZONE_PRODUCTS_CHECK_CRON', '* * * * *')
    ]
];
