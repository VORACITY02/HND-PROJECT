<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'payment_simulator' => [
        'base_url' => env('PAYMENT_SIMULATOR_BASE_URL', ''),
        'api_key' => env('PAYMENT_SIMULATOR_API_KEY', ''),
    ],

    'rabbitmaid' => [
        // Full endpoint (note: docs mention /api/v1/*, but examples show /v1/*).
        'endpoint' => env('RABBITMAID_API_ENDPOINT', ''),
        'application_key' => env('RABBITMAID_APPLICATION_KEY', ''),
        'access_key' => env('RABBITMAID_ACCESS_KEY', ''),
        'secret_key' => env('RABBITMAID_SECRET_KEY', ''),
        // Which application wallet to operate on
        'service' => env('RABBITMAID_SERVICE', 'mtn'),
    ],

];
