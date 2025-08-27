<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Litepie\Users\Repositories\Contracts\UserRepositoryInterface;
use Litepie\Users\Repositories\Contracts\UserProfileRepositoryInterface;
use Litepie\Users\Contracts\UserManagerContract;
use Litepie\Users\Models\User;

class RepositoryIntegrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected UserRepositoryInterface $userRepository;
    protected UserProfileRepositoryInterface $profileRepository;
    protected UserManagerContract $userManager;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->userRepository = app(UserRepositoryInterface::class);
        $this->profileRepository = app(UserProfileRepositoryInterface::class);
        $this->userManager = app(UserManagerContract::class);
    }

    public function test_user_repository_can_create_user()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'user_type' => 'user',
            'status' => 'active',
        ];

        $user = $this->userRepository->create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userData['name'], $user->name);
        $this->assertEquals($userData['email'], $user->email);
        $this->assertEquals($userData['user_type'], $user->user_type);
        $this->assertEquals($userData['status'], $user->status);
    }

    public function test_user_repository_can_find_by_email()
    {
        $user = User::factory()->create();

        $foundUser = $this->userRepository->findByEmail($user->email);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
        $this->assertEquals($user->email, $foundUser->email);
    }

    public function test_user_repository_can_search_users()
    {
        $users = User::factory()->count(3)->create();
        $searchTerm = substr($users->first()->name, 0, 3);

        $results = $this->userRepository->searchUsers($searchTerm);

        $this->assertNotEmpty($results);
        $this->assertTrue($results->contains('id', $users->first()->id));
    }

    public function test_user_repository_can_get_statistics()
    {
        User::factory()->count(5)->create(['status' => 'active']);
        User::factory()->count(3)->create(['status' => 'inactive']);
        User::factory()->count(2)->create(['status' => 'pending']);

        $stats = $this->userRepository->getUserStatistics();

        $this->assertIsArray($stats);
        $this->assertEquals(10, $stats['total']);
        $this->assertEquals(5, $stats['active']);
        $this->assertEquals(3, $stats['inactive']);
        $this->assertEquals(2, $stats['pending']);
    }

    public function test_user_manager_can_create_user_with_profile()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'profile' => [
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'bio' => $this->faker->sentence,
            ]
        ];

        $user = $this->userManager->create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotNull($user->profile);
        $this->assertEquals($userData['profile']['first_name'], $user->profile->first_name);
        $this->assertEquals($userData['profile']['last_name'], $user->profile->last_name);
    }

    public function test_repository_performance_features()
    {
        // Test caching functionality
        $user = User::factory()->create();
        
        // First call should hit database
        $result1 = $this->userRepository->find($user->id);
        
        // Second call should use cache (if caching is enabled)
        $result2 = $this->userRepository->find($user->id);
        
        $this->assertEquals($result1->id, $result2->id);
    }

    public function test_repository_filtering()
    {
        User::factory()->count(3)->create(['status' => 'active', 'user_type' => 'admin']);
        User::factory()->count(2)->create(['status' => 'inactive', 'user_type' => 'user']);

        $activeUsers = $this->userRepository->getFilteredUsers([
            'status' => 'active'
        ]);

        $adminUsers = $this->userRepository->getFilteredUsers([
            'user_type' => 'admin'
        ]);

        $this->assertCount(3, $activeUsers);
        $this->assertCount(3, $adminUsers);
    }

    public function test_repository_pagination()
    {
        User::factory()->count(25)->create();

        $paginatedUsers = $this->userRepository->getPaginatedUsers([], 10);

        $this->assertEquals(10, $paginatedUsers->perPage());
        $this->assertEquals(25, $paginatedUsers->total());
        $this->assertEquals(3, $paginatedUsers->lastPage());
    }
}
