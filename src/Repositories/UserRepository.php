<?php

namespace Litepie\Users\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Litepie\Repository\BaseRepository;
use Litepie\Users\Models\User;
use Litepie\Users\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * Find users by status.
     */
    public function findByStatus(string $status): Collection
    {
        return $this->where('status', $status)->get();
    }

    /**
     * Find active users.
     */
    public function findActiveUsers(): Collection
    {
        return $this->findByStatus(User::STATUS_ACTIVE);
    }

    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?User
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Find user by email and verify password.
     */
    public function findByEmailAndPassword(string $email, string $password): ?User
    {
        $user = $this->findByEmail($email);
        
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }

        return null;
    }

    /**
     * Find users by user type.
     */
    public function findByUserType(string $userType): Collection
    {
        return $this->where('user_type', $userType)->get();
    }

    /**
     * Search users by name or email.
     */
    public function searchUsers(string $term, array $columns = ['name', 'email']): Collection
    {
        $query = $this->makeModel()->newQuery();
        
        foreach ($columns as $i => $column) {
            if ($i === 0) {
                $query = $query->where($column, 'LIKE', "%{$term}%");
            } else {
                $query = $query->orWhere($column, 'LIKE', "%{$term}%");
            }
        }

        return $query->get();
    }

    /**
     * Get recent users within specified days.
     */
    public function getRecentUsers(int $days = 30): Collection
    {
        return $this->where('created_at', '>=', Carbon::now()->subDays($days))->get();
    }

    /**
     * Get users with their profiles.
     */
    public function getUsersWithProfiles(): Collection
    {
        return $this->with('profile')->get();
    }

    /**
     * Get users with roles and permissions.
     */
    public function getUsersWithRolesAndPermissions(): Collection
    {
        return $this->with(['roles', 'permissions'])->get();
    }

    /**
     * Get paginated users with filters.
     */
    public function getPaginatedUsers(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->makeModel()->newQuery();

        foreach ($filters as $field => $value) {
            if (is_array($value)) {
                $query = $query->whereIn($field, $value);
            } else {
                $query = $query->where($field, $value);
            }
        }

        return $query->paginate($perPage);
    }

    /**
     * Get users by tenant.
     */
    public function getUsersByTenant(int $tenantId): Collection
    {
        return $this->where('tenant_id', $tenantId)->get();
    }

    /**
     * Create user with profile.
     */
    public function createUserWithProfile(array $userData, array $profileData = []): User
    {
        return DB::transaction(function () use ($userData, $profileData) {
            $user = $this->create($userData);
            
            if (!empty($profileData)) {
                $user->profile()->create($profileData);
            }

            return $user->load('profile');
        });
    }

    /**
     * Update user status.
     */
    public function updateStatus(int $userId, string $status): bool
    {
        $user = $this->find($userId);
        if ($user) {
            return $user->update(['status' => $status]);
        }
        return false;
    }

    /**
     * Bulk update user status.
     */
    public function bulkUpdateStatus(array $userIds, string $status): int
    {
        return $this->makeModel()->newQuery()
            ->whereIn('id', $userIds)
            ->update(['status' => $status]);
    }

    /**
     * Count users by status.
     */
    public function countByStatus(string $status): int
    {
        return $this->where('status', $status)->count();
    }

    /**
     * Count users by user type.
     */
    public function countByUserType(string $userType): int
    {
        return $this->where('user_type', $userType)->count();
    }

    /**
     * Get user statistics.
     */
    public function getUserStatistics(): array
    {
        return [
            'total' => $this->count(),
            'active' => $this->countByStatus(User::STATUS_ACTIVE),
            'inactive' => $this->countByStatus(User::STATUS_INACTIVE),
            'pending' => $this->countByStatus(User::STATUS_PENDING),
            'suspended' => $this->countByStatus(User::STATUS_SUSPENDED),
            'banned' => $this->countByStatus(User::STATUS_BANNED),
            'by_type' => [
                'admin' => $this->countByUserType(User::TYPE_ADMIN),
                'client' => $this->countByUserType(User::TYPE_CLIENT),
                'user' => $this->countByUserType(User::TYPE_USER),
                'system' => $this->countByUserType(User::TYPE_SYSTEM),
            ],
            'recent_registrations' => $this->getRecentUsers(30)->count(),
            'recent_logins' => $this->where('last_login_at', '>=', Carbon::now()->subDays(30))->count(),
        ];
    }

    /**
     * Get users for export.
     */
    public function getUsersForExport(array $filters = []): Collection
    {
        $query = $this->makeModel()->newQuery()->with(['profile', 'roles']);

        foreach ($filters as $field => $value) {
            if (is_array($value) && count($value) === 2) {
                $query = $query->where($field, $value[0], $value[1]);
            } elseif (is_array($value)) {
                $query = $query->whereIn($field, $value);
            } else {
                $query = $query->where($field, $value);
            }
        }

        return $query->get();
    }

    /**
     * Find users with pending email verification.
     */
    public function findUsersWithPendingVerification(): Collection
    {
        return $this->whereNull('email_verified_at')->get();
    }

    /**
     * Find users with expired passwords.
     */
    public function findUsersWithExpiredPasswords(int $days = 90): Collection
    {
        return $this->makeModel()->newQuery()
            ->where('password_updated_at', '<', Carbon::now()->subDays($days))
            ->orWhereNull('password_updated_at')
            ->get();
    }

    /**
     * Find inactive users.
     */
    public function findInactiveUsers(int $days = 30): Collection
    {
        return $this->makeModel()->newQuery()
            ->where('last_login_at', '<', Carbon::now()->subDays($days))
            ->orWhereNull('last_login_at')
            ->where('status', User::STATUS_ACTIVE)
            ->get();
    }

    /**
     * Get user login trends.
     */
    public function getUserLoginTrends(string $period = '30days'): array
    {
        $days = $this->parsePeriodToDays($period);
        $startDate = Carbon::now()->subDays($days);

        return $this->makeModel()->newQuery()
            ->selectRaw('DATE(last_login_at) as date, COUNT(*) as count')
            ->where('last_login_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
    }

    /**
     * Get user registration trends.
     */
    public function getUserRegistrationTrends(string $period = '30days'): array
    {
        $days = $this->parsePeriodToDays($period);
        $startDate = Carbon::now()->subDays($days);

        return $this->makeModel()->newQuery()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
    }

    /**
     * Find users by role.
     */
    public function findUsersByRole(string $role): Collection
    {
        return $this->makeModel()->newQuery()->whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })->get();
    }

    /**
     * Find users by permission.
     */
    public function findUsersByPermission(string $permission): Collection
    {
        return $this->makeModel()->newQuery()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->get();
    }

    /**
     * Get users with multiple filters.
     */
    public function getFilteredUsers(array $filters, array $sorts = [], int $limit = null): Collection
    {
        $query = $this->makeModel()->newQuery();

        foreach ($filters as $field => $value) {
            if (is_array($value)) {
                $query = $query->whereIn($field, $value);
            } else {
                $query = $query->where($field, $value);
            }
        }

        foreach ($sorts as $field => $direction) {
            $query = $query->orderBy($field, $direction);
        }

        if ($limit) {
            $query = $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Search users with advanced filters.
     */
    public function advancedSearch(array $criteria): Collection
    {
        $query = $this->makeModel()->newQuery();

        foreach ($criteria as $field => $config) {
            if (isset($config['operator']) && isset($config['value'])) {
                $query = $query->where($field, $config['operator'], $config['value']);
            } elseif (isset($config['in'])) {
                $query = $query->whereIn($field, $config['in']);
            } elseif (isset($config['like'])) {
                $query = $query->where($field, 'LIKE', "%{$config['like']}%");
            }
        }

        return $query->get();
    }

    /**
     * Get user activity summary.
     */
    public function getUserActivitySummary(int $userId, int $days = 30): array
    {
        $user = $this->find($userId);
        $startDate = Carbon::now()->subDays($days);

        return [
            'user_id' => $userId,
            'period_days' => $days,
            'login_count' => $user->loginAttempts()
                ->where('created_at', '>=', $startDate)
                ->where('successful', true)
                ->count(),
            'last_login' => $user->last_login_at?->toDateTimeString(),
            'profile_updates' => $user->activityLogs()
                ->where('description', 'like', '%profile%')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'total_activity' => $user->activityLogs()
                ->where('created_at', '>=', $startDate)
                ->count(),
        ];
    }

    /**
     * Archive inactive users.
     */
    public function archiveInactiveUsers(int $days = 180): int
    {
        return $this->makeModel()->newQuery()
            ->where('last_login_at', '<', Carbon::now()->subDays($days))
            ->where('status', User::STATUS_ACTIVE)
            ->update(['status' => 'archived']);
    }

    /**
     * Restore archived users.
     */
    public function restoreArchivedUsers(array $userIds): int
    {
        return $this->makeModel()->newQuery()
            ->whereIn('id', $userIds)
            ->where('status', 'archived')
            ->update(['status' => User::STATUS_ACTIVE]);
    }

    /**
     * Get users for analytics.
     */
    public function getUsersForAnalytics(array $metrics = [], string $period = '30days'): array
    {
        $days = $this->parsePeriodToDays($period);
        $startDate = Carbon::now()->subDays($days);

        $data = [];

        if (empty($metrics) || in_array('user_types', $metrics)) {
            $data['user_types'] = $this->makeModel()->newQuery()
                ->selectRaw('user_type, COUNT(*) as count')
                ->where('created_at', '>=', $startDate)
                ->groupBy('user_type')
                ->get()
                ->pluck('count', 'user_type')
                ->toArray();
        }

        if (empty($metrics) || in_array('status_distribution', $metrics)) {
            $data['status_distribution'] = $this->makeModel()->newQuery()
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();
        }

        if (empty($metrics) || in_array('registration_growth', $metrics)) {
            $data['registration_growth'] = $this->getUserRegistrationTrends($period);
        }

        if (empty($metrics) || in_array('login_activity', $metrics)) {
            $data['login_activity'] = $this->getUserLoginTrends($period);
        }

        return $data;
    }

    /**
     * Parse period string to days.
     */
    private function parsePeriodToDays(string $period): int
    {
        return match ($period) {
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            '1year' => 365,
            default => 30,
        };
    }
}
