<?php

namespace Litepie\Users\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Litepie\Users\Models\User;

class UserRoleChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The user instance.
     */
    public User $user;

    /**
     * The action performed (assigned/removed).
     */
    public string $action;

    /**
     * The role name.
     */
    public string $role;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $action, string $role)
    {
        $this->user = $user;
        $this->action = $action;
        $this->role = $role;
    }
}
