<?php

namespace Litepie\Users\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Litepie\Repository\Contracts\RepositoryInterface;
use Litepie\Users\Models\UserProfile;

interface UserProfileRepositoryInterface extends RepositoryInterface
{
    /**
     * Find profile by user ID.
     */
    public function findByUserId(int $userId): ?UserProfile;

    /**
     * Create or update profile for user.
     */
    public function createOrUpdateForUser(int $userId, array $data): UserProfile;

    /**
     * Search profiles by name.
     */
    public function searchByName(string $term): Collection;

    /**
     * Find profiles by location.
     */
    public function findByLocation(string $location): Collection;

    /**
     * Get profiles with users.
     */
    public function getProfilesWithUsers(): Collection;

    /**
     * Update profile avatar.
     */
    public function updateAvatar(int $profileId, string $avatarPath): bool;

    /**
     * Remove profile avatar.
     */
    public function removeAvatar(int $profileId): bool;

    /**
     * Get incomplete profiles.
     */
    public function getIncompleteProfiles(): Collection;

    /**
     * Bulk update profiles.
     */
    public function bulkUpdateProfiles(array $updates): int;

    /**
     * Get profiles for export.
     */
    public function getProfilesForExport(array $filters = []): Collection;

    /**
     * Find profiles by company.
     */
    public function findByCompany(string $company): Collection;

    /**
     * Get profile statistics.
     */
    public function getProfileStatistics(): array;

    /**
     * Search profiles with filters.
     */
    public function searchWithFilters(array $filters): Collection;
}
