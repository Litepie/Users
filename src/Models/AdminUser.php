<?php

namespace Litepie\Users\Models;

class AdminUser extends User
{
    /**
     * The user type for this model.
     */
    protected string $userType = 'admin';

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        parent::booted();

        static::creating(function ($user) {
            $user->user_type = 'admin';
        });
    }

    /**
     * Get the registration workflow name.
     */
    public function getWorkflowName(): string
    {
        return 'admin_registration';
    }

    /**
     * Get admin's department.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get users managed by this admin.
     */
    public function managedUsers()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    /**
     * Get admin's activity logs.
     */
    public function adminLogs()
    {
        return $this->hasMany(AdminLog::class, 'admin_id');
    }

    /**
     * Check if admin can manage user.
     */
    public function canManageUser(User $user): bool
    {
        // Super admins can manage anyone
        if ($this->hasRole('super-admin')) {
            return true;
        }

        // Admins can manage users in their department
        if ($this->department_id && $user->department_id === $this->department_id) {
            return true;
        }

        // Admins can manage users they directly manage
        return $user->manager_id === $this->id;
    }

    /**
     * Log admin action.
     */
    public function logAction(string $action, array $data = []): void
    {
        $this->adminLogs()->create([
            'action' => $action,
            'data' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}
