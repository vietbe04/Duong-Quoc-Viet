<?php

use App\Models\User;

/**
 * Check if authenticated user has a specific permission
 *
 * @param string $permission
 * @return bool
 */
function hasPermission($permission)
{
    if (!auth()->check()) {
        return false;
    }

    /** @var User $user */
    $user = auth()->user();

    return $user->hasPermission($permission) || $user->isSuperAdmin();
}

/**
 * Check if authenticated user has a specific role
 *
 * @param string $role
 * @return bool
 */
function hasRole($role)
{
    if (!auth()->check()) {
        return false;
    }

    /** @var User $user */
    $user = auth()->user();

    return $user->hasRole($role);
}

/**
 * Check if authenticated user is admin
 *
 * @return bool
 */
function isAdmin()
{
    if (!auth()->check()) {
        return false;
    }

    /** @var User $user */
    $user = auth()->user();

    return $user->isAdmin();
}

/**
 * Check if authenticated user is super admin
 *
 * @return bool
 */
function isSuperAdmin()
{
    if (!auth()->check()) {
        return false;
    }

    /** @var User $user */
    $user = auth()->user();

    return $user->isSuperAdmin();
}