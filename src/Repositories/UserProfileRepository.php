<?php

namespace Litepie\Users\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Litepie\Repository\BaseRepository;
use Litepie\Users\Models\UserProfile;
use Litepie\Users\Repositories\Contracts\UserProfileRepositoryInterface;

class UserProfileRepository extends BaseRepository implements UserProfileRepositoryInterface
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return UserProfile::class;
    }

    /**
     * Find profile by user ID.
     */
    public function findByUserId(int $userId): ?UserProfile
    {
        return $this->where('user_id', $userId)->first();
    }

    /**
     * Create or update profile for user.
     */
    public function createOrUpdateForUser(int $userId, array $data): UserProfile
    {
        $profile = $this->findByUserId($userId);

        if ($profile) {
            $profile->update($data);
            return $profile;
        }

        $data['user_id'] = $userId;
        return $this->create($data);
    }

    /**
     * Search profiles by name.
     */
    public function searchByName(string $term): Collection
    {
        return $this->makeModel()->newQuery()->where(function ($query) use ($term) {
            $query->where('first_name', 'LIKE', "%{$term}%")
                  ->orWhere('last_name', 'LIKE', "%{$term}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$term}%"]);
        })->get();
    }

    /**
     * Find profiles by location.
     */
    public function findByLocation(string $location): Collection
    {
        return $this->makeModel()->newQuery()->where(function ($query) use ($location) {
            $query->where('city', 'LIKE', "%{$location}%")
                  ->orWhere('state', 'LIKE', "%{$location}%")
                  ->orWhere('country', 'LIKE', "%{$location}%")
                  ->orWhere('address_line_1', 'LIKE', "%{$location}%")
                  ->orWhere('address_line_2', 'LIKE', "%{$location}%");
        })->get();
    }

    /**
     * Get profiles with users.
     */
    public function getProfilesWithUsers(): Collection
    {
        return $this->with('user')->get();
    }

    /**
     * Update profile avatar.
     */
    public function updateAvatar(int $profileId, string $avatarPath): bool
    {
        $profile = $this->find($profileId);
        if ($profile) {
            return $profile->update(['avatar' => $avatarPath]);
        }
        return false;
    }

    /**
     * Remove profile avatar.
     */
    public function removeAvatar(int $profileId): bool
    {
        $profile = $this->find($profileId);
        if ($profile) {
            return $profile->update(['avatar' => null]);
        }
        return false;
    }

    /**
     * Get incomplete profiles.
     */
    public function getIncompleteProfiles(): Collection
    {
        return $this->makeModel()->newQuery()->where(function ($query) {
            $query->whereNull('first_name')
                  ->orWhereNull('last_name')
                  ->orWhereNull('phone')
                  ->orWhereNull('date_of_birth')
                  ->orWhere('first_name', '')
                  ->orWhere('last_name', '')
                  ->orWhere('phone', '');
        })->get();
    }

    /**
     * Bulk update profiles.
     */
    public function bulkUpdateProfiles(array $updates): int
    {
        $count = 0;
        
        foreach ($updates as $profileId => $data) {
            $profile = $this->find($profileId);
            if ($profile && $profile->update($data)) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Get profiles for export.
     */
    public function getProfilesForExport(array $filters = []): Collection
    {
        $query = $this->makeModel()->newQuery()->with('user');

        foreach ($filters as $field => $value) {
            if (is_array($value) && count($value) === 2) {
                $query = $query->where($field, $value[0], $value[1]);
            } elseif (is_array($value)) {
                $query = $query->whereIn($field, $value);
            } else {
                $query = $query->where($field, $value);
            }
        }

        return $query->get();
    }

    /**
     * Find profiles by company.
     */
    public function findByCompany(string $company): Collection
    {
        return $this->where('company', 'LIKE', "%{$company}%")->get();
    }

    /**
     * Get profile statistics.
     */
    public function getProfileStatistics(): array
    {
        $total = $this->count();
        $complete = $this->whereNotNull('first_name')
            ->whereNotNull('last_name')
            ->whereNotNull('phone')
            ->whereNotNull('date_of_birth')
            ->where('first_name', '!=', '')
            ->where('last_name', '!=', '')
            ->where('phone', '!=', '')
            ->count();

        $withAvatar = $this->whereNotNull('avatar')
            ->where('avatar', '!=', '')
            ->count();

        $withBio = $this->whereNotNull('bio')
            ->where('bio', '!=', '')
            ->count();

        $withAddress = $this->whereNotNull('address_line_1')
            ->where('address_line_1', '!=', '')
            ->count();

        return [
            'total' => $total,
            'complete' => $complete,
            'incomplete' => $total - $complete,
            'completion_rate' => $total > 0 ? round(($complete / $total) * 100, 2) : 0,
            'with_avatar' => $withAvatar,
            'with_bio' => $withBio,
            'with_address' => $withAddress,
            'by_gender' => $this->getGenderDistribution(),
            'age_ranges' => $this->getAgeRangeDistribution(),
        ];
    }

    /**
     * Search profiles with filters.
     */
    public function searchWithFilters(array $filters): Collection
    {
        $query = $this->makeModel()->newQuery();

        if (!empty($filters['name'])) {
            $name = $filters['name'];
            $query = $query->where(function ($q) use ($name) {
                $q->where('first_name', 'LIKE', "%{$name}%")
                  ->orWhere('last_name', 'LIKE', "%{$name}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$name}%"]);
            });
        }

        if (!empty($filters['location'])) {
            $location = $filters['location'];
            $query = $query->where(function ($q) use ($location) {
                $q->where('city', 'LIKE', "%{$location}%")
                  ->orWhere('state', 'LIKE', "%{$location}%")
                  ->orWhere('country', 'LIKE', "%{$location}%");
            });
        }

        if (!empty($filters['company'])) {
            $query = $query->where('company', 'LIKE', "%{$filters['company']}%");
        }

        if (!empty($filters['gender'])) {
            $query = $query->where('gender', $filters['gender']);
        }

        if (!empty($filters['age_min']) && !empty($filters['age_max'])) {
            $maxDate = now()->subYears($filters['age_min'])->format('Y-m-d');
            $minDate = now()->subYears($filters['age_max'] + 1)->format('Y-m-d');
            $query = $query->whereBetween('date_of_birth', [$minDate, $maxDate]);
        }

        if (!empty($filters['has_avatar'])) {
            if ($filters['has_avatar'] === 'yes') {
                $query = $query->whereNotNull('avatar')->where('avatar', '!=', '');
            } else {
                $query = $query->where(function ($q) {
                    $q->whereNull('avatar')->orWhere('avatar', '');
                });
            }
        }

        return $query->get();
    }

    /**
     * Get gender distribution.
     */
    private function getGenderDistribution(): array
    {
        return $this->makeModel()->newQuery()
            ->selectRaw('gender, COUNT(*) as count')
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->get()
            ->pluck('count', 'gender')
            ->toArray();
    }

    /**
     * Get age range distribution.
     */
    private function getAgeRangeDistribution(): array
    {
        $ranges = [
            '18-25' => [18, 25],
            '26-35' => [26, 35],
            '36-45' => [36, 45],
            '46-55' => [46, 55],
            '56-65' => [56, 65],
            '65+' => [65, 120],
        ];

        $distribution = [];

        foreach ($ranges as $range => [$min, $max]) {
            $maxDate = now()->subYears($min)->format('Y-m-d');
            $minDate = now()->subYears($max + 1)->format('Y-m-d');
            
            $count = $this->makeModel()->newQuery()
                ->whereNotNull('date_of_birth')
                ->whereBetween('date_of_birth', [$minDate, $maxDate])
                ->count();

            $distribution[$range] = $count;
        }

        return $distribution;
    }
}