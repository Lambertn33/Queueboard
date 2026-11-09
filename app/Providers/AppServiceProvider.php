<?php

namespace App\Providers;

use App\Enums\RoleName;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('createTeam', fn (User $user): bool => $user->hasRole(RoleName::Admin));

        Gate::define('updateTeam', fn (User $user): bool => $user->hasRole(RoleName::Admin));

        Gate::define('createProject', fn (User $user): bool => $user->hasRole(RoleName::Admin));

        Gate::define('createUser', fn (User $user): bool => $user->hasRole(RoleName::Admin));

        Gate::define('assignRole', fn (User $user): bool => $user->hasRole(RoleName::Admin));
    }
}
