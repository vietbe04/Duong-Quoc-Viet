<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if (!hasPermission('permissions.view')) {
            abort(403, 'Bạn không có quyền xem danh sách quyền.');
        }

        $query = Permission::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        $permissions = $query->latest()->paginate(10);
        $groups = Permission::distinct()->pluck('group')->filter();

        return view('admin.permissions.index', compact('permissions', 'groups'));
    }

    public function create()
    {
        if (!hasPermission('permissions.create')) {
            abort(403, 'Bạn không có quyền tạo quyền.');
        }

        $groups = Permission::distinct()->pluck('group')->filter();
        return view('admin.permissions.create', compact('groups'));
    }

    public function store(Request $request)
    {
        if (!hasPermission('permissions.create')) {
            abort(403, 'Bạn không có quyền tạo quyền.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'slug' => 'nullable|string|max:255|unique:permissions',
            'group' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Permission::create([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'group' => $request->group,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission đã được tạo thành công!');
    }

    public function edit(Permission $permission)
    {
        if (!hasPermission('permissions.edit')) {
            abort(403, 'Bạn không có quyền chỉnh sửa quyền.');
        }

        $groups = Permission::distinct()->pluck('group')->filter();
        return view('admin.permissions.edit', compact('permission', 'groups'));
    }

    public function update(Request $request, Permission $permission)
    {
        if (!hasPermission('permissions.edit')) {
            abort(403, 'Bạn không có quyền chỉnh sửa quyền.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'slug' => 'nullable|string|max:255|unique:permissions,slug,' . $permission->id,
            'group' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $permission->update([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'group' => $request->group,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission đã được cập nhật thành công!');
    }

    public function destroy(Permission $permission)
    {
        if (!hasPermission('permissions.delete')) {
            abort(403, 'Bạn không có quyền xóa quyền.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission đã được xóa thành công!');
    }
}
