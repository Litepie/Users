<?php

namespace Litepie\Users\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Litepie\Tenancy\Traits\BelongsToTenant;
use Litepie\Shield\Traits\HasRoles;
use Litepie\Filehub\Traits\HasFileAttachments;
use Litepie\Flow\Traits\HasWorkflow;
use Litepie\Flow\Contracts\Workflowable;
use Litepie\Logs\Traits\LogsActivity;
use Litepie\Organization\Traits\HasOrganization;
use Litepie\Organization\Models\Organization;
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
        LogsActivity,
        HasOrganization;

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
        // Organization fields
        'organization_id',
        'organization_position',
        'is_organization_admin',
        'is_organization_owner',
        'reports_to_user_id',
        'primary_manager_id',
        'secondary_manager_id',
        'organization_permissions',
        'organization_settings',
        'organization_joined_at',
        'organization_left_at',
        'work_schedule',
        'work_location',
        'office_location',
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
        // Organization casts
        'is_organization_admin' => 'boolean',
        'is_organization_owner' => 'boolean',
        'organization_permissions' => 'array',
        'organization_settings' => 'array',
        'organization_joined_at' => 'datetime',
        'organization_left_at' => 'datetime',
        'work_schedule' => 'array',
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
     * Get the user's organization.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the user that this user reports to.
     */
    public function reportsTo()
    {
        return $this->belongsTo(User::class, 'reports_to_user_id');
    }

    /**
     * Get users that report to this user.
     */
    public function directReports()
    {
        return $this->hasMany(User::class, 'reports_to_user_id');
    }

    /**
     * Get the primary manager.
     */
    public function primaryManager()
    {
        return $this->belongsTo(User::class, 'primary_manager_id');
    }

    /**
     * Get the secondary manager.
     */
    public function secondaryManager()
    {
        return $this->belongsTo(User::class, 'secondary_manager_id');
    }

    /**
     * Get users who have this user as their primary manager.
     */
    public function primaryManagedUsers()
    {
        return $this->hasMany(User::class, 'primary_manager_id');
    }

    /**
     * Get users who have this user as their secondary manager.
     */
    public function secondaryManagedUsers()
    {
        return $this->hasMany(User::class, 'secondary_manager_id');
    }

    /**
     * Get all users managed by this user (primary + secondary).
     */
    public function managedUsers()
    {
        return User::where('primary_manager_id', $this->id)
                  ->orWhere('secondary_manager_id', $this->id);
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
     * Scope users by organization.
     */
    public function scopeInOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Scope organization admins.
     */
    public function scopeOrganizationAdmins($query)
    {
        return $query->where('is_organization_admin', true);
    }

    /**
     * Scope organization owners.
     */
    public function scopeOrganizationOwners($query)
    {
        return $query->where('is_organization_owner', true);
    }

    /**
     * Scope users by organization position.
     */
    public function scopeByPosition($query, string $position)
    {
        return $query->where('organization_position', $position);
    }

    /**
     * Scope users by work location.
     */
    public function scopeByWorkLocation($query, string $location)
    {
        return $query->where('work_location', $location);
    }

    /**
     * Scope managers (users who manage others).
     */
    public function scopeManagers($query)
    {
        return $query->whereExists(function ($subquery) {
            $subquery->select(DB::raw(1))
                    ->from('users as u')
                    ->whereRaw('u.primary_manager_id = users.id OR u.secondary_manager_id = users.id');
        });
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
     * Check if user is organization admin.
     */
    public function isOrganizationAdmin(): bool
    {
        return $this->is_organization_admin || $this->is_organization_owner;
    }

    /**
     * Check if user is organization owner.
     */
    public function isOrganizationOwner(): bool
    {
        return $this->is_organization_owner;
    }

    /**
     * Check if user belongs to an organization.
     */
    public function belongsToOrganization($organizationId = null): bool
    {
        if ($organizationId) {
            return $this->organization_id == $organizationId;
        }
        
        return !is_null($this->organization_id);
    }

    /**
     * Check if user can manage organization.
     */
    public function canManageOrganization(): bool
    {
        return $this->isOrganizationAdmin() && $this->belongsToOrganization();
    }

    /**
     * Check if user is a manager.
     */
    public function isManager(): bool
    {
        return $this->primaryManagedUsers()->exists() || $this->secondaryManagedUsers()->exists();
    }

    /**
     * Check if user reports to another user.
     */
    public function hasReportsTo(): bool
    {
        return !is_null($this->reports_to_user_id);
    }

    /**
     * Get organization hierarchy level.
     */
    public function getHierarchyLevel(): int
    {
        $level = 0;
        $currentUser = $this;
        
        while ($currentUser && $currentUser->reports_to_user_id) {
            $level++;
            $currentUser = $currentUser->reportsTo;
            
            // Prevent infinite loops
            if ($level > 10) {
                break;
            }
        }
        
        return $level;
    }

    /**
     * Get all subordinates (recursive).
     */
    public function getAllSubordinates()
    {
        $subordinates = collect();
        
        foreach ($this->directReports as $report) {
            $subordinates->push($report);
            $subordinates = $subordinates->merge($report->getAllSubordinates());
        }
        
        return $subordinates;
    }

    /**
     * Get organization hierarchy path.
     */
    public function getHierarchyPath(): array
    {
        $path = [];
        $currentUser = $this;
        
        while ($currentUser) {
            array_unshift($path, [
                'id' => $currentUser->id,
                'name' => $currentUser->name,
                'position' => $currentUser->organization_position,
            ]);
            
            $currentUser = $currentUser->reportsTo;
            
            // Prevent infinite loops
            if (count($path) > 10) {
                break;
            }
        }
        
        return $path;
    }

    /**
     * Check if user has organization permission.
     */
    public function hasOrganizationPermission(string $permission): bool
    {
        $permissions = $this->organization_permissions ?? [];
        
        return in_array($permission, $permissions) || $this->isOrganizationAdmin();
    }

    /**
     * Join organization.
     */
    public function joinOrganization(int $organizationId, string $position = null, bool $isAdmin = false): bool
    {
        return $this->update([
            'organization_id' => $organizationId,
            'organization_position' => $position,
            'is_organization_admin' => $isAdmin,
            'organization_joined_at' => now(),
            'organization_left_at' => null,
        ]);
    }

    /**
     * Leave organization.
     */
    public function leaveOrganization(): bool
    {
        return $this->update([
            'organization_id' => null,
            'organization_position' => null,
            'is_organization_admin' => false,
            'is_organization_owner' => false,
            'reports_to_user_id' => null,
            'primary_manager_id' => null,
            'secondary_manager_id' => null,
            'organization_permissions' => null,
            'organization_settings' => null,
            'organization_left_at' => now(),
        ]);
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
     * Activity logging configuration.
     */
    protected $logOnly = ['name', 'email', 'user_type', 'status'];
    protected $logEvents = ['created', 'updated', 'deleted'];
    protected $logName = 'user-management';

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
