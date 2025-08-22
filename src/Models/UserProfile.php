<?php

namespace Litepie\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Litepie\Tenancy\Traits\BelongsToTenant;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

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
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'social_links' => 'array',
        'preferences' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
     * Get activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'first_name',
                'last_name',
                'phone',
                'company',
                'job_title'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
