@extends('admin.layouts.app')

@section('title', 'Chi tiết bài viết')
@section('page-title', 'Chi tiết bài viết')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Bài viết</a></li>
    <li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $post->name }}</h3>
            </div>
            <div class="card-body">
                @if($post->image)
                    <div class="mb-3">
                        <img src="{{ $post->image_url }}" class="img-fluid" alt="{{ $post->name }}">
                    </div>
                @endif
                
                @if($post->description)
                    <div class="lead mb-3">{{ $post->description }}</div>
                @endif
                
                <div class="content">
                    {!! $post->content !!}
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>ID:</th>
                        <td>{{ $post->id }}</td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td>{{ $post->slug }}</td>
                    </tr>
                    <tr>
                        <th>Danh mục:</th>
                        <td>{{ $post->category->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Tác giả:</th>
                        <td>{{ $post->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái:</th>
                        <td>
                            @if($post->status == 'active')
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày xuất bản:</th>
                        <td>{{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Cập nhật:</th>
                        <td>{{ $post->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Sửa
                </a>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
