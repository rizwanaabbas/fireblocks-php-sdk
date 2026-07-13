<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Fireblocks API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the Fireblocks PHP SDK.
    | You can obtain your API key and secret from the Fireblocks Console.
    |
    */

    // API Base URL
    'base_url' => env('FIREBLOCKS_BASE_URL', 'https://api.fireblocks.io'),

    // API Key from Fireblocks Console
    'api_key' => env('FIREBLOCKS_API_KEY', ''),

    // API Secret (private key) for JWT signing
    'api_secret' => env('FIREBLOCKS_API_SECRET', ''),

    // Path to API secret file (alternative to api_secret)
    'api_secret_path' => env('FIREBLOCKS_API_SECRET_PATH', ''),

    // Timeout for API requests (seconds)
    'timeout' => env('FIREBLOCKS_TIMEOUT', 30),

    // Connection timeout (seconds)
    'connect_timeout' => env('FIREBLOCKS_CONNECT_TIMEOUT', 10),

    // Retry attempts for failed requests
    'max_retries' => env('FIREBLOCKS_MAX_RETRIES', 3),

    // Retry delay in milliseconds
    'retry_delay' => env('FIREBLOCKS_RETRY_DELAY', 500),

    // Enable debug logging
    'debug' => env('FIREBLOCKS_DEBUG', false),

    // Proxy configuration
    'proxy' => env('FIREBLOCKS_PROXY', null),

    // Custom headers
    'headers' => [],
];
