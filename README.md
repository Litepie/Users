# 🚀 Litepie Users - Complete Laravel User Management System

[![Latest Version on Packagist](https://img.shields.io/packagist/v/litepie/users.svg?style=flat-square)](https://packagist.org/packages/litepie/users)
[![Total Downloads](https://img.shields.io/packagist/dt/litepie/users.svg?style=flat-square)](https://packagist.org/packages/litepie/users)
[![License](https://img.shields.io/github/license/litepie/users.svg?style=flat-square)](https://github.com/litepie/users/blob/main/LICENSE.md)
[![PHP Version Require](https://img.shields.io/packagist/php-v/litepie/users.svg?style=flat-square)](https://packagist.org/packages/litepie/users)
[![Laravel Compatible](https://img.shields.io/badge/Laravel-11%20%7C%2012-orange?style=flat-square)](https://laravel.com)
[![Tests](https://img.shields.io/github/actions/workflow/status/litepie/users/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/litepie/users/actions)

> **A production-ready, feature-rich Laravel user management package** designed for modern applications requiring advanced user management, multi-tenancy, sophisticated role-based access control, and seamless integration with the Litepie ecosystem.

## 📖 Table of Contents

- [🌟 Overview](#-overview)
- [✨ Key Features](#-key-features)
- [🎯 Why Choose Litepie Users?](#-why-choose-litepie-users)
- [📋 Requirements](#-requirements)
- [🚀 Quick Start](#-quick-start)
- [📦 Installation](#-installation)
- [⚙️ Configuration](#️-configuration)
- [🔧 Basic Usage](#-basic-usage)
- [🏗️ Advanced Features](#️-advanced-features)
- [🔌 API Documentation](#-api-documentation)
- [🏢 Multi-Tenancy](#-multi-tenancy)
- [🔐 Security & Authentication](#-security--authentication)
- [🔄 Workflows & Automation](#-workflows--automation)
- [📊 Analytics & Reporting](#-analytics--reporting)
- [🧪 Testing](#-testing)
- [🛠️ Extending & Customization](#️-extending--customization)
- [🔌 Events & Hooks](#-events--hooks)
- [🚀 Performance & Optimization](#-performance--optimization)
- [📱 Frontend Integration](#-frontend-integration)
- [🤝 Contributing](#-contributing)
- [📞 Support](#-support)
- [🗺️ Roadmap](#️-roadmap)

## 🌟 Overview

**Litepie Users** is a comprehensive, enterprise-grade user management package built specifically for Laravel applications. It provides everything you need to manage users, roles, permissions, authentication, and user workflows in a scalable, secure, and maintainable way.

Whether you're building a simple application or a complex multi-tenant SaaS platform, Litepie Users provides the foundation you need with sensible defaults and extensive customization options.

### 🎯 Built for Modern Laravel Applications

- **Laravel 12 Ready**: Fully optimized for Laravel 12's latest features while maintaining backward compatibility with Laravel 11
- **Production-Tested**: Used in production by thousands of applications worldwide
- **Developer-Friendly**: Extensive documentation, intuitive API, and comprehensive testing
- **Enterprise-Ready**: Advanced security features, audit trails, and compliance tools

## ✨ Key Features

### 🏗️ **Core User Management**
- **Complete User Lifecycle** - Registration, activation, suspension, deactivation, and deletion
- **Multiple User Types** - Support for Regular, Client, Admin, and System users with custom types
- **Rich User Profiles** - Extended profile management with custom fields and file attachments
- **Status Management** - Active, Pending, Suspended, Banned status with automated workflows
- **Bulk Operations** - Mass user operations with queued processing for large datasets

### 🔐 **Advanced Authentication & Security**
- **Multi-Factor Authentication** - TOTP, SMS, Email with backup codes and device trust
- **Advanced Password Security** - Password history, complexity rules, expiration policies
- **Session Management** - Device tracking, concurrent session limits, remote logout
- **Rate Limiting** - Sophisticated protection against brute force attacks
- **Security Monitoring** - Real-time threat detection and automated response
- **Audit Trails** - Comprehensive logging of all user actions and security events

### 🏢 **Enterprise Multi-Tenancy**
- **Complete Tenant Isolation** - Database-level isolation with shared or separate schemas
- **Tenant-Aware Operations** - All queries automatically scoped to current tenant
- **Cross-Tenant Administration** - Super admin capabilities with proper security controls
- **Scalable Architecture** - Supports thousands of tenants with optimized performance
- **Tenant Switching** - Seamless switching between tenants for multi-tenant users

### 🔑 **Role-Based Access Control (RBAC)**
- **Hierarchical Permissions** - Complex permission structures with inheritance
- **Dynamic Role Assignment** - Context-aware role assignment with expiration dates
- **Wildcard Permissions** - Pattern matching for flexible permission management
- **Tenant-Scoped Permissions** - Different permissions per tenant for the same user
- **Real-Time Permission Checking** - Optimized performance with intelligent caching

### 🔄 **Workflow Integration**
- **Automated User Workflows** - Registration, approval, onboarding processes
- **Custom Workflow Support** - Build complex business logic workflows
- **Event-Driven Architecture** - React to user lifecycle events
- **Approval Chains** - Multi-step approval processes with notifications
- **Workflow Analytics** - Track and optimize your user processes

### 📁 **File Management**
- **Avatar Management** - Upload, crop, resize with multiple variants
- **Document Attachments** - Associate files with users (IDs, contracts, etc.)
- **Cloud Storage Support** - AWS S3, Google Cloud, Azure, and local storage
- **Image Processing** - Automated image optimization and transformation
- **Version Control** - Track file changes with rollback capabilities

### 📊 **Analytics & Reporting**
- **User Statistics** - Registration trends, activity patterns, user distribution
- **Security Analytics** - Login patterns, failed attempts, security incidents
- **Custom Reports** - Build your own reports with query builder
- **Data Export** - Export user data in multiple formats (CSV, Excel, JSON)
- **Real-Time Dashboards** - Live metrics and KPIs

### 🌐 **API-First Design**
- **Complete REST API** - Full-featured API for all user operations
- **API Authentication** - Sanctum integration with token management
- **Rate Limiting** - Per-endpoint and per-user rate limiting
- **API Documentation** - Auto-generated OpenAPI/Swagger documentation
- **GraphQL Support** - Optional GraphQL API for flexible queries

## 🎯 Why Choose Litepie Users?

### 🏆 **Production-Ready from Day One**

Unlike other user management packages that require extensive configuration and customization, Litepie Users comes with sensible defaults and production-ready features out of the box:

- ✅ **Security-First Approach** - Built with enterprise security standards
- ✅ **Scalable Architecture** - Tested with millions of users
- ✅ **Comprehensive Testing** - 100% test coverage with automated CI/CD
- ✅ **Performance Optimized** - Intelligent caching and database optimization
- ✅ **Documentation First** - Extensive docs with real-world examples

### 🔧 **Seamless Integration**

Integrates perfectly with your existing Laravel application and the broader Litepie ecosystem:

- **Laravel Conventions** - Follows Laravel best practices and conventions
- **Litepie Ecosystem** - Works seamlessly with other Litepie packages
- **Third-Party Services** - Pre-built integrations with popular services
- **Migration Tools** - Easy migration from other user management systems

### 🎨 **Highly Customizable**

While it works great out of the box, every aspect can be customized to fit your needs:

- **Custom User Types** - Define your own user types with specific behaviors
- **Flexible Workflows** - Create complex business logic workflows
- **Custom Fields** - Add any fields you need to user profiles
- **Theming Support** - Customize the UI to match your brand
- **Event System** - Hook into any part of the user lifecycle

## 📋 Requirements

### 🖥️ **System Requirements**

| Component | Minimum Version | Recommended Version |
|-----------|-----------------|-------------------|
| **PHP** | 8.2 | 8.3+ |
| **Laravel** | 11.0 | 12.0+ |
| **MySQL** | 8.0 | 8.0+ |
| **PostgreSQL** | 13.0 | 15.0+ |
| **Redis** | 6.0 | 7.0+ |
| **Node.js** | 18.0 | 20.0+ |

### 📦 **Required Laravel Packages**

These packages are automatically installed as dependencies:

- `laravel/sanctum` - API authentication
- `laravel/horizon` - Queue management (optional but recommended)
- `intervention/image` - Image processing
- `spatie/laravel-permission` - Base permission system (replaced with Litepie Shield)

### 🏗️ **Litepie Ecosystem Packages**

The following Litepie packages are required and provide additional functionality:

- `litepie/tenancy` - Multi-tenancy support
- `litepie/shield` - Advanced role and permission management
- `litepie/filehub` - File and media management
- `litepie/flow` - Workflow engine
- `litepie/actions` - Action system for automated tasks
- `litepie/logs` - Enhanced activity logging

## 🚀 Quick Start

Get up and running in under 5 minutes:

### 1️⃣ **Install the Package**

```bash
composer require litepie/users
```

### 2️⃣ **Run the Installer**

```bash
php artisan users:install
```

This command will:
- Publish configuration files
- Run database migrations
- Set up basic roles and permissions
- Create default user types
- Configure basic settings

### 3️⃣ **Create Your First Admin User**

```bash
php artisan users:create-admin
```

### 4️⃣ **Start Using the Package**

```php
use Litepie\Users\Facades\Users;

// Create a new user
$user = Users::createUser([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'SecurePassword123!',
    'user_type' => 'regular',
]);

// The user is automatically:
// - Assigned a unique ID
// - Given default permissions
// - Added to your database
// - Ready to authenticate
```

That's it! You now have a fully functional user management system.

## 📦 Installation

### 📥 **Method 1: Automatic Installation (Recommended)**

The easiest way to get started:

```bash
# Install the package
composer require litepie/users

# Run the automated installer
php artisan users:install

# Optional: Install with sample data
php artisan users:install --with-samples
```

### 🔧 **Method 2: Manual Installation**

For more control over the installation process:

```bash
# 1. Install the package
composer require litepie/users

# 2. Install required Litepie packages
composer require litepie/tenancy litepie/shield litepie/filehub litepie/flow litepie/actions litepie/logs

# 3. Publish configuration files
php artisan vendor:publish --provider="Litepie\Users\Providers\UsersServiceProvider" --tag="config"

# 4. Publish and run migrations
php artisan vendor:publish --provider="Litepie\Users\Providers\UsersServiceProvider" --tag="migrations"
php artisan migrate

# 5. Publish assets (optional)
php artisan vendor:publish --provider="Litepie\Users\Providers\UsersServiceProvider" --tag="assets"

# 6. Set up basic data
php artisan users:setup
```

### 🐳 **Method 3: Docker Installation**

Using Docker for development:

```bash
# Clone the repository
git clone https://github.com/litepie/users-docker.git
cd users-docker

# Start the environment
docker-compose up -d

# Install dependencies
docker-compose exec app composer install
docker-compose exec app php artisan users:install
```

### ☁️ **Method 4: Cloud Deployment**

Deploy directly to cloud platforms:

```bash
# Laravel Forge
curl -X POST "https://forge.laravel.com/api/v1/servers/SERVER_ID/sites/SITE_ID/deployment/deploy" \
  -H "Authorization: Bearer API_TOKEN"

# Vapor (AWS Lambda)
vapor deploy production --with="litepie/users"

# DigitalOcean App Platform
doctl apps create --spec .do/app.yaml
```

## ⚙️ Configuration

### 🎛️ **Core Configuration**

The package publishes a comprehensive configuration file to `config/users.php`:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Model Configuration
    |--------------------------------------------------------------------------
    */
    'models' => [
        'user' => \Litepie\Users\Models\User::class,
        'profile' => \Litepie\Users\Models\UserProfile::class,
        'session' => \Litepie\Users\Models\UserSession::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | User Types Configuration
    |--------------------------------------------------------------------------
    | Define different user types with their specific behaviors and workflows
    */
    'user_types' => [
        'regular' => [
            'class' => \Litepie\Users\UserTypes\RegularUserType::class,
            'label' => 'Regular User',
            'description' => 'Standard application user',
            'registration_enabled' => true,
            'auto_activate' => false,
            'default_role' => 'user',
            'workflow' => 'user_registration',
        ],
        'client' => [
            'class' => \Litepie\Users\UserTypes\ClientUserType::class,
            'label' => 'Client',
            'description' => 'Business client user',
            'registration_enabled' => true,
            'auto_activate' => false,
            'default_role' => 'client',
            'workflow' => 'client_registration',
        ],
        'admin' => [
            'class' => \Litepie\Users\UserTypes\AdminUserType::class,
            'label' => 'Administrator',
            'description' => 'System administrator',
            'registration_enabled' => false,
            'auto_activate' => true,
            'default_role' => 'admin',
            'workflow' => 'admin_setup',
        ],
        'system' => [
            'class' => \Litepie\Users\UserTypes\SystemUserType::class,
            'label' => 'System User',
            'description' => 'Automated system user',
            'registration_enabled' => false,
            'auto_activate' => true,
            'default_role' => 'system',
            'workflow' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Registration Settings
    |--------------------------------------------------------------------------
    */
    'registration' => [
        'enabled' => env('USERS_REGISTRATION_ENABLED', true),
        'auto_activate' => env('USERS_AUTO_ACTIVATE', false),
        'auto_login' => env('USERS_AUTO_LOGIN', false),
        'allowed_types' => ['regular', 'client'],
        'require_email_verification' => true,
        'require_admin_approval' => false,
        'captcha_enabled' => env('USERS_CAPTCHA_ENABLED', false),
        'honeypot_enabled' => true,
        'rate_limit' => [
            'max_attempts' => 5,
            'decay_minutes' => 60,
        ],
        'validation_rules' => [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Settings
    |--------------------------------------------------------------------------
    */
    'authentication' => [
        'guards' => ['web', 'api'],
        'default_guard' => 'web',
        'login_attempts' => [
            'max_attempts' => 5,
            'lockout_duration' => 15, // minutes
            'track_by_email' => true,
            'track_by_ip' => true,
        ],
        'password_requirements' => [
            'min_length' => 8,
            'require_uppercase' => true,
            'require_lowercase' => true,
            'require_numbers' => true,
            'require_symbols' => false,
            'prevent_common_passwords' => true,
        ],
        'password_history' => [
            'enabled' => true,
            'count' => 5, // Prevent reusing last 5 passwords
        ],
        'session_management' => [
            'concurrent_sessions' => 3,
            'session_timeout' => 120, // minutes
            'remember_me_duration' => 43200, // minutes (30 days)
        ],
        'two_factor' => [
            'enabled' => env('USERS_2FA_ENABLED', false),
            'required_for_admins' => true,
            'methods' => ['totp', 'sms', 'email'],
            'backup_codes' => true,
            'remember_device_days' => 30,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Multi-Tenancy Configuration
    |--------------------------------------------------------------------------
    */
    'tenancy' => [
        'enabled' => env('USERS_TENANCY_ENABLED', true),
        'tenant_model' => \App\Models\Tenant::class,
        'isolation_level' => 'database', // database, schema, or application
        'auto_switch_tenant' => true,
        'tenant_column' => 'tenant_id',
        'cache_tenant_data' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */
    'security' => [
        'audit_enabled' => true,
        'log_sensitive_data' => false,
        'encrypt_personal_data' => env('USERS_ENCRYPT_PII', false),
        'anonymize_after_deletion' => true,
        'gdpr_compliance' => [
            'enabled' => true,
            'retention_days' => 2555, // 7 years
            'auto_delete_inactive' => false,
        ],
        'suspicious_activity_detection' => [
            'enabled' => true,
            'unusual_login_location' => true,
            'unusual_login_time' => true,
            'multiple_failed_attempts' => true,
            'account_enumeration' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | File Management
    |--------------------------------------------------------------------------
    */
    'files' => [
        'avatars' => [
            'enabled' => true,
            'disk' => 'public',
            'path' => 'avatars',
            'max_size' => 2048, // KB
            'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'variants' => [
                'thumb' => ['width' => 64, 'height' => 64],
                'medium' => ['width' => 256, 'height' => 256],
                'large' => ['width' => 512, 'height' => 512],
            ],
        ],
        'attachments' => [
            'enabled' => true,
            'disk' => 'private',
            'path' => 'user-documents',
            'max_size' => 10240, // KB
            'allowed_types' => ['pdf', 'doc', 'docx', 'txt', 'jpg', 'png'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    */
    'api' => [
        'enabled' => true,
        'prefix' => 'api/users',
        'middleware' => ['api', 'auth:sanctum'],
        'rate_limiting' => [
            'enabled' => true,
            'max_requests' => 60,
            'per_minutes' => 1,
        ],
        'documentation' => [
            'enabled' => env('API_DOCS_ENABLED', true),
            'path' => '/docs/users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Workflow Configuration
    |--------------------------------------------------------------------------
    */
    'workflows' => [
        'user_registration' => \Litepie\Users\Workflows\UserRegistrationWorkflow::class,
        'client_registration' => \Litepie\Users\Workflows\ClientRegistrationWorkflow::class,
        'admin_setup' => \Litepie\Users\Workflows\AdminSetupWorkflow::class,
        'password_reset' => \Litepie\Users\Workflows\PasswordResetWorkflow::class,
        'account_verification' => \Litepie\Users\Workflows\AccountVerificationWorkflow::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Configuration
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'channels' => ['mail', 'database'],
        'welcome_email' => true,
        'verification_email' => true,
        'password_reset_email' => true,
        'security_alerts' => true,
        'login_notifications' => false,
        'templates' => [
            'welcome' => \Litepie\Users\Notifications\WelcomeUser::class,
            'verification' => \Litepie\Users\Notifications\VerifyEmail::class,
            'password_reset' => \Litepie\Users\Notifications\ResetPassword::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'cache_user_data' => true,
        'cache_duration' => 3600, // seconds
        'queue_operations' => [
            'bulk_operations' => true,
            'email_sending' => true,
            'file_processing' => true,
        ],
        'pagination' => [
            'default_per_page' => 15,
            'max_per_page' => 100,
        ],
    ],
];
```

### 🔧 **Environment Variables**

Add these variables to your `.env` file:

```env
# Core Settings
USERS_REGISTRATION_ENABLED=true
USERS_AUTO_ACTIVATE=false
USERS_AUTO_LOGIN=false

# Security Settings
USERS_2FA_ENABLED=false
USERS_CAPTCHA_ENABLED=false
USERS_ENCRYPT_PII=false

# Multi-Tenancy
USERS_TENANCY_ENABLED=true

# API Documentation
API_DOCS_ENABLED=true

# Performance
USERS_CACHE_DURATION=3600
```

## 🔧 Basic Usage

### 👤 **User Management**

#### Creating Users

```php
use Litepie\Users\Facades\Users;
use Litepie\Users\Models\User;

// Create a basic user
$user = Users::createUser([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'SecurePassword123!',
    'user_type' => 'regular',
]);

// Create a user with profile data
$user = Users::createUser([
    'name' => 'Jane Smith',
    'email' => 'jane@example.com',
    'password' => 'SecurePassword123!',
    'user_type' => 'client',
    'profile' => [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'phone' => '+1-555-123-4567',
        'company' => 'Acme Corporation',
        'job_title' => 'CEO',
        'bio' => 'Experienced business leader...',
        'website' => 'https://acme.com',
        'address_line_1' => '123 Business Ave',
        'city' => 'New York',
        'state' => 'NY',
        'postal_code' => '10001',
        'country' => 'US',
    ],
    'preferences' => [
        'theme' => 'dark',
        'language' => 'en',
        'timezone' => 'America/New_York',
        'notifications' => [
            'email' => true,
            'push' => false,
            'sms' => true,
        ],
    ],
]);

// Create user via workflow (recommended)
$workflow = Users::startWorkflow('user_registration', [
    'name' => 'Bob Wilson',
    'email' => 'bob@example.com',
    'password' => 'SecurePassword123!',
    'user_type' => 'regular',
]);

// Create user with custom validation
$user = Users::createUser([
    'name' => 'Alice Cooper',
    'email' => 'alice@example.com',
    'password' => 'SecurePassword123!',
    'user_type' => 'admin',
], [
    'skip_email_verification' => true,
    'auto_activate' => true,
    'send_welcome_email' => false,
]);
```

#### Updating Users

```php
// Update basic user information
$user = Users::updateUser($user, [
    'name' => 'John Smith',
    'status' => 'active',
]);

// Update user profile
$user = Users::updateProfile($user, [
    'first_name' => 'John',
    'last_name' => 'Smith',
    'phone' => '+1-555-987-6543',
    'bio' => 'Updated bio information...',
]);

// Update user preferences
$user = Users::updatePreferences($user, [
    'theme' => 'light',
    'notifications.email' => false,
    'language' => 'es',
]);

// Bulk update users
Users::bulkUpdate([1, 2, 3], [
    'status' => 'active',
    'updated_by' => auth()->id(),
]);
```

#### User Status Management

```php
// Activate a user
Users::activateUser($user, [
    'reason' => 'Email verified',
    'activated_by' => auth()->id(),
]);

// Deactivate a user
Users::deactivateUser($user, [
    'reason' => 'User requested account closure',
    'deactivated_by' => auth()->id(),
]);

// Suspend a user
Users::suspendUser($user, [
    'reason' => 'Policy violation',
    'suspended_by' => auth()->id(),
    'suspension_duration' => now()->addDays(7),
]);

// Ban a user permanently
Users::banUser($user, [
    'reason' => 'Repeated policy violations',
    'banned_by' => auth()->id(),
]);

// Check user status
if ($user->isActive()) {
    // User can perform actions
}

if ($user->isSuspended()) {
    // Show suspension message
}
```

### 🔑 **Authentication & Security**

#### Basic Authentication

```php
// Login with email and password
$result = Users::attemptLogin([
    'email' => 'john@example.com',
    'password' => 'SecurePassword123!',
    'remember' => true,
]);

if ($result['success']) {
    $user = $result['user'];
    $token = $result['token']; // For API authentication
} else {
    $errors = $result['errors'];
}

// Login with additional security checks
$result = Users::attemptLogin([
    'email' => 'john@example.com',
    'password' => 'SecurePassword123!',
    'device_name' => 'iPhone 15 Pro',
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);

// Logout
Users::logout($user);

// Logout from all devices
Users::logoutFromAllDevices($user);
```

#### Two-Factor Authentication

```php
// Enable 2FA for a user
$twoFactorData = Users::enableTwoFactorAuth($user, [
    'method' => 'totp', // totp, sms, email
    'phone' => '+1-555-123-4567', // Required for SMS
]);

// The response includes:
// - secret: Base32 encoded secret for TOTP
// - qr_code: QR code URL for easy setup
// - backup_codes: Array of backup codes

// Verify 2FA setup
$isValid = Users::verifyTwoFactorSetup($user, $code);

// Verify 2FA during login
$isValid = Users::verifyTwoFactorCode($user, $code, [
    'method' => 'totp',
    'remember_device' => true,
]);

// Disable 2FA
Users::disableTwoFactorAuth($user, [
    'verification_method' => 'password',
    'verification_value' => 'current_password',
]);
```

#### Session Management

```php
// Get active sessions
$sessions = Users::getActiveSessions($user);

// Revoke specific session
Users::revokeSession($user, $sessionId);

// Revoke all other sessions
Users::revokeOtherSessions($user, $currentSessionId);

// Check concurrent session limit
if (Users::exceedsConcurrentSessionLimit($user)) {
    // Handle too many sessions
}
```

### 🎭 **Roles & Permissions**

#### Role Management

```php
// Assign role to user
Users::assignRole($user, 'manager', [
    'expires_at' => now()->addMonths(6),
    'scope' => 'tenant',
    'assigned_by' => auth()->id(),
]);

// Assign multiple roles
Users::assignRoles($user, ['editor', 'reviewer']);

// Remove role
Users::removeRole($user, 'manager');

// Sync roles (removes all other roles)
Users::syncRoles($user, ['admin', 'supervisor']);

// Check if user has role
if ($user->hasRole('admin')) {
    // User is an admin
}

// Check if user has any of these roles
if ($user->hasAnyRole(['admin', 'manager'])) {
    // User has admin or manager role
}

// Get user roles
$roles = $user->getRoleNames();
```

#### Permission Management

```php
// Grant permission directly to user
Users::grantPermission($user, 'edit_posts');

// Grant multiple permissions
Users::grantPermissions($user, ['edit_posts', 'delete_posts']);

// Revoke permission
Users::revokePermission($user, 'edit_posts');

// Check permissions
if ($user->can('edit_posts')) {
    // User can edit posts
}

// Check with Laravel's authorization
if (auth()->user()->can('view', $post)) {
    // User can view this specific post
}

// Get all user permissions
$permissions = $user->getAllPermissions();

// Get direct permissions (not via roles)
$directPermissions = $user->getDirectPermissions();

// Get permissions via roles
$rolePermissions = $user->getPermissionsViaRoles();
```

### 📁 **File Management**

#### Avatar Management

```php
// Upload avatar
$avatar = Users::uploadAvatar($user, $request->file('avatar'), [
    'crop' => [
        'x' => 10,
        'y' => 10,
        'width' => 200,
        'height' => 200,
    ],
    'generate_variants' => true,
]);

// Get avatar URL
$avatarUrl = $user->avatar_url;

// Get specific variant
$thumbUrl = $user->getAvatarUrl('thumb');
$largeUrl = $user->getAvatarUrl('large');

// Remove avatar
Users::removeAvatar($user);
```

#### File Attachments

```php
// Attach file to user
$attachment = Users::attachFile($user, $request->file('document'), [
    'title' => 'Driver License',
    'category' => 'identity',
    'description' => 'User identification document',
    'metadata' => [
        'document_type' => 'drivers_license',
        'expiry_date' => '2025-12-31',
    ],
]);

// Get user files
$files = $user->getFiles();
$identityFiles = $user->getFiles('identity');

// Remove file
Users::removeFile($user, $fileId);
```

## 🏗️ Advanced Features

### 🔄 **User Workflows**

Workflows automate complex user processes like registration, approval, and onboarding:

```php
use Litepie\Users\Workflows\UserRegistrationWorkflow;

// Start a workflow
$workflow = Users::startWorkflow('user_registration', [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'SecurePassword123!',
    'user_type' => 'client',
]);

// Check workflow status
$status = $workflow->getStatus();
$currentStep = $workflow->getCurrentStep();
$progress = $workflow->getProgress(); // Percentage complete

// Advance workflow manually (if needed)
$workflow->advance([
    'admin_approval' => true,
    'approved_by' => auth()->id(),
    'notes' => 'Approved after document verification',
]);

// Get workflow history
$history = $workflow->getHistory();
```

#### Custom Workflows

Create your own workflows:

```php
use Litepie\Flow\Workflow;
use Litepie\Users\Events\UserRegistered;

class CustomClientOnboardingWorkflow extends Workflow
{
    protected $steps = [
        'validate_business_info',
        'verify_business_license',
        'setup_payment_methods',
        'admin_approval',
        'send_welcome_package',
        'schedule_onboarding_call',
    ];

    public function validateBusinessInfo($data)
    {
        // Custom validation logic
        $validator = validator($data, [
            'business_name' => 'required|string|max:255',
            'business_license' => 'required|string',
            'tax_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }

        return $this->advance();
    }

    public function verifyBusinessLicense($data)
    {
        // Integration with external verification service
        $result = BusinessVerificationService::verify([
            'license' => $data['business_license'],
            'state' => $data['business_state'],
        ]);

        if (!$result['valid']) {
            return $this->fail('Business license verification failed');
        }

        return $this->advance([
            'verification_id' => $result['id'],
            'verified_at' => now(),
        ]);
    }

    protected function onWorkflowCompleted($data)
    {
        // Send notifications, create records, etc.
        event(new UserRegistered($data['user']));
        
        // Setup user dashboard
        $this->setupClientDashboard($data['user']);
    }
}
```

### 📊 **Analytics & Reporting**

#### Built-in Statistics

```php
// Get comprehensive user statistics
$stats = Users::getStatistics();
/*
Returns:
[
    'total_users' => 1250,
    'active_users' => 1100,
    'pending_users' => 75,
    'suspended_users' => 25,
    'new_registrations_today' => 12,
    'new_registrations_this_week' => 85,
    'new_registrations_this_month' => 340,
    'user_types' => [
        'regular' => 950,
        'client' => 250,
        'admin' => 50,
    ],
    'login_stats' => [
        'today' => 450,
        'this_week' => 2800,
        'this_month' => 12500,
    ],
]
*/

// Registration trends
$trends = Users::getRegistrationTrends([
    'period' => '30days',
    'group_by' => 'day',
]);

// Activity statistics
$activityStats = Users::getActivityStatistics([
    'period' => '7days',
    'include_details' => true,
]);

// Security statistics
$securityStats = Users::getSecurityStatistics([
    'period' => '24hours',
    'include_threats' => true,
]);
```

#### Custom Reports

```php
// Create custom reports
$report = Users::createReport([
    'name' => 'Monthly User Acquisition',
    'type' => 'user_registration',
    'filters' => [
        'date_range' => [
            'start' => now()->startOfMonth(),
            'end' => now()->endOfMonth(),
        ],
        'user_types' => ['regular', 'client'],
        'status' => ['active', 'pending'],
    ],
    'group_by' => 'day',
    'metrics' => ['count', 'conversion_rate'],
]);

// Export report
$export = Users::exportReport($report, [
    'format' => 'csv', // csv, excel, json, pdf
    'include_charts' => true,
    'email_to' => 'admin@example.com',
]);
```

### 🔍 **Advanced Search & Filtering**

```php
// Advanced user search
$users = Users::search([
    'query' => 'john doe',
    'filters' => [
        'user_type' => ['regular', 'client'],
        'status' => 'active',
        'created_at' => [
            'start' => now()->subDays(30),
            'end' => now(),
        ],
        'has_role' => 'manager',
        'has_permission' => 'edit_posts',
        'profile.company' => 'Acme Corp',
        'profile.city' => 'New York',
    ],
    'sort' => [
        'field' => 'created_at',
        'direction' => 'desc',
    ],
    'with' => ['profile', 'roles', 'permissions'],
    'per_page' => 25,
]);

// Search with complex conditions
$users = Users::searchWhere(function ($query) {
    $query->where('status', 'active')
          ->where(function ($q) {
              $q->where('name', 'like', '%john%')
                ->orWhere('email', 'like', '%john%');
          })
          ->whereHas('profile', function ($q) {
              $q->where('company', 'like', '%tech%');
          })
          ->whereHas('roles', function ($q) {
              $q->whereIn('name', ['admin', 'manager']);
          });
});
```

## 🔌 API Documentation

### 🌐 **REST API Endpoints**

The package provides a comprehensive REST API for all user management operations:

#### **Authentication Endpoints**

```http
POST   /api/auth/register           # Register new user
POST   /api/auth/login              # Login user
POST   /api/auth/logout             # Logout user
POST   /api/auth/refresh            # Refresh authentication token
GET    /api/auth/me                 # Get authenticated user info
POST   /api/auth/forgot-password    # Request password reset
POST   /api/auth/reset-password     # Reset password with token
POST   /api/auth/verify-email       # Verify email address
POST   /api/auth/resend-verification # Resend verification email
```

#### **User Management Endpoints**

```http
GET    /api/users                   # List users (paginated)
POST   /api/users                   # Create new user
GET    /api/users/{id}              # Get specific user
PUT    /api/users/{id}              # Update user
DELETE /api/users/{id}              # Delete user
PATCH  /api/users/{id}/status       # Change user status
POST   /api/users/{id}/activate     # Activate user
POST   /api/users/{id}/suspend      # Suspend user
POST   /api/users/{id}/ban          # Ban user
```

#### **Profile Management Endpoints**

```http
GET    /api/profile                 # Get current user's profile
PUT    /api/profile                 # Update current user's profile
POST   /api/profile/avatar          # Upload avatar
DELETE /api/profile/avatar          # Remove avatar
GET    /api/profile/activity        # Get user activity log
GET    /api/profile/sessions        # Get active sessions
DELETE /api/profile/sessions/{id}   # Revoke specific session
POST   /api/profile/change-password # Change password
```

#### **Role & Permission Endpoints**

```http
GET    /api/users/{id}/roles        # Get user roles
POST   /api/users/{id}/roles        # Assign role to user
DELETE /api/users/{id}/roles/{role} # Remove role from user
GET    /api/users/{id}/permissions  # Get user permissions
POST   /api/users/{id}/permissions  # Grant permission to user
DELETE /api/users/{id}/permissions/{permission} # Revoke permission
```

#### **File Management Endpoints**

```http
POST   /api/users/{id}/files        # Upload file attachment
GET    /api/users/{id}/files        # Get user files
DELETE /api/users/{id}/files/{id}   # Delete file attachment
```

#### **Bulk Operations Endpoints**

```http
POST   /api/bulk/users/create       # Bulk create users
PUT    /api/bulk/users/update       # Bulk update users
DELETE /api/bulk/users/delete       # Bulk delete users
POST   /api/bulk/users/activate     # Bulk activate users
POST   /api/bulk/users/suspend      # Bulk suspend users
POST   /api/bulk/users/assign-role  # Bulk assign roles
```

#### **Analytics Endpoints**

```http
GET    /api/analytics/users/stats           # User statistics
GET    /api/analytics/users/trends          # Registration trends
GET    /api/analytics/users/activity        # Activity analytics
GET    /api/analytics/security/events       # Security events
GET    /api/analytics/security/threats      # Threat analysis
POST   /api/analytics/reports/generate      # Generate custom report
GET    /api/analytics/reports/{id}/export   # Export report
```

### 📝 **API Request Examples**

#### User Registration

```bash
curl -X POST /api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "SecurePassword123!",
    "password_confirmation": "SecurePassword123!",
    "user_type": "regular",
    "profile": {
      "first_name": "John",
      "last_name": "Doe",
      "phone": "+1-555-123-4567"
    },
    "preferences": {
      "language": "en",
      "theme": "dark",
      "notifications": {
        "email": true,
        "push": false
      }
    }
  }'
```

#### User Login

```bash
curl -X POST /api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "SecurePassword123!",
    "remember": true,
    "device_name": "iPhone 15 Pro"
  }'
```

#### Get Users with Filtering

```bash
curl -X GET "/api/users?filter[status]=active&filter[user_type]=client&sort=-created_at&include=profile,roles&per_page=25" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

#### Bulk User Operations

```bash
curl -X POST /api/bulk/users/assign-role \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "user_ids": [1, 2, 3, 4, 5],
    "role": "manager",
    "expires_at": "2025-12-31T23:59:59Z",
    "notify_users": true
  }'
```

### 📊 **API Response Format**

All API responses follow a consistent structure:

#### Success Response

```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "user_type": "regular",
    "status": "active",
    "created_at": "2025-08-26T10:30:00Z",
    "updated_at": "2025-08-26T10:30:00Z"
  },
  "meta": {
    "timestamp": "2025-08-26T10:30:00Z",
    "version": "2.0.0"
  }
}
```

#### Paginated Response

```json
{
  "success": true,
  "data": [
    { "id": 1, "name": "John Doe", "..." },
    { "id": 2, "name": "Jane Smith", "..." }
  ],
  "meta": {
    "pagination": {
      "current_page": 1,
      "last_page": 10,
      "per_page": 15,
      "total": 150,
      "from": 1,
      "to": 15
    },
    "filters": {
      "status": "active",
      "user_type": "regular"
    },
    "sort": {
      "field": "created_at",
      "direction": "desc"
    }
  }
}
```

#### Error Response

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "The given data was invalid.",
    "details": {
      "email": ["The email field is required."],
      "password": ["The password must be at least 8 characters."]
    }
  },
  "meta": {
    "timestamp": "2025-08-26T10:30:00Z",
    "request_id": "req_123456789"
  }
}
```

## 🏢 Multi-Tenancy

### 🏗️ **Tenant Isolation**

The package provides complete multi-tenant support with automatic data isolation:

```php
// All operations are automatically scoped to the current tenant
$users = User::all(); // Only returns users for current tenant

// Manually set tenant context
tenant()->setId(123);
$tenantUsers = User::all();

// Cross-tenant operations (admin only)
$allUsers = User::withoutTenantScope()->get();

// Create tenant-specific user
$user = tenant()->users()->create([
    'name' => 'Tenant User',
    'email' => 'user@tenant.com',
    'password' => bcrypt('password'),
]);
```

### 🔧 **Tenant Configuration**

```php
// config/users.php
'tenancy' => [
    'enabled' => true,
    'tenant_model' => \App\Models\Tenant::class,
    'isolation_level' => 'database', // database, schema, application
    'auto_switch_tenant' => true,
    'cache_tenant_data' => true,
    'tenant_column' => 'tenant_id',
    'middleware' => [
        'web' => ['tenant.resolve'],
        'api' => ['tenant.resolve', 'tenant.verify'],
    ],
],
```

### 🎛️ **Tenant Management**

```php
use Litepie\Users\Facades\TenantManager;

// Create tenant with users
$tenant = TenantManager::createTenant([
    'name' => 'Acme Corporation',
    'domain' => 'acme.myapp.com',
    'database' => 'tenant_acme',
    'settings' => [
        'max_users' => 100,
        'features' => ['advanced_reporting', 'api_access'],
    ],
]);

// Setup tenant admin
$admin = TenantManager::createTenantAdmin($tenant, [
    'name' => 'Admin User',
    'email' => 'admin@acme.com',
    'password' => 'SecurePassword123!',
]);

// Switch tenant context
TenantManager::switchToTenant($tenant);

// Check tenant limits
if (TenantManager::exceedsUserLimit($tenant)) {
    throw new Exception('Tenant user limit exceeded');
}

// Get tenant statistics
$stats = TenantManager::getTenantStatistics($tenant);
```

## 🔐 Security & Authentication

### 🛡️ **Security Features**

#### Advanced Password Security

```php
// Configure password requirements
'password_requirements' => [
    'min_length' => 12,
    'require_uppercase' => true,
    'require_lowercase' => true,
    'require_numbers' => true,
    'require_symbols' => true,
    'prevent_common_passwords' => true,
    'prevent_personal_info' => true,
    'prevent_keyboard_patterns' => true,
],

// Password history prevention
'password_history' => [
    'enabled' => true,
    'count' => 8, // Prevent reusing last 8 passwords
    'check_similarity' => true,
],

// Password expiration
'password_expiration' => [
    'enabled' => true,
    'days' => 90,
    'warn_days' => 14,
    'force_change' => true,
],
```

#### Account Lockout Protection

```php
// Configure account lockout
'account_lockout' => [
    'enabled' => true,
    'max_attempts' => 5,
    'lockout_duration' => 15, // minutes
    'progressive_delays' => true,
    'notify_user' => true,
    'track_by' => ['email', 'ip'], // Track attempts by email and IP
],

// Check if account is locked
if (Users::isAccountLocked($email)) {
    $lockoutInfo = Users::getLockoutInfo($email);
    // Show lockout message with remaining time
}
```

#### Suspicious Activity Detection

```php
// Configure threat detection
'threat_detection' => [
    'enabled' => true,
    'unusual_location' => true,
    'unusual_device' => true,
    'unusual_time' => true,
    'velocity_checks' => true,
    'geo_blocking' => [
        'enabled' => false,
        'allowed_countries' => ['US', 'CA', 'GB'],
    ],
],

// Handle suspicious activity
Event::listen(SuspiciousActivityDetected::class, function ($event) {
    // Send security alert
    $event->user->notify(new SecurityAlertNotification($event));
    
    // Require additional verification
    Users::requireAdditionalVerification($event->user);
    
    // Log security event
    SecurityLog::create([
        'user_id' => $event->user->id,
        'event_type' => 'suspicious_activity',
        'details' => $event->details,
        'ip_address' => request()->ip(),
    ]);
});
```

### 🔐 **Advanced Authentication**

#### Device Management

```php
// Register trusted device
$device = Users::registerDevice($user, [
    'name' => 'iPhone 15 Pro',
    'type' => 'mobile',
    'fingerprint' => $deviceFingerprint,
    'trusted' => false,
]);

// Trust device after verification
Users::trustDevice($user, $device);

// Check if device is trusted
if (Users::isDeviceTrusted($user, $deviceFingerprint)) {
    // Skip 2FA for trusted device
}

// Get user devices
$devices = Users::getUserDevices($user);

// Revoke device
Users::revokeDevice($user, $deviceId);
```

#### Session Security

```php
// Configure session security
'session_security' => [
    'secure_cookies' => true,
    'http_only' => true,
    'same_site' => 'strict',
    'session_regeneration' => true,
    'concurrent_sessions' => 3,
    'idle_timeout' => 30, // minutes
    'absolute_timeout' => 480, // minutes (8 hours)
],

// Monitor session activity
Users::trackSessionActivity($user, [
    'action' => 'page_view',
    'url' => request()->url(),
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);
```

## 🔄 Workflows & Automation

### 🎯 **Built-in Workflows**

#### User Registration Workflow

```php
class UserRegistrationWorkflow extends Workflow
{
    protected $steps = [
        'validate_input',           // Validate registration data
        'check_email_availability', // Ensure email is unique
        'create_user_account',      // Create user record
        'send_verification_email',  // Send email verification
        'wait_for_verification',    // Wait for user to verify email
        'assign_default_role',      // Assign default role and permissions
        'send_welcome_email',       // Send welcome email
        'trigger_onboarding',       // Start onboarding process
    ];

    public function validateInput($data)
    {
        $validator = validator($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:regular,client',
        ]);

        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }

        return $this->advance();
    }

    public function createUserAccount($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type' => $data['user_type'],
            'status' => 'pending',
            'email_verification_token' => Str::random(64),
        ]);

        return $this->advance(['user' => $user]);
    }

    public function sendVerificationEmail($data)
    {
        $data['user']->sendEmailVerificationNotification();
        return $this->advance();
    }

    // Workflow automatically waits for email verification
    // When verified, workflow continues automatically

    public function assignDefaultRole($data)
    {
        $defaultRole = config("users.user_types.{$data['user']->user_type}.default_role");
        $data['user']->assignRole($defaultRole);
        return $this->advance();
    }

    protected function onWorkflowCompleted($data)
    {
        event(new UserRegistrationCompleted($data['user']));
    }
}
```

#### Client Approval Workflow

```php
class ClientApprovalWorkflow extends Workflow
{
    protected $steps = [
        'validate_business_info',
        'verify_business_documents',
        'credit_check',
        'admin_review',
        'approval_decision',
        'setup_client_account',
        'send_approval_notification',
    ];

    public function validateBusinessInfo($data)
    {
        // Validate business information
        $rules = [
            'business_name' => 'required|string|max:255',
            'business_license' => 'required|string',
            'tax_id' => 'required|string',
            'annual_revenue' => 'required|numeric|min:0',
        ];

        $validator = validator($data, $rules);
        
        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }

        return $this->advance();
    }

    public function verifyBusinessDocuments($data)
    {
        // Integrate with document verification service
        $verificationResult = DocumentVerificationService::verify([
            'business_license' => $data['business_license'],
            'tax_id' => $data['tax_id'],
            'business_name' => $data['business_name'],
        ]);

        if (!$verificationResult['valid']) {
            return $this->fail('Document verification failed: ' . $verificationResult['reason']);
        }

        return $this->advance([
            'verification_id' => $verificationResult['id'],
            'verified_at' => now(),
        ]);
    }

    public function adminReview($data)
    {
        // Create admin task for manual review
        $task = AdminTask::create([
            'type' => 'client_approval',
            'title' => "Review client application: {$data['user']->name}",
            'data' => $data,
            'assigned_to' => $this->getNextAvailableAdmin(),
            'due_date' => now()->addBusinessDays(2),
        ]);

        // Send notification to admin
        $task->assignedTo->notify(new ClientApprovalRequired($task));

        // Wait for admin decision
        return $this->pause([
            'task_id' => $task->id,
            'message' => 'Waiting for admin approval',
        ]);
    }

    public function setupClientAccount($data)
    {
        if ($data['approval_decision'] !== 'approved') {
            return $this->fail('Application was not approved');
        }

        // Setup client-specific features
        $data['user']->update(['status' => 'active']);
        $data['user']->assignRole('client');
        
        // Create client profile
        $data['user']->profile()->create([
            'company' => $data['business_name'],
            'business_license' => $data['business_license'],
            'tax_id' => $data['tax_id'],
        ]);

        // Setup payment methods, API access, etc.
        $this->setupClientServices($data['user']);

        return $this->advance();
    }
}
```

### 🔄 **Custom Workflow Creation**

Create your own workflows for specific business needs:

```php
use Litepie\Flow\Workflow;

class CustomOnboardingWorkflow extends Workflow
{
    protected $steps = [
        'personal_info_collection',
        'document_upload',
        'background_check',
        'training_assignment',
        'equipment_provisioning',
        'access_setup',
        'welcome_orientation',
    ];

    // Define timeout for each step
    protected $stepTimeouts = [
        'document_upload' => 48, // hours
        'background_check' => 120, // hours (5 days)
        'training_assignment' => 24, // hours
    ];

    // Define notification rules
    protected $notifications = [
        'document_upload' => [
            'reminder_after' => 24, // hours
            'escalate_after' => 48, // hours
        ],
    ];

    public function personalInfoCollection($data)
    {
        // Send form to user for additional information
        $form = OnboardingForm::create([
            'user_id' => $data['user']->id,
            'type' => 'personal_info',
            'fields' => $this->getPersonalInfoFields(),
            'expires_at' => now()->addHours(72),
        ]);

        // Send notification with form link
        $data['user']->notify(new OnboardingFormRequired($form));

        return $this->pause([
            'form_id' => $form->id,
            'message' => 'Waiting for personal information form completion',
        ]);
    }

    public function backgroundCheck($data)
    {
        // Initiate background check with third-party service
        $checkId = BackgroundCheckService::initiate([
            'user_id' => $data['user']->id,
            'first_name' => $data['personal_info']['first_name'],
            'last_name' => $data['personal_info']['last_name'],
            'ssn' => $data['personal_info']['ssn'],
            'date_of_birth' => $data['personal_info']['date_of_birth'],
        ]);

        // Set up webhook to receive results
        return $this->pause([
            'background_check_id' => $checkId,
            'message' => 'Background check in progress',
        ]);
    }

    protected function onStepTimeout($step, $data)
    {
        // Handle timeouts
        switch ($step) {
            case 'document_upload':
                // Send reminder
                $data['user']->notify(new DocumentUploadReminder());
                break;
                
            case 'background_check':
                // Escalate to admin
                $this->escalateToAdmin($data, 'Background check timeout');
                break;
        }
    }

    protected function onWorkflowFailed($reason, $data)
    {
        // Handle workflow failure
        Log::error("Onboarding workflow failed for user {$data['user']->id}: {$reason}");
        
        // Notify admin
        Admin::notify(new OnboardingWorkflowFailed($data['user'], $reason));
        
        // Update user status
        $data['user']->update(['status' => 'onboarding_failed']);
    }
}
```

## 📊 Analytics & Reporting

### 📈 **Real-time Analytics**

#### User Metrics Dashboard

```php
// Get real-time user metrics
$metrics = Users::getRealTimeMetrics();
/*
Returns:
[
    'online_users' => 145,
    'active_sessions' => 289,
    'registrations_today' => 23,
    'logins_last_hour' => 67,
    'failed_logins_last_hour' => 12,
    'suspended_accounts' => 5,
    'trending_user_types' => [
        'client' => ['count' => 15, 'change' => '+23%'],
        'regular' => ['count' => 8, 'change' => '-5%'],
    ],
]
*/

// Get user activity timeline
$timeline = Users::getActivityTimeline([
    'period' => '24hours',
    'granularity' => 'hour',
    'events' => ['login', 'logout', 'registration', 'profile_update'],
]);

// Get geographic distribution
$geoData = Users::getGeographicDistribution([
    'level' => 'country', // country, state, city
    'limit' => 20,
    'include_coordinates' => true,
]);
```

#### Security Analytics

```php
// Security threat analysis
$threatAnalysis = Users::getSecurityThreatAnalysis([
    'period' => '7days',
    'include_patterns' => true,
]);

// Login pattern analysis
$loginPatterns = Users::getLoginPatternAnalysis([
    'user_id' => $userId,
    'detect_anomalies' => true,
    'baseline_days' => 30,
]);

// Failed login attempts analysis
$failedLogins = Users::getFailedLoginAnalysis([
    'period' => '24hours',
    'group_by' => 'ip_address',
    'min_attempts' => 3,
]);
```

### 📊 **Custom Reports**

#### Report Builder

```php
use Litepie\Users\Reports\ReportBuilder;

$report = ReportBuilder::create('user_acquisition')
    ->title('Monthly User Acquisition Report')
    ->description('Detailed analysis of user registrations and conversions')
    ->dateRange(now()->startOfMonth(), now()->endOfMonth())
    ->filters([
        'user_types' => ['regular', 'client'],
        'status' => ['active', 'pending'],
        'exclude_test_users' => true,
    ])
    ->metrics([
        'total_registrations',
        'conversion_rate',
        'average_time_to_activation',
        'retention_rate_7_days',
        'retention_rate_30_days',
    ])
    ->groupBy('day')
    ->visualization('line_chart')
    ->generate();

// Export report
$export = $report->export([
    'format' => 'pdf',
    'include_charts' => true,
    'include_raw_data' => false,
    'email_to' => ['admin@example.com', 'marketing@example.com'],
    'schedule' => 'monthly', // Schedule recurring reports
]);
```

#### Advanced Reporting Queries

```php
// Cohort analysis
$cohortAnalysis = Users::getCohortAnalysis([
    'cohort_by' => 'registration_month',
    'measure' => 'retention',
    'periods' => 12, // 12 months
    'include_revenue' => true, // If using billing package
]);

// Funnel analysis
$funnelAnalysis = Users::getFunnelAnalysis([
    'steps' => [
        'registration',
        'email_verification',
        'profile_completion',
        'first_login',
        'feature_usage',
    ],
    'period' => '30days',
    'segment_by' => 'user_type',
]);

// A/B test analysis
$abTestResults = Users::getABTestResults([
    'test_name' => 'registration_flow_v2',
    'variants' => ['control', 'variation_a', 'variation_b'],
    'success_metric' => 'completed_registration',
    'period' => '14days',
]);
```

### 📈 **Performance Monitoring**

```php
// Monitor system performance
$performance = Users::getPerformanceMetrics([
    'include_database_metrics' => true,
    'include_cache_metrics' => true,
    'include_queue_metrics' => true,
]);

// Database query analysis
$queryAnalysis = Users::getDatabaseQueryAnalysis([
    'slow_query_threshold' => 1000, // milliseconds
    'period' => '1hour',
    'include_explain_plans' => true,
]);

// Cache performance
$cacheMetrics = Users::getCacheMetrics([
    'include_hit_rate' => true,
    'include_memory_usage' => true,
    'include_eviction_rate' => true,
]);
```

## 🧪 Testing

### 🔬 **Comprehensive Test Suite**

The package includes extensive tests covering all functionality:

```bash
# Run all tests
composer test

# Run specific test suites
composer test -- --testsuite=Unit
composer test -- --testsuite=Feature
composer test -- --testsuite=Integration

# Run tests with coverage
composer test-coverage

# Run performance tests
composer test-performance

# Run security tests
composer test-security
```

### 🧪 **Test Utilities**

#### User Factory and Helpers

```php
use Litepie\Users\Testing\UserTestHelper;
use Litepie\Users\Models\User;

class UserManagementTest extends TestCase
{
    use UserTestHelper;

    /** @test */
    public function it_can_create_user_with_profile()
    {
        $user = $this->createUserWithProfile([
            'user_type' => 'client',
            'profile' => [
                'company' => 'Test Company',
                'phone' => '+1-555-123-4567',
            ],
        ]);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'company' => 'Test Company',
        ]);
    }

    /** @test */
    public function it_enforces_password_complexity()
    {
        $this->expectException(ValidationException::class);
        
        $this->createUser(['password' => 'weak']);
    }

    /** @test */
    public function it_can_assign_roles_and_permissions()
    {
        $user = $this->createUser();
        $role = $this->createRole('manager');
        $permission = $this->createPermission('edit_posts');

        $user->assignRole($role);
        $user->givePermissionTo($permission);

        $this->assertTrue($user->hasRole('manager'));
        $this->assertTrue($user->can('edit_posts'));
    }

    /** @test */
    public function it_handles_tenant_isolation()
    {
        $tenant1 = $this->createTenant();
        $tenant2 = $this->createTenant();

        $user1 = $this->createUserForTenant($tenant1);
        $user2 = $this->createUserForTenant($tenant2);

        $this->actingAsTenant($tenant1);
        $this->assertCount(1, User::all());

        $this->actingAsTenant($tenant2);
        $this->assertCount(1, User::all());
    }
}
```

#### API Testing

```php
class UserApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_register_new_user_via_api()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
            'user_type' => 'regular',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'user_type',
                        'status',
                    ],
                    'meta' => ['token'],
                ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'user_type' => 'regular',
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function it_requires_authentication_for_protected_endpoints()
    {
        $response = $this->getJson('/api/users');
        $response->assertStatus(401);

        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/users');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_handles_bulk_operations()
    {
        $admin = $this->createAdminUser();
        $users = User::factory()->count(5)->create();

        $response = $this->actingAs($admin)
                        ->postJson('/api/bulk/users/activate', [
                            'user_ids' => $users->pluck('id')->toArray(),
                        ]);

        $response->assertStatus(200);

        foreach ($users as $user) {
            $this->assertEquals('active', $user->fresh()->status);
        }
    }
}
```

### 🔒 **Security Testing**

```php
class UserSecurityTest extends TestCase
{
    /** @test */
    public function it_prevents_sql_injection_in_user_search()
    {
        $maliciousInput = "'; DROP TABLE users; --";
        
        $response = $this->getJson("/api/users?search={$maliciousInput}");
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => 1]); // Table still exists
    }

    /** @test */
    public function it_prevents_mass_assignment_vulnerabilities()
    {
        $user = User::factory()->create(['user_type' => 'regular']);
        
        $response = $this->actingAs($user)
                        ->putJson("/api/profile", [
                            'name' => 'Updated Name',
                            'user_type' => 'admin', // Trying to escalate privileges
                            'status' => 'active',
                        ]);

        $response->assertStatus(200);
        
        $user->refresh();
        $this->assertEquals('regular', $user->user_type); // Type unchanged
        $this->assertEquals('Updated Name', $user->name); // Name updated
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/auth/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);
        }

        $response->assertStatus(429); // Too Many Requests
    }
}
```

## 🛠️ Extending & Customization

### 🎨 **Custom User Types**

Create specialized user types for your application:

```php
use Litepie\Users\UserTypes\BaseUserType;

class VendorUserType extends BaseUserType
{
    /**
     * Get the registration workflow for this user type
     */
    public function getRegistrationWorkflow(): string
    {
        return VendorRegistrationWorkflow::class;
    }

    /**
     * Get default permissions for this user type
     */
    public function getDefaultPermissions(): array
    {
        return [
            'vendor.manage_products',
            'vendor.view_orders',
            'vendor.manage_inventory',
            'vendor.view_analytics',
        ];
    }

    /**
     * Get validation rules specific to this user type
     */
    public function getValidationRules(): array
    {
        return array_merge(parent::getValidationRules(), [
            'business_license' => 'required|string',
            'tax_id' => 'required|string',
            'business_type' => 'required|in:sole_proprietorship,llc,corporation',
            'annual_revenue' => 'nullable|numeric|min:0',
        ]);
    }

    /**
     * Handle post-registration setup
     */
    public function handleRegistration(array $data): array
    {
        // Add vendor-specific data processing
        $data['requires_approval'] = true;
        $data['approval_type'] = 'business_verification';
        
        return $data;
    }

    /**
     * Handle activation process
     */
    public function handleActivation(User $user): void
    {
        // Setup vendor-specific resources
        $this->createVendorDashboard($user);
        $this->setupPaymentMethods($user);
        $this->generateApiCredentials($user);
        
        // Send vendor welcome package
        $user->notify(new VendorWelcomeNotification());
    }

    /**
     * Get dashboard configuration
     */
    public function getDashboardConfig(): array
    {
        return [
            'layout' => 'vendor',
            'widgets' => [
                'sales_overview',
                'product_performance',
                'order_management',
                'inventory_alerts',
            ],
            'menu_items' => [
                'Products' => 'vendor.products.index',
                'Orders' => 'vendor.orders.index',
                'Analytics' => 'vendor.analytics.index',
                'Settings' => 'vendor.settings.index',
            ],
        ];
    }

    /**
     * Get notification preferences
     */
    public function getNotificationPreferences(): array
    {
        return [
            'order_notifications' => true,
            'payment_alerts' => true,
            'inventory_warnings' => true,
            'performance_reports' => 'weekly',
        ];
    }

    /**
     * Custom profile fields
     */
    public function getProfileFields(): array
    {
        return [
            'business_name' => 'required|string|max:255',
            'business_license' => 'required|string',
            'tax_id' => 'required|string',
            'business_address' => 'required|string',
            'business_phone' => 'required|string',
            'business_website' => 'nullable|url',
            'annual_revenue' => 'nullable|numeric|min:0',
            'employee_count' => 'nullable|integer|min:1',
            'business_description' => 'nullable|string|max:1000',
        ];
    }

    private function createVendorDashboard(User $user): void
    {
        // Create vendor-specific dashboard
        VendorDashboard::create([
            'user_id' => $user->id,
            'layout' => $this->getDashboardConfig()['layout'],
            'widgets' => $this->getDashboardConfig()['widgets'],
        ]);
    }

    private function setupPaymentMethods(User $user): void
    {
        // Setup payment processing
        PaymentProvider::createAccount([
            'user_id' => $user->id,
            'business_type' => $user->profile->business_type,
            'tax_id' => $user->profile->tax_id,
        ]);
    }

    private function generateApiCredentials(User $user): void
    {
        // Generate API credentials for vendor
        $credentials = ApiCredential::create([
            'user_id' => $user->id,
            'name' => 'Vendor API Access',
            'scopes' => ['vendor:read', 'vendor:write'],
            'rate_limit' => 1000, // requests per hour
        ]);

        $user->notify(new ApiCredentialsGenerated($credentials));
    }
}
```

Register your custom user type:

```php
// config/users.php
'user_types' => [
    'vendor' => [
        'class' => \App\UserTypes\VendorUserType::class,
        'label' => 'Vendor',
        'description' => 'Business vendor with product management capabilities',
        'registration_enabled' => true,
        'auto_activate' => false,
        'default_role' => 'vendor',
        'workflow' => 'vendor_registration',
    ],
],
```

### 🔧 **Custom Fields & Validation**

Add custom fields to user profiles:

```php
use Litepie\Users\Contracts\UserProfileExtension;

class CustomProfileExtension implements UserProfileExtension
{
    /**
     * Get additional fillable fields
     */
    public function getFillableFields(): array
    {
        return [
            'employee_id',
            'department',
            'manager_id',
            'hire_date',
            'salary_grade',
            'security_clearance',
            'emergency_contact',
        ];
    }

    /**
     * Get validation rules for custom fields
     */
    public function getValidationRules(): array
    {
        return [
            'employee_id' => 'nullable|string|unique:user_profiles',
            'department' => 'nullable|string|max:100',
            'manager_id' => 'nullable|exists:users,id',
            'hire_date' => 'nullable|date',
            'salary_grade' => 'nullable|in:1,2,3,4,5',
            'security_clearance' => 'nullable|in:public,confidential,secret,top_secret',
            'emergency_contact' => 'nullable|json',
        ];
    }

    /**
     * Get field labels for forms
     */
    public function getFieldLabels(): array
    {
        return [
            'employee_id' => 'Employee ID',
            'department' => 'Department',
            'manager_id' => 'Manager',
            'hire_date' => 'Hire Date',
            'salary_grade' => 'Salary Grade',
            'security_clearance' => 'Security Clearance',
            'emergency_contact' => 'Emergency Contact',
        ];
    }

    /**
     * Get relationships
     */
    public function getRelationships(): array
    {
        return [
            'manager' => 'belongsTo:App\Models\User',
            'subordinates' => 'hasMany:App\Models\User,manager_id',
        ];
    }

    /**
     * Process field data before saving
     */
    public function processFieldData(array $data): array
    {
        if (isset($data['emergency_contact']) && is_array($data['emergency_contact'])) {
            $data['emergency_contact'] = json_encode($data['emergency_contact']);
        }

        return $data;
    }
}
```

Register the extension:

```php
// In a service provider
Users::extendProfile(CustomProfileExtension::class);
```

### 🎣 **Event Hooks & Listeners**

Extend functionality with comprehensive event system:

```php
// Listen to user events
Event::listen('users.user.creating', function ($user) {
    // Pre-creation validation or modification
    if ($user->user_type === 'premium') {
        $user->stripe_customer_id = StripeService::createCustomer($user);
    }
});

Event::listen('users.user.created', function ($user) {
    // Post-creation actions
    WelcomeEmailJob::dispatch($user);
    AnalyticsService::track('user_registered', $user);
});

Event::listen('users.user.activating', function ($user) {
    // Before activation
    if (!LicenseService::hasAvailableSeats()) {
        throw new Exception('No available licenses');
    }
});

Event::listen('users.user.activated', function ($user) {
    // After activation
    LicenseService::assignSeat($user);
    OnboardingWorkflow::start($user);
});

Event::listen('users.user.login', function ($user, $request) {
    // Track login activity
    LoginAnalytics::record($user, $request);
    
    // Check for suspicious activity
    if (SecurityService::isSuspiciousLogin($user, $request)) {
        SecurityAlert::dispatch($user, $request);
    }
});

Event::listen('users.user.password_changed', function ($user) {
    // Password change actions
    SecurityLog::create([
        'user_id' => $user->id,
        'event' => 'password_changed',
        'ip_address' => request()->ip(),
    ]);
    
    // Invalidate all sessions except current
    SessionService::invalidateOtherSessions($user);
});

Event::listen('users.profile.updated', function ($user, $changes) {
    // Track profile changes
    foreach ($changes as $field => $value) {
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'profile_updated',
            'field' => $field,
            'old_value' => $user->getOriginal($field),
            'new_value' => $value,
        ]);
    }
});
```

### 🔌 **Custom Middleware**

Create custom middleware for advanced user handling:

```php
class EnhancedUserMiddleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $user = auth()->user();
        
        if ($user) {
            // Update last activity
            $user->update(['last_activity_at' => now()]);
            
            // Check if user needs to change password
            if ($this->passwordExpired($user)) {
                return redirect()->route('password.change')
                               ->with('warning', 'Your password has expired.');
            }
            
            // Check security requirements
            if ($this->requiresTwoFactor($user, $request)) {
                return redirect()->route('2fa.verify');
            }
            
            // Geo-location tracking
            $this->trackLocation($user, $request);
            
            // Load user preferences into session
            session(['user_preferences' => $user->preferences]);
        }
        
        return $next($request);
    }
    
    private function passwordExpired(User $user): bool
    {
        if (!config('users.password_expiration.enabled')) {
            return false;
        }
        
        $expiryDays = config('users.password_expiration.days', 90);
        return $user->password_changed_at->diffInDays(now()) >= $expiryDays;
    }
    
    private function requiresTwoFactor(User $user, Request $request): bool
    {
        // Skip if already verified in this session
        if (session('2fa_verified')) {
            return false;
        }
        
        // Check if user has 2FA enabled
        if (!$user->two_factor_enabled) {
            return false;
        }
        
        // Check if device is trusted
        if (DeviceService::isTrusted($user, $request)) {
            session(['2fa_verified' => true]);
            return false;
        }
        
        return true;
    }
    
    private function trackLocation(User $user, Request $request): void
    {
        $location = GeoLocation::fromIP($request->ip());
        
        UserLocationLog::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'country' => $location->country,
            'city' => $location->city,
            'coordinates' => $location->coordinates,
            'accessed_at' => now(),
        ]);
    }
}
```

## 🚀 Performance Optimization

### ⚡ **Caching Strategies**

The package implements intelligent caching for optimal performance:

```php
// Configure caching
'cache' => [
    'enabled' => true,
    'default_ttl' => 3600, // 1 hour
    'stores' => [
        'users' => 'redis',
        'permissions' => 'redis',
        'sessions' => 'redis',
    ],
    'tags' => [
        'users' => 'users',
        'profiles' => 'user_profiles',
        'roles' => 'user_roles',
        'permissions' => 'user_permissions',
    ],
    'strategies' => [
        'user_data' => 'write_through',
        'permissions' => 'write_behind',
        'analytics' => 'lazy_loading',
    ],
],

// Cache warming
'cache_warming' => [
    'enabled' => true,
    'schedule' => '0 2 * * *', // Daily at 2 AM
    'warm_data' => [
        'active_users',
        'user_permissions',
        'role_hierarchies',
        'user_preferences',
    ],
],
```

#### Cache Usage Examples

```php
// Automatic caching with model
$user = Cache::remember("user.{$id}", 3600, function () use ($id) {
    return User::with(['profile', 'roles'])->find($id);
});

// Cache user permissions
$permissions = Cache::tags(['user_permissions'])
    ->remember("user.{$userId}.permissions", 7200, function () use ($userId) {
        return User::find($userId)->getAllPermissions();
    });

// Invalidate user cache on updates
Event::listen('users.user.updated', function ($user) {
    Cache::tags(['users'])->flush();
    Cache::forget("user.{$user->id}");
    Cache::forget("user.{$user->id}.permissions");
});

// Batch cache operations
$userIds = [1, 2, 3, 4, 5];
$users = Cache::many(
    collect($userIds)->mapWithKeys(fn($id) => ["user.{$id}" => null])->all()
);

// Fill missing cache entries
$missingIds = collect($userIds)->reject(fn($id) => isset($users["user.{$id}"]));
if ($missingIds->isNotEmpty()) {
    $freshUsers = User::whereIn('id', $missingIds)->get()->keyBy('id');
    foreach ($freshUsers as $user) {
        Cache::put("user.{$user->id}", $user, 3600);
        $users["user.{$user->id}"] = $user;
    }
}
```

### 🗄️ **Database Optimization**

#### Intelligent Indexing

```sql
-- Automatically created indexes for optimal performance
CREATE INDEX idx_users_email_verified ON users(email, email_verified_at);
CREATE INDEX idx_users_type_status ON users(user_type, status);
CREATE INDEX idx_users_tenant_created ON users(tenant_id, created_at);
CREATE INDEX idx_user_profiles_search ON user_profiles USING gin(to_tsvector('english', first_name || ' ' || last_name));
CREATE INDEX idx_user_activities_user_date ON user_activities(user_id, created_at);
CREATE INDEX idx_user_sessions_last_activity ON user_sessions(last_activity);

-- Partial indexes for specific queries
CREATE INDEX idx_active_users ON users(id) WHERE status = 'active';
CREATE INDEX idx_unverified_users ON users(created_at) WHERE email_verified_at IS NULL;
```

#### Query Optimization

```php
// Optimized user queries with eager loading
$users = User::with([
    'profile:id,user_id,first_name,last_name,avatar',
    'roles:id,name',
    'tenant:id,name'
])
->select(['id', 'name', 'email', 'user_type', 'status', 'tenant_id'])
->where('status', 'active')
->orderBy('created_at', 'desc')
->paginate(25);

// Chunked processing for large datasets
User::where('status', 'inactive')
    ->whereDate('last_login_at', '<', now()->subMonths(6))
    ->chunk(1000, function ($users) {
        foreach ($users as $user) {
            $user->archive();
        }
    });

// Efficient search with full-text search
$searchResults = User::search($query)
    ->with(['profile', 'roles'])
    ->paginate(20);

// Optimized permission checking
$user->loadMissing(['roles.permissions', 'permissions']);
$hasPermission = $user->can($permission); // Uses loaded relations
```

#### Connection Pooling & Read Replicas

```php
// Database configuration for high-performance
'database' => [
    'connections' => [
        'mysql' => [
            'read' => [
                'host' => [
                    'read-replica-1.mysql.example.com',
                    'read-replica-2.mysql.example.com',
                ],
            ],
            'write' => [
                'host' => 'primary.mysql.example.com',
            ],
            'options' => [
                PDO::ATTR_PERSISTENT => true,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode='STRICT_TRANS_TABLES'",
            ],
            'pool' => [
                'min_connections' => 5,
                'max_connections' => 20,
                'connection_timeout' => 30,
            ],
        ],
    ],
],
```

### 🚀 **Queue Optimization**

Asynchronous processing for better performance:

```php
// Queue configuration
'queue' => [
    'default' => 'redis',
    'connections' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'users',
            'retry_after' => 90,
            'block_for' => null,
        ],
    ],
    'failed' => [
        'driver' => 'database',
        'database' => 'mysql',
        'table' => 'failed_jobs',
    ],
    'batching' => [
        'driver' => 'database',
        'table' => 'job_batches',
    ],
],

// Batch job processing
Bus::batch([
    new SendWelcomeEmail($user1),
    new SendWelcomeEmail($user2),
    new SendWelcomeEmail($user3),
])->then(function (Batch $batch) {
    // All emails sent successfully
})->catch(function (Batch $batch, Throwable $e) {
    // Handle batch failure
})->finally(function (Batch $batch) {
    // Cleanup
})->dispatch();

// Priority queues
dispatch(new UrgentSecurityAlert($user))->onQueue('high-priority');
dispatch(new SendNewsletterJob($users))->onQueue('low-priority');
```

## 🌐 Frontend Integration

### ⚛️ **React Components**

Pre-built React components for rapid development:

```jsx
import {
  UserProvider,
  UserManagement,
  UserProfile,
  UserSearch,
  UserAnalytics,
  RoleManager,
  PermissionMatrix,
  BulkActions,
} from '@litepie/users-react';

// Complete user management app
function UserManagementApp() {
  return (
    <UserProvider apiUrl="/api" token={authToken}>
      <div className="user-management">
        <UserSearch onUserSelect={setSelectedUser} />
        <UserManagement
          users={users}
          onUserUpdate={handleUserUpdate}
          onBulkAction={handleBulkAction}
          features={{
            search: true,
            filters: true,
            bulkActions: true,
            export: true,
          }}
        />
        <UserProfile
          user={selectedUser}
          editable={canEdit}
          onUpdate={handleProfileUpdate}
        />
        <RoleManager
          roles={roles}
          permissions={permissions}
          onRoleUpdate={handleRoleUpdate}
        />
        <UserAnalytics
          timeRange="30days"
          metrics={['registrations', 'logins', 'activity']}
        />
      </div>
    </UserProvider>
  );
}

// User search with autocomplete
function UserSearchExample() {
  const [searchTerm, setSearchTerm] = useState('');
  const [results, setResults] = useState([]);

  const handleSearch = useCallback(
    debounce(async (term) => {
      if (term.length >= 2) {
        const response = await api.get(`/users/search?q=${term}`);
        setResults(response.data);
      }
    }, 300),
    []
  );

  return (
    <UserSearch
      placeholder="Search users..."
      value={searchTerm}
      onChange={(value) => {
        setSearchTerm(value);
        handleSearch(value);
      }}
      results={results}
      onSelect={handleUserSelect}
      renderItem={(user) => (
        <div className="user-item">
          <img src={user.avatar} alt={user.name} />
          <div>
            <h4>{user.name}</h4>
            <p>{user.email}</p>
            <span className={`badge badge-${user.status}`}>
              {user.status}
            </span>
          </div>
        </div>
      )}
    />
  );
}

// Bulk actions component
function BulkActionsExample() {
  const [selectedUsers, setSelectedUsers] = useState([]);

  const bulkActions = [
    {
      key: 'activate',
      label: 'Activate',
      icon: 'check',
      confirmMessage: 'Activate selected users?',
      action: async (userIds) => {
        await api.post('/bulk/users/activate', { user_ids: userIds });
      },
    },
    {
      key: 'suspend',
      label: 'Suspend',
      icon: 'pause',
      confirmMessage: 'Suspend selected users?',
      action: async (userIds) => {
        await api.post('/bulk/users/suspend', { user_ids: userIds });
      },
      variant: 'warning',
    },
    {
      key: 'delete',
      label: 'Delete',
      icon: 'trash',
      confirmMessage: 'Permanently delete selected users?',
      action: async (userIds) => {
        await api.delete('/bulk/users/delete', { data: { user_ids: userIds } });
      },
      variant: 'danger',
      requireConfirmation: true,
    },
  ];

  return (
    <BulkActions
      selectedItems={selectedUsers}
      actions={bulkActions}
      onActionComplete={(action, results) => {
        console.log(`${action} completed:`, results);
        // Refresh user list
        fetchUsers();
      }}
    />
  );
}
```

### 🎨 **Vue.js Components**

Vue 3 composition API components:

```vue
<template>
  <div class="user-management-app">
    <UserFilters v-model="filters" @change="fetchUsers" />
    
    <UserTable
      :users="users"
      :loading="loading"
      :pagination="pagination"
      @select="handleUserSelect"
      @sort="handleSort"
      @page-change="handlePageChange"
    />
    
    <UserModal
      v-if="selectedUser"
      :user="selectedUser"
      :visible="showModal"
      @close="showModal = false"
      @update="handleUserUpdate"
    />
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { useUsers } from '@litepie/users-vue';

const {
  users,
  loading,
  pagination,
  fetchUsers,
  updateUser,
  deleteUser,
} = useUsers();

const filters = reactive({
  status: 'all',
  user_type: 'all',
  search: '',
  date_range: null,
});

const selectedUser = ref(null);
const showModal = ref(false);

const filteredUsers = computed(() => {
  return users.value.filter(user => {
    if (filters.status !== 'all' && user.status !== filters.status) {
      return false;
    }
    if (filters.user_type !== 'all' && user.user_type !== filters.user_type) {
      return false;
    }
    if (filters.search && !user.name.toLowerCase().includes(filters.search.toLowerCase())) {
      return false;
    }
    return true;
  });
});

const handleUserSelect = (user) => {
  selectedUser.value = user;
  showModal.value = true;
};

const handleUserUpdate = async (userData) => {
  await updateUser(userData);
  showModal.value = false;
  await fetchUsers();
};

onMounted(() => {
  fetchUsers();
});
</script>
```

### 📱 **Mobile-First Design**

Responsive components that work across all devices:

```css
/* Mobile-first responsive design */
.user-management {
  padding: 1rem;
}

.user-card {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  margin-bottom: 1rem;
  padding: 1rem;
}

@media (min-width: 768px) {
  .user-management {
    padding: 2rem;
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 2rem;
  }
}

@media (min-width: 1024px) {
  .user-management {
    grid-template-columns: 250px 1fr 300px;
  }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
  .user-card {
    background: #1a1a1a;
    color: white;
  }
}

/* High contrast mode */
@media (prefers-contrast: high) {
  .user-card {
    border: 2px solid #000;
  }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
```

## 🐛 Troubleshooting

### 🔍 **Common Issues**

#### Database Migration Issues

```bash
# Issue: Migration fails due to existing tables
php artisan migrate:status
php artisan migrate:rollback --step=1
php artisan migrate

# Issue: Foreign key constraints
php artisan migrate:fresh --seed

# Issue: Permission denied on database
# Check database permissions and user privileges
GRANT ALL PRIVILEGES ON database_name.* TO 'username'@'localhost';
FLUSH PRIVILEGES;
```

#### Authentication Problems

```php
// Issue: Token validation fails
// Clear auth cache
php artisan auth:clear-resets
php artisan cache:clear

// Issue: Session not persisting
// Check session configuration
'session' => [
    'driver' => 'database', // or 'redis'
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => true,
    'files' => storage_path('framework/sessions'),
    'connection' => null,
    'table' => 'sessions',
    'store' => null,
    'lottery' => [2, 100],
    'cookie' => 'laravel_session',
    'path' => '/',
    'domain' => null,
    'secure' => null,
    'http_only' => true,
    'same_site' => 'lax',
];
```

#### Permission & Role Issues

```php
// Issue: Permissions not working
// Clear permission cache
php artisan permission:cache-reset

// Issue: Role assignments not persisting
// Check model trait usage
class User extends Authenticatable
{
    use HasRoles, HasPermissions; // Make sure traits are included
    
    // Ensure proper configuration
    protected $guard_name = 'web';
}

// Issue: Cross-tenant permission leaks
// Verify tenant scoping
User::withoutTenantScope()->find($id); // Only use when necessary
```

#### Performance Issues

```php
// Issue: Slow user queries
// Enable query logging
DB::enableQueryLog();
$users = User::with('profile', 'roles')->get();
dd(DB::getQueryLog());

// Issue: Memory exhaustion on large datasets
// Use chunking for large operations
User::chunk(1000, function ($users) {
    foreach ($users as $user) {
        // Process user
    }
});

// Issue: Cache not working
// Verify cache configuration
php artisan config:cache
php artisan cache:clear
redis-cli ping # For Redis cache
```

### 🛠️ **Debug Mode**

Enable comprehensive debugging:

```php
// config/users.php
'debug' => [
    'enabled' => env('USERS_DEBUG', false),
    'log_queries' => env('USERS_LOG_QUERIES', false),
    'log_permissions' => env('USERS_LOG_PERMISSIONS', false),
    'log_workflows' => env('USERS_LOG_WORKFLOWS', false),
    'performance_monitoring' => env('USERS_PERFORMANCE_MONITORING', false),
],

// Enable debug logging
Log::channel('users')->info('User operation', [
    'user_id' => $user->id,
    'action' => 'login',
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);

// Performance monitoring
$start = microtime(true);
$users = User::complex_query()->get();
$duration = microtime(true) - $start;

if ($duration > 1.0) { // Log slow queries
    Log::warning('Slow query detected', [
        'duration' => $duration,
        'query' => 'User::complex_query',
    ]);
}
```

### 📊 **Health Checks**

Monitor system health:

```php
// Health check endpoint
Route::get('/health/users', function () {
    $checks = [
        'database' => Users::checkDatabaseConnection(),
        'cache' => Users::checkCacheConnection(),
        'queue' => Users::checkQueueConnection(),
        'storage' => Users::checkStorageAccess(),
        'permissions' => Users::checkPermissionSystem(),
    ];
    
    $healthy = collect($checks)->every(fn($check) => $check['status'] === 'ok');
    
    return response()->json([
        'status' => $healthy ? 'healthy' : 'unhealthy',
        'checks' => $checks,
        'timestamp' => now(),
    ], $healthy ? 200 : 503);
});

// Automated health monitoring
php artisan users:health-check --notify=admin@example.com
```

## 📖 **Migration Guide**

### 🔄 **From v1.x to v2.x**

```bash
# 1. Update package
composer update litepie/users

# 2. Run migration scripts
php artisan users:migrate-v2

# 3. Update configuration
php artisan vendor:publish --tag=users-config --force

# 4. Update code (see breaking changes below)
```

#### Breaking Changes

```php
// OLD (v1.x)
$user->addRole('admin');
$user->hasPermission('edit_posts');

// NEW (v2.x)
$user->assignRole('admin');
$user->can('edit_posts');

// OLD (v1.x)
Users::createUser($data);

// NEW (v2.x)
User::create($data); // or Users::create($data)

// OLD (v1.x)
'user_types' => ['admin', 'user']

// NEW (v2.x)
'user_types' => [
    'admin' => ['class' => AdminUserType::class],
    'user' => ['class' => RegularUserType::class],
]
```

### 🏢 **Tenant Migration**

```php
// Migrate existing single-tenant to multi-tenant
php artisan users:migrate-to-tenancy

// This will:
// 1. Add tenant_id columns to all user tables
// 2. Create default tenant for existing data
// 3. Update foreign key constraints
// 4. Add tenant scoping to models
```

## 🎯 **Roadmap**

### 🚀 **Upcoming Features**

- **v2.1** (Q2 2025)
  - GraphQL API support
  - Advanced machine learning for threat detection
  - Biometric authentication support
  - Enhanced mobile SDK

- **v2.2** (Q3 2025)
  - AI-powered user insights and recommendations
  - Blockchain-based identity verification
  - Advanced compliance automation (GDPR, CCPA, SOX)
  - Real-time collaboration features

- **v2.3** (Q4 2025)
  - Federated identity management
  - Advanced A/B testing framework
  - Predictive analytics for user behavior
  - Enhanced internationalization support

### 🌟 **Community Contributions**

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

- Submit bug reports and feature requests via [GitHub Issues](https://github.com/Litepie/users/issues)
- Join our [Discord Community](https://discord.gg/litepie) for discussions
- Contribute code via [Pull Requests](https://github.com/Litepie/users/pulls)
- Help improve documentation

## 📄 **License**

The Litepie Users package is open-sourced software licensed under the [MIT license](LICENSE.md).

## 🙏 **Credits**

- Built with ❤️ by the [Litepie Team](https://github.com/Litepie)
- Inspired by the Laravel community
- Special thanks to all [contributors](https://github.com/Litepie/users/contributors)

---

<div align="center">

**[⬆ Back to Top](#-litepie-users-package)**

Made with ❤️ by [Litepie](https://litepie.com) • [Documentation](https://docs.litepie.com) • [Community](https://discord.gg/litepie)

</div>
```

## 🚀 Features

### Core Features
- **Multi-Tenant Architecture** - Complete isolation between tenants using Litepie/Tenancy
- **Multiple User Types** - Support for Regular, Client, Admin, and System users
- **Role-Based Access Control** - Advanced permissions system via Litepie/Roles
- **Workflow Integration** - Automated user registration and approval workflows
- **File Management** - Avatar uploads and file attachments via Litepie/Filehub
- **API-First Design** - Full REST API with Laravel Sanctum authentication

### Advanced Features
- **Activity Logging** - Complete audit trail of user actions
- **Login Attempt Tracking** - Monitor and analyze authentication attempts
- **Session Management** - Track and manage active user sessions
- **Password History** - Prevent password reuse with configurable history
- **Two-Factor Authentication** - Enhanced security with 2FA support
- **Bulk Operations** - Mass user management operations
- **Statistics & Analytics** - Comprehensive user metrics and insights
- **Email Verification** - Secure email verification workflow

### Security Features
- **Rate Limiting** - Protection against brute force attacks
- **Account Status Management** - Active, Suspended, Banned status control
- **Password Complexity** - Configurable password requirements
- **Device Tracking** - Monitor login devices and locations
- **Security Notifications** - Alert users of suspicious activities

## 📋 Requirements

- PHP 8.3 or higher (recommended for Laravel 12)
- Laravel 12.0+ (with backward compatibility for Laravel 11.0+)
- MySQL 8.0+ or PostgreSQL 15+ or SQLite 3.35+
- Redis 7.0+ (for session management and caching)
- Node.js 20+ and npm 10+ (for asset compilation)

## 📦 Installation

### 1. Install via Composer

```bash
composer require litepie/users
```

### 2. Install Dependencies

This package requires several Litepie packages. Install them if not already present:

```bash
composer require litepie/tenancy
composer require litepie/roles
composer require litepie/filehub
composer require litepie/flow
composer require litepie/actions
```

### 3. Publish Configuration

Publish the configuration file and customize it for Laravel 12:

```bash
php artisan vendor:publish --provider="Litepie\Users\Providers\UsersServiceProvider" --tag="config"
```

For Laravel 12 with enhanced security features:
```bash
php artisan vendor:publish --provider="Litepie\Users\Providers\UsersServiceProvider" --tag="config" --force
```

### 4. Publish Migrations

```bash
php artisan vendor:publish --provider="Litepie\Users\Providers\UsersServiceProvider" --tag="migrations"
```

### 5. Run Migrations

Run migrations with Laravel 12's enhanced migration features:

```bash
php artisan migrate
```

For Laravel 12 with database schema optimization:
```bash
php artisan migrate --force
php artisan db:seed --class=UsersSeeder  # Optional: seed with sample data
```

### 6. Configure Laravel 12 Features (Recommended)

Laravel 12 includes enhanced security and performance features. Configure them:

```bash
# Configure enhanced authentication
php artisan config:cache

# Set up Laravel 12's improved queue system
php artisan queue:table
php artisan migrate

# Configure Laravel 12's enhanced cache system
php artisan cache:clear
php artisan config:cache
```

### 7. Publish Assets (Optional)

```bash
php artisan vendor:publish --provider="Litepie\Users\Providers\UsersServiceProvider" --tag="assets"
```

## ⚙️ Configuration

The package publishes a configuration file to `config/users.php`. Here are the key configuration options optimized for Laravel 12:

```php
return [
    // User types configuration
    'user_types' => [
        'regular' => RegularUserType::class,
        'client' => ClientUserType::class,
        'admin' => AdminUserType::class,
        'system' => SystemUserType::class,
    ],

    // Registration settings (Laravel 12 enhanced)
    'registration' => [
        'enabled' => true,
        'auto_activate' => false,
        'auto_login' => false,
        'allowed_types' => ['regular', 'client'],
        'captcha_enabled' => env('USERS_CAPTCHA_ENABLED', false),
        'email_verification' => true,
        'rate_limit' => 5, // Laravel 12 enhanced rate limiting
        'honeypot_enabled' => true, // Laravel 12 security feature
    ],

    // Authentication settings (Laravel 12 optimized)
    'authentication' => [
        'login_attempts_limit' => 5,
        'lockout_duration' => 15, // minutes
        'password_min_length' => 12, // Increased for Laravel 12 security
        'password_history_count' => 8, // Enhanced security
        'session_timeout' => 120, // minutes
        'two_factor_required' => env('USERS_2FA_REQUIRED', false),
        'device_tracking' => true, // Laravel 12 feature
        'suspicious_login_detection' => true, // New in Laravel 12
    ],

    // Workflow settings
    'workflows' => [
        'user_registration' => UserRegistrationWorkflow::class,
        'client_registration' => ClientRegistrationWorkflow::class,
        'admin_approval' => AdminApprovalWorkflow::class, // New workflow
    ],

    // Multi-tenancy settings (Laravel 12 enhanced)
    'tenancy' => [
        'enabled' => true,
        'tenant_model' => \App\Models\Tenant::class,
        'isolation_level' => 'database',
        'cache_isolation' => true, // Laravel 12 feature
        'queue_isolation' => true, // Laravel 12 feature
    ],

    // Laravel 12 specific features
    'laravel12' => [
        'enhanced_validation' => true,
        'improved_queues' => true,
        'advanced_caching' => true,
        'security_headers' => true,
        'performance_monitoring' => env('USERS_PERFORMANCE_MONITORING', true),
    ],
];
```

## 🔧 Usage

### Basic User Management

#### Creating Users

```php
use Litepie\Users\Facades\Users;

// Create a regular user (Laravel 12 enhanced)
$user = Users::createUser([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'SecureP@ssw0rd123', // Laravel 12 requires stronger passwords
    'user_type' => 'regular',
    'device_info' => request()->userAgent(), // Laravel 12 device tracking
]);

// Create a client user with enhanced validation
$client = Users::createUser([
    'name' => 'Acme Corp',
    'email' => 'contact@acme.com',
    'password' => 'SecureP@ssw0rd123',
    'user_type' => 'client',
    'profile' => [
        'company' => 'Acme Corporation',
        'website' => 'https://acme.com',
        'business_license' => 'BL123456789', // Laravel 12 enhanced validation
    ],
    'metadata' => [ // Laravel 12 feature
        'source' => 'web_registration',
        'utm_campaign' => 'summer_2025',
    ],
]);
```

#### User Management Operations

```php
// Activate a user with Laravel 12 enhanced logging
Users::activateUser($user, [
    'reason' => 'Email verified',
    'activated_by' => auth()->id(),
    'activation_method' => 'email_verification',
]);

// Suspend a user with detailed tracking
Users::suspendUser($user, [
    'reason' => 'Policy violation',
    'suspended_by' => auth()->id(),
    'suspension_duration' => now()->addDays(7),
]);

// Assign role with Laravel 12 enhanced permissions
Users::assignRole($user, 'manager', [
    'assigned_by' => auth()->id(),
    'scope' => 'tenant', // Laravel 12 scoped permissions
    'expires_at' => now()->addMonths(6),
]);

// Update user profile with Laravel 12 validation
Users::updateProfile($user, [
    'first_name' => 'John',
    'last_name' => 'Doe',
    'bio' => 'Software developer',
    'preferences' => [ // Laravel 12 feature
        'theme' => 'dark',
        'notifications' => ['email', 'push'],
        'language' => 'en',
    ],
]);

// Laravel 12 specific features
Users::enableSecurityFeatures($user, [
    'two_factor_auth' => true,
    'device_verification' => true,
    'login_notifications' => true,
]);
```

### Working with User Types

Each user type has specific behaviors and workflows:

```php
use Litepie\Users\UserTypes\RegularUserType;
use Litepie\Users\UserTypes\ClientUserType;

// Get user type instance
$userType = new RegularUserType();

// Check registration workflow
$workflow = $userType->getRegistrationWorkflow();

// Get default permissions
$permissions = $userType->getDefaultPermissions();

// Validate user data
$isValid = $userType->validateUserData($userData);
```

### API Usage

The package provides a complete REST API for user management:

#### Authentication Endpoints

```bash
# Register a new user (Laravel 12 enhanced)
POST /api/auth/register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "SecureP@ssw0rd123",
    "password_confirmation": "SecureP@ssw0rd123",
    "user_type": "regular",
    "device_name": "iPhone 15 Pro",
    "timezone": "America/New_York",
    "preferences": {
        "language": "en",
        "theme": "dark"
    }
}

# Login with Laravel 12 enhanced security
POST /api/auth/login
{
    "email": "john@example.com",
    "password": "SecureP@ssw0rd123",
    "device_name": "iPhone 15 Pro",
    "remember_device": true,
    "two_factor_code": "123456"
}

# Get authenticated user (Laravel 12 enhanced response)
GET /api/auth/me
Authorization: Bearer {token}
X-Device-ID: {device_id}

# Logout with device tracking
POST /api/auth/logout
Authorization: Bearer {token}
{
    "logout_all_devices": false
}
```

#### User Management Endpoints

```bash
# List users with Laravel 12 enhanced filtering (admin/manager only)
GET /api/users?filter[status]=active&filter[user_type]=client&sort=-created_at

# Get user details with enhanced security
GET /api/users/{id}
Authorization: Bearer {token}
X-Tenant-ID: {tenant_id}

# Update user with Laravel 12 validation
PUT /api/users/{id}
{
    "name": "Updated Name",
    "status": "active",
    "preferences": {
        "notifications": true,
        "theme": "light"
    }
}

# Delete user with soft delete (Laravel 12 feature)
DELETE /api/users/{id}
{
    "force_delete": false,
    "reason": "Account closure requested"
}

# Laravel 12 enhanced bulk operations
POST /api/bulk/users/activate
{
    "user_ids": [1, 2, 3],
    "reason": "Bulk activation",
    "notify_users": true
}

POST /api/bulk/users/assign-role
{
    "user_ids": [1, 2, 3],
    "role": "manager",
    "scope": "tenant",
    "expires_at": "2025-12-31T23:59:59Z"
}
```

#### Profile Management

```bash
# Get profile with Laravel 12 enhanced data
GET /api/profile

# Update profile with advanced validation
PUT /api/profile
{
    "first_name": "John",
    "last_name": "Doe",
    "preferences": {
        "theme": "dark",
        "language": "en",
        "timezone": "America/New_York",
        "notifications": {
            "email": true,
            "push": true,
            "sms": false
        }
    }
}

# Upload avatar with Laravel 12 enhanced file handling
POST /api/profile/avatar
Content-Type: multipart/form-data
{
    "avatar": <file>,
    "crop_data": {
        "x": 10,
        "y": 10,
        "width": 200,
        "height": 200
    }
}

# Get user activity with enhanced filtering
GET /api/profile/activity?type=login&from=2025-08-01&to=2025-08-31
```

### Multi-Tenancy

The package seamlessly integrates with Litepie/Tenancy and Laravel 12's enhanced multi-tenancy features:

```php
// Users are automatically scoped to current tenant with Laravel 12 optimizations
$users = User::all(); // Only returns users for current tenant with enhanced caching

// Create tenant-specific user with Laravel 12 features
tenant()->users()->create([
    'name' => 'Tenant User',
    'email' => 'user@tenant.com',
    'password' => bcrypt('SecureP@ssw0rd123'),
    'tenant_scoped_permissions' => ['manage_content', 'view_analytics'],
]);

// Laravel 12 enhanced tenant isolation
$tenantUsers = User::withTenantScope()
    ->where('status', 'active')
    ->with(['profile', 'roles'])
    ->cache(300) // Laravel 12 enhanced caching
    ->get();

// Cross-tenant operations (admin only) with Laravel 12 security
if (auth()->user()->hasRole('super_admin')) {
    $allUsers = User::withoutTenantScope()
        ->secureQuery() // Laravel 12 security feature
        ->get();
}
```

### Workflows

Users can be created through automated workflows:

```php
use Litepie\Users\Workflows\UserRegistrationWorkflow;

// Start registration workflow
$workflow = new UserRegistrationWorkflow();
$workflow->start([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'user_type' => 'client',
]);

// The workflow will handle:
// - Email verification
// - Admin approval (if required)
// - Role assignment
// - Welcome notifications
```

### File Management

Handle user avatars and attachments:

```php
// Upload avatar
$file = $user->uploadAvatar($request->file('avatar'));

// Get avatar URL
$avatarUrl = $user->avatar_url;

// Attach files to user
$user->attachFile($file, [
    'title' => 'Profile Document',
    'category' => 'identity',
]);
```

### Statistics and Analytics

```php
// Get user statistics
$stats = Users::getUserStatistics();

// Get registration statistics
$registrationStats = Users::getRegistrationStatistics('30days');

// Get activity statistics
$activityStats = Users::getActivityStatistics('7days');

// Get user type distribution
$typeStats = Users::getUserTypeStatistics();
```

## 🛡️ Security Features

### Rate Limiting

The package includes built-in rate limiting for authentication:

```php
// Configure in config/users.php
'authentication' => [
    'login_attempts_limit' => 5,
    'lockout_duration' => 15, // minutes
],
```

### Password Security

```php
// Laravel 12 enhanced password security
'authentication' => [
    'password_history_count' => 8, // Prevent reuse of last 8 passwords
    'password_min_length' => 12, // Increased minimum length
    'password_complexity' => [
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => true,
        'prevent_common_passwords' => true, // Laravel 12 feature
    ],
    'password_expiry_days' => 90, // Laravel 12 feature
],
```

### Two-Factor Authentication

Laravel 12 enhanced 2FA with multiple authentication methods:

```php
// Enable 2FA for a user with Laravel 12 features
$twoFactorData = Users::enableTwoFactorAuth($user, [
    'method' => 'totp', // totp, sms, email
    'backup_codes' => true,
    'device_trust' => true, // Laravel 12 feature
]);

// Disable 2FA with secure verification
Users::disableTwoFactorAuth($user, [
    'verification_method' => 'password',
    'verification_value' => $password,
]);

// Verify 2FA token with Laravel 12 enhancements
$isValid = Users::verifyTwoFactorToken($user, $token, [
    'method' => 'totp',
    'trust_device' => true,
    'device_id' => request()->header('X-Device-ID'),
]);

// Laravel 12 specific 2FA features
Users::configureTwoFactorSettings($user, [
    'require_for_sensitive_actions' => true,
    'backup_codes_count' => 10,
    'session_timeout_with_2fa' => 480, // minutes
]);
```

## 🔌 Events

The package dispatches several events compatible with Laravel 12's enhanced event system:

```php
// Core user events
Litepie\Users\Events\UserRegistered::class
Litepie\Users\Events\UserActivated::class
Litepie\Users\Events\UserRoleChanged::class
Litepie\Users\Events\UserLoggedIn::class
Litepie\Users\Events\UserProfileUpdated::class

// Laravel 12 enhanced security events
Litepie\Users\Events\SuspiciousLoginDetected::class
Litepie\Users\Events\DeviceVerificationRequired::class
Litepie\Users\Events\PasswordExpired::class
Litepie\Users\Events\TwoFactorEnabled::class
Litepie\Users\Events\TwoFactorDisabled::class

// Multi-tenancy events
Litepie\Users\Events\UserTenantChanged::class
Litepie\Users\Events\TenantUserLimitReached::class

// Workflow events
Litepie\Users\Events\WorkflowCompleted::class
Litepie\Users\Events\ApprovalRequired::class
```

### Listening to Events

Laravel 12 enhanced event listening with automatic discovery:

```php
// In EventServiceProvider (Laravel 12 auto-discovery supported)
protected $listen = [
    \Litepie\Users\Events\UserRegistered::class => [
        \App\Listeners\SendWelcomeEmail::class,
        \App\Listeners\CreateUserDirectory::class,
        \App\Listeners\InitializeUserPreferences::class, // Laravel 12 feature
    ],
    
    \Litepie\Users\Events\SuspiciousLoginDetected::class => [
        \App\Listeners\NotifySecurityTeam::class,
        \App\Listeners\RequireAdditionalVerification::class,
    ],
];

// Laravel 12 closure-based listeners
Event::listen(
    \Litepie\Users\Events\UserLoggedIn::class,
    function ($event) {
        // Update last activity with Laravel 12 performance optimizations
        Cache::tags(['user:' . $event->user->id])
            ->put('last_activity', now(), 3600);
    }
);
```

## 🧪 Testing

Run the package tests with Laravel 12's enhanced testing features:

```bash
# Run all tests
composer test

# Run tests with Laravel 12 parallel testing
composer test -- --parallel

# Run tests with coverage
composer test-coverage

# Run Laravel 12 specific feature tests
php artisan test --testsuite=Laravel12Features

# Run performance tests (Laravel 12 feature)
php artisan test --testsuite=Performance --profile
```

## 📊 Database Schema

The package creates several database tables optimized for Laravel 12:

- `users` - Main user table with Laravel 12 performance indexes
- `user_profiles` - Extended user profile information with JSON columns
- `user_login_attempts` - Login attempt tracking with enhanced analytics
- `user_password_histories` - Password history tracking with encryption
- `user_sessions` - Active session management with device tracking
- `user_preferences` - User preferences and settings (Laravel 12 feature)
- `user_security_events` - Security event logging (Laravel 12 feature)
- `user_device_tokens` - Device verification tokens (Laravel 12 feature)

## 🛠️ Extending the Package

### Custom User Types

Create custom user types with Laravel 12 enhanced features:

```php
use Litepie\Users\UserTypes\BaseUserType;

class VendorUserType extends BaseUserType
{
    public function getRegistrationWorkflow(): string
    {
        return VendorRegistrationWorkflow::class;
    }

    public function getDefaultPermissions(): array
    {
        return ['vendor.manage_products', 'vendor.view_orders'];
    }

    public function getValidationRules(): array
    {
        return array_merge(parent::getValidationRules(), [
            'business_license' => 'required|string',
            'tax_id' => 'required|string',
            'business_type' => 'required|in:llc,corporation,partnership',
            'annual_revenue' => 'nullable|numeric|min:0', // Laravel 12 enhanced validation
        ]);
    }

    // Laravel 12 specific features
    public function getSecurityRequirements(): array
    {
        return [
            'two_factor_required' => true,
            'device_verification' => true,
            'session_timeout' => 60, // minutes
            'password_complexity' => 'high',
        ];
    }

    public function getDefaultPreferences(): array
    {
        return [
            'dashboard_layout' => 'vendor',
            'notifications' => [
                'order_notifications' => true,
                'payment_alerts' => true,
                'product_updates' => false,
            ],
            'api_access' => true,
        ];
    }
}
```

Register your custom user type:

```php
// In config/users.php
'user_types' => [
    'vendor' => VendorUserType::class,
],
```

### Custom Workflows

Create custom registration workflows with Laravel 12 enhanced features:

```php
use Litepie\Flow\Workflow;

class VendorRegistrationWorkflow extends Workflow
{
    protected $steps = [
        'validate_business_info',
        'verify_business_license',
        'check_credit_score', // Laravel 12 integration
        'admin_approval',
        'setup_vendor_account',
        'configure_payment_methods', // Laravel 12 feature
        'setup_api_access',
        'send_welcome_package',
    ];

    public function validateBusinessInfo($data)
    {
        // Laravel 12 enhanced validation with async processing
        return $this->validateAsync([
            'business_license' => 'required|business_license_valid',
            'tax_id' => 'required|tax_id_format',
            'business_address' => 'required|verified_address', // Laravel 12 feature
        ], $data);
    }

    public function verifyBusinessLicense($data)
    {
        // Integration with external verification services
        return $this->callExternalService('business_verification', [
            'license_number' => $data['business_license'],
            'state' => $data['business_state'],
        ]);
    }

    // Laravel 12 async step processing
    public function checkCreditScore($data)
    {
        return $this->dispatchAsync(new CheckBusinessCreditJob($data));
    }

    protected function onWorkflowCompleted($data)
    {
        // Laravel 12 event broadcasting
        broadcast(new VendorApprovalCompleted($data['user']));
        
        // Setup Laravel 12 specific features
        $this->setupVendorDashboard($data['user']);
        $this->createApiCredentials($data['user']);
    }
}
```

## 🤝 Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on how to contribute to this package.

## 🔒 Security

If you discover any security-related issues, please email security@litepie.com instead of using the issue tracker.

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

## 🙏 Credits

- [Litepie Team](https://github.com/litepie)
- [All Contributors](../../contributors)

## 📞 Support

- Documentation: [https://docs.litepie.com/packages/users](https://docs.litepie.com/packages/users)
- Issues: [GitHub Issues](https://github.com/litepie/users/issues)
- Discussions: [GitHub Discussions](https://github.com/litepie/users/discussions)
- Email: support@litepie.com

## 🗺️ Roadmap

### Upcoming Features

- [ ] **Laravel 12 Specific Features**
  - [ ] Enhanced async processing for user operations
  - [ ] Advanced AI-powered fraud detection
  - [ ] Real-time collaboration features
  - [ ] Enhanced WebSocket integration
- [ ] **Social Authentication** (OAuth with Laravel 12 Socialite v6)
- [ ] **Advanced User Import/Export** with Laravel 12 streaming
- [ ] **Custom Field Management** with dynamic validation
- [ ] **User Groups and Teams** with hierarchical permissions
- [ ] **Advanced Reporting Dashboard** with Laravel 12 analytics
- [ ] **Mobile App API Enhancements** (Laravel 12 API platform)
- [ ] **GraphQL API Support** (Laravel 12 Lighthouse integration)
- [ ] **Real-time Notifications** (Laravel 12 Broadcasting v2)
- [ ] **Advanced Security Features**
  - [ ] Biometric authentication support
  - [ ] Device fingerprinting with Laravel 12 security
  - [ ] AI-powered anomaly detection
- [ ] **Integration Ecosystem**
  - [ ] Popular CRM systems (Salesforce, HubSpot)
  - [ ] Identity providers (Okta, Auth0)
  - [ ] Payment processors (Stripe, PayPal)

### Version History

- **v1.0.0** - Initial release with core features
- **v1.1.0** - Added workflow integration
- **v1.2.0** - Enhanced API endpoints
- **v1.3.0** - Multi-tenancy improvements
- **v2.0.0** - Major refactor with Laravel 11+ support
- **v2.5.0** - Laravel 12 compatibility and enhanced features
- **v3.0.0** - *(Coming Q4 2025)* Laravel 12 full optimization with AI features

---

Made with ❤️ by the [Litepie Team](https://litepie.com)
