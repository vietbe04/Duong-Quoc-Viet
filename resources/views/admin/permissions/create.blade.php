@extends('admin.layouts.app')

@section('title', 'Thêm quyền')
@section('page-title', 'Thêm quyền mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Quyền</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin quyền</h3>
            </div>
            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên quyền <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required placeholder="VD: Xem bài viết">
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" 
                               value="{{ old('slug') }}" required placeholder="VD: posts.view">
                        @error('slug')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="text-muted">Định dạng: <code>module.action</code> (VD: posts.create, users.edit)</small>
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea name="description" id="description" rows="3" class="form-control" 
                                  placeholder="Mô tả chi tiết về quyền này">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hướng dẫn</h3>
            </div>
            <div class="card-body">
                <h5>Quy tắc đặt tên Slug:</h5>
                <ul class="list-unstyled">
                    <li><code>posts.view</code> - Xem danh sách bài viết</li>
                    <li><code>posts.create</code> - Tạo bài viết mới</li>
                    <li><code>posts.edit</code> - Chỉnh sửa bài viết</li>
                    <li><code>posts.delete</code> - Xóa bài viết</li>
                </ul>
                
                <h5 class="mt-4">Các nhóm quyền phổ biến:</h5>
                <ul>
                    <li><strong>posts</strong> - Quản lý bài viết</li>
                    <li><strong>products</strong> - Quản lý sản phẩm</li>
                    <li><strong>categories</strong> - Quản lý danh mục</li>
                    <li><strong>users</strong> - Quản lý người dùng</li>
                    <li><strong>roles</strong> - Quản lý vai trò</li>
                    <li><strong>orders</strong> - Quản lý đơn hàng</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#name').on('blur', function() {
        var name = $(this).val();
        var slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '.')
            .replace(/\.+/g, '.');
        if (!$('#slug').val()) {
            $('#slug').val(slug);
        }
    });
});
</script>
@endpush
