<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
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
            return hasPermission($permission);
        });

        // @hasRole('role-slug')
        Blade::if('hasRole', function ($role) {
            return hasRole($role);
        });

        // @isAdmin
        Blade::if('isAdmin', function () {
            return isAdmin();
        });

        // @isSuperAdmin
        Blade::if('isSuperAdmin', function () {
            return isSuperAdmin();
        });
    }
}
