<?php

namespace Litepie\Users\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Litepie\Users\Models\User;

interface UserManagerContract
{
    /**
     * Create a new user.
     */
    public function create(array $data, string $userType = null): User;

    /**
     * Update an existing user.
     */
    public function update(User $user, array $data): User;

    /**
     * Delete a user.
     */
    public function delete(User $user): bool;

    /**
     * Find user by ID.
     */
    public function find(int $id): ?User;

    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Get users by type.
     */
    public function getUsersByType(string $userType): Collection;

    /**
     * Get all user types.
     */
    public function getUserTypes(): array;

    /**
     * Activate a user.
     */
    public function activate(User $user): bool;

    /**
     * Deactivate a user.
     */
    public function deactivate(User $user): bool;

    /**
     * Assign role to user.
     */
    public function assignRole(User $user, string $role): bool;

    /**
     * Remove role from user.
     */
    public function removeRole(User $user, string $role): bool;

    /**
     * Change user password.
     */
    public function changePassword(User $user, string $password): bool;

    /**
     * Send password reset.
     */
    public function sendPasswordReset(User $user): bool;

    /**
     * Get user statistics.
     */
    public function getStatistics(): array;
}
