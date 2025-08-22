<?php

namespace Litepie\Users\UserTypes;

use Litepie\Users\Contracts\UserTypeContract;

abstract class BaseUserType implements UserTypeContract
{
    /**
     * The user type name.
     */
    protected string $name;

    /**
     * The user type display name.
     */
    protected string $displayName;

    /**
     * Default roles for this user type.
     */
    protected array $defaultRoles = [];

    /**
     * Default permissions for this user type.
     */
    protected array $defaultPermissions = [];

    /**
     * Registration workflow name.
     */
    protected string $registrationWorkflow = 'user_registration';

    /**
     * Whether this user type is allowed in tenants.
     */
    protected bool $allowedInTenants = true;

    /**
     * Features accessible by this user type.
     */
    protected array $accessibleFeatures = [];

    /**
     * Get the user type name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the user type display name.
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * Get default roles for this user type.
     */
    public function getDefaultRoles(): array
    {
        return $this->defaultRoles;
    }

    /**
     * Get default permissions for this user type.
     */
    public function getDefaultPermissions(): array
    {
        return $this->defaultPermissions;
    }

    /**
     * Get the registration workflow name.
     */
    public function getRegistrationWorkflow(): string
    {
        return $this->registrationWorkflow;
    }

    /**
     * Check if user type is allowed in tenants.
     */
    public function isAllowedInTenants(): bool
    {
        return $this->allowedInTenants;
    }

    /**
     * Get validation rules for registration.
     */
    public function getRegistrationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => config('users.security.password_rules', 'required|string|min:8|confirmed'),
        ];
    }

    /**
     * Get validation rules for profile update.
     */
    public function getUpdateRules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,{id}',
        ];
    }

    /**
     * Handle user registration.
     */
    public function handleRegistration(array $data): array
    {
        // Add user type
        $data['user_type'] = $this->getName();
        
        // Set default status
        $data['status'] = $this->getDefaultStatus();

        return $data;
    }

    /**
     * Handle user activation.
     */
    public function handleActivation($user): bool
    {
        // Assign default roles
        foreach ($this->getDefaultRoles() as $role) {
            $user->assignRole($role);
        }

        // Grant default permissions
        foreach ($this->getDefaultPermissions() as $permission) {
            $user->givePermissionTo($permission);
        }

        return true;
    }

    /**
     * Check if user can access specific features.
     */
    public function canAccess(string $feature): bool
    {
        return in_array($feature, $this->accessibleFeatures) || 
               in_array('*', $this->accessibleFeatures);
    }

    /**
     * Get default status for new users.
     */
    protected function getDefaultStatus(): string
    {
        return 'pending';
    }
}
