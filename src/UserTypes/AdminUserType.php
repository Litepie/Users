<?php

namespace Litepie\Users\UserTypes;

class AdminUserType extends BaseUserType
{
    protected string $name = 'admin';
    protected string $displayName = 'Administrator';
    protected array $defaultRoles = ['admin'];
    protected array $defaultPermissions = [
        'view_profile',
        'edit_profile',
        'upload_files',
        'manage_users',
        'manage_roles',
        'manage_permissions',
        'view_reports',
        'manage_settings',
    ];
    protected string $registrationWorkflow = 'admin_registration';
    protected bool $allowedInTenants = false; // Admins are global
    protected array $accessibleFeatures = [
        '*', // Admins can access all features
    ];

    /**
     * Get validation rules for registration.
     */
    public function getRegistrationRules(): array
    {
        return array_merge(parent::getRegistrationRules(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'department' => 'required|string|max:255',
            'invitation_code' => 'required|string|exists:admin_invitations,code',
        ]);
    }

    /**
     * Get validation rules for profile update.
     */
    public function getUpdateRules(): array
    {
        return array_merge(parent::getUpdateRules(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'department' => 'sometimes|required|string|max:255',
        ]);
    }

    /**
     * Handle user registration.
     */
    public function handleRegistration(array $data): array
    {
        $data = parent::handleRegistration($data);
        
        // Admins require super admin approval
        $data['status'] = 'pending';
        
        // Remove tenant assignment for admins
        unset($data['tenant_id']);
        
        return $data;
    }

    /**
     * Get default status for new users.
     */
    protected function getDefaultStatus(): string
    {
        return 'pending'; // Admins require super admin approval
    }
}
