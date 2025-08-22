<?php

namespace Litepie\Users\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Litepie\Users\Models\User;
use Litepie\Users\Services\UserManager;
use Litepie\Users\Http\Requests\UpdateProfileRequest;
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
    public function show(): View
    {
        $user = Auth::user();
        $user->load(['profile', 'roles']);
        
        return view('users::profile.show', compact('user'));
    }

    /**
     * Show the form for editing the profile
     */
    public function edit(): View
    {
        $user = Auth::user();
        $user->load(['profile']);
        
        return view('users::profile.edit', compact('user'));
    }

    /**
     * Update the user's profile
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        try {
            $user = Auth::user();
            $this->userManager->updateProfile($user, $request->validated());
            
            return redirect()
                ->route('users.profile.show')
                ->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    /**
     * Upload user avatar
     */
    public function uploadAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $user = Auth::user();
            
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
            
            return redirect()
                ->back()
                ->with('success', 'Avatar uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to upload avatar: ' . $e->getMessage());
        }
    }

    /**
     * Remove user avatar
     */
    public function removeAvatar(): RedirectResponse
    {
        try {
            $user = Auth::user();
            
            if ($user->profile && $user->profile->avatar_id) {
                // Delete the file using Filehub
                $this->filehub->delete($user->profile->avatar_id);
                
                // Remove avatar from profile
                $user->profile->update(['avatar_id' => null]);
            }
            
            return redirect()
                ->back()
                ->with('success', 'Avatar removed successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to remove avatar: ' . $e->getMessage());
        }
    }

    /**
     * Show user activity
     */
    public function activity(): View
    {
        $user = Auth::user();
        
        // Get user activity logs
        $activities = activity()
            ->causedBy($user)
            ->latest()
            ->paginate(20);
        
        return view('users::profile.activity', compact('user', 'activities'));
    }

    /**
     * Show user's login attempts
     */
    public function loginAttempts(): View
    {
        $user = Auth::user();
        $loginAttempts = $user->loginAttempts()
            ->latest()
            ->paginate(20);
        
        return view('users::profile.login-attempts', compact('user', 'loginAttempts'));
    }

    /**
     * Show user's active sessions
     */
    public function sessions(): View
    {
        $user = Auth::user();
        $sessions = $user->sessions()
            ->where('active', true)
            ->latest()
            ->get();
        
        return view('users::profile.sessions', compact('user', 'sessions'));
    }

    /**
     * Revoke a session
     */
    public function revokeSession(Request $request, string $sessionId): RedirectResponse
    {
        $request->validate([
            'session_id' => 'required|string'
        ]);

        try {
            $user = Auth::user();
            $session = $user->sessions()->where('id', $sessionId)->first();
            
            if ($session) {
                $session->update(['active' => false]);
                
                return redirect()
                    ->back()
                    ->with('success', 'Session revoked successfully.');
            }
            
            return redirect()
                ->back()
                ->with('error', 'Session not found.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to revoke session: ' . $e->getMessage());
        }
    }
}
