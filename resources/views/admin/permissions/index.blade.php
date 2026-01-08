@extends('admin.layouts.app')

@section('title', 'Quản lý quyền')
@section('page-title', 'Quản lý quyền')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Quyền</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách quyền</h3>
        <div class="card-tools">
            @if(hasPermission('permissions.create'))
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.permissions.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="group" class="form-control">
                        <option value="">-- Nhóm quyền --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>{{ ucfirst($group) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> Lọc</button>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        @php
            $groupedPermissions = $permissions->groupBy(function($permission) {
                return explode('.', $permission->slug)[0];
            });
        @endphp

        @foreach($groupedPermissions as $group => $perms)
        <div class="card card-outline card-primary mb-3">
            <div class="card-header">
                <h3 class="card-title text-uppercase">
                    <i class="fas fa-folder mr-1"></i> {{ ucfirst($group) }}
                    <span class="badge badge-info ml-2">{{ $perms->count() }} quyền</span>
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">ID</th>
                            <th>Tên quyền</th>
                            <th>Slug</th>
                            <th>Mô tả</th>
                            <th width="100">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($perms as $permission)
                        <tr>
                            <td>{{ $permission->id }}</td>
                            <td><strong>{{ $permission->name }}</strong></td>
                            <td><code>{{ $permission->slug }}</code></td>
                            <td>{{ $permission->description }}</td>
                            <td>
                                @if(hasPermission('permissions.edit'))
                                <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @if(hasPermission('permissions.delete'))
                                <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
