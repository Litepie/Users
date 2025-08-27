# 🏢 Organization Hierarchy Integration Guide

This document outlines the integration between the Litepie Users package and the Litepie Organization package to provide comprehensive user hierarchy management.

## 📋 Overview

The integration provides:

- **Hierarchical User Management**: Users can be organized within organizational structures
- **Reporting Relationships**: Define manager-subordinate relationships
- **Organization-Specific Roles**: Different permission sets based on organization position
- **Multi-level Hierarchy**: Support for complex organizational structures
- **Team and Department Management**: Organize users by departments, divisions, and teams

## 🚀 Installation

### 1. Install Required Packages

```bash
# Install the organization package
composer require litepie/organization

# Ensure users package is up to date
composer update litepie/users
```

### 2. Run Migrations

```bash
# Publish organization migrations
php artisan vendor:publish --provider="Litepie\Organization\OrganizationServiceProvider" --tag="migrations"

# Run migrations
php artisan migrate
```

### 3. Configure Organization Settings

```bash
# Publish configuration
php artisan vendor:publish --provider="Litepie\Organization\OrganizationServiceProvider" --tag="config"
```

## 📊 Database Schema

### Users Table Additions

```sql
-- Organization relationship
organization_id (foreign key to organizations)
organization_position (CEO, Manager, Staff, etc.)
is_organization_admin (boolean)
is_organization_owner (boolean)

-- Reporting structure
reports_to_user_id (foreign key to users)
primary_manager_id (foreign key to users)
secondary_manager_id (foreign key to users)

-- Organization settings
organization_permissions (JSON)
organization_settings (JSON)
organization_joined_at (timestamp)
organization_left_at (timestamp)

-- Work configuration
work_schedule (JSON)
work_location (remote, office, hybrid)
office_location (string)
```

### User Profiles Table Additions

```sql
-- Organization details
organization_id (foreign key to organizations)
organization_role (string)
employee_id (unique string)
department (string)
division (string)
team (string)

-- Employment information
hire_date (date)
termination_date (date)
employment_status (active, inactive, terminated, suspended)
employment_type (full-time, part-time, contract, freelance)
salary (decimal)
salary_currency (3-char code)

-- Metadata
reporting_structure (JSON)
permissions (JSON)
organization_metadata (JSON)
```

## 🏗️ Basic Usage

### Creating Organization Users

```php
use Litepie\Users\Models\User;
use Litepie\Organization\Models\Organization;

// Create an organization
$organization = Organization::create([
    'type' => 'company',
    'name' => 'Acme Corporation',
    'code' => 'ACME',
    'status' => 'active',
]);

// Create a user with organization
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@acme.com',
    'password' => bcrypt('password'),
    'user_type' => 'organization',
    'organization_id' => $organization->id,
    'organization_position' => 'Manager',
    'is_organization_admin' => false,
    'work_location' => 'office',
    'organization_joined_at' => now(),
]);

// Create user profile with organization data
$user->profile()->create([
    'first_name' => 'John',
    'last_name' => 'Doe',
    'organization_id' => $organization->id,
    'organization_role' => 'Engineering Manager',
    'employee_id' => 'EMP001',
    'department' => 'Engineering',
    'division' => 'Technology',
    'team' => 'Backend Development',
    'hire_date' => now(),
    'employment_status' => 'active',
    'employment_type' => 'full-time',
    'salary' => 85000.00,
    'salary_currency' => 'USD',
]);
```

### Setting Up Reporting Structure

```php
// Create a CEO
$ceo = User::create([
    'name' => 'Jane Smith',
    'email' => 'jane@acme.com',
    'user_type' => 'organization',
    'organization_id' => $organization->id,
    'organization_position' => 'CEO',
    'is_organization_admin' => true,
    'is_organization_owner' => true,
]);

// Create a manager who reports to CEO
$manager = User::create([
    'name' => 'Bob Johnson',
    'email' => 'bob@acme.com',
    'user_type' => 'organization',
    'organization_id' => $organization->id,
    'organization_position' => 'Manager',
    'reports_to_user_id' => $ceo->id,
    'primary_manager_id' => $ceo->id,
]);

// Create staff who reports to manager
$staff = User::create([
    'name' => 'Alice Brown',
    'email' => 'alice@acme.com',
    'user_type' => 'organization',
    'organization_id' => $organization->id,
    'organization_position' => 'Staff',
    'reports_to_user_id' => $manager->id,
    'primary_manager_id' => $manager->id,
]);
```

