<?php

namespace Litepie\Users\UserTypes;

use Litepie\Users\Models\User;
use Litepie\Organization\Models\Organization;

class OrganizationUserType extends BaseUserType
{
    /**
     * The user type name.
     */
    protected string $name = 'organization';

    /**
     * The user type display name.
     */
    protected string $displayName = 'Organization Member';

    /**
     * Default roles for organization users.
     */
    protected array $defaultRoles = ['organization-member'];

    /**
     * Default permissions for organization users.
     */
    protected array $defaultPermissions = [
        'organization.view',
        'organization.members.view',
        'profile.update',
        'profile.view',
    ];

    /**
     * Registration workflow name.
     */
    protected string $registrationWorkflow = 'organization_user_registration';

    /**
     * Whether this user type is allowed in tenants.
     */
    protected bool $allowedInTenants = true;

    /**
     * Features accessible by this user type.
     */
    protected array $accessibleFeatures = [
        'organization_dashboard',
        'team_management', 
        'directory',
        'announcements'
    ];
    /**
     * Get the registration workflow for this user type.
     */
    public function getRegistrationWorkflow(): string
    {
        // TODO: Create workflow when flow package is integrated
        return 'organization_user_registration';
    }

    /**
     * Get default permissions for organization users.
     */
    public function getDefaultPermissions(): array
    {
        return [
            'organization.view',
            'organization.members.view',
            'profile.update',
            'profile.view',
        ];
    }

    /**
     * Get validation rules for organization user registration.
     */
    public function getRegistrationRules(): array
    {
        return array_merge(parent::getRegistrationRules(), [
            'organization_id' => 'required|exists:organizations,id',
            'organization_position' => 'nullable|string|max:255',
            'reports_to_user_id' => 'nullable|exists:users,id',
            'primary_manager_id' => 'nullable|exists:users,id',
            'work_location' => 'nullable|in:remote,office,hybrid',
            'office_location' => 'nullable|string|max:255',
        ]);
    }

    /**
     * Get validation rules for profile update.
     */
    public function getUpdateRules(): array
    {
        return array_merge(parent::getUpdateRules(), [
            'organization_position' => 'nullable|string|max:255',
            'reports_to_user_id' => 'nullable|exists:users,id',
            'primary_manager_id' => 'nullable|exists:users,id',
            'work_location' => 'nullable|in:remote,office,hybrid',
            'office_location' => 'nullable|string|max:255',
        ]);
    }

    /**
     * Handle organization-specific registration logic.
     */
    public function handleRegistration(array $data): array
    {
        // TODO: Validate organization membership when organization package is available
        // $organization = Organization::find($data['organization_id']);
        
        // if (!$organization) {
        //     throw new \InvalidArgumentException('Invalid organization');
        // }

        // Check organization limits
        // if ($organization->hasReachedUserLimit()) {
        //     throw new \InvalidArgumentException('Organization has reached user limit');
        // }

        // Set default organization data
        $data['organization_joined_at'] = now();
        $data['status'] = 'pending'; // Default to pending for organization approval
        $data['organization_permissions'] = $this->getDefaultPermissions();
        
        // Set default work settings
        $data['work_schedule'] = $data['work_schedule'] ?? [
            'timezone' => config('app.timezone'),
            'working_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'working_hours' => [
                'start' => '09:00',
                'end' => '17:00'
            ]
        ];

