@extends('layouts.app')

@section('title', 'Bài viết')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <h2 class="mb-4"><i class="fas fa-newspaper me-2"></i> Bài viết</h2>
            
            @forelse($posts as $post)
            <div class="card mb-4">
                <div class="row g-0">
                    <div class="col-md-4">
                        <a href="{{ route('posts.show', $post->slug) }}">
                            <img src="{{ $post->image_url }}" class="img-fluid rounded-start h-100" alt="{{ $post->name }}" style="object-fit: cover;">
                        </a>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <div class="mb-2">
                                @if($post->category)
                                    <a href="{{ route('posts.index', ['category' => $post->category->slug]) }}" class="badge bg-primary text-decoration-none">
                                        {{ $post->category->name }}
                                    </a>
                                @endif
                                <small class="text-muted ms-2">
                                    <i class="fas fa-calendar me-1"></i> {{ $post->published_at->format('d/m/Y') }}
                                </small>
                            </div>
                            <h5 class="card-title">
                                <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none text-dark">
                                    {{ $post->name }}
                                </a>
                            </h5>
                            <p class="card-text text-muted">{{ Str::limit($post->description, 150) }}</p>
                            <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-outline-primary btn-sm">
                                Đọc tiếp <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i> Chưa có bài viết nào.
            </div>
            @endforelse

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->withQueryString()->links() }}
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Search -->
            <div class="sidebar-widget">
                <h5><i class="fas fa-search me-2"></i> Tìm kiếm</h5>
                <form action="{{ route('posts.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm bài viết..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>

            <!-- Categories -->
            <div class="sidebar-widget">
                <h5><i class="fas fa-th-list me-2"></i> Danh mục</h5>
                <ul class="list-unstyled category-list">
                    <li>
                        <a href="{{ route('posts.index') }}" class="text-decoration-none {{ !request('category') ? 'fw-bold text-primary' : '' }}">
                            Tất cả bài viết
                        </a>
                    </li>
                    @foreach($categories as $category)
                    <li>
                        <a href="{{ route('posts.index', ['category' => $category->slug]) }}" 
                           class="text-decoration-none {{ request('category') == $category->slug ? 'fw-bold text-primary' : '' }}">
                            {{ $category->name }} <span class="text-muted">({{ $category->posts_count }})</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Recent Posts -->
            @if($recentPosts->count() > 0)
            <div class="sidebar-widget">
                <h5><i class="fas fa-clock me-2"></i> Bài viết gần đây</h5>
                @foreach($recentPosts as $recent)
                <div class="d-flex mb-3">
                    <img src="{{ $recent->image_url }}" alt="" width="80" height="60" class="me-3" style="object-fit: cover; border-radius: 5px;">
                    <div>
                        <h6 class="mb-1">
                            <a href="{{ route('posts.show', $recent->slug) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($recent->name, 40) }}
                            </a>
                        </h6>
                        <small class="text-muted">{{ $recent->published_at->format('d/m/Y') }}</small>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
