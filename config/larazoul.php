<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Larazoul Configuration
    |--------------------------------------------------------------------------
    |
    | Central configuration for the LaraZoul package.
    |
    */

    // Route prefix for the admin panel (e.g. /admin)
    'admin_prefix' => env('LARAZOUL_ADMIN_PREFIX', 'admin'),

    // The middleware group applied to admin routes
    'admin_middleware' => ['web', 'larazoul.admin'],

    // Default locale for the admin panel
    'locale' => env('LARAZOUL_LOCALE', 'en'),

    // Locales supported by the panel
    'locales' => [
        'en' => 'English',
        'ar' => 'العربية',
    ],

    // The model used to identify an admin user (must implement Authenticatable)
    'user_model' => 'App\\Models\\User',

    // Guard used to authenticate admin panel users
    'guard' => 'web',

    // The column on the user model that marks the user as admin
    'admin_role_column' => 'is_admin',

    // The value of admin_role_column that grants admin access
    'admin_role_value' => true,

    // Database connection used by Larazoul tables (null = default)
    'connection' => null,

    // Namespace used when generating application modules
    'module_namespace' => 'App\\Modules',

    // Storage disk used for module export/import bundles
    'export_disk' => 'local',

];
