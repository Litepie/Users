<?php

namespace Litepie\Users\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Litepie\Users\Contracts\UserManagerContract;
use Litepie\Users\Services\UserManager;
use Litepie\Users\Models\User;
use Litepie\Users\Policies\UserPolicy;
use Litepie\Users\Commands\InstallUsersCommand;
use Litepie\Users\Commands\CreateUserCommand;
use Litepie\Users\Commands\ImportUsersCommand;
use Litepie\Users\Commands\CleanupInactiveUsersCommand;

class UsersServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/users.php',
            'users'
        );

        $this->app->bind(UserManagerContract::class, UserManager::class);

        $this->app->singleton('users', function ($app) {
            return new UserManager($app);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'users');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'users');

        $this->publishes([
            __DIR__.'/../../config/users.php' => config_path('users.php'),
        ], 'users-config');

        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'users-migrations');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/users'),
        ], 'users-views');

        $this->publishes([
            __DIR__.'/../../resources/lang' => resource_path('lang/vendor/users'),
        ], 'users-lang');

        $this->publishes([
            __DIR__.'/../../resources/assets' => public_path('vendor/users'),
        ], 'users-assets');

        $this->registerRoutes();
        $this->registerPolicies();
        $this->registerCommands();
        $this->registerWorkflows();
        $this->registerEventListeners();
    }

    /**
     * Register routes.
     */
    protected function registerRoutes(): void
    {
        if (! $this->app->routesAreCached()) {
            Route::group([
                'middleware' => ['web'],
                'prefix' => 'users',
                'namespace' => 'Litepie\Users\Controllers',
            ], function () {
                $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
            });

            Route::group([
                'middleware' => ['api'],
                'prefix' => 'api/users',
                'namespace' => 'Litepie\Users\Controllers\Api',
            ], function () {
                $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
            });

            Route::group([
                'middleware' => ['web', 'auth', 'role:admin'],
                'prefix' => 'admin/users',
                'namespace' => 'Litepie\Users\Controllers\Admin',
            ], function () {
                $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');
            });
        }
    }

    /**
     * Register policies.
     */
    protected function registerPolicies(): void
    {
        Gate::policy(User::class, UserPolicy::class);
    }

    /**
     * Register artisan commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallUsersCommand::class,
                CreateUserCommand::class,
                ImportUsersCommand::class,
                CleanupInactiveUsersCommand::class,
            ]);
        }
    }

    /**
     * Register workflows.
     */
    protected function registerWorkflows(): void
    {
        if (config('users.workflows.enabled', true)) {
            $this->app->booted(function () {
                if (class_exists(\Litepie\Flow\Facades\Flow::class)) {
                    \Litepie\Flow\Facades\Flow::register(
                        'user_registration',
                        \Litepie\Users\Workflows\UserRegistrationWorkflow::create()
                    );

                    \Litepie\Flow\Facades\Flow::register(
                        'client_registration',
                        \Litepie\Users\Workflows\ClientRegistrationWorkflow::create()
                    );

                    \Litepie\Flow\Facades\Flow::register(
                        'admin_registration',
                        \Litepie\Users\Workflows\AdminRegistrationWorkflow::create()
                    );

                    \Litepie\Flow\Facades\Flow::register(
                        'password_reset',
                        \Litepie\Users\Workflows\PasswordResetWorkflow::create()
                    );
                }
            });
        }
    }

    /**
     * Register event listeners.
     */
    protected function registerEventListeners(): void
    {
        $this->app['events']->listen(
            \Litepie\Users\Events\UserRegistered::class,
            \Litepie\Users\Listeners\SendWelcomeEmail::class
        );

        $this->app['events']->listen(
            \Litepie\Users\Events\UserActivated::class,
            \Litepie\Users\Listeners\NotifyUserActivation::class
        );

        $this->app['events']->listen(
            \Litepie\Users\Events\UserRoleChanged::class,
            \Litepie\Users\Listeners\LogRoleChange::class
        );
    }
}
