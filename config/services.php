<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'authy' => [
        'secret' => env('AUTHY_SECRET'),
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
	
	'google' => [
    'client_id' => '949638696552-1f03qjdcqmnrp0j5vtb42tsg2lmgu72l.apps.googleusercontent.com',
    'client_secret' => 'y2-9grwZqXv07xnWTvdBH_Tb',
    'redirect' => 'http://192.34.58.254/google/callback',
	],
];
