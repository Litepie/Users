<?php

namespace Litepie\Users\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Litepie\Users\Models\User;
use Litepie\Users\Services\UserManager;
use Litepie\Users\Contracts\UserManagerContract;
use Litepie\Users\Contracts\UserTypeContract;
use Litepie\Users\UserTypes\BaseUserType;
use Litepie\Users\UserTypes\AdminUserType;
use Litepie\Users\UserTypes\ClientUserType;
use Litepie\Users\UserTypes\SystemUserType;
use Litepie\Users\UserTypes\RegularUserType;
use Litepie\Users\UserTypes\OrganizationUserType;

class UsersServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/users.php',
            'users'
        );

        // Register contracts
        $this->app->bind(UserManagerContract::class, UserManager::class);
        
        // Register facade
        $this->app->bind('users', function ($app) {
            return $app->make(UserManagerContract::class);
        });
        
        // Register user types
        $this->registerUserTypes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        // Publish configuration
        $this->publishes([
            __DIR__ . '/../../config/users.php' => config_path('users.php'),
        ], 'users-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ], 'users-migrations');

        // Publish views
        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/users'),
        ], 'users-views');

        // Register policies after the application is fully booted
        $this->app->booted(function () {
            $this->registerPolicies();
        });

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }
    }

    /**
     * Register user types.
     */
    protected function registerUserTypes(): void
    {
        $userTypes = config('users.user_types', []);

        foreach ($userTypes as $type => $config) {
            if (isset($config['class'])) {
                $this->app->bind("users.type.{$type}", $config['class']);
            }
        }

        // Register default user types
        $this->app->bind('users.type.admin', AdminUserType::class);
        $this->app->bind('users.type.client', ClientUserType::class);
        $this->app->bind('users.type.system', SystemUserType::class);
        $this->app->bind('users.type.user', RegularUserType::class);
        $this->app->bind('users.type.organization', OrganizationUserType::class);
    }

    /**
     * Register policies.
     */
    protected function registerPolicies(): void
    {
        // Check if Shield package is properly installed and registered
        if (!class_exists('\Litepie\Shield\PermissionRegistrar')) {
            // Log that Shield is not available
            if (app()->bound('log')) {
                app('log')->info('Users package: Shield package not installed, skipping permission registration');
            }
            return;
        }

        // Try to check if Shield services are available
        try {
            // Test if we can access Shield's PermissionRegistrar
            if (!app()->bound(\Litepie\Shield\PermissionRegistrar::class)) {
                if (app()->bound('log')) {
                    app('log')->warning('Users package: Shield PermissionRegistrar not bound in container');
                }
                return;
            }

            // Test if we can actually resolve the service
            $permissionRegistrar = app(\Litepie\Shield\PermissionRegistrar::class);

            // Register user management policies
            Gate::define('manage-users', function ($user) {
                return $user->hasRole('admin') || $user->hasRole('manager');
            });

            Gate::define('view-user', function ($user, $targetUser) {
                if ($user->hasRole('admin')) {
                    return true;
                }
                
                if ($user->hasRole('manager') && $user->organization_id === $targetUser->organization_id) {
                    return true;
                }
                
                return $user->id === $targetUser->id;
            });

            Gate::define('edit-user', function ($user, $targetUser) {
                if ($user->hasRole('admin')) {
                    return true;
                }
                
                if ($user->hasRole('manager') && $user->organization_id === $targetUser->organization_id) {
                    return true;
                }
                
                return $user->id === $targetUser->id;
            });

            Gate::define('delete-user', function ($user, $targetUser) {
                return $user->hasRole('admin') && $user->id !== $targetUser->id;
            });

            // Organization-specific policies
            Gate::define('manage-organization-users', function ($user, $organizationId) {
                return $user->belongsToOrganization($organizationId) && $user->isOrganizationAdmin();
            });

            Gate::define('view-organization-users', function ($user, $organizationId) {
                return $user->belongsToOrganization($organizationId);
            });

            Gate::define('manage-user-hierarchy', function ($user, $organizationId) {
                return $user->belongsToOrganization($organizationId) && 
                       ($user->isOrganizationAdmin() || $user->isManager());
            });

            // Log successful registration
            if (app()->bound('log')) {
                app('log')->info('Users package: Shield integration and policies registered successfully');
            }

        } catch (\Exception $e) {
            // Log the error but don't break the application
            if (app()->bound('log')) {
                app('log')->warning(
                    'Users package: Failed to register policies with Shield - ' . $e->getMessage()
                );
            }
        }
    }

    /**
     * Register artisan commands.
     */
    protected function registerCommands(): void
    {
        $this->commands([
            // Add console commands here when created
            // \Litepie\Users\Console\Commands\CreateUserCommand::class,
            // \Litepie\Users\Console\Commands\ImportUsersCommand::class,
        ]);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            UserManagerContract::class,
            'users',
            'users.type.admin',
            'users.type.client',
            'users.type.system', 
            'users.type.user',
            'users.type.organization',
        ];
    }
}
