<?php

namespace Litepie\Users\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Litepie\Users\Models\User create(array $data, string $userType = null)
 * @method static \Litepie\Users\Models\User update(\Litepie\Users\Models\User $user, array $data)
 * @method static bool delete(\Litepie\Users\Models\User $user)
 * @method static \Litepie\Users\Models\User|null find(int $id)
 * @method static \Litepie\Users\Models\User|null findByEmail(string $email)
 * @method static \Illuminate\Database\Eloquent\Collection getUsersByType(string $userType)
 * @method static array getUserTypes()
 * @method static bool activate(\Litepie\Users\Models\User $user)
 * @method static bool deactivate(\Litepie\Users\Models\User $user)
 * @method static bool assignRole(\Litepie\Users\Models\User $user, string $role)
 * @method static bool removeRole(\Litepie\Users\Models\User $user, string $role)
 * @method static bool changePassword(\Litepie\Users\Models\User $user, string $password)
 * @method static bool sendPasswordReset(\Litepie\Users\Models\User $user)
 * @method static array getStatistics()
 *
 * @see \Litepie\Users\Services\UserManager
 */
class Users extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'users';
    }
}
