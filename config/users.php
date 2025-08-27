<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Management Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the Litepie Users package.
    | You can customize user types, workflows, permissions, and more.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | User Types
    |--------------------------------------------------------------------------
    |
    | Define the available user types in your application. Each user type
    | can have different permissions, workflows, and behaviors.
    |
    */
    'user_types' => [
        'user' => [
            'name' => 'Regular User',
            'class' => \Litepie\Users\UserTypes\RegularUserType::class,
            'model' => \Litepie\Users\Models\User::class,
            'registration_workflow' => 'user_registration',
            'default_roles' => ['user'],
            'allowed_in_tenants' => true,
        ],
        'client' => [
            'name' => 'Client User',
            'class' => \Litepie\Users\UserTypes\ClientUserType::class,
            'model' => \Litepie\Users\Models\ClientUser::class,
            'registration_workflow' => 'client_registration',
            'default_roles' => ['client'],
            'allowed_in_tenants' => true,
        ],
        'admin' => [
            'name' => 'Administrator',
            'class' => \Litepie\Users\UserTypes\AdminUserType::class,
            'model' => \Litepie\Users\Models\AdminUser::class,
            'registration_workflow' => 'admin_registration',
            'default_roles' => ['admin'],
            'allowed_in_tenants' => false,
        ],
        'system' => [
            'name' => 'System User',
            'class' => \Litepie\Users\UserTypes\SystemUserType::class,
            'model' => \Litepie\Users\Models\SystemUser::class,
            'registration_workflow' => 'system_registration',
            'default_roles' => ['system'],
            'allowed_in_tenants' => false,
        ],
        'organization' => [
            'name' => 'Organization Member',
            'class' => \Litepie\Users\UserTypes\OrganizationUserType::class,
            'model' => \Litepie\Users\Models\User::class,
            'registration_workflow' => 'organization_user_registration',
            'default_roles' => ['organization-member'],
            'allowed_in_tenants' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default User Type
    |--------------------------------------------------------------------------
    |
    | The default user type when creating new users without specifying a type.
    |
    */
    'default_user_type' => 'user',

    /*
    |--------------------------------------------------------------------------
    | Multi-Tenancy Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how users interact with the multi-tenancy system.
    |
    */
    'tenancy' => [
        'enabled' => true,
        'allow_cross_tenant_users' => false,
        'tenant_user_limit' => null, // null for unlimited
        'auto_assign_to_tenant' => true,
        'tenant_column' => 'tenant_id',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Configuration
    |--------------------------------------------------------------------------
    |
    | Configure authentication settings for different user types.
    |
    */
    'authentication' => [
        'guards' => [
            'web' => ['user', 'client', 'admin'],
            'api' => ['user', 'client', 'system'],
            'admin' => ['admin'],
        ],
        'two_factor_enabled' => true,
        'password_expires_days' => 90,
        'max_login_attempts' => 5,
        'lockout_duration' => 900, // 15 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | User Profile Configuration
    |--------------------------------------------------------------------------
    |
    | Configure user profile settings and required fields.
    |
    */
    'profiles' => [
        'enabled' => true,
        'required_fields' => ['first_name', 'last_name', 'phone'],
        'optional_fields' => ['bio', 'website', 'company', 'address'],
        'avatar_sizes' => [
            'thumbnail' => [50, 50],
            'small' => [100, 100],
            'medium' => [200, 200],
            'large' => [400, 400],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | File Management Configuration
    |--------------------------------------------------------------------------
    |
    | Configure file attachments and document management.
    |
    */
    'files' => [
        'collections' => [
            'avatars' => [
                'max_files' => 1,
                'allowed_types' => ['image/jpeg', 'image/png', 'image/webp'],
                'max_size' => 2048, // KB
                'generate_variants' => true,
            ],
            'documents' => [
                'max_files' => 10,
                'allowed_types' => ['application/pdf', 'image/jpeg', 'image/png'],
                'max_size' => 5120, // KB
                'generate_variants' => false,
            ],
            'identity_documents' => [
                'max_files' => 5,
                'allowed_types' => ['image/jpeg', 'image/png', 'application/pdf'],
                'max_size' => 3072, // KB
                'generate_variants' => false,
                'security_scan' => true,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Workflow Configuration
    |--------------------------------------------------------------------------
    |
    | Configure workflows for user management processes.
    |
    */
    'workflows' => [
        'enabled' => true,
        'user_registration' => [
            'enabled' => true,
            'requires_email_verification' => true,
            'requires_admin_approval' => false,
            'auto_activate' => true,
        ],
        'client_registration' => [
            'enabled' => true,
            'requires_email_verification' => true,
            'requires_admin_approval' => true,
            'auto_activate' => false,
        ],
        'admin_registration' => [
            'enabled' => true,
            'requires_email_verification' => true,
            'requires_admin_approval' => true,
            'auto_activate' => false,
        ],
        'password_reset' => [
            'enabled' => true,
            'token_expires_minutes' => 60,
            'require_current_password' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit and Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure audit trails and logging for user activities.
    |
    */
    'audit' => [
        'enabled' => true,
        'log_authentication' => true,
        'log_user_changes' => true,
        'log_permission_changes' => true,
        'log_file_uploads' => true,
        'retention_days' => 365,
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Configuration
    |--------------------------------------------------------------------------
    |
    | Configure notifications for user events.
    |
    */
    'notifications' => [
        'enabled' => true,
        'channels' => ['mail', 'database'],
        'welcome_email' => true,
        'password_reset_email' => true,
        'account_activation_email' => true,
        'role_change_email' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | Configure security settings for user management.
    |
    */
    'security' => [
        'password_rules' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
        'require_email_verification' => true,
        'session_timeout' => 7200, // 2 hours
        'force_password_change_days' => 90,
        'prevent_password_reuse' => 5, // Last 5 passwords
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    |
    | Configure database table names and settings.
    |
    */
    'database' => [
        'tables' => [
            'users' => 'users',
            'user_profiles' => 'user_profiles',
            'user_types' => 'user_types',
            'user_sessions' => 'user_sessions',
            'user_login_attempts' => 'user_login_attempts',
            'password_histories' => 'password_histories',
        ],
        'connection' => null, // Use default connection
    ],

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | Configure API settings for user management.
    |
    */
    'api' => [
        'enabled' => true,
        'rate_limiting' => [
            'enabled' => true,
            'attempts' => 60,
            'decay_minutes' => 1,
        ],
        'pagination' => [
            'per_page' => 15,
            'max_per_page' => 100,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure caching for user data and permissions.
    |
    */
    'cache' => [
        'enabled' => true,
        'ttl' => 3600, // 1 hour
        'prefix' => 'users',
        'tags' => ['users', 'permissions'],
    ],
];
