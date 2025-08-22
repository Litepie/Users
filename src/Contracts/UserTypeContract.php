<?php

namespace Litepie\Users\Contracts;

interface UserTypeContract
{
    /**
     * Get the user type name.
     */
    public function getName(): string;

    /**
     * Get the user type display name.
     */
    public function getDisplayName(): string;

    /**
     * Get default roles for this user type.
     */
    public function getDefaultRoles(): array;

    /**
     * Get default permissions for this user type.
     */
    public function getDefaultPermissions(): array;

    /**
     * Get the registration workflow name.
     */
    public function getRegistrationWorkflow(): string;

    /**
     * Check if user type is allowed in tenants.
     */
    public function isAllowedInTenants(): bool;

    /**
     * Get validation rules for registration.
     */
    public function getRegistrationRules(): array;

    /**
     * Get validation rules for profile update.
     */
    public function getUpdateRules(): array;

    /**
     * Handle user registration.
     */
    public function handleRegistration(array $data): array;

    /**
     * Handle user activation.
     */
    public function handleActivation($user): bool;

    /**
     * Check if user can access specific features.
     */
    public function canAccess(string $feature): bool;
}
