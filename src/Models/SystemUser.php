<?php

namespace Litepie\Users\Models;

class SystemUser extends User
{
    /**
     * The user type for this model.
     */
    protected string $userType = 'system';

    /**
     * Additional fillable fields for system users.
     */
    protected $fillable = [
        'name',
        'email',
        'api_key',
        'api_secret',
        'description',
        'is_active',
        'last_used_at',
        'rate_limit',
        'allowed_ips',
        'metadata',
    ];

    /**
     * Additional cast fields for system users.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
        'allowed_ips' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        parent::booted();

        static::creating(function ($user) {
            $user->user_type = 'system';
            
            if (!$user->api_key) {
                $user->api_key = 'sys_' . bin2hex(random_bytes(32));
            }
        });
    }

    /**
     * Get the registration workflow name.
     */
    public function getWorkflowName(): string
    {
        return 'system_registration';
    }

    /**
     * Get API usage logs.
     */
    public function apiLogs()
    {
        return $this->hasMany(ApiLog::class, 'system_user_id');
    }

    /**
     * Check if IP address is allowed.
     */
    public function isIpAllowed(string $ip): bool
    {
        if (empty($this->allowed_ips)) {
            return true; // No restrictions
        }

        return in_array($ip, $this->allowed_ips);
    }

    /**
     * Check if API key is valid and active.
     */
    public function isApiKeyValid(): bool
    {
        return $this->is_active && !empty($this->api_key);
    }

    /**
     * Update last used timestamp.
     */
    public function updateLastUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Generate new API credentials.
     */
    public function regenerateApiCredentials(): array
    {
        $apiKey = 'sys_' . bin2hex(random_bytes(32));
        $apiSecret = bin2hex(random_bytes(64));

        $this->update([
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
        ]);

        return [
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
        ];
    }

    /**
     * Log API usage.
     */
    public function logApiUsage(string $endpoint, string $method, array $data = []): void
    {
        $this->apiLogs()->create([
            'endpoint' => $endpoint,
            'method' => $method,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'data' => $data,
        ]);
    }
}
