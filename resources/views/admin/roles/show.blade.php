@extends('admin.layouts.app')

@section('title', 'Chi tiết vai trò')
@section('page-title', 'Chi tiết vai trò')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Vai trò</a></li>
    <li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Thông tin vai trò</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>ID:</th>
                        <td>{{ $role->id }}</td>
                    </tr>
                    <tr>
                        <th>Tên:</th>
                        <td><strong>{{ $role->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td><code>{{ $role->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Mô tả:</th>
                        <td>{{ $role->description ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Số quyền:</th>
                        <td><span class="badge badge-info">{{ $role->permissions->count() }}</span></td>
                    </tr>
                    <tr>
                        <th>Số người dùng:</th>
                        <td><span class="badge badge-success">{{ $role->users->count() }}</span></td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td>{{ $role->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-info">
                    <i class="fas fa-edit"></i> Sửa
                </a>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách quyền</h3>
            </div>
            <div class="card-body">
                @php
                    $groupedPermissions = $role->permissions->groupBy(function($permission) {
                        return explode('.', $permission->slug)[0];
                    });
                @endphp

                @if($groupedPermissions->count() > 0)
                    @foreach($groupedPermissions as $group => $perms)
                        <div class="mb-3">
                            <h6 class="text-uppercase text-primary">
                                <i class="fas fa-folder mr-1"></i> {{ ucfirst($group) }}
                            </h6>
                            @foreach($perms as $permission)
                                <span class="badge badge-secondary mr-1 mb-1">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Vai trò này chưa được gán quyền nào.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Người dùng có vai trò này</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($role->users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                     width="32" height="32" style="border-radius: 50%;">
                            </td>
                            <td><a href="{{ route('admin.users.show', $user->id) }}">{{ $user->name }}</a></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->status == 'active')
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Chưa có người dùng nào có vai trò này</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
