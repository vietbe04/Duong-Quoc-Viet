<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register blade directives
        $this->registerBladeDirectives();
    }

    /**
     * Register custom blade directives
     */
    private function registerBladeDirectives()
    {
        // @hasPermission('permission-slug')
        Blade::if('hasPermission', function ($permission) {
            if (!Auth::check()) {
                return false;
            }
            /** @var User $user */
            $user = Auth::user();
            return $user->hasPermission($permission) || $user->isSuperAdmin();
        });

        // @hasRole('role-slug')
        Blade::if('hasRole', function ($role) {
            if (!Auth::check()) {
                return false;
            }
            /** @var User $user */
            $user = Auth::user();
            return $user->roles()->where('slug', $role)->exists();
        });

        // @isAdmin
        Blade::if('isAdmin', function () {
            if (!Auth::check()) {
                return false;
            }
            /** @var User $user */
            $user = Auth::user();
            return $user->isAdmin();
        });

        // @isSuperAdmin
        Blade::if('isSuperAdmin', function () {
            if (!Auth::check()) {
                return false;
            }
            /** @var User $user */
            $user = Auth::user();
            return $user->isSuperAdmin();
        });
    }
}
