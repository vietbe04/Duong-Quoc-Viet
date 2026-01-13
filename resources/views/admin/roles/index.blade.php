@extends('admin.layouts.app')

@section('title', 'Quản lý vai trò')
@section('page-title', 'Quản lý vai trò')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Vai trò</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách vai trò</h3>
        <div class="card-tools">
            @if(hasPermission('roles.create'))
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Tên vai trò</th>
                    <th>Slug</th>
                    <th>Mô tả</th>
                    <th>Số quyền</th>
                    <th>Số người dùng</th>
                    <th width="150">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td><strong>{{ $role->name }}</strong></td>
                    <td><code>{{ $role->slug }}</code></td>
                    <td>{{ $role->description }}</td>
                    <td><span class="badge badge-info">{{ $role->permissions->count() }}</span></td>
                    <td><span class="badge badge-success">{{ $role->users->count() }}</span></td>
                    <td class="text-nowrap">
                        <div class="action-buttons">
                            @if(hasPermission('roles.view'))
                            <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endif
                            @if(hasPermission('roles.edit'))
                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            @if(hasPermission('roles.delete') && !in_array($role->slug, ['super-admin', 'admin', 'user']))
                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Không có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($roles instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="card-footer">
        {{ $roles->links() }}
    </div>
    @endif
</div>
@endsection
