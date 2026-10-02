<?php

return [
    // admin contact number, no + or spaces, used for wa.me links
    'whatsapp_number' => '6285641034599',

    'email' => 'theovrlndr@gmail.com',

    // Where "new booking" alerts are sent. Override with BOOKING_NOTIFY_EMAIL, leave empty to disable.
    'notify_email' => env('BOOKING_NOTIFY_EMAIL', 'theovrlndr@gmail.com'),

    // Social accounts are hidden while null. Once an account exists, set
    // ['label' => '@handle', 'url' => 'https://...'] and it appears in the footer and Contact page.
    'socials' => [
        'instagram' => null,
        'tiktok' => null,
        'facebook' => null,
    ],
];
