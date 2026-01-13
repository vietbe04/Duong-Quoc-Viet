@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Chào mừng đến với ShopOnline</h1>
                <p class="lead mb-4">Khám phá hàng ngàn sản phẩm chất lượng với giá cả hợp lý. Mua sắm ngay hôm nay!</p>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg me-2">
                    <i class="fas fa-shopping-bag me-2"></i> Mua sắm ngay
                </a>
                <a href="{{ route('posts.index') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-newspaper me-2"></i> Tin tức
                </a>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://via.placeholder.com/500x350" alt="Hero" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-star text-warning me-2"></i> Sản phẩm nổi bật</h2>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Xem tất cả <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
        
        <div class="row">
            @forelse($featuredProducts as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card card-product h-100">
                    <a href="{{ route('products.show', $product->slug) }}">
                        <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($product->name, 40) }}
                            </a>
                        </h5>
                        <div class="mb-2">
                            @if($product->sale_price)
                                <span class="price-original">{{ number_format($product->regular_price, 0, ',', '.') }}đ</span>
                                <span class="price-sale">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                            @else
                                <span class="price-sale">{{ number_format($product->regular_price, 0, ',', '.') }}đ</span>
                            @endif
                        </div>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-add-to-cart">
                                <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center text-muted">Chưa có sản phẩm nào.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4"><i class="fas fa-th-large me-2"></i> Danh mục sản phẩm</h2>
        <div class="row">
            @foreach($categories as $category)
            <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="fas fa-folder fa-3x text-primary"></i>
                            </div>
                            <h6 class="card-title text-dark">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->products_count }} sản phẩm</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Latest Posts -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-newspaper me-2"></i> Bài viết mới nhất</h2>
            <a href="{{ route('posts.index') }}" class="btn btn-outline-primary">Xem tất cả <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
        
        <div class="row">
            @forelse($latestPosts as $post)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <a href="{{ route('posts.show', $post->slug) }}">
                        <img src="{{ $post->image_url }}" class="card-img-top" alt="{{ $post->name }}" style="height: 200px; object-fit: cover;">
                    </a>
                    <div class="card-body">
                        <small class="text-muted"><i class="fas fa-calendar me-1"></i> {{ $post->published_at->format('d/m/Y') }}</small>
                        <h5 class="card-title mt-2">
                            <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($post->name, 50) }}
                            </a>
                        </h5>
                        <p class="card-text text-muted">{{ Str::limit($post->description, 100) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-outline-primary btn-sm">
                            Đọc tiếp <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center text-muted">Chưa có bài viết nào.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="p-4">
                    <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                    <h5>Giao hàng nhanh</h5>
                    <p class="text-muted mb-0">Giao hàng toàn quốc trong 2-5 ngày</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="p-4">
                    <i class="fas fa-undo fa-3x text-primary mb-3"></i>
                    <h5>Đổi trả dễ dàng</h5>
                    <p class="text-muted mb-0">Đổi trả trong vòng 7 ngày</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="p-4">
                    <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                    <h5>Hỗ trợ 24/7</h5>
                    <p class="text-muted mb-0">Đội ngũ hỗ trợ chuyên nghiệp</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="p-4">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                    <h5>Thanh toán bảo mật</h5>
                    <p class="text-muted mb-0">Bảo mật thông tin tuyệt đối</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
