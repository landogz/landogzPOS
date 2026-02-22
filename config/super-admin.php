<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default role for sidebar menu when no user is authenticated (web).
    | Used when the super-admin panel is loaded without web session auth.
    | Set to a role (e.g. 'admin', 'manager', 'pharmacist') to test menu filtering,
    | or leave null to show all menu items when auth()->user() is null.
    | When auth()->user() exists, $user->role is used instead.
    */
    'menu_default_role' => env('SUPER_ADMIN_MENU_DEFAULT_ROLE', 'super_admin'), // e.g. 'admin', 'manager', 'pharmacist' to test menu
];
