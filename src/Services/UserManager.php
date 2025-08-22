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

class UserManager implements UserManagerContract
{
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

        // Create user
        $user = User::create($data);

        // Create profile if profile data is provided
        if (isset($data['profile'])) {
            $user->profile()->create($data['profile']);
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

        // Update user
        $user->update($data);

        // Update profile if profile data is provided
        if (isset($data['profile'])) {
            $user->profile()->updateOrCreate([], $data['profile']);
        }

        return $user->fresh();
    }

    /**
     * Delete a user.
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Find user by ID.
     */
    public function find(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Get users by type.
     */
    public function getUsersByType(string $userType): Collection
    {
        return User::ofType($userType)->get();
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
        $result = $user->activate();

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
        $result = $user->deactivate();

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
        
        $result = $user->update(['password' => $hashedPassword]);
        
        if ($result) {
            $this->addPasswordToHistory($user, $hashedPassword);
        }
        
        return $result;
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
        $total = User::count();
        $active = User::active()->count();
        $pending = User::pending()->count();
        $verified = User::verified()->count();

        $userTypes = [];
        foreach ($this->getUserTypes() as $type => $config) {
            $userTypes[$type] = User::ofType($type)->count();
        }

        return [
            'total' => $total,
            'active' => $active,
            'pending' => $pending,
            'verified' => $verified,
            'unverified' => $total - $verified,
            'user_types' => $userTypes,
            'last_updated' => now(),
        ];
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
