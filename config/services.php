<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'apple' => [
        "client_id" => env('APPLE_APP_ID'),
        "client_secret" => env('APPLE_CLIENT_SECRET'),
        'redirect' => env('APPLE_REDIRECT_URI')
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'firebase' => [
        'api_key' => env('FIREBASE_API_KEY'),
        'dynamic_link_domain' => env('FIREBASE_DYNAMIC_LINK_DOMAIN'),
        'android_package_name' => env('FIREBASE_ANDROID_PACKAGE_NAME'),
    ],

    'onesignal' => [
        'app_id' => env('ONESIGNAL_APP_ID'),
        'api_key' => env('ONESIGNAL_API_KEY'),
    ],

    'locationiq' => [
        'token_1' => env('LOCATIONIQ_TOKEN_1'),
        'token_2' => env('LOCATIONIQ_TOKEN_2'),
    ],

    'allegro' => [
        'client_id' => env('ALLEGRO_CLIENT_ID'),
        'client_secret' => env('ALLEGRO_CLIENT_SECRET'),
        'redirect_url' => env('ALLEGRO_REDIRECT_URL'),
        'login' => env('ALLEGRO_LOGIN'),
        'password' => env('ALLEGRO_PASSWORD'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => 'https://api.'.env('APP_URL').'/google-callback',
    ],

    'youtube-google' => [
        'client_id' => env('GOOGLE_YOUTUBE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_YOUTUBE_CLIENT_SECRET'),
        'redirect' => 'https://api.'.env('APP_URL').'/youtube/google-callback',
    ],

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect' => env('APP_URL').'/login/linkedin/callback',
    ],

];
