<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'fitbit' => [
        'client_id' => env('FITBIT_CLIENT_ID'),
        'client_secret' => env('FITBIT_CLIENT_SECRET'),
        'redirect' => env('APP_URL').'/socialite/update/fitbit',
    ],

    'trakt' => [
        'client_id' => env('TRAKT_CLIENT_ID'),
        'client_secret' => env('TRAKT_CLIENT_SECRET'),
        'redirect' => env('APP_URL').'/socialite/update/trakt',
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'strava' => [
        'client_id' => env('STRAVA_CLIENT_ID'),
        'client_secret' => env('STRAVA_CLIENT_SECRET'),
        'redirect' => env('APP_URL').'/socialite/update/strava',
    ],

    'stripe' => [
        'plans' => [
            'web' => [
                'annual' => env('STRIPE_WEB_ANNUAL'),
                'biannual' => env('STRIPE_WEB_BIANNUAL'),
                'monthly' => env('STRIPE_WEB_MONTHLY'),
            ],
            'mobile' => [
                'annual' => env('STRIPE_MOBILE_ANNUAL'),
                'biannual' => env('STRIPE_MOBILE_BIANNUAL'),
                'monthly' => env('STRIPE_MOBILE_MONTHLY'),
            ],
        ],
    ],

];
