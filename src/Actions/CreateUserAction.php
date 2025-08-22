<?php

namespace Litepie\Users\Actions;

use Litepie\Actions\CompleteAction;
use Litepie\Users\Models\User;
use Litepie\Users\Events\UserRegistered;
use Illuminate\Support\Facades\Hash;

class CreateUserAction extends CompleteAction
{
    /**
     * Get validation rules.
     */
    protected function rules(): array
    {
        $userType = $this->data['user_type'] ?? config('users.default_user_type', 'user');
        
        // Get user type instance for specific validation rules
        $userTypes = config('users.user_types', []);
        $userTypeConfig = $userTypes[$userType] ?? null;
        
        if ($userTypeConfig && isset($userTypeConfig['class'])) {
            $userTypeInstance = app($userTypeConfig['class']);
            return $userTypeInstance->getRegistrationRules();
        }
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'sometimes|string|in:user,client,admin,system',
        ];
    }

    /**
     * Handle the action execution.
     */
    protected function handle(): User
    {
        $userType = $this->data['user_type'] ?? config('users.default_user_type', 'user');
        
        // Get user type instance
        $userTypes = config('users.user_types', []);
        $userTypeConfig = $userTypes[$userType] ?? null;
        
        if ($userTypeConfig && isset($userTypeConfig['class'])) {
            $userTypeInstance = app($userTypeConfig['class']);
            $this->data = $userTypeInstance->handleRegistration($this->data);
        }

        // Hash password
        if (isset($this->data['password'])) {
            $this->data['password'] = Hash::make($this->data['password']);
        }

        // Set tenant if available and user type allows it
        if ($userTypeInstance && $userTypeInstance->isAllowedInTenants()) {
            if (function_exists('tenancy') && tenancy()->current()) {
                $this->data['tenant_id'] = tenancy()->current()->id;
            }
        }

        // Create user
        $user = User::create($this->data);

        // Create profile if profile data exists
        if (isset($this->data['profile'])) {
            $user->profile()->create($this->data['profile']);
        }

        return $user;
    }

    /**
     * Get sub-actions to execute.
     */
    protected function getSubActions(string $timing): array
    {
        if ($timing === 'after') {
            return [
                [
                    'action' => AssignDefaultRolesAction::class,
                    'data' => ['user_type' => $this->data['user_type'] ?? 'user'],
                ],
                [
                    'action' => SendWelcomeEmailAction::class,
                    'condition' => fn($data) => config('users.notifications.welcome_email', true),
                ],
            ];
        }

        return [];
    }

    /**
     * Get events to fire.
     */
    protected function getEvents(): array
    {
        return [
            UserRegistered::class,
        ];
    }

    /**
     * Get notifications to send.
     */
    protected function getNotifications(): array
    {
        if (!config('users.notifications.enabled', true)) {
            return [];
        }

        return [
            [
                'recipients' => [$this->getResult()->getData()],
                'class' => \Litepie\Users\Notifications\UserRegisteredNotification::class,
            ],
        ];
    }

    /**
     * Get description for logging.
     */
    protected function getDescription(string $status): string
    {
        $userType = $this->data['user_type'] ?? 'user';
        $email = $this->data['email'] ?? 'unknown';
        
        return "User registration ({$userType}): {$email} - {$status}";
    }
}
