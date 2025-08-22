<?php

namespace Litepie\Users\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Litepie\Users\Models\User;
use Litepie\Users\Models\UserLoginAttempt;
use Litepie\Users\Http\Requests\LoginRequest;

class LoginController extends BaseController
{
    /**
     * Show the login form
     */
    public function showLoginForm(): View
    {
        return view('users::auth.login');
    }

    /**
     * Handle a login request
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $this->ensureIsNotRateLimited($request);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Log the login attempt
        $this->logLoginAttempt($request, $credentials['email']);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Clear the rate limiter
            RateLimiter::clear($this->throttleKey($request));
            
            // Update login attempt as successful
            $this->updateLoginAttempt($request, true);
            
            // Update user's last login
            $user = Auth::user();
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip()
            ]);
            
            return redirect()
                ->intended(route('users.profile.show'))
                ->with('success', 'Welcome back!');
        }

        // Update login attempt as failed
        $this->updateLoginAttempt($request, false);
        
        // Hit the rate limiter
        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }

    /**
     * Log the user out
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('users.login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Ensure the login request is not rate limited
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request
     */
    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }

    /**
     * Log the login attempt
     */
    protected function logLoginAttempt(Request $request, string $email): void
    {
        $user = User::where('email', $email)->first();

        UserLoginAttempt::create([
            'user_id' => $user?->id,
            'email' => $email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'successful' => false, // Will be updated later
            'attempted_at' => now(),
        ]);
    }

    /**
     * Update the login attempt status
     */
    protected function updateLoginAttempt(Request $request, bool $successful): void
    {
        UserLoginAttempt::where('ip_address', $request->ip())
            ->where('attempted_at', '>=', now()->subMinutes(5))
            ->latest()
            ->first()
            ?->update(['successful' => $successful]);
    }

    /**
     * Show password reset form
     */
    public function showResetForm(): View
    {
        return view('users::auth.passwords.reset');
    }

    /**
     * Send password reset link
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        try {
            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                return redirect()
                    ->back()
                    ->with('success', 'If an account with that email exists, we\'ve sent password reset instructions.');
            }
            
            // Send password reset notification
            $user->sendPasswordResetNotification($user->createToken('password-reset')->plainTextToken);
            
            return redirect()
                ->back()
                ->with('success', 'Password reset instructions have been sent to your email.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to send reset email: ' . $e->getMessage());
        }
    }
}
