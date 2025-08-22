<?php

namespace Litepie\Users\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Litepie\Users\Models\User;
use Litepie\Users\Services\UserManager;
use Litepie\Users\Http\Requests\Api\CreateUserRequest;
use Litepie\Users\Http\Requests\Api\UpdateUserRequest;
use Litepie\Users\Http\Resources\UserResource;
use Litepie\Users\Http\Resources\UserCollection;

class UserController extends BaseController
{
    public function __construct(
        private UserManager $userManager
    ) {}

    /**
     * Display a listing of users
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::with(['profile', 'roles']);

        // Apply filters
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->user_type) {
            $query->where('user_type', $request->user_type);
        }

        if ($request->role) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => new UserCollection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    /**
     * Store a newly created user
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userManager->createUser($request->validated());
            
            return response()->json([
                'message' => 'User created successfully',
                'data' => new UserResource($user)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user): JsonResponse
    {
        $user->load(['profile', 'roles', 'permissions']);
        
        return response()->json([
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Update the specified user
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            $updatedUser = $this->userManager->updateUser($user, $request->validated());
            
            return response()->json([
                'message' => 'User updated successfully',
                'data' => new UserResource($updatedUser)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $this->userManager->deleteUser($user);
            
            return response()->json([
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Activate a user
     */
    public function activate(User $user): JsonResponse
    {
        try {
            $this->userManager->activateUser($user);
            
            return response()->json([
                'message' => 'User activated successfully',
                'data' => new UserResource($user->fresh())
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to activate user',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Deactivate a user
     */
    public function deactivate(User $user): JsonResponse
    {
        try {
            $this->userManager->deactivateUser($user);
            
            return response()->json([
                'message' => 'User deactivated successfully',
                'data' => new UserResource($user->fresh())
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to deactivate user',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Suspend a user
     */
    public function suspend(User $user): JsonResponse
    {
        try {
            $this->userManager->suspendUser($user);
            
            return response()->json([
                'message' => 'User suspended successfully',
                'data' => new UserResource($user->fresh())
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to suspend user',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Ban a user
     */
    public function ban(User $user): JsonResponse
    {
        try {
            $this->userManager->banUser($user);
            
            return response()->json([
                'message' => 'User banned successfully',
                'data' => new UserResource($user->fresh())
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to ban user',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get user roles
     */
    public function getRoles(User $user): JsonResponse
    {
        return response()->json([
            'data' => $user->roles->pluck('name')
        ]);
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        try {
            $this->userManager->assignRole($user, $request->role);
            
            return response()->json([
                'message' => 'Role assigned successfully',
                'data' => new UserResource($user->fresh(['roles']))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to assign role',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove role from user
     */
    public function removeRole(User $user, string $role): JsonResponse
    {
        try {
            $this->userManager->removeRole($user, $role);
            
            return response()->json([
                'message' => 'Role removed successfully',
                'data' => new UserResource($user->fresh(['roles']))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to remove role',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get user permissions
     */
    public function getPermissions(User $user): JsonResponse
    {
        return response()->json([
            'data' => [
                'direct_permissions' => $user->permissions->pluck('name'),
                'role_permissions' => $user->getPermissionsViaRoles()->pluck('name'),
                'all_permissions' => $user->getAllPermissions()->pluck('name')
            ]
        ]);
    }

    /**
     * Give permission to user
     */
    public function givePermission(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'permission' => 'required|string|exists:permissions,name'
        ]);

        try {
            $user->givePermissionTo($request->permission);
            
            return response()->json([
                'message' => 'Permission granted successfully',
                'data' => new UserResource($user->fresh(['permissions']))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to grant permission',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Revoke permission from user
     */
    public function revokePermission(User $user, string $permission): JsonResponse
    {
        try {
            $user->revokePermissionTo($permission);
            
            return response()->json([
                'message' => 'Permission revoked successfully',
                'data' => new UserResource($user->fresh(['permissions']))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to revoke permission',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get user activity
     */
    public function getActivity(Request $request, User $user): JsonResponse
    {
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
     * Get user login attempts
     */
    public function getLoginAttempts(Request $request, User $user): JsonResponse
    {
        $attempts = $user->loginAttempts()
            ->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $attempts->items(),
            'meta' => [
                'current_page' => $attempts->currentPage(),
                'last_page' => $attempts->lastPage(),
                'per_page' => $attempts->perPage(),
                'total' => $attempts->total(),
            ]
        ]);
    }

    /**
     * Get user sessions
     */
    public function getSessions(User $user): JsonResponse
    {
        $sessions = $user->sessions()
            ->where('active', true)
            ->latest()
            ->get();

        return response()->json([
            'data' => $sessions
        ]);
    }

    /**
     * Get user statistics
     */
    public function getUserStatistics(): JsonResponse
    {
        $stats = $this->userManager->getUserStatistics();
        
        return response()->json([
            'data' => $stats
        ]);
    }

    /**
     * Get registration statistics
     */
    public function getRegistrationStats(Request $request): JsonResponse
    {
        $period = $request->get('period', '30days');
        $stats = $this->userManager->getRegistrationStatistics($period);
        
        return response()->json([
            'data' => $stats
        ]);
    }

    /**
     * Get activity statistics
     */
    public function getActivityStats(Request $request): JsonResponse
    {
        $period = $request->get('period', '30days');
        $stats = $this->userManager->getActivityStatistics($period);
        
        return response()->json([
            'data' => $stats
        ]);
    }

    /**
     * Get user type statistics
     */
    public function getUserTypeStats(): JsonResponse
    {
        $stats = $this->userManager->getUserTypeStatistics();
        
        return response()->json([
            'data' => $stats
        ]);
    }

    /**
     * Bulk activate users
     */
    public function bulkActivate(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            $count = $this->userManager->bulkActivateUsers($request->user_ids);
            
            return response()->json([
                'message' => "Successfully activated {$count} users"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to activate users',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Bulk deactivate users
     */
    public function bulkDeactivate(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            $count = $this->userManager->bulkDeactivateUsers($request->user_ids);
            
            return response()->json([
                'message' => "Successfully deactivated {$count} users"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to deactivate users',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Bulk delete users
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            $count = $this->userManager->bulkDeleteUsers($request->user_ids);
            
            return response()->json([
                'message' => "Successfully deleted {$count} users"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete users',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Bulk assign role
     */
    public function bulkAssignRole(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'role' => 'required|string|exists:roles,name'
        ]);

        try {
            $count = $this->userManager->bulkAssignRole($request->user_ids, $request->role);
            
            return response()->json([
                'message' => "Successfully assigned role to {$count} users"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to assign role',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Export users
     */
    public function exportUsers(Request $request): JsonResponse
    {
        $request->validate([
            'format' => 'in:csv,excel,json',
            'filters' => 'array'
        ]);

        try {
            $export = $this->userManager->exportUsers(
                $request->get('format', 'csv'),
                $request->get('filters', [])
            );
            
            return response()->json([
                'message' => 'Export generated successfully',
                'download_url' => $export['url'],
                'expires_at' => $export['expires_at']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to export users',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
