<?php

namespace Litepie\Users\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Litepie\Users\Models\User;
use Litepie\Users\Http\Resources\UserResource;
use Litepie\Users\Http\Controllers\Controller;

class OrganizationUserController extends Controller
{
    /**
     * Display organization users.
     */
    public function index(Request $request, int $organizationId): JsonResponse
    {
        $this->authorize('viewOrganizationUsers', [User::class, $organizationId]);
        
        $query = User::inOrganization($organizationId)
            ->with(['profile', 'roles', 'reportsTo', 'primaryManager']);

        // Apply filters
        if ($request->filled('position')) {
            $query->byPosition($request->position);
        }

        if ($request->filled('work_location')) {
            $query->byWorkLocation($request->work_location);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_admin')) {
            if ($request->boolean('is_admin')) {
                $query->organizationAdmins();
            }
        }

        if ($request->filled('is_manager')) {
            if ($request->boolean('is_manager')) {
                $query->managers();
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($profileQuery) use ($search) {
                      $profileQuery->where('first_name', 'like', "%{$search}%")
                                  ->orWhere('last_name', 'like', "%{$search}%")
                                  ->orWhere('employee_id', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        
        $validSortFields = [
            'name', 'email', 'organization_position', 'organization_joined_at',
            'work_location', 'status', 'created_at'
        ];

        if (in_array($sortBy, $validSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $users = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => UserResource::collection($users->items()),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    /**
     * Store a new organization user.
     */
    public function store(Request $request, int $organizationId): JsonResponse
    {
        $this->authorize('createOrganizationUser', [User::class, $organizationId]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'organization_position' => 'nullable|string|max:255',
            'is_organization_admin' => 'boolean',
            'reports_to_user_id' => 'nullable|exists:users,id',
            'primary_manager_id' => 'nullable|exists:users,id',
            'secondary_manager_id' => 'nullable|exists:users,id',
            'work_location' => 'nullable|in:remote,office,hybrid',
            'office_location' => 'nullable|string|max:255',
            'work_schedule' => 'nullable|array',
            // Profile fields
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'job_title' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:50|unique:user_profiles,employee_id',
            'department' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'team' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'employment_type' => 'nullable|in:full-time,part-time,contract,freelance',
            'salary' => 'nullable|numeric|min:0',
            'salary_currency' => 'nullable|string|size:3',
        ]);

        // Validate reporting structure
        $this->validateReportingStructure($validated, $organizationId);

        // Create user
        $userData = array_merge($validated, [
            'user_type' => 'organization',
            'organization_id' => $organizationId,
            'password' => bcrypt(\Str::random(16)), // Temporary password
            'status' => 'pending', // Will be activated after email verification
            'organization_joined_at' => now(),
        ]);

        $user = User::create($userData);

        // Create profile if profile data provided
        $profileData = array_intersect_key($validated, array_flip([
            'first_name', 'last_name', 'phone', 'job_title', 'employee_id',
            'department', 'division', 'team', 'hire_date', 'employment_type',
            'salary', 'salary_currency'
        ]));

        if (!empty($profileData)) {
            $profileData['organization_id'] = $organizationId;
            $profileData['employment_status'] = 'active';
            $user->profile()->create($profileData);
        }

        // Send invitation email
        $user->sendEmailVerificationNotification();

        // Log activity
        activity('organization-user-management')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties([
                'organization_id' => $organizationId,
                'action' => 'created',
                'user_data' => $userData
            ])
            ->log('Organization user created');

        return response()->json([
            'message' => 'User created successfully',
            'data' => new UserResource($user->load(['profile', 'organization']))
        ], 201);
    }

    /**
     * Display the specified organization user.
     */
    public function show(int $organizationId, User $user): JsonResponse
    {
        $this->authorize('viewOrganizationUser', [$user, $organizationId]);

        if ($user->organization_id !== $organizationId) {
            abort(404, 'User not found in this organization');
        }

        $user->load([
            'profile',
            'organization',
            'reportsTo',
            'directReports',
            'primaryManager',
            'secondaryManager',
            'primaryManagedUsers',
            'secondaryManagedUsers',
            'roles'
        ]);

        return response()->json([
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Update the specified organization user.
     */
    public function update(Request $request, int $organizationId, User $user): JsonResponse
    {
        $this->authorize('updateOrganizationUser', [$user, $organizationId]);

        if ($user->organization_id !== $organizationId) {
            abort(404, 'User not found in this organization');
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'organization_position' => 'nullable|string|max:255',
            'is_organization_admin' => 'boolean',
            'reports_to_user_id' => 'nullable|exists:users,id',
            'primary_manager_id' => 'nullable|exists:users,id',
            'secondary_manager_id' => 'nullable|exists:users,id',
            'work_location' => 'nullable|in:remote,office,hybrid',
            'office_location' => 'nullable|string|max:255',
            'work_schedule' => 'nullable|array',
            'status' => 'sometimes|in:active,inactive,suspended',
        ]);

        // Validate reporting structure
        $this->validateReportingStructure($validated, $organizationId, $user->id);

        $oldData = $user->toArray();
        $user->update($validated);

        // Update roles if admin status changed
        if (isset($validated['is_organization_admin'])) {
            $this->updateOrganizationRoles($user);
        }

        // Log activity
        activity('organization-user-management')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties([
                'organization_id' => $organizationId,
                'action' => 'updated',
                'old_data' => $oldData,
                'new_data' => $validated
            ])
            ->log('Organization user updated');

        return response()->json([
            'message' => 'User updated successfully',
            'data' => new UserResource($user->fresh()->load(['profile', 'organization']))
        ]);
    }

    /**
     * Update organization user profile.
     */
    public function updateProfile(Request $request, int $organizationId, User $user): JsonResponse
    {
        $this->authorize('updateOrganizationUser', [$user, $organizationId]);

        if ($user->organization_id !== $organizationId) {
            abort(404, 'User not found in this organization');
        }

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'job_title' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:50|unique:user_profiles,employee_id,' . $user->profile?->id,
            'department' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'team' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'termination_date' => 'nullable|date|after:hire_date',
            'employment_status' => 'nullable|in:active,inactive,terminated,suspended',
            'employment_type' => 'nullable|in:full-time,part-time,contract,freelance',
            'salary' => 'nullable|numeric|min:0',
            'salary_currency' => 'nullable|string|size:3',
            'bio' => 'nullable|string|max:1000',
        ]);

        // Create profile if it doesn't exist
        if (!$user->profile) {
            $user->profile()->create($validated);
        } else {
            $user->profile->update($validated);
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user->fresh()->load(['profile', 'organization']))
        ]);
    }

    /**
     * Remove user from organization.
     */
    public function destroy(int $organizationId, User $user): JsonResponse
    {
        $this->authorize('deleteOrganizationUser', [$user, $organizationId]);

        if ($user->organization_id !== $organizationId) {
            abort(404, 'User not found in this organization');
        }

        // Prevent removing organization owner
        if ($user->is_organization_owner) {
            return response()->json([
                'message' => 'Cannot remove organization owner'
            ], 422);
        }

        // Update reporting structure before removing user
        $this->updateReportingStructureForDeparture($user);

        // Remove user from organization (soft approach)
        $user->leaveOrganization();

        // Or hard delete if preferred
        // $user->delete();

        // Log activity
        activity('organization-user-management')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties([
                'organization_id' => $organizationId,
                'action' => 'removed'
            ])
            ->log('User removed from organization');

        return response()->json([
            'message' => 'User removed from organization successfully'
        ]);
    }

    /**
     * Get organization hierarchy.
     */
    public function hierarchy(int $organizationId): JsonResponse
    {
        $this->authorize('viewOrganizationUsers', [User::class, $organizationId]);

        $users = User::inOrganization($organizationId)
            ->with(['profile', 'directReports.profile'])
            ->whereNull('reports_to_user_id') // Top-level users
            ->get();

        $hierarchy = $users->map(function ($user) {
            return $this->buildHierarchyTree($user);
        });

        return response()->json([
            'data' => $hierarchy
        ]);
    }

    /**
     * Transfer user to different manager.
     */
    public function transfer(Request $request, int $organizationId, User $user): JsonResponse
    {
        $this->authorize('updateOrganizationUser', [$user, $organizationId]);

        if ($user->organization_id !== $organizationId) {
            abort(404, 'User not found in this organization');
        }

        $validated = $request->validate([
            'new_manager_id' => 'required|exists:users,id',
            'transfer_reports' => 'boolean',
            'effective_date' => 'nullable|date|after_or_equal:today',
        ]);

        $newManager = User::findOrFail($validated['new_manager_id']);

        // Validate new manager is in same organization
        if ($newManager->organization_id !== $organizationId) {
            return response()->json([
                'message' => 'New manager must be in the same organization'
            ], 422);
        }

        // Prevent circular reporting
        if ($this->wouldCreateCircularReporting($user, $newManager)) {
            return response()->json([
                'message' => 'Transfer would create circular reporting structure'
            ], 422);
        }

        $effectiveDate = $validated['effective_date'] ?? now();

        // Update user's manager
        $user->update([
            'reports_to_user_id' => $newManager->id,
            'primary_manager_id' => $newManager->id,
        ]);

        // Transfer direct reports if requested
        if ($validated['transfer_reports'] ?? false) {
            $user->directReports()->update([
                'reports_to_user_id' => $newManager->id,
                'primary_manager_id' => $newManager->id,
            ]);
        }

        // Log activity
        activity('organization-user-management')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties([
                'organization_id' => $organizationId,
                'action' => 'transferred',
                'new_manager_id' => $newManager->id,
                'transfer_reports' => $validated['transfer_reports'] ?? false,
                'effective_date' => $effectiveDate
            ])
            ->log('User transferred to new manager');

        return response()->json([
            'message' => 'User transferred successfully',
            'data' => new UserResource($user->fresh()->load(['profile', 'reportsTo']))
        ]);
    }

    /**
     * Validate reporting structure to prevent issues.
     */
    protected function validateReportingStructure(array $data, int $organizationId, ?int $excludeUserId = null): void
    {
        if (isset($data['reports_to_user_id']) && $data['reports_to_user_id']) {
            $manager = User::find($data['reports_to_user_id']);
            
            if (!$manager || $manager->organization_id !== $organizationId) {
                throw new \InvalidArgumentException('Manager must be in the same organization');
            }

            if ($excludeUserId && $manager->id === $excludeUserId) {
                throw new \InvalidArgumentException('User cannot report to themselves');
            }
        }

        if (isset($data['primary_manager_id']) && $data['primary_manager_id']) {
            $primaryManager = User::find($data['primary_manager_id']);
            
            if (!$primaryManager || $primaryManager->organization_id !== $organizationId) {
                throw new \InvalidArgumentException('Primary manager must be in the same organization');
            }
        }
    }

    /**
     * Update organization roles based on admin status.
     */
    protected function updateOrganizationRoles(User $user): void
    {
        // Remove existing organization roles
        $organizationRoles = ['organization-admin', 'organization-member'];
        
        foreach ($organizationRoles as $role) {
            if ($user->hasRole($role)) {
                $user->removeRole($role);
            }
        }

        // Assign new role
        if ($user->is_organization_admin) {
            $user->assignRole('organization-admin');
        } else {
            $user->assignRole('organization-member');
        }
    }

    /**
     * Update reporting structure when user leaves organization.
     */
    protected function updateReportingStructureForDeparture(User $user): void
    {
        // Update direct reports
        $directReports = $user->directReports;
        
        foreach ($directReports as $report) {
            $newManagerId = $user->primary_manager_id ?? $user->reports_to_user_id;
            
            $report->update([
                'reports_to_user_id' => $newManagerId,
                'primary_manager_id' => $newManagerId
            ]);
        }

        // Update managed users
        $managedUsers = $user->managedUsers()->get();
        
        foreach ($managedUsers as $managedUser) {
            if ($managedUser->primary_manager_id === $user->id) {
                $managedUser->update(['primary_manager_id' => $user->primary_manager_id]);
            }
            
            if ($managedUser->secondary_manager_id === $user->id) {
                $managedUser->update(['secondary_manager_id' => null]);
            }
        }
    }

    /**
     * Build hierarchy tree structure.
     */
    protected function buildHierarchyTree(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'position' => $user->organization_position,
            'is_admin' => $user->is_organization_admin,
            'profile' => $user->profile ? [
                'first_name' => $user->profile->first_name,
                'last_name' => $user->profile->last_name,
                'job_title' => $user->profile->job_title,
                'department' => $user->profile->department,
                'team' => $user->profile->team,
            ] : null,
            'children' => $user->directReports->map(function ($child) {
                return $this->buildHierarchyTree($child);
            })->toArray()
        ];
    }

    /**
     * Check if transfer would create circular reporting.
     */
    protected function wouldCreateCircularReporting(User $user, User $newManager): bool
    {
        $currentManager = $newManager;
        $maxDepth = 10; // Prevent infinite loops
        $depth = 0;

        while ($currentManager && $depth < $maxDepth) {
            if ($currentManager->reports_to_user_id === $user->id) {
                return true;
            }
            
            $currentManager = $currentManager->reportsTo;
            $depth++;
        }

        return false;
    }
}
