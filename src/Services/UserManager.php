<?php

namespace Litepie\Users\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Litepie\Users\Contracts\UserManagerContract;
use Litepie\Users\Models\User;
use Litepie\Users\Models\PasswordHistory;
use Litepie\Users\Events\UserRegistered;
use Litepie\Users\Events\UserActivated;
use Litepie\Users\Events\UserDeactivated;
use Litepie\Users\Events\UserRoleChanged;
use Litepie\Users\Mail\PasswordResetMail;
use Litepie\Users\Repositories\Contracts\UserRepositoryInterface;
use Litepie\Users\Repositories\Contracts\UserProfileRepositoryInterface;

class UserManager implements UserManagerContract
{
    /**
     * User repository instance.
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * User profile repository instance.
     */
    protected UserProfileRepositoryInterface $profileRepository;

    /**
     * Create a new user manager instance.
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        UserProfileRepositoryInterface $profileRepository
    ) {
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
    }

    /**
     * Create a new user.
     */
    public function create(array $data, string $userType = null): User
    {
        $userType = $userType ?: config('users.default_user_type', 'user');
        
        // Get user type instance
        $userTypeInstance = $this->getUserTypeInstance($userType);
        
        if ($userTypeInstance) {
            $data = $userTypeInstance->handleRegistration($data);
        }

        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Extract profile data if present
        $profileData = $data['profile'] ?? [];
        unset($data['profile']);

        // Create user using repository
        if (!empty($profileData)) {
            $user = $this->userRepository->createUserWithProfile($data, $profileData);
        } else {
            $user = $this->userRepository->create($data);
        }

        // Store password in history
        if (isset($data['password'])) {
            $this->addPasswordToHistory($user, $data['password']);
        }

        // Fire event
        event(new UserRegistered($user));

        return $user;
    }

    /**
     * Update an existing user.
     */
    public function update(User $user, array $data): User
    {
        // Hash password if provided
        if (isset($data['password'])) {
            // Check if password was used recently
            if ($this->wasPasswordUsedRecently($user, $data['password'])) {
                throw new \Exception('Password was used recently. Please choose a different password.');
            }

            $data['password'] = Hash::make($data['password']);
            $this->addPasswordToHistory($user, $data['password']);
        }

        // Extract profile data if present
        $profileData = $data['profile'] ?? [];
        unset($data['profile']);

        // Update user using repository
        $updatedUser = $this->userRepository->update($user->id, $data);

        // Update profile if profile data is provided
        if (!empty($profileData)) {
            $this->profileRepository->createOrUpdateForUser($user->id, $profileData);
        }

        return $updatedUser->fresh();
    }

    /**
     * Delete a user.
     */
    public function delete(User $user): bool
    {
        return $this->userRepository->delete($user->id);
    }

