<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Super Admin Login OTP
    |--------------------------------------------------------------------------
    | When enabled, super_admin login requires a second step: OTP sent via
    | the chosen channel (email or sms). SMS uses Semaphore; email uses Laravel Mail.
    */

    'super_admin_login' => [
        'enabled' => env('SUPER_ADMIN_OTP_ENABLED', false),
        'channel' => env('OTP_CHANNEL', 'sms'), // 'sms' | 'email'
        'ttl_seconds' => (int) env('OTP_TTL', 300),
        'length' => (int) env('OTP_LENGTH', 6),
    ],

];