        return $data;
    }

    /**
     * Handle user activation for organization users.
     */
    public function handleActivation($user): bool
    {
        parent::handleActivation($user);
        
        $this->assignOrganizationRole($user);
        $this->setupOrganizationAccess($user);
        $this->notifyOrganizationAdmins($user);
        $this->createOrganizationProfile($user);
        
        return true;
    }

    /**
     * Handle user deactivation for organization users.
     */
    public function handleDeactivation($user): bool
    {
        $this->revokeOrganizationAccess($user);
        $this->notifyOrganizationOfDeparture($user);
        $this->updateReportingStructure($user);
        
        return true;
    }

    /**
     * Get organization-specific dashboard configuration.
     */
    public function getDashboardConfig(): array
    {
        return [
            'layout' => 'organization',
            'widgets' => [
                'organization_overview',
                'team_activities',
                'recent_announcements',
                'org_calendar',
                'reporting_chain'
            ],
            'navigation' => [
                'organization' => true,
                'team' => true,
                'directory' => true,
                'announcements' => true
            ]
        ];
    }

    /**
     * Assign organization-specific role.
     */
    protected function assignOrganizationRole(User $user): void
    {
        $role = match($user->organization_position) {
            'CEO', 'President' => 'organization-executive',
            'Manager', 'Director', 'VP' => 'organization-manager',
            'Supervisor', 'Team Lead' => 'organization-supervisor',
            'Intern' => 'organization-intern',
            default => 'organization-member'
        };

        $user->assignRole($role);

        // Additional role for admins
        if ($user->is_organization_admin) {
            $user->assignRole('organization-admin');
        }

        // Additional role for owners
        if ($user->is_organization_owner) {
            $user->assignRole('organization-owner');
        }
    }

    /**
     * Setup organization access and permissions.
     */
    protected function setupOrganizationAccess(User $user): void
    {
        $organization = $user->organization;
        
        if (!$organization) {
            return;
        }

        // Grant organization-specific permissions
        $permissions = $this->getOrganizationPermissions($user);
        $user->update(['organization_permissions' => $permissions]);

        // Setup organization settings
        $settings = [
            'dashboard_config' => $this->getDashboardConfig(),
            'notification_preferences' => [
                'organization_updates' => true,
                'team_activities' => true,
                'announcements' => true,
                'calendar_reminders' => true
            ],
            'privacy_settings' => [
                'show_in_directory' => true,
                'show_contact_info' => true,
                'allow_direct_messages' => true
            ]
        ];

        $user->update(['organization_settings' => $settings]);
    }

    /**
     * Get organization permissions based on user position.
     */
    protected function getOrganizationPermissions(User $user): array
    {
        $basePermissions = $this->getDefaultPermissions();

        $additionalPermissions = match($user->organization_position) {
            'CEO', 'President' => [
                'organization.manage',
                'organization.users.manage',
                'organization.settings.manage',
                'organization.billing.manage',
                'organization.reports.view'
            ],
            'Manager', 'Director', 'VP' => [
                'organization.team.manage',
                'organization.users.view',
                'organization.reports.view'
            ],
            'Supervisor', 'Team Lead' => [
                'organization.team.view',
                'organization.users.view'
            ],
            default => []
        };

        return array_merge($basePermissions, $additionalPermissions);
    }

    /**
     * Create organization-specific profile data.
     */
    protected function createOrganizationProfile(User $user): void
    {
        if (!$user->profile) {
            $user->profile()->create([]);
        }

        // TODO: Update when organization package is available
        // $organization = $user->organization;
        
        $user->profile->update([
            'organization_id' => $user->organization_id,
            'organization_role' => $user->organization_position,
            'hire_date' => $user->organization_joined_at,
            'employment_status' => 'active',
            'employment_type' => 'full-time', // Default, can be updated
            // 'department' => $organization->departments()->first()?->name,
        ]);
    }

    /**
     * Notify organization admins of new user.
     */
    protected function notifyOrganizationAdmins(User $user): void
    {
        // TODO: Implement when notification classes are created
        // $organization = $user->organization;
        // $admins = $organization->users()
        //     ->where('is_organization_admin', true)
        //     ->where('id', '!=', $user->id)
        //     ->get();

        // foreach ($admins as $admin) {
        //     $admin->notify(new \Litepie\Users\Notifications\NewOrganizationMember($user));
        // }
    }

    /**
     * Revoke organization access.
     */
    protected function revokeOrganizationAccess(User $user): void
    {
        // Remove organization-specific roles
        $organizationRoles = [
            'organization-member',
            'organization-supervisor', 
            'organization-manager',
            'organization-admin',
            'organization-owner',
            'organization-executive',
            'organization-intern'
        ];

        foreach ($organizationRoles as $role) {
            if ($user->hasRole($role)) {
                $user->removeRole($role);
            }
        }

        // Clear organization permissions
        $user->update([
            'organization_permissions' => null,
            'organization_settings' => null
        ]);
    }

    /**
     * Notify organization of user departure.
     */
    protected function notifyOrganizationOfDeparture(User $user): void
    {
        // TODO: Implement when notification classes are created
        // $organization = $user->organization;
        
        // if (!$organization) {
        //     return;
        // }

        // $admins = $organization->users()
        //     ->where('is_organization_admin', true)
        //     ->where('id', '!=', $user->id)
        //     ->get();

        // foreach ($admins as $admin) {
        //     $admin->notify(new \Litepie\Users\Notifications\OrganizationMemberLeft($user));
        // }
    }

    /**
     * Update reporting structure when user leaves.
     */
    protected function updateReportingStructure(User $user): void
    {
        // Update users who report to this user
        $directReports = $user->directReports;
        
        foreach ($directReports as $report) {
            // Move to user's manager or set to null
            $newManagerId = $user->primary_manager_id ?? $user->reports_to_user_id;
            
            $report->update([
                'reports_to_user_id' => $newManagerId,
                'primary_manager_id' => $newManagerId
            ]);
        }

        // Update users who have this user as manager
        $managedUsers = $user->managedUsers()->get();
        
        foreach ($managedUsers as $managedUser) {
            if ($managedUser->primary_manager_id === $user->id) {
                $managedUser->update(['primary_manager_id' => $user->primary_manager_id]);
            }
            
            if ($managedUser->secondary_manager_id === $user->id) {
                $managedUser->update(['secondary_manager_id' => null]);
            }
        }
    }
}
