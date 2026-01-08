@extends('admin.layouts.app')

@section('title', 'Quản lý danh mục')
@section('page-title', 'Quản lý danh mục')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Danh mục</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách danh mục</h3>
        <div class="card-tools">
            @if(hasPermission('categories.create'))
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-control">
                        <option value="">-- Loại --</option>
                        <option value="post" {{ request('type') == 'post' ? 'selected' : '' }}>Bài viết</option>
                        <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>Sản phẩm</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> Lọc</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th>Loại</th>
                    <th>Danh mục cha</th>
                    <th>Trạng thái</th>
                    <th width="120">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>
                        @if($category->type == 'post')
                            <span class="badge badge-info">Bài viết</span>
                        @else
                            <span class="badge badge-warning">Sản phẩm</span>
                        @endif
                    </td>
                    <td>{{ $category->parent->name ?? '-' }}</td>
                    <td>
                        @if($category->status == 'active')
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        @if(hasPermission('categories.edit'))
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                        @if(hasPermission('categories.delete'))
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
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
    <div class="card-footer">
        {{ $categories->withQueryString()->links() }}
    </div>
</div>
@endsection
