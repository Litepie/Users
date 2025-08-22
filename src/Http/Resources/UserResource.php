<?php

namespace Litepie\Users\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'user_type' => $this->user_type,
            'status' => $this->status,
            'phone' => $this->phone,
            'timezone' => $this->timezone,
            'locale' => $this->locale,
            'email_verified_at' => $this->email_verified_at,
            'last_login_at' => $this->last_login_at,
            'last_login_ip' => $this->last_login_ip,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relationships
            'profile' => new UserProfileResource($this->whenLoaded('profile')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            
            // Computed attributes
            'avatar_url' => $this->avatar_url,
            'full_name' => $this->full_name,
            'is_active' => $this->is_active,
            'is_verified' => $this->hasVerifiedEmail(),
            'can_login' => $this->canLogin(),
            
            // Statistics (only for admin/owner)
            $this->mergeWhen($this->shouldShowStatistics($request), [
                'login_attempts_count' => $this->login_attempts_count,
                'successful_logins_count' => $this->successful_logins_count,
                'failed_logins_count' => $this->failed_logins_count,
                'active_sessions_count' => $this->active_sessions_count,
            ]),
            
            // Sensitive data (only for owner or admin)
            $this->mergeWhen($this->shouldShowSensitiveData($request), [
                'two_factor_enabled' => $this->two_factor_enabled,
                'backup_codes_generated' => $this->backup_codes_generated,
            ]),
        ];
    }

    /**
     * Determine if statistics should be shown
     */
    protected function shouldShowStatistics(Request $request): bool
    {
        $user = $request->user();
        
        return $user && (
            $user->id === $this->id || 
            $user->hasRole(['admin', 'manager'])
        );
    }

    /**
     * Determine if sensitive data should be shown
     */
    protected function shouldShowSensitiveData(Request $request): bool
    {
        $user = $request->user();
        
        return $user && (
            $user->id === $this->id || 
            $user->hasRole('admin')
        );
    }
}
