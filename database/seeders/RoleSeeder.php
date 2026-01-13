<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $superAdmin = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            ['name' => 'Super Admin', 'description' => 'Quản trị viên cao cấp với toàn quyền']
        );

        $admin = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin', 'description' => 'Quản trị viên']
        );

        $user = Role::firstOrCreate(
            ['slug' => 'user'],
            ['name' => 'User', 'description' => 'Người dùng thông thường']
        );

        // Assign all permissions to super-admin
        $allPermissions = Permission::all();
        $superAdmin->permissions()->sync($allPermissions->pluck('id'));

        // Assign specific permissions to admin
        $adminPermissions = Permission::whereIn('slug', [
            'posts.view', 'posts.create', 'posts.edit', 'posts.delete',
            'products.view', 'products.create', 'products.edit', 'products.delete',
            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
            'orders.view', 'orders.edit',
            'users.view',
            'dashboard.view',
        ])->pluck('id');
        $admin->permissions()->sync($adminPermissions);

        // User role doesn't need admin permissions
    }
}
