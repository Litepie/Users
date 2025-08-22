<?php

namespace Litepie\Users\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Litepie\Tenancy\Traits\BelongsToTenant;
use Litepie\Roles\Traits\HasRoles;
use Litepie\Filehub\Traits\HasFileAttachments;
use Litepie\Flow\Traits\HasWorkflow;
use Litepie\Flow\Contracts\Workflowable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail, Workflowable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        SoftDeletes,
        BelongsToTenant,
        HasRoles,
        HasFileAttachments,
        HasWorkflow,
        LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'status',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
        'timezone',
        'locale',
        'avatar_url',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'metadata',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_enabled' => 'boolean',
        'two_factor_recovery_codes' => 'array',
        'metadata' => 'array',
    ];

    /**
     * User status constants.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_BANNED = 'banned';

    /**
     * User type constants.
     */
    const TYPE_USER = 'user';
    const TYPE_CLIENT = 'client';
    const TYPE_ADMIN = 'admin';
    const TYPE_SYSTEM = 'system';

    /**
     * Get the user's profile.
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get the user's login attempts.
     */
    public function loginAttempts()
    {
        return $this->hasMany(UserLoginAttempt::class);
    }

    /**
     * Get the user's password history.
     */
    public function passwordHistory()
    {
        return $this->hasMany(PasswordHistory::class);
    }

    /**
     * Get the user's sessions.
     */
    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * Scope users by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('user_type', $type);
    }

    /**
     * Scope active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Scope pending users.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope verified users.
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope unverified users.
     */
    public function scopeUnverified($query)
    {
        return $query->whereNull('email_verified_at');
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if user is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if user is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    /**
     * Check if user is banned.
     */
    public function isBanned(): bool
    {
        return $this->status === self::STATUS_BANNED;
    }

    /**
     * Activate the user.
     */
    public function activate(): bool
    {
        return $this->update(['status' => self::STATUS_ACTIVE]);
    }

    /**
     * Deactivate the user.
     */
    public function deactivate(): bool
    {
        return $this->update(['status' => self::STATUS_INACTIVE]);
    }

    /**
     * Suspend the user.
     */
    public function suspend(): bool
    {
        return $this->update(['status' => self::STATUS_SUSPENDED]);
    }

    /**
     * Ban the user.
     */
    public function ban(): bool
    {
        return $this->update(['status' => self::STATUS_BANNED]);
    }

    /**
     * Get user type instance.
     */
    public function getUserTypeInstance()
    {
        $userTypes = config('users.user_types', []);
        $userType = $userTypes[$this->user_type] ?? null;

        if ($userType && isset($userType['class'])) {
            return app($userType['class']);
        }

        return null;
    }

    /**
     * Get workflow name for this user.
     */
    public function getWorkflowName(): string
    {
        $userTypeInstance = $this->getUserTypeInstance();
        
        if ($userTypeInstance) {
            return $userTypeInstance->getRegistrationWorkflow();
        }

        return 'user_registration';
    }

    /**
     * Get workflow state column.
     */
    protected function getWorkflowStateColumn(): string
    {
        return 'status';
    }

    /**
     * Check if user can access tenant.
     */
    public function canAccessTenant(int $tenantId): bool
    {
        if (!config('users.tenancy.enabled', true)) {
            return true;
        }

        // Super admins can access all tenants
        if ($this->hasRole('super-admin')) {
            return true;
        }

        // Check if user belongs to the tenant
        return $this->tenant_id === $tenantId;
    }

    /**
     * Get user's full name.
     */
    public function getFullNameAttribute(): string
    {
        if ($this->profile) {
            return trim($this->profile->first_name . ' ' . $this->profile->last_name);
        }

        return $this->name;
    }

    /**
     * Get user's avatar URL.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        $avatar = $this->getFirstFileAttachment('avatars');
        
        if ($avatar) {
            return $avatar->getVariantUrl('medium');
        }

        return $this->attributes['avatar_url'] ?? null;
    }

    /**
     * Update last login information.
     */
    public function updateLastLogin(): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }

    /**
     * Check if password needs to be changed.
     */
    public function needsPasswordChange(): bool
    {
        $days = config('users.security.force_password_change_days', 90);
        
        if (!$days) {
            return false;
        }

        $lastPasswordChange = $this->passwordHistory()
            ->latest()
            ->first();

        if (!$lastPasswordChange) {
            return true;
        }

        return $lastPasswordChange->created_at->addDays($days)->isPast();
    }

    /**
     * Check if password was used recently.
     */
    public function wasPasswordUsedRecently(string $password): bool
    {
        $preventReuse = config('users.security.prevent_password_reuse', 5);
        
        if (!$preventReuse) {
            return false;
        }

        $recentPasswords = $this->passwordHistory()
            ->latest()
            ->limit($preventReuse)
            ->get();

        foreach ($recentPasswords as $passwordRecord) {
            if (password_verify($password, $passwordRecord->password)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'user_type', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Route notifications for the mail channel.
     */
    public function routeNotificationForMail(): array|string
    {
        return $this->email;
    }

    /**
     * Route notifications for the database channel.
     */
    public function routeNotificationForDatabase(): string
    {
        return $this->id;
    }
}