## 🔍 Querying Organization Users

### Basic Queries

```php
// Get all users in an organization
$orgUsers = User::inOrganization($organization->id)->get();

// Get organization admins
$admins = User::inOrganization($organization->id)
    ->organizationAdmins()
    ->get();

// Get users by position
$managers = User::inOrganization($organization->id)
    ->byPosition('Manager')
    ->get();

// Get users by work location
$remoteUsers = User::byWorkLocation('remote')->get();

// Get all managers (users who manage others)
$allManagers = User::managers()->get();
```

### Relationship Queries

```php
// Get user's direct reports
$directReports = $user->directReports;

// Get user's manager
$manager = $user->reportsTo;

// Get all subordinates (recursive)
$allSubordinates = $user->getAllSubordinates();

// Get user's hierarchy path
$hierarchyPath = $user->getHierarchyPath();

// Get organization hierarchy
$hierarchy = User::inOrganization($organization->id)
    ->whereNull('reports_to_user_id')
    ->with('directReports.directReports')
    ->get();
```

## 🎯 Organization User Types

### Defining User Types

```php
// config/users.php
'user_types' => [
    'organization' => [
        'class' => \Litepie\Users\UserTypes\OrganizationUserType::class,
        'label' => 'Organization Member',
        'description' => 'User belonging to an organization',
        'registration_enabled' => true,
        'auto_activate' => false,
        'default_role' => 'organization-member',
        'workflow' => 'organization_user_registration',
        'permissions' => [
            'organization.view',
            'organization.members.view',
            'profile.update',
        ],
    ],
],
```

### User Type Features

The `OrganizationUserType` provides:

- **Custom Registration Workflow**: Handle organization-specific registration
- **Role Assignment**: Automatic role assignment based on position
- **Permission Management**: Organization-specific permissions
- **Activation Handling**: Setup organization access and notifications
- **Deactivation Cleanup**: Handle reporting structure updates

## 🌐 API Endpoints

### Organization User Management

```php
// List organization users
GET /api/organizations/{id}/users
// Query parameters: position, work_location, status, is_admin, is_manager, search

// Create organization user
POST /api/organizations/{id}/users
// Body: user data + profile data

// Get specific user
GET /api/organizations/{id}/users/{userId}

// Update user
PUT /api/organizations/{id}/users/{userId}

// Update user profile
PUT /api/organizations/{id}/users/{userId}/profile

// Remove user from organization
DELETE /api/organizations/{id}/users/{userId}

// Get organization hierarchy
GET /api/organizations/{id}/users/hierarchy

// Transfer user to new manager
POST /api/organizations/{id}/users/{userId}/transfer
// Body: new_manager_id, transfer_reports, effective_date

// Bulk operations
POST /api/organizations/{id}/users/bulk/transfer
POST /api/organizations/{id}/users/bulk/update-position
GET /api/organizations/{id}/users/export
```

### Example API Usage

```javascript
// Create organization user
const response = await fetch('/api/organizations/1/users', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token
    },
    body: JSON.stringify({
        name: 'John Doe',
        email: 'john@acme.com',
        organization_position: 'Developer',
        reports_to_user_id: 5,
        primary_manager_id: 5,
        work_location: 'remote',
        // Profile data
        first_name: 'John',
        last_name: 'Doe',
        employee_id: 'EMP001',
        department: 'Engineering',
        team: 'Backend',
        hire_date: '2024-01-15',
        employment_type: 'full-time',
        salary: 75000,
        salary_currency: 'USD'
    })
});

// Transfer user to new manager
const transferResponse = await fetch('/api/organizations/1/users/10/transfer', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token
    },
    body: JSON.stringify({
        new_manager_id: 7,
        transfer_reports: true,
        effective_date: '2024-02-01'
    })
});
```

