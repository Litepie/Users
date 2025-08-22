<?php

namespace Litepie\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'started_at',
        'last_activity',
        'ended_at',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'started_at' => 'datetime',
        'last_activity' => 'datetime',
        'ended_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the session.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope active sessions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope expired sessions.
     */
    public function scopeExpired($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * End the session.
     */
    public function end(): bool
    {
        return $this->update([
            'ended_at' => now(),
            'is_active' => false,
        ]);
    }
}
