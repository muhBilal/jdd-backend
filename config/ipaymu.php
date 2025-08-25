<?php

return [
    'base_url' => env('IPAYMU_BASE_URL', 'https://sandbox.ipaymu.com/api/v2'),
    'api_key' => env('IPAYMU_API_KEY'),
    'va' => env('IPAYMU_VA'),
    'production' => env('IPAYMU_PRODUCTION', false),
];