## 🛡️ Authorization & Permissions

### Organization-Specific Permissions

```php
// Check organization membership
$user->belongsToOrganization($organizationId);

// Check admin status
$user->isOrganizationAdmin();
$user->isOrganizationOwner();

// Check management capability
$user->canManageOrganization();
$user->isManager();

// Check specific permissions
$user->hasOrganizationPermission('organization.users.manage');
```

### Role-Based Access

```php
// Organization roles are automatically assigned:
// - organization-owner (highest level)
// - organization-admin
// - organization-executive (CEO, President)
// - organization-manager (Manager, Director, VP)
// - organization-supervisor (Supervisor, Team Lead)
// - organization-member (default)
// - organization-intern

// Policies can check organization context
Gate::define('manage-organization-users', function ($user, $organizationId) {
    return $user->belongsToOrganization($organizationId) && 
           $user->isOrganizationAdmin();
});
```

## 📈 Advanced Features

### Hierarchy Validation

```php
// Prevent circular reporting
$user->wouldCreateCircularReporting($newManager); // returns boolean

// Get hierarchy level
$level = $user->getHierarchyLevel(); // returns integer

// Validate reporting structure
$this->validateReportingStructure($data, $organizationId);
```

### Employment Tracking

```php
// Check employment status
$user->profile->isCurrentlyEmployed(); // boolean

// Get employment duration
$days = $user->profile->employment_duration; // days
$years = $user->profile->years_of_service; // years

// Format salary
$formatted = $user->profile->formatted_salary; // "75,000.00 USD"
```

### Work Schedule Management

```php
// Set work schedule
$user->update([
    'work_schedule' => [
        'timezone' => 'America/New_York',
        'working_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
        'working_hours' => [
            'start' => '09:00',
            'end' => '17:00'
        ],
        'break_times' => [
            ['start' => '12:00', 'end' => '13:00']
        ]
    ]
]);
```

## 🔄 Workflows

### Organization Registration Workflow

The package includes an `OrganizationUserRegistrationWorkflow` that handles:

1. **Organization Eligibility Validation**
2. **User Account Creation** 
3. **Email Verification**
4. **Organization Approval** (if required)
5. **Role Assignment**
6. **Access Setup**
7. **Welcome Notifications**

### Departure Workflow

When users leave the organization:

1. **Reporting Structure Update**: Reassign direct reports
2. **Access Revocation**: Remove organization permissions
3. **Role Cleanup**: Remove organization-specific roles
4. **Notification**: Notify organization admins
5. **Data Archival**: Mark departure date

## 🎨 Frontend Integration

### Organization Directory Component

```vue
<template>
  <div class="organization-directory">
    <!-- Search and filters -->
    <div class="filters">
      <input v-model="search" placeholder="Search users..." />
      <select v-model="positionFilter">
        <option value="">All Positions</option>
        <option value="CEO">CEO</option>
        <option value="Manager">Manager</option>
        <option value="Staff">Staff</option>
      </select>
      <select v-model="locationFilter">
        <option value="">All Locations</option>
        <option value="office">Office</option>
        <option value="remote">Remote</option>
        <option value="hybrid">Hybrid</option>
      </select>
    </div>

    <!-- User list -->
    <div class="user-grid">
      <UserCard
        v-for="user in filteredUsers"
        :key="user.id"
        :user="user"
        @edit="editUser"
        @transfer="transferUser"
      />
    </div>

    <!-- Hierarchy view -->
    <OrganizationChart
      v-if="showHierarchy"
      :hierarchy="hierarchy"
      @select="selectUser"
    />
  </div>
</template>
```

### Hierarchy Chart Component

```vue
<template>
  <div class="org-chart">
    <div class="chart-container">
      <OrgNode
        v-for="root in hierarchy"
        :key="root.id"
        :node="root"
        :level="0"
        @select="$emit('select', $event)"
      />
    </div>
  </div>
</template>
```

