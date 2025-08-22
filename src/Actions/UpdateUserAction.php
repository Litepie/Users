<?php

namespace Litepie\Users\Actions;

use Litepie\Actions\CompleteAction;
use Litepie\Users\Models\User;
use Litepie\Users\Events\UserUpdated;

class UpdateUserAction extends CompleteAction
{
    /**
     * Get validation rules.
     */
    protected function rules(): array
    {
        $userId = $this->model->id ?? null;
        
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => "sometimes|required|string|email|max:255|unique:users,email,{$userId}",
            'user_type' => 'sometimes|string|in:user,client,admin,system',
            'status' => 'sometimes|string|in:pending,active,inactive,suspended,banned',
            'timezone' => 'sometimes|string|max:255',
            'locale' => 'sometimes|string|max:10',
        ];
    }

    /**
     * Handle the action execution.
     */
    protected function handle(): User
    {
        $user = $this->model;
        
        // Handle password change
        if (isset($this->data['password'])) {
            // Check if password was used recently
            if ($user->wasPasswordUsedRecently($this->data['password'])) {
                throw new \Exception('Password was used recently. Please choose a different password.');
            }
            
            $this->data['password'] = \Hash::make($this->data['password']);
            
            // Add to password history
            $user->passwordHistory()->create([
                'password' => $this->data['password'],
            ]);
        }

        // Update user
        $user->update($this->data);

        // Update profile if profile data exists
        if (isset($this->data['profile'])) {
            $user->profile()->updateOrCreate([], $this->data['profile']);
        }

        return $user->fresh();
    }

    /**
     * Get sub-actions to execute.
     */
    protected function getSubActions(string $timing): array
    {
        if ($timing === 'after') {
            $subActions = [];
            
            // If status changed to active, assign default roles
            if (isset($this->data['status']) && $this->data['status'] === 'active') {
                $subActions[] = [
                    'action' => AssignDefaultRolesAction::class,
                    'data' => ['user_type' => $this->model->user_type],
                ];
            }
            
            // If email changed, send verification
            if (isset($this->data['email']) && $this->data['email'] !== $this->model->email) {
                $subActions[] = [
                    'action' => SendEmailVerificationAction::class,
                ];
            }
            
            return $subActions;
        }

        return [];
    }

    /**
     * Get events to fire.
     */
    protected function getEvents(): array
    {
        return [
            UserUpdated::class,
        ];
    }

    /**
     * Get description for logging.
     */
    protected function getDescription(string $status): string
    {
        $email = $this->model->email ?? 'unknown';
        
        return "User update: {$email} - {$status}";
    }
}