    /**
     * Find user by ID.
     */
    public function find(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Get users by type.
     */
    public function getUsersByType(string $userType): Collection
    {
        return $this->userRepository->findByUserType($userType);
    }

    /**
     * Get all user types.
     */
    public function getUserTypes(): array
    {
        return config('users.user_types', []);
    }

    /**
     * Activate a user.
     */
    public function activate(User $user): bool
    {
        $result = $this->userRepository->updateStatus($user->id, 'active');

        if ($result) {
            // Handle activation via user type
            $userTypeInstance = $user->getUserTypeInstance();
            if ($userTypeInstance) {
                $userTypeInstance->handleActivation($user);
            }

            event(new UserActivated($user));
        }

        return $result;
    }

    /**
     * Deactivate a user.
     */
    public function deactivate(User $user): bool
    {
        $result = $this->userRepository->updateStatus($user->id, 'inactive');

        if ($result) {
            event(new UserDeactivated($user));
        }

        return $result;
    }

    /**
     * Assign role to user.
     */
    public function assignRole(User $user, string $role): bool
    {
        $user->assignRole($role);
        
        event(new UserRoleChanged($user, 'assigned', $role));
        
        return true;
    }

    /**
     * Remove role from user.
     */
    public function removeRole(User $user, string $role): bool
    {
        $user->removeRole($role);
        
        event(new UserRoleChanged($user, 'removed', $role));
        
        return true;
    }

    /**
     * Change user password.
     */
    public function changePassword(User $user, string $password): bool
    {
        // Check if password was used recently
        if ($this->wasPasswordUsedRecently($user, $password)) {
            throw new \Exception('Password was used recently. Please choose a different password.');
        }

        $hashedPassword = Hash::make($password);
        
        $updatedUser = $this->userRepository->update($user->id, ['password' => $hashedPassword]);
        
        if ($updatedUser) {
            $this->addPasswordToHistory($user, $hashedPassword);
            return true;
        }
        
        return false;
    }

    /**
     * Send password reset.
     */
    public function sendPasswordReset(User $user): bool
    {
        try {
            Mail::to($user->email)->send(new PasswordResetMail($user));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get user statistics.
     */
    public function getStatistics(): array
    {
        return $this->userRepository->getUserStatistics();
    }

    /**
     * Search users.
     */
    public function searchUsers(string $term, array $columns = ['name', 'email']): Collection
    {
        return $this->userRepository->searchUsers($term, $columns);
    }

    /**
     * Get filtered users.
     */
    public function getFilteredUsers(array $filters, array $sorts = [], int $limit = null): Collection
    {
        return $this->userRepository->getFilteredUsers($filters, $sorts, $limit);
    }

    /**
     * Get paginated users.
     */
    public function getPaginatedUsers(array $filters = [], int $perPage = 15)
    {
        return $this->userRepository->getPaginatedUsers($filters, $perPage);
    }

    /**
     * Get recent users.
     */
    public function getRecentUsers(int $days = 30): Collection
    {
        return $this->userRepository->getRecentUsers($days);
    }

    /**
     * Get active users.
     */
    public function getActiveUsers(): Collection
    {
        return $this->userRepository->findActiveUsers();
    }

    /**
     * Get users with pending verification.
     */
    public function getUsersWithPendingVerification(): Collection
    {
        return $this->userRepository->findUsersWithPendingVerification();
    }

    /**
     * Get users with expired passwords.
     */
    public function getUsersWithExpiredPasswords(int $days = 90): Collection
    {
        return $this->userRepository->findUsersWithExpiredPasswords($days);
    }

    /**
     * Get inactive users.
     */
    public function getInactiveUsers(int $days = 30): Collection
    {
        return $this->userRepository->findInactiveUsers($days);
    }

    /**
     * Bulk update user status.
     */
    public function bulkUpdateStatus(array $userIds, string $status): int
    {
        return $this->userRepository->bulkUpdateStatus($userIds, $status);
    }

    /**
     * Archive inactive users.
     */
    public function archiveInactiveUsers(int $days = 180): int
    {
        return $this->userRepository->archiveInactiveUsers($days);
    }

    /**
     * Get user analytics.
     */
    public function getUserAnalytics(array $metrics = [], string $period = '30days'): array
    {
        return $this->userRepository->getUsersForAnalytics($metrics, $period);
    }

    /**
     * Export users.
     */
    public function exportUsers(array $filters = []): Collection
    {
        return $this->userRepository->getUsersForExport($filters);
    }

    /**
     * Get user type instance.
     */
    protected function getUserTypeInstance(string $userType)
    {
        $userTypes = config('users.user_types', []);
        $userTypeConfig = $userTypes[$userType] ?? null;

        if ($userTypeConfig && isset($userTypeConfig['class'])) {
            return app($userTypeConfig['class']);
        }

        return null;
    }

    /**
     * Add password to history.
     */
    protected function addPasswordToHistory(User $user, string $hashedPassword): void
    {
        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $hashedPassword,
        ]);

        // Clean up old password history
        $keepCount = config('users.security.prevent_password_reuse', 5);
        if ($keepCount > 0) {
            $oldPasswords = PasswordHistory::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->skip($keepCount)
                ->pluck('id');

            if ($oldPasswords->isNotEmpty()) {
                PasswordHistory::whereIn('id', $oldPasswords)->delete();
            }
        }
    }

    /**
     * Check if password was used recently.
     */
    protected function wasPasswordUsedRecently(User $user, string $password): bool
    {
        $preventReuse = config('users.security.prevent_password_reuse', 5);
        
        if (!$preventReuse) {
            return false;
        }

        $recentPasswords = PasswordHistory::where('user_id', $user->id)
            ->latest()
            ->limit($preventReuse)
            ->get();

        foreach ($recentPasswords as $passwordRecord) {
            if (Hash::check($password, $passwordRecord->password)) {
                return true;
            }
        }

        return false;
    }
}
