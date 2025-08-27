<?php

namespace Litepie\Users\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Litepie\Repository\Contracts\RepositoryInterface;
use Litepie\Users\Models\User;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Find users by status.
     */
    public function findByStatus(string $status): Collection;

    /**
     * Find active users.
     */
    public function findActiveUsers(): Collection;

    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find user by email and verify password.
     */
    public function findByEmailAndPassword(string $email, string $password): ?User;

    /**
     * Find users by user type.
     */
    public function findByUserType(string $userType): Collection;

    /**
     * Search users by name or email.
     */
    public function searchUsers(string $term, array $columns = ['name', 'email']): Collection;

    /**
     * Get recent users within specified days.
     */
    public function getRecentUsers(int $days = 30): Collection;

    /**
     * Get users with their profiles.
     */
    public function getUsersWithProfiles(): Collection;

    /**
     * Get users with roles and permissions.
     */
    public function getUsersWithRolesAndPermissions(): Collection;

    /**
     * Get paginated users with filters.
     */
    public function getPaginatedUsers(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get users by tenant.
     */
    public function getUsersByTenant(int $tenantId): Collection;

    /**
     * Create user with profile.
     */
    public function createUserWithProfile(array $userData, array $profileData = []): User;

    /**
     * Update user status.
     */
    public function updateStatus(int $userId, string $status): bool;

    /**
     * Bulk update user status.
     */
    public function bulkUpdateStatus(array $userIds, string $status): int;

    /**
     * Count users by status.
     */
    public function countByStatus(string $status): int;

    /**
     * Count users by user type.
     */
    public function countByUserType(string $userType): int;

    /**
     * Get user statistics.
     */
    public function getUserStatistics(): array;

    /**
     * Get users for export.
     */
    public function getUsersForExport(array $filters = []): Collection;

    /**
     * Find users with pending email verification.
     */
    public function findUsersWithPendingVerification(): Collection;

    /**
     * Find users with expired passwords.
     */
    public function findUsersWithExpiredPasswords(int $days = 90): Collection;

    /**
     * Find inactive users.
     */
    public function findInactiveUsers(int $days = 30): Collection;

    /**
     * Get user login trends.
     */
    public function getUserLoginTrends(string $period = '30days'): array;

    /**
     * Get user registration trends.
     */
    public function getUserRegistrationTrends(string $period = '30days'): array;

    /**
     * Find users by role.
     */
    public function findUsersByRole(string $role): Collection;

    /**
     * Find users by permission.
     */
    public function findUsersByPermission(string $permission): Collection;

    /**
     * Get users with multiple filters.
     */
    public function getFilteredUsers(array $filters, array $sorts = [], int $limit = null): Collection;

    /**
     * Search users with advanced filters.
     */
    public function advancedSearch(array $criteria): Collection;

    /**
     * Get user activity summary.
     */
    public function getUserActivitySummary(int $userId, int $days = 30): array;

    /**
     * Archive inactive users.
     */
    public function archiveInactiveUsers(int $days = 180): int;

    /**
     * Restore archived users.
     */
    public function restoreArchivedUsers(array $userIds): int;

    /**
     * Get users for analytics.
     */
    public function getUsersForAnalytics(array $metrics = [], string $period = '30days'): array;
}