## 📊 Reporting & Analytics

### Organization Metrics

```php
// Get organization statistics
$stats = [
    'total_users' => User::inOrganization($orgId)->count(),
    'active_users' => User::inOrganization($orgId)->active()->count(),
    'admins' => User::inOrganization($orgId)->organizationAdmins()->count(),
    'managers' => User::inOrganization($orgId)->managers()->count(),
    'remote_workers' => User::inOrganization($orgId)->byWorkLocation('remote')->count(),
    'recent_hires' => User::inOrganization($orgId)
        ->whereHas('profile', fn($q) => $q->where('hire_date', '>=', now()->subDays(30)))
        ->count(),
];

// Department breakdown
$departments = User::inOrganization($orgId)
    ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
    ->selectRaw('department, count(*) as count')
    ->groupBy('department')
    ->pluck('count', 'department');
```

## 🔧 Configuration

### Organization Settings

```php
// config/organization.php
return [
    'hierarchy' => [
        'max_depth' => 10,
        'allow_circular' => false,
        'auto_assign_roles' => true,
    ],
    'permissions' => [
        'positions' => [
            'CEO' => ['organization.manage', 'organization.users.manage'],
            'Manager' => ['organization.team.manage', 'organization.users.view'],
            'Staff' => ['organization.view'],
        ],
    ],
    'features' => [
        'salary_tracking' => true,
        'work_schedule' => true,
        'reporting_chain' => true,
        'departure_workflow' => true,
    ],
];
```

## 🎯 Best Practices

### 1. Organization Design

- **Keep hierarchy shallow**: Avoid too many management levels
- **Clear role definitions**: Define responsibilities for each position
- **Regular reviews**: Periodically review and update structure

### 2. Security

- **Permission scoping**: Ensure users can only access their organization data
- **Audit logging**: Track all hierarchy changes
- **Approval workflows**: Require approval for sensitive changes

### 3. Performance

- **Eager loading**: Load relationships efficiently
- **Indexing**: Ensure proper database indexes
- **Caching**: Cache frequently accessed hierarchy data

### 4. Data Integrity

- **Validation**: Validate reporting structures
- **Cleanup**: Handle departures properly
- **Backup**: Regular backups of organization data

## 🚀 Migration Guide

If migrating from a simpler user system:

### 1. Data Migration

```php
// Migration script example
$users = User::whereNotNull('company')->get();

foreach ($users as $user) {
    // Create organization if needed
    $org = Organization::firstOrCreate([
        'name' => $user->company,
        'type' => 'company',
        'status' => 'active'
    ]);
    
    // Update user
    $user->update([
        'organization_id' => $org->id,
        'organization_position' => $user->job_title ?? 'Staff',
        'organization_joined_at' => $user->created_at,
    ]);
    
    // Update profile
    if ($user->profile) {
        $user->profile->update([
            'organization_id' => $org->id,
            'employment_status' => 'active',
            'hire_date' => $user->created_at,
        ]);
    }
}
```

### 2. Role Migration

```php
// Convert existing roles to organization roles
$admins = User::role('admin')->get();
foreach ($admins as $admin) {
    $admin->assignRole('organization-admin');
    $admin->update(['is_organization_admin' => true]);
}
```

## 📚 Resources

- [Organization Package Documentation](https://github.com/Litepie/organization)
- [Users Package Documentation](https://github.com/Litepie/users)
- [Tenancy Documentation](https://github.com/Litepie/tenancy)
- [Shield (Roles/Permissions) Documentation](https://github.com/Litepie/shield)

## 🤝 Support

For issues and questions:

- GitHub Issues: [Users](https://github.com/Litepie/users/issues) | [Organization](https://github.com/Litepie/organization/issues)
- Documentation: [Litepie Docs](https://docs.lavalite.org)
- Community: [Discord](https://discord.gg/lavalite)

---

This integration provides a comprehensive solution for managing users within organizational hierarchies, combining the power of the Litepie Users and Organization packages for enterprise-grade user management.
