<?php

/**
 * Example usage of the Litepie Repository integration with Users package
 * 
 * This file demonstrates how to use the repository pattern that has been
 * integrated into the Users package using the Litepie Repository package.
 */

// Example 1: Basic user repository usage
use Litepie\Users\Repositories\Contracts\UserRepositoryInterface;
use Litepie\Users\Repositories\Contracts\UserProfileRepositoryInterface;
use Litepie\Users\Contracts\UserManagerContract;

// Injecting repositories via dependency injection
class UserController
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserProfileRepositoryInterface $profileRepository,
        private UserManagerContract $userManager
    ) {}

    // Example 2: Finding users with repository methods
    public function index()
    {
        // Get paginated users with filters
        $users = $this->userRepository->getPaginatedUsers([
            'status' => 'active',
            'user_type' => 'customer'
        ], 15);

        // Search users by name or email
        $searchResults = $this->userRepository->searchUsers('john', ['name', 'email']);

        // Get user statistics
        $stats = $this->userRepository->getUserStatistics();

        return view('users.index', compact('users', 'searchResults', 'stats'));
    }

    // Example 3: Creating users with the UserManager service
    public function store(array $data)
    {
        // Create user with profile data
        $user = $this->userManager->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'user_type' => 'customer',
            'profile' => [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'bio' => $data['bio'] ?? null,
            ]
        ]);

        return response()->json($user->load('profile'));
    }

    // Example 4: Using advanced repository features
    public function analytics()
    {
        // Get user registration trends
        $registrationTrends = $this->userRepository->getUserRegistrationTrends('30days');

        // Get users for analytics with specific metrics
        $analyticsData = $this->userRepository->getUsersForAnalytics([
            'user_types'
        ], '30days');

        // Find inactive users
        $inactiveUsers = $this->userRepository->findInactiveUsers(30);

        // Get filtered users with sorting
        $topUsers = $this->userRepository->getFilteredUsers(
            ['status' => 'active'],
            ['created_at' => 'desc'],
            10
        );

        return response()->json([
            'registration_trends' => $registrationTrends,
            'analytics' => $analyticsData,
            'inactive_count' => $inactiveUsers->count(),
            'top_users' => $topUsers,
        ]);
    }

    // Example 5: Bulk operations
    public function bulkOperations()
    {
        // Bulk update user status
        $userIds = [1, 2, 3, 4, 5];
        $updatedCount = $this->userRepository->bulkUpdateStatus($userIds, 'inactive');

        // Archive inactive users
        $archivedCount = $this->userRepository->archiveInactiveUsers(180);

        // Export users with filters
        $exportData = $this->userRepository->getUsersForExport([
            'status' => 'active',
            'created_at' => ['>=', now()->subDays(30)]
        ]);

        return response()->json([
            'updated' => $updatedCount,
            'archived' => $archivedCount,
            'exported' => $exportData->count(),
        ]);
    }
}

// Example 6: Profile management
class ProfileController
{
    public function __construct(
        private UserProfileRepositoryInterface $profileRepository
    ) {}

    public function updateProfile(int $userId, array $data)
    {
        // Create or update profile
        $profile = $this->profileRepository->createOrUpdateForUser($userId, $data);

        return response()->json($profile);
    }

    public function searchByLocation(string $location)
    {
        // Find profiles by location
        $profiles = $this->profileRepository->findByLocation($location);

        return response()->json($profiles->load('user'));
    }

    public function getStatistics()
    {
        // Get profile completion statistics
        $stats = $this->profileRepository->getProfileStatistics();

        return response()->json($stats);
    }
}

// Example 7: Service layer usage
class UserService
{
    public function __construct(
        private UserManagerContract $userManager
    ) {}

    public function registerUser(array $data): User
    {
        // Use the service layer for complex business logic
        return $this->userManager->create($data, 'customer');
    }

    public function activateUser(User $user): bool
    {
        return $this->userManager->activate($user);
    }

    public function getUsersForDashboard(): array
    {
        return [
            'statistics' => $this->userManager->getStatistics(),
            'recent_users' => $this->userManager->getRecentUsers(7),
            'pending_verification' => $this->userManager->getUsersWithPendingVerification(),
            'active_users' => $this->userManager->getActiveUsers(),
        ];
    }
}

/*
 * Key Benefits of the Repository Integration:
 * 
 * 1. Performance Optimization:
 *    - Built-in caching with the PerformanceRepository
 *    - Optimized pagination for large datasets
 *    - Query optimization and smart loading
 * 
 * 2. Advanced Filtering:
 *    - Request-based filtering and sorting
 *    - Complex query building with filters
 *    - Search capabilities across multiple columns
 * 
 * 3. Analytics and Reporting:
 *    - User statistics and trends
 *    - Growth analytics and metrics
 *    - Activity tracking and insights
 * 
 * 4. Bulk Operations:
 *    - Efficient bulk updates and operations
 *    - Mass data processing
 *    - Batch exports and imports
 * 
 * 5. Clean Architecture:
 *    - Separation of concerns with repositories
 *    - Service layer for business logic
 *    - Dependency injection for testability
 * 
 * 6. Scalability:
 *    - Memory-efficient operations
 *    - Chunked processing for large datasets
 *    - Cursor-based pagination
 */
