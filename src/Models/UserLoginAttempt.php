<?php

namespace Litepie\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserLoginAttempt extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'email',
        'ip_address',
        'user_agent',
        'successful',
        'attempted_at',
        'location',
        'device_info',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'successful' => 'boolean',
        'attempted_at' => 'datetime',
        'device_info' => 'array',
    ];

    /**
     * Get the user that owns the login attempt.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope successful attempts.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('successful', true);
    }

    /**
     * Scope failed attempts.
     */
    public function scopeFailed($query)
    {
        return $query->where('successful', false);
    }

    /**
     * Scope recent attempts.
     */
    public function scopeRecent($query, $minutes = 60)
    {
        return $query->where('attempted_at', '>=', now()->subMinutes($minutes));
    }
}
