<?php

namespace Litepie\Users\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Litepie\Users\Services\UserManager;
use Litepie\Users\Http\Requests\Api\UpdateProfileRequest;
use Litepie\Users\Http\Resources\UserResource;
use Litepie\Filehub\Contracts\FilehubContract;

class ProfileController extends BaseController
{
    public function __construct(
        private UserManager $userManager,
        private FilehubContract $filehub
    ) {}

    /**
     * Display the user's profile
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load(['profile', 'roles']);
        
        return response()->json([
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Update the user's profile
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            $updatedUser = $this->userManager->updateProfile($user, $request->validated());
            
            return response()->json([
                'message' => 'Profile updated successfully',
                'data' => new UserResource($updatedUser)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Upload user avatar
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $user = $request->user();
            
            // Upload the file using Filehub
            $file = $this->filehub->upload($request->file('avatar'), [
                'folder' => 'avatars',
                'user_id' => $user->id,
                'public' => true
            ]);
            
            // Update user profile with avatar
            $user->profile()->updateOrCreate([], [
                'avatar_id' => $file->id
            ]);
            
            return response()->json([
                'message' => 'Avatar uploaded successfully',
                'data' => [
                    'avatar_url' => $file->url,
                    'avatar_id' => $file->id
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to upload avatar',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove user avatar
     */
    public function removeAvatar(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if ($user->profile && $user->profile->avatar_id) {
                // Delete the file using Filehub
                $this->filehub->delete($user->profile->avatar_id);
                
                // Remove avatar from profile
                $user->profile->update(['avatar_id' => null]);
            }
            
            return response()->json([
                'message' => 'Avatar removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to remove avatar',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get user activity
     */
    public function activity(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Get user activity logs
        $activities = activity()
            ->causedBy($user)
            ->latest()
            ->paginate($request->get('per_page', 15));
        
        return response()->json([
            'data' => $activities->items(),
            'meta' => [
                'current_page' => $activities->currentPage(),
                'last_page' => $activities->lastPage(),
                'per_page' => $activities->perPage(),
                'total' => $activities->total(),
            ]
        ]);
    }

    /**
     * Get user's login attempts
     */
    public function loginAttempts(Request $request): JsonResponse
    {
        $user = $request->user();
        $loginAttempts = $user->loginAttempts()
            ->latest()
            ->paginate($request->get('per_page', 15));
        
        return response()->json([
            'data' => $loginAttempts->items(),
            'meta' => [
                'current_page' => $loginAttempts->currentPage(),
                'last_page' => $loginAttempts->lastPage(),
                'per_page' => $loginAttempts->perPage(),
                'total' => $loginAttempts->total(),
            ]
        ]);
    }

    /**
     * Get user's active sessions
     */
    public function sessions(Request $request): JsonResponse
    {
        $user = $request->user();
        $sessions = $user->sessions()
            ->where('active', true)
            ->latest()
            ->get();
        
        return response()->json([
            'data' => $sessions
        ]);
    }

    /**
     * Revoke a session
     */
    public function revokeSession(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => 'required|string'
        ]);

        try {
            $user = $request->user();
            $session = $user->sessions()->where('id', $request->session_id)->first();
            
            if ($session) {
                $session->update(['active' => false]);
                
                return response()->json([
                    'message' => 'Session revoked successfully'
                ]);
            }
            
            return response()->json([
                'message' => 'Session not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to revoke session',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Change password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = $request->user();
            
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'Current password is incorrect',
                    'errors' => [
                        'current_password' => ['Current password is incorrect']
                    ]
                ], 422);
            }
            
            // Update password
            $this->userManager->changePassword($user, $request->new_password);
            
            return response()->json([
                'message' => 'Password changed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to change password',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Enable two-factor authentication
     */
    public function enableTwoFactor(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $secret = $this->userManager->enableTwoFactorAuth($user);
            
            return response()->json([
                'message' => 'Two-factor authentication enabled',
                'data' => [
                    'secret' => $secret,
                    'qr_code_url' => $this->userManager->getTwoFactorQrCodeUrl($user, $secret)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to enable two-factor authentication',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Disable two-factor authentication
     */
    public function disableTwoFactor(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        try {
            $user = $request->user();
            
            // Verify password
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Password is incorrect',
                    'errors' => [
                        'password' => ['Password is incorrect']
                    ]
                ], 422);
            }
            
            $this->userManager->disableTwoFactorAuth($user);
            
            return response()->json([
                'message' => 'Two-factor authentication disabled'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to disable two-factor authentication',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get user statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $stats = [
            'total_logins' => $user->loginAttempts()->where('successful', true)->count(),
            'failed_logins' => $user->loginAttempts()->where('successful', false)->count(),
            'active_sessions' => $user->sessions()->where('active', true)->count(),
            'last_login' => $user->last_login_at,
            'account_age_days' => $user->created_at->diffInDays(now()),
            'profile_completion' => $this->userManager->calculateProfileCompletion($user),
        ];
        
        return response()->json([
            'data' => $stats
        ]);
    }

    /**
     * Delete account
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
            'confirmation' => 'required|in:DELETE_MY_ACCOUNT'
        ]);

        try {
            $user = $request->user();
            
            // Verify password
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Password is incorrect',
                    'errors' => [
                        'password' => ['Password is incorrect']
                    ]
                ], 422);
            }
            
            // Delete the account
            $this->userManager->deleteUser($user);
            
            return response()->json([
                'message' => 'Account deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete account',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
