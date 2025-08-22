<?php

namespace Litepie\Users\UserTypes;

class RegularUserType extends BaseUserType
{
    protected string $name = 'user';
    protected string $displayName = 'Regular User';
    protected array $defaultRoles = ['user'];
    protected array $defaultPermissions = [
        'view_profile',
        'edit_profile',
        'upload_files',
    ];
    protected string $registrationWorkflow = 'user_registration';
    protected bool $allowedInTenants = true;
    protected array $accessibleFeatures = [
        'profile_management',
        'file_uploads',
        'notifications',
        'basic_dashboard',
    ];

    /**
     * Get validation rules for registration.
     */
    public function getRegistrationRules(): array
    {
        return array_merge(parent::getRegistrationRules(), [
            'terms_accepted' => 'required|accepted',
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
            'phone' => 'sometimes|nullable|string|max:20',
        ]);
    }

    /**
     * Get default status for new users.
     */
    protected function getDefaultStatus(): string
    {
        return 'active'; // Regular users are activated immediately
    }
}
