<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

if (!function_exists('hasPermission')) {
    /**
     * Check if authenticated user has a specific permission
     *
     * @param string $permission
     * @return bool
     */
    function hasPermission($permission)
    {
        if (!Auth::check()) {
            return false;
        }

        /** @var User $user */
        $user = Auth::user();

        return $user->hasPermission($permission) || $user->isSuperAdmin();
    }
}

if (!function_exists('hasRole')) {
    /**
     * Check if authenticated user has a specific role
     *
     * @param string $role
     * @return bool
     */
    function hasRole($role)
    {
        if (!Auth::check()) {
            return false;
        }

        /** @var User $user */
        $user = Auth::user();

        return $user->roles()->where('slug', $role)->exists();
    }
}

if (!function_exists('isAdmin')) {
    /**
     * Check if authenticated user is admin
     *
     * @return bool
     */
    function isAdmin()
    {
        if (!Auth::check()) {
            return false;
        }

        /** @var User $user */
        $user = Auth::user();

        return $user->isAdmin();
    }
}

if (!function_exists('isSuperAdmin')) {
    /**
     * Check if authenticated user is super admin
     *
     * @return bool
     */
    function isSuperAdmin()
    {
        if (!Auth::check()) {
            return false;
        }

        /** @var User $user */
        $user = Auth::user();

        return $user->isSuperAdmin();
    }
}