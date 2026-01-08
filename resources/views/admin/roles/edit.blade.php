@extends('admin.layouts.app')

@section('title', 'Sửa vai trò')
@section('page-title', 'Sửa vai trò')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Vai trò</a></li>
    <li class="breadcrumb-item active">Sửa</li>
@endsection

@section('content')
<form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin vai trò</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên vai trò <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $role->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" 
                               value="{{ old('slug', $role->slug) }}" {{ in_array($role->slug, ['super-admin', 'admin', 'user']) ? 'readonly' : '' }} required>
                        @error('slug')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $role->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Phân quyền</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" onclick="toggleAllPermissions()">
                            <i class="fas fa-check-double"></i> Chọn tất cả
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        // $permissions đã được groupBy('group') từ controller
                        $rolePermissions = $role->permissions->pluck('id')->toArray();
                    @endphp

                    @foreach($permissions as $group => $perms)
                        <div class="mb-3">
                            <h6 class="text-uppercase text-primary">
                                <i class="fas fa-folder mr-1"></i> {{ ucfirst($group) }}
                            </h6>
                            <div class="row">
                                @foreach($perms as $permission)
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                   id="perm_{{ $permission->id }}" class="custom-control-input permission-checkbox"
                                                   {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function toggleAllPermissions() {
    var checkboxes = document.querySelectorAll('.permission-checkbox');
    var allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}
</script>
@endpush
