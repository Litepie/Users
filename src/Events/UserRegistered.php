<?php

namespace Litepie\Users\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Litepie\Users\Models\User;

class UserRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The user instance.
     */
    public User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
