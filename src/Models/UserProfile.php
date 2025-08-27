<?php

namespace Litepie\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Litepie\Tenancy\Traits\BelongsToTenant;
use Litepie\Logs\Traits\LogsActivity;
use Litepie\Organization\Models\Organization;

class UserProfile extends Model
{
    use HasFactory, BelongsToTenant, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'bio',
        'website',
        'company',
        'job_title',
        'date_of_birth',
        'gender',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'social_links',
        'preferences',
        'metadata',
        // Organization hierarchy fields
        'organization_id',
        'organization_role',
        'employee_id',
        'department',
        'division',
        'team',
        'hire_date',
        'termination_date',
        'employment_status',
        'employment_type',
        'salary',
        'salary_currency',
        'reporting_structure',
        'permissions',
        'organization_metadata',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'social_links' => 'array',
        'preferences' => 'array',
        'metadata' => 'array',
        // Organization hierarchy casts
        'hire_date' => 'date',
        'termination_date' => 'date',
        'salary' => 'decimal:2',
        'reporting_structure' => 'array',
        'permissions' => 'array',
        'organization_metadata' => 'array',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the organization associated with this profile.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the full address.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get age from date of birth.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return $this->date_of_birth->age;
    }

    /**
     * Check if employee is currently employed.
     */
    public function isCurrentlyEmployed(): bool
    {
        return $this->employment_status === 'active' && 
               ($this->hire_date && $this->hire_date->isPast()) &&
               (!$this->termination_date || $this->termination_date->isFuture());
    }

    /**
     * Get employment duration in days.
     */
    public function getEmploymentDurationAttribute(): ?int
    {
        if (!$this->hire_date) {
            return null;
        }

        $endDate = $this->termination_date ?? now();
        return $this->hire_date->diffInDays($endDate);
    }

    /**
     * Get years of service.
     */
    public function getYearsOfServiceAttribute(): ?int
    {
        if (!$this->hire_date) {
            return null;
        }

        $endDate = $this->termination_date ?? now();
        return $this->hire_date->diffInYears($endDate);
    }

    /**
     * Get organization display path.
     */
    public function getOrganizationPathAttribute(): string
    {
        $parts = array_filter([
            $this->department,
            $this->division,
            $this->team
        ]);

        return implode(' > ', $parts);
    }

    /**
     * Check if user has specific organization permission.
     */
    public function hasPermission(string $permission): bool
    {
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    /**
     * Get formatted salary.
     */
    public function getFormattedSalaryAttribute(): ?string
    {
        if (!$this->salary) {
            return null;
        }

        return number_format($this->salary, 2) . ' ' . ($this->salary_currency ?? 'USD');
    }

    /**
     * Activity logging configuration.
     */
    protected $logOnly = [
        'first_name',
        'last_name', 
        'phone',
        'company',
        'job_title',
        'organization_role',
        'employee_id',
        'department',
        'employment_status',
        'hire_date',
        'termination_date'
    ];
    protected $logEvents = ['created', 'updated', 'deleted'];
    protected $logName = 'user-profile';
}
