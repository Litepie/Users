<?php

namespace Litepie\Users\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Litepie\Users\Models\User;
use Litepie\Users\Services\UserManager;
use Litepie\Users\Http\Requests\RegisterRequest;
use Litepie\Users\Events\UserRegistered;

class RegisterController extends BaseController
{
    public function __construct(
        private UserManager $userManager
    ) {}

    /**
     * Show the registration form
     */
    public function showRegistrationForm(): View
    {
        return view('users::auth.register');
    }

    /**
     * Handle a registration request
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        try {
            $userData = $request->validated();
            
            // Hash the password
            $userData['password'] = Hash::make($userData['password']);
            
            // Set default user type if not provided
            $userData['user_type'] = $userData['user_type'] ?? 'regular';
            
            // Set initial status
            $userData['status'] = config('users.registration.auto_activate') ? 'active' : 'pending';
            
            // Create the user
            $user = $this->userManager->createUser($userData);
            
            // Fire registration event
            event(new UserRegistered($user));
            
            // Auto-login if configured
            if (config('users.registration.auto_login') && $user->status === 'active') {
                Auth::login($user);
                
                return redirect()
                    ->intended(route('users.profile.show'))
                    ->with('success', 'Registration successful! Welcome aboard.');
            }
            
            return redirect()
                ->route('users.login')
                ->with('success', 'Registration successful! Please check your email for verification instructions.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle email verification
     */
    public function verify(Request $request, string $token): RedirectResponse
    {
        try {
            $user = User::where('verification_token', $token)->first();
            
            if (!$user) {
                return redirect()
                    ->route('users.login')
                    ->with('error', 'Invalid verification token.');
            }
            
            if ($user->hasVerifiedEmail()) {
                return redirect()
                    ->route('users.login')
                    ->with('info', 'Email already verified.');
            }
            
            // Verify the email
            $user->markEmailAsVerified();
            $user->update(['status' => 'active']);
            
            return redirect()
                ->route('users.login')
                ->with('success', 'Email verified successfully! You can now login.');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('users.login')
                ->with('error', 'Verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        try {
            $user = User::where('email', $request->email)->first();
            
            if ($user->hasVerifiedEmail()) {
                return redirect()
                    ->back()
                    ->with('info', 'Email is already verified.');
            }
            
            // Resend verification email
            $user->sendEmailVerificationNotification();
            
            return redirect()
                ->back()
                ->with('success', 'Verification email sent successfully.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to send verification email: ' . $e->getMessage());
        }
    }
}
