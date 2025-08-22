<?php

namespace Litepie\Users\Models;

class ClientUser extends User
{
    /**
     * The user type for this model.
     */
    protected string $userType = 'client';

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        parent::booted();

        static::creating(function ($user) {
            $user->user_type = 'client';
        });
    }

    /**
     * Get the registration workflow name.
     */
    public function getWorkflowName(): string
    {
        return 'client_registration';
    }

    /**
     * Get client-specific projects.
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    /**
     * Get client-specific tickets.
     */
    public function tickets()
    {
        return $this->hasMany(SupportTicket::class, 'client_id');
    }

    /**
     * Get client's billing information.
     */
    public function billingInfo()
    {
        return $this->hasOne(ClientBilling::class, 'client_id');
    }

    /**
     * Check if client has active projects.
     */
    public function hasActiveProjects(): bool
    {
        return $this->projects()->where('status', 'active')->exists();
    }

    /**
     * Get client's total project value.
     */
    public function getTotalProjectValue(): float
    {
        return $this->projects()->sum('value') ?? 0.0;
    }
}
