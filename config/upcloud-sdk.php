<?php

return [
    'api_username' => env('UPCLOUD_API_USER', 'ibro_app'),
    'api_password' => env('UPCLOUD_API_PASSWORD', 'Bypass6-Carpentry6-Clime-switch-3etc-invent9-rays-pony5'),

    'default' => [
        'datacenter' => env('UPCLOUD_DEFAULT_DATACENTER', 'de-fra1'),
        'server_template_uuid' => env('UPCLOUD_DEFAULT_SERVER_TEMPLATE_UUID', '01000000-0000-4000-8000-000030240200'),
    ]
];
