<?php

namespace Litepie\Users\UserTypes;

class SystemUserType extends BaseUserType
{
    protected string $name = 'system';
    protected string $displayName = 'System User';
    protected array $defaultRoles = ['system'];
    protected array $defaultPermissions = [
        'api_access',
        'system_operations',
        'read_data',
        'write_data',
    ];
    protected string $registrationWorkflow = 'system_registration';
    protected bool $allowedInTenants = false; // System users are global
    protected array $accessibleFeatures = [
        'api_access',
        'system_operations',
        'automated_tasks',
    ];

    /**
     * Get validation rules for registration.
     */
    public function getRegistrationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'api_key' => 'required|string|unique:users,api_key',
            'description' => 'required|string|max:500',
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ];
    }

    /**
     * Get validation rules for profile update.
     */
    public function getUpdateRules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:500',
            'permissions' => 'sometimes|required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ];
    }

    /**
     * Handle user registration.
     */
    public function handleRegistration(array $data): array
    {
        $data = parent::handleRegistration($data);
        
        // System users don't need email verification
        $data['email_verified_at'] = now();
        
        // System users are immediately active
        $data['status'] = 'active';
        
        // Generate API token if not provided
        if (!isset($data['api_key'])) {
            $data['api_key'] = $this->generateApiKey();
        }
        
        // Remove tenant assignment for system users
        unset($data['tenant_id']);
        
        return $data;
    }

    /**
     * Handle user activation.
     */
    public function handleActivation($user): bool
    {
        parent::handleActivation($user);
        
        // Generate API token for system user
        $token = $user->createToken('system-token', $this->getDefaultPermissions());
        
        // Store token reference
        $user->update(['api_key' => $token->plainTextToken]);
        
        return true;
    }

    /**
     * Get default status for new users.
     */
    protected function getDefaultStatus(): string
    {
        return 'active'; // System users are immediately active
    }

    /**
     * Generate a unique API key.
     */
    protected function generateApiKey(): string
    {
        return 'sys_' . bin2hex(random_bytes(32));
    }
}
