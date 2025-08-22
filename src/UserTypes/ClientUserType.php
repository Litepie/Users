<?php

namespace Litepie\Users\UserTypes;

class ClientUserType extends BaseUserType
{
    protected string $name = 'client';
    protected string $displayName = 'Client User';
    protected array $defaultRoles = ['client'];
    protected array $defaultPermissions = [
        'view_profile',
        'edit_profile',
        'upload_files',
        'view_projects',
        'create_tickets',
    ];
    protected string $registrationWorkflow = 'client_registration';
    protected bool $allowedInTenants = true;
    protected array $accessibleFeatures = [
        'profile_management',
        'file_uploads',
        'notifications',
        'client_dashboard',
        'project_access',
        'support_tickets',
        'billing',
    ];

    /**
     * Get validation rules for registration.
     */
    public function getRegistrationRules(): array
    {
        return array_merge(parent::getRegistrationRules(), [
            'company' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'terms_accepted' => 'required|accepted',
            'privacy_accepted' => 'required|accepted',
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
            'company' => 'sometimes|required|string|max:255',
            'job_title' => 'sometimes|nullable|string|max:255',
        ]);
    }

    /**
     * Handle user registration.
     */
    public function handleRegistration(array $data): array
    {
        $data = parent::handleRegistration($data);
        
        // Clients require admin approval
        $data['status'] = 'pending';
        
        return $data;
    }

    /**
     * Get default status for new users.
     */
    protected function getDefaultStatus(): string
    {
        return 'pending'; // Clients require approval
    }
}
