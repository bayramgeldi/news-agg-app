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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'NewsAPIOrg' => [
        'key' => env('NEWS_API_ORG_KEY'),
        'base_url' => env('NEWS_API_ORG_URL'),
    ],
    'NewsCred' => [
        'key' => env('NEWS_CRED_KEY'),
        'base_url' => env('NEWS_CRED_URL'),
    ],
    'TheGuardian' => [
        'key' => env('THE_GUARDIAN_KEY'),
        'base_url' => env('THE_GUARDIAN_URL'),
    ],
    'NewYorkTimes' => [
        'key' => env('NEW_YORK_TIMES_KEY'),
        'secret' => env('NEW_YORK_TIMES_SECRET'),
        'base_url' => env('NEW_YORK_TIMES_URL'),
    ],

];
