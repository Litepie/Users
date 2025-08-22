# Litepie Users Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/litepie/users.svg?style=flat-square)](https://packagist.org/packages/litepie/users)
[![Total Downloads](https://img.shields.io/packagist/dt/litepie/users.svg?style=flat-square)](https://packagist.org/packages/litepie/users)
[![License](https://img.shields.io/github/license/litepie/users.svg?style=flat-square)](https://github.com/litepie/users/blob/main/LICENSE.md)

A comprehensive Laravel user management package with multi-tenancy support, role-based access control, workflow integration, and file management capabilities. Built for Laravel 11+ and designed to work seamlessly with the Litepie ecosystem.

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

- PHP 8.2 or higher
- Laravel 11.0 or 12.0
- MySQL 8.0+ or PostgreSQL 13+

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

```bash
php artisan vendor:publish --provider="Litepie\Users\Providers\UsersServiceProvider" --tag="config"
```

### 4. Publish Migrations

```bash
php artisan vendor:publish --provider="Litepie\Users\Providers\UsersServiceProvider" --tag="migrations"
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Publish Assets (Optional)

```bash
php artisan vendor:publish --provider="Litepie\Users\Providers\UsersServiceProvider" --tag="assets"
```

## ⚙️ Configuration

The package publishes a configuration file to `config/users.php`. Here are the key configuration options:

```php
return [
    // User types configuration
    'user_types' => [
        'regular' => RegularUserType::class,
        'client' => ClientUserType::class,
        'admin' => AdminUserType::class,
        'system' => SystemUserType::class,
    ],

    // Registration settings
    'registration' => [
        'enabled' => true,
        'auto_activate' => false,
        'auto_login' => false,
        'allowed_types' => ['regular', 'client'],
        'captcha_enabled' => false,
        'email_verification' => true,
    ],

    // Authentication settings
    'authentication' => [
        'login_attempts_limit' => 5,
        'lockout_duration' => 15, // minutes
        'password_min_length' => 8,
        'password_history_count' => 5,
        'session_timeout' => 120, // minutes
    ],

    // Workflow settings
    'workflows' => [
        'user_registration' => UserRegistrationWorkflow::class,
        'client_registration' => ClientRegistrationWorkflow::class,
    ],

    // Multi-tenancy settings
    'tenancy' => [
        'enabled' => true,
        'tenant_model' => \App\Models\Tenant::class,
        'isolation_level' => 'database',
    ],
];
```

## 🔧 Usage

### Basic User Management

#### Creating Users

```php
use Litepie\Users\Facades\Users;

// Create a regular user
$user = Users::createUser([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'secret123',
    'user_type' => 'regular',
]);

// Create a client user
$client = Users::createUser([
    'name' => 'Acme Corp',
    'email' => 'contact@acme.com',
    'password' => 'secret123',
    'user_type' => 'client',
    'profile' => [
        'company' => 'Acme Corporation',
        'website' => 'https://acme.com',
    ],
]);
```

#### User Management Operations

```php
// Activate a user
Users::activateUser($user);

// Suspend a user
Users::suspendUser($user);

// Assign role
Users::assignRole($user, 'manager');

// Update user profile
Users::updateProfile($user, [
    'first_name' => 'John',
    'last_name' => 'Doe',
    'bio' => 'Software developer',
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
# Register a new user
POST /api/auth/register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "secret123",
    "password_confirmation": "secret123",
    "user_type": "regular"
}

# Login
POST /api/auth/login
{
    "email": "john@example.com",
    "password": "secret123"
}

# Get authenticated user
GET /api/auth/me
Authorization: Bearer {token}

# Logout
POST /api/auth/logout
Authorization: Bearer {token}
```

#### User Management Endpoints

```bash
# List users (admin/manager only)
GET /api/users

# Get user details
GET /api/users/{id}

# Update user
PUT /api/users/{id}

# Delete user
DELETE /api/users/{id}

# Bulk operations
POST /api/bulk/users/activate
POST /api/bulk/users/assign-role
```

#### Profile Management

```bash
# Get profile
GET /api/profile

# Update profile
PUT /api/profile

# Upload avatar
POST /api/profile/avatar

# Get user activity
GET /api/profile/activity
```

### Multi-Tenancy

The package seamlessly integrates with Litepie/Tenancy:

```php
// Users are automatically scoped to current tenant
$users = User::all(); // Only returns users for current tenant

// Create tenant-specific user
tenant()->users()->create([
    'name' => 'Tenant User',
    'email' => 'user@tenant.com',
    'password' => bcrypt('password'),
]);
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
// Password history tracking
'authentication' => [
    'password_history_count' => 5, // Prevent reuse of last 5 passwords
    'password_min_length' => 8,
],
```

### Two-Factor Authentication

```php
// Enable 2FA for a user
$secret = Users::enableTwoFactorAuth($user);

// Disable 2FA
Users::disableTwoFactorAuth($user);

// Verify 2FA token
$isValid = Users::verifyTwoFactorToken($user, $token);
```

## 🔌 Events

The package dispatches several events that you can listen to:

```php
// User registered
Litepie\Users\Events\UserRegistered::class

// User activated
Litepie\Users\Events\UserActivated::class

// User role changed
Litepie\Users\Events\UserRoleChanged::class

// User logged in
Litepie\Users\Events\UserLoggedIn::class

// User profile updated
Litepie\Users\Events\UserProfileUpdated::class
```

### Listening to Events

```php
// In EventServiceProvider
protected $listen = [
    \Litepie\Users\Events\UserRegistered::class => [
        \App\Listeners\SendWelcomeEmail::class,
        \App\Listeners\CreateUserDirectory::class,
    ],
];
```

## 🧪 Testing

Run the package tests:

```bash
composer test
```

Run tests with coverage:

```bash
composer test-coverage
```

## 📊 Database Schema

The package creates several database tables:

- `users` - Main user table
- `user_profiles` - Extended user profile information
- `user_login_attempts` - Login attempt tracking
- `user_password_histories` - Password history tracking
- `user_sessions` - Active session management

## 🛠️ Extending the Package

### Custom User Types

Create custom user types by extending the base class:

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
        ]);
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

Create custom registration workflows:

```php
use Litepie\Flow\Workflow;

class VendorRegistrationWorkflow extends Workflow
{
    protected $steps = [
        'validate_business_info',
        'verify_business_license',
        'admin_approval',
        'setup_vendor_account',
        'send_welcome_email',
    ];

    public function validateBusinessInfo($data)
    {
        // Custom validation logic
    }

    public function verifyBusinessLicense($data)
    {
        // Business verification logic
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

- [ ] Social authentication (OAuth)
- [ ] Advanced user import/export
- [ ] Custom field management
- [ ] User groups and teams
- [ ] Advanced reporting dashboard
- [ ] Mobile app API enhancements
- [ ] GraphQL API support
- [ ] Real-time notifications
- [ ] Advanced security features (device fingerprinting)
- [ ] Integration with popular CRM systems

### Version History

- **v1.0.0** - Initial release with core features
- **v1.1.0** - Added workflow integration
- **v1.2.0** - Enhanced API endpoints
- **v1.3.0** - Multi-tenancy improvements
- **v2.0.0** - Major refactor with Laravel 11+ support

---

Made with ❤️ by the [Litepie Team](https://litepie.com)
