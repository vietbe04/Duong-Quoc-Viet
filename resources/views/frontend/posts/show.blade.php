@extends('layouts.app')

@section('title', $post->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">Bài viết</a></li>
                    @if($post->category)
                        <li class="breadcrumb-item"><a href="{{ route('posts.index', ['category' => $post->category->slug]) }}">{{ $post->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->name, 30) }}</li>
                </ol>
            </nav>

            <article>
                <header class="mb-4">
                    @if($post->category)
                        <a href="{{ route('posts.index', ['category' => $post->category->slug]) }}" class="badge bg-primary text-decoration-none mb-2">
                            {{ $post->category->name }}
                        </a>
                    @endif
                    <h1 class="mb-3">{{ $post->name }}</h1>
                    <div class="text-muted mb-3">
                        <span class="me-3"><i class="fas fa-calendar me-1"></i> {{ $post->published_at->format('d/m/Y') }}</span>
                        @if($post->author)
                            <span class="me-3"><i class="fas fa-user me-1"></i> {{ $post->author->name }}</span>
                        @endif
                    </div>
                </header>

                @if($post->image)
                <img src="{{ $post->image_url }}" class="img-fluid rounded mb-4" alt="{{ $post->name }}">
                @endif

                <div class="lead mb-4">{{ $post->description }}</div>

                <div class="post-content">
                    {!! $post->content !!}
                </div>

                <!-- Share Buttons -->
                <div class="border-top border-bottom py-3 my-4">
                    <span class="me-3"><strong>Chia sẻ:</strong></span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->name) }}" target="_blank" class="btn btn-outline-info btn-sm me-2">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($post->name) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                        <i class="fab fa-linkedin-in"></i> LinkedIn
                    </a>
                </div>

                <!-- Navigation -->
                <div class="row">
                    <div class="col-6">
                        @if($prevPost)
                            <a href="{{ route('posts.show', $prevPost->slug) }}" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i> {{ Str::limit($prevPost->name, 30) }}
                            </a>
                        @endif
                    </div>
                    <div class="col-6 text-end">
                        @if($nextPost)
                            <a href="{{ route('posts.show', $nextPost->slug) }}" class="text-decoration-none">
                                {{ Str::limit($nextPost->name, 30) }} <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Author -->
            @if($post->author)
            <div class="sidebar-widget">
                <h5><i class="fas fa-user me-2"></i> Tác giả</h5>
                <div class="d-flex align-items-center">
                    <img src="{{ $post->author->avatar_url }}" alt="" width="60" height="60" class="rounded-circle me-3" style="object-fit: cover;">
                    <div>
                        <h6 class="mb-0">{{ $post->author->name }}</h6>
                        <small class="text-muted">{{ $post->author->email }}</small>
                    </div>
                </div>
            </div>
            @endif

            <!-- Categories -->
            <div class="sidebar-widget">
                <h5><i class="fas fa-th-list me-2"></i> Danh mục</h5>
                <ul class="list-unstyled category-list">
                    @foreach($categories as $category)
                    <li>
                        <a href="{{ route('posts.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                            {{ $category->name }} <span class="text-muted">({{ $category->posts_count }})</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
            <div class="sidebar-widget">
                <h5><i class="fas fa-newspaper me-2"></i> Bài viết liên quan</h5>
                @foreach($relatedPosts as $related)
                <div class="d-flex mb-3">
                    <img src="{{ $related->image_url }}" alt="" width="80" height="60" class="me-3" style="object-fit: cover; border-radius: 5px;">
                    <div>
                        <h6 class="mb-1">
                            <a href="{{ route('posts.show', $related->slug) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($related->name, 40) }}
                            </a>
                        </h6>
                        <small class="text-muted">{{ $related->published_at->format('d/m/Y') }}</small>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.post-content img {
    max-width: 100%;
    height: auto;
}
.post-content p {
    margin-bottom: 1rem;
    line-height: 1.8;
}
</style>
@endpush
