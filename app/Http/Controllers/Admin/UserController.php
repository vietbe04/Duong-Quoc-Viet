<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!hasPermission('users.view')) {
            abort(403, 'Bạn không có quyền xem danh sách người dùng.');
        }

        $query = User::with('roles');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        $users = $query->latest()->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        if (!hasPermission('users.create')) {
            abort(403, 'Bạn không có quyền tạo người dùng.');
        }

        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!hasPermission('users.create')) {
            abort(403, 'Bạn không có quyền tạo người dùng.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $data = $request->except(['password', 'avatar', 'role_id', 'password_confirmation']);
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('avatar')) {
            $imageService = app(\App\Services\ImageService::class);
            $data['avatar'] = $imageService->uploadThumbnailOnly(
                $request->file('avatar'),
                'avatar-' . Str::slug($request->name)
            );
        }

        $user = User::create($data);

        if ($request->filled('role_id')) {
            $user->role_id = $request->role_id;
            $user->save();
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User đã được tạo thành công!');
    }

    public function show(User $user)
    {
        if (!hasPermission('users.view')) {
            abort(403, 'Bạn không có quyền xem chi tiết người dùng.');
        }

        $user->load(['roles', 'orders']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        if (!hasPermission('users.edit')) {
            abort(403, 'Bạn không có quyền chỉnh sửa người dùng.');
        }

        $roles = Role::all();
        $userRole = $user->role_id;
        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
    }

    public function update(Request $request, User $user)
    {
        if (!hasPermission('users.edit')) {
            abort(403, 'Bạn không có quyền chỉnh sửa người dùng.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $data = $request->except(['password', 'avatar', 'role_id', 'password_confirmation']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $imageService = app(\App\Services\ImageService::class);
            if ($user->avatar) {
                $imageService->delete($user->avatar);
            }
            $data['avatar'] = $imageService->uploadThumbnailOnly(
                $request->file('avatar'),
                'avatar-' . Str::slug($request->name)
            );
        }

        $user->update($data);
        $user->role_id = $request->role_id ?: null;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User đã được cập nhật thành công!');
    }

    public function destroy(User $user)
    {
        if (!hasPermission('users.delete')) {
            abort(403, 'Bạn không có quyền xóa người dùng.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Không thể xóa chính mình!');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User đã được xóa thành công!');
    }
}
