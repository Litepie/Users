<?php

namespace Litepie\Users\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Routing\Controller as BaseController;
use Litepie\Users\Models\User;
use Litepie\Users\Services\UserManager;
use Litepie\Users\Http\Requests\CreateUserRequest;
use Litepie\Users\Http\Requests\UpdateUserRequest;

class UserController extends BaseController
{
    public function __construct(
        private UserManager $userManager
    ) {}

    /**
     * Display a listing of users
     */
    public function index(Request $request): View
    {
        $users = User::with(['profile', 'roles'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->user_type, function ($query, $userType) {
                $query->where('user_type', $userType);
            })
            ->paginate(20);

        return view('users::manage.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create(): View
    {
        return view('users::manage.create');
    }

    /**
     * Store a newly created user
     */
    public function store(CreateUserRequest $request): RedirectResponse
    {
        try {
            $user = $this->userManager->createUser($request->validated());
            
            return redirect()
                ->route('users.manage.show', $user)
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user): View
    {
        $user->load(['profile', 'roles', 'permissions', 'loginAttempts']);
        
        return view('users::manage.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user): View
    {
        $user->load(['profile', 'roles']);
        
        return view('users::manage.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            $this->userManager->updateUser($user, $request->validated());
            
            return redirect()
                ->route('users.manage.show', $user)
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            $this->userManager->deleteUser($user);
            
            return redirect()
                ->route('users.manage.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Activate a user
     */
    public function activate(User $user): RedirectResponse
    {
        try {
            $this->userManager->activateUser($user);
            
            return redirect()
                ->back()
                ->with('success', 'User activated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to activate user: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate a user
     */
    public function deactivate(User $user): RedirectResponse
    {
        try {
            $this->userManager->deactivateUser($user);
            
            return redirect()
                ->back()
                ->with('success', 'User deactivated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to deactivate user: ' . $e->getMessage());
        }
    }

    /**
     * Suspend a user
     */
    public function suspend(User $user): RedirectResponse
    {
        try {
            $this->userManager->suspendUser($user);
            
            return redirect()
                ->back()
                ->with('success', 'User suspended successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to suspend user: ' . $e->getMessage());
        }
    }

    /**
     * Ban a user
     */
    public function ban(User $user): RedirectResponse
    {
        try {
            $this->userManager->banUser($user);
            
            return redirect()
                ->back()
                ->with('success', 'User banned successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to ban user: ' . $e->getMessage());
        }
    }

    /**
     * Assign a role to user
     */
    public function assignRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        try {
            $this->userManager->assignRole($user, $request->role);
            
            return redirect()
                ->back()
                ->with('success', 'Role assigned successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to assign role: ' . $e->getMessage());
        }
    }

    /**
     * Remove a role from user
     */
    public function removeRole(User $user, string $role): RedirectResponse
    {
        try {
            $this->userManager->removeRole($user, $role);
            
            return redirect()
                ->back()
                ->with('success', 'Role removed successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to remove role: ' . $e->getMessage());
        }
    }
}
