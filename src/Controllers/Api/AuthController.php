<?php

namespace Litepie\Users\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Litepie\Users\Models\User;
use Litepie\Users\Services\UserManager;
use Litepie\Users\Http\Requests\Api\RegisterRequest;
use Litepie\Users\Http\Requests\Api\LoginRequest;
use Litepie\Users\Http\Resources\UserResource;
use Litepie\Users\Events\UserRegistered;

class AuthController extends BaseController
{
    public function __construct(
        private UserManager $userManager
    ) {}

    /**
     * Register a new user
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $userData = $request->validated();
            
            // Hash the password
            $userData['password'] = Hash::make($userData['password']);
            
            // Set default user type
            $userData['user_type'] = $userData['user_type'] ?? 'regular';
            
            // Set initial status
            $userData['status'] = config('users.registration.auto_activate') ? 'active' : 'pending';
            
            // Create the user
            $user = $this->userManager->createUser($userData);
            
            // Fire registration event
            event(new UserRegistered($user));
            
            // Create token if user is active
            $token = null;
            if ($user->status === 'active') {
                $token = $user->createToken('auth-token')->plainTextToken;
            }
            
            return response()->json([
                'message' => 'User registered successfully',
                'user' => new UserResource($user),
                'token' => $token,
                'requires_verification' => $user->status !== 'active'
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Login user
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $this->ensureIsNotRateLimited($request);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is active
            if ($user->status !== 'active') {
                return response()->json([
                    'message' => 'Account is not active',
                    'status' => $user->status
                ], 403);
            }
            
            // Clear rate limiter
            RateLimiter::clear($this->throttleKey($request));
            
            // Create token
            $token = $user->createToken('auth-token')->plainTextToken;
            
            // Update user's last login
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip()
            ]);
            
            return response()->json([
                'message' => 'Login successful',
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer'
            ]);
        }

        // Hit the rate limiter
        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        // Revoke the current token
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Refresh token
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Revoke current token
        $request->user()->currentAccessToken()->delete();
        
        // Create new token
        $token = $user->createToken('auth-token')->plainTextToken;
        
        return response()->json([
            'message' => 'Token refreshed successfully',
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load(['profile', 'roles']);
        
        return response()->json([
            'user' => new UserResource($user)
        ]);
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
            'email' => ["Too many login attempts. Please try again in {$seconds} seconds."],
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
     * Verify email
     */
    public function verifyEmail(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        try {
            $user = User::where('verification_token', $request->token)->first();
            
            if (!$user) {
                return response()->json([
                    'message' => 'Invalid verification token'
                ], 400);
            }
            
            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'message' => 'Email already verified'
                ], 400);
            }
            
            // Verify the email
            $user->markEmailAsVerified();
            $user->update(['status' => 'active']);
            
            return response()->json([
                'message' => 'Email verified successfully',
                'user' => new UserResource($user)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Verification failed',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        try {
            $user = User::where('email', $request->email)->first();
            
            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'message' => 'Email is already verified'
                ], 400);
            }
            
            // Resend verification email
            $user->sendEmailVerificationNotification();
            
            return response()->json([
                'message' => 'Verification email sent successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send verification email',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
