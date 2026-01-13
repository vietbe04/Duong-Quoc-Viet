<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Posts
            ['name' => 'Xem bài viết', 'slug' => 'posts.view', 'group' => 'posts', 'description' => 'Xem danh sách và chi tiết bài viết'],
            ['name' => 'Tạo bài viết', 'slug' => 'posts.create', 'group' => 'posts', 'description' => 'Tạo bài viết mới'],
            ['name' => 'Sửa bài viết', 'slug' => 'posts.edit', 'group' => 'posts', 'description' => 'Chỉnh sửa bài viết'],
            ['name' => 'Xóa bài viết', 'slug' => 'posts.delete', 'group' => 'posts', 'description' => 'Xóa bài viết'],

            // Products
            ['name' => 'Xem sản phẩm', 'slug' => 'products.view', 'group' => 'products', 'description' => 'Xem danh sách và chi tiết sản phẩm'],
            ['name' => 'Tạo sản phẩm', 'slug' => 'products.create', 'group' => 'products', 'description' => 'Tạo sản phẩm mới'],
            ['name' => 'Sửa sản phẩm', 'slug' => 'products.edit', 'group' => 'products', 'description' => 'Chỉnh sửa sản phẩm'],
            ['name' => 'Xóa sản phẩm', 'slug' => 'products.delete', 'group' => 'products', 'description' => 'Xóa sản phẩm'],

            // Categories
            ['name' => 'Xem danh mục', 'slug' => 'categories.view', 'group' => 'categories', 'description' => 'Xem danh sách danh mục'],
            ['name' => 'Tạo danh mục', 'slug' => 'categories.create', 'group' => 'categories', 'description' => 'Tạo danh mục mới'],
            ['name' => 'Sửa danh mục', 'slug' => 'categories.edit', 'group' => 'categories', 'description' => 'Chỉnh sửa danh mục'],
            ['name' => 'Xóa danh mục', 'slug' => 'categories.delete', 'group' => 'categories', 'description' => 'Xóa danh mục'],

            // Users
            ['name' => 'Xem người dùng', 'slug' => 'users.view', 'group' => 'users', 'description' => 'Xem danh sách người dùng'],
            ['name' => 'Tạo người dùng', 'slug' => 'users.create', 'group' => 'users', 'description' => 'Tạo người dùng mới'],
            ['name' => 'Sửa người dùng', 'slug' => 'users.edit', 'group' => 'users', 'description' => 'Chỉnh sửa người dùng'],
            ['name' => 'Xóa người dùng', 'slug' => 'users.delete', 'group' => 'users', 'description' => 'Xóa người dùng'],

            // Roles
            ['name' => 'Xem vai trò', 'slug' => 'roles.view', 'group' => 'roles', 'description' => 'Xem danh sách vai trò'],
            ['name' => 'Tạo vai trò', 'slug' => 'roles.create', 'group' => 'roles', 'description' => 'Tạo vai trò mới'],
            ['name' => 'Sửa vai trò', 'slug' => 'roles.edit', 'group' => 'roles', 'description' => 'Chỉnh sửa vai trò'],
            ['name' => 'Xóa vai trò', 'slug' => 'roles.delete', 'group' => 'roles', 'description' => 'Xóa vai trò'],

            // Permissions
            ['name' => 'Xem quyền', 'slug' => 'permissions.view', 'group' => 'permissions', 'description' => 'Xem danh sách quyền'],
            ['name' => 'Tạo quyền', 'slug' => 'permissions.create', 'group' => 'permissions', 'description' => 'Tạo quyền mới'],
            ['name' => 'Sửa quyền', 'slug' => 'permissions.edit', 'group' => 'permissions', 'description' => 'Chỉnh sửa quyền'],
            ['name' => 'Xóa quyền', 'slug' => 'permissions.delete', 'group' => 'permissions', 'description' => 'Xóa quyền'],

            // Orders
            ['name' => 'Xem đơn hàng', 'slug' => 'orders.view', 'group' => 'orders', 'description' => 'Xem danh sách đơn hàng'],
            ['name' => 'Tạo đơn hàng', 'slug' => 'orders.create', 'group' => 'orders', 'description' => 'Tạo đơn hàng mới'],
            ['name' => 'Cập nhật đơn hàng', 'slug' => 'orders.edit', 'group' => 'orders', 'description' => 'Cập nhật trạng thái đơn hàng'],
            ['name' => 'Xóa đơn hàng', 'slug' => 'orders.delete', 'group' => 'orders', 'description' => 'Xóa đơn hàng'],

            // Dashboard
            ['name' => 'Xem dashboard', 'slug' => 'dashboard.view', 'group' => 'dashboard', 'description' => 'Xem dashboard admin'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['slug' => $permission['slug']], $permission);
        }
    }
}
