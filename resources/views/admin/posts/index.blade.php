@extends('admin.layouts.app')

@section('title', 'Quản lý bài viết')
@section('page-title', 'Quản lý bài viết')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Bài viết</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách bài viết</h3>
        <div class="card-tools">
            @if(hasPermission('posts.create'))
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form action="{{ route('admin.posts.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">-- Trạng thái --</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-control">
                        <option value="">-- Danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th width="80">Hình ảnh</th>
                    <th>Tên bài viết</th>
                    <th>Danh mục</th>
                    <th>Tác giả</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th width="120">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>
                        <img src="{{ $post->image_url }}" alt="{{ $post->name }}" width="60" height="60" style="object-fit: cover;">
                    </td>
                    <td>
                        <a href="{{ route('admin.posts.edit', $post->id) }}">{{ $post->name }}</a>
                        <br><small class="text-muted">{{ $post->slug }}</small>
                    </td>
                    <td>{{ $post->category->name ?? 'N/A' }}</td>
                    <td>{{ $post->user->name ?? 'N/A' }}</td>
                    <td>
                        @if($post->status == 'active')
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $post->created_at->format('d/m/Y') }}</td>
                    <td class="text-nowrap">
                        @if(hasPermission('posts.edit'))
                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                        @if(hasPermission('posts.delete'))
                        <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Không có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $posts->withQueryString()->links() }}
    </div>
</div>
@endsection
