<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if (!hasPermission('roles.view')) {
            abort(403, 'Bạn không có quyền xem danh sách vai trò.');
        }

        $query = Role::withCount(['users', 'permissions']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $roles = $query->latest()->paginate(10);

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        if (!hasPermission('roles.create')) {
            abort(403, 'Bạn không có quyền tạo vai trò.');
        }

        $permissions = Permission::all()->groupBy('group');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        if (!hasPermission('roles.create')) {
            abort(403, 'Bạn không có quyền tạo vai trò.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'slug' => 'nullable|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role đã được tạo thành công!');
    }

    public function show(Role $role)
    {
        if (!hasPermission('roles.view')) {
            abort(403, 'Bạn không có quyền xem chi tiết vai trò.');
        }

        $role->load(['permissions', 'users']);
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        if (!hasPermission('roles.edit')) {
            abort(403, 'Bạn không có quyền chỉnh sửa vai trò.');
        }

        $permissions = Permission::all()->groupBy('group');
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        if (!hasPermission('roles.edit')) {
            abort(403, 'Bạn không có quyền chỉnh sửa vai trò.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'slug' => 'nullable|string|max:255|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role đã được cập nhật thành công!');
    }

    public function destroy(Role $role)
    {
        if (!hasPermission('roles.delete')) {
            abort(403, 'Bạn không có quyền xóa vai trò.');
        }

        if ($role->slug === 'super-admin' || $role->slug === 'admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Không thể xóa role hệ thống!');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role đã được xóa thành công!');
    }
}
