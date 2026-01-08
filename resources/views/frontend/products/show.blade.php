@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-5 mb-4">
            <div class="card">
                <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}" id="mainImage">
            </div>
            @if($product->thumbnail)
            <div class="row mt-3">
                <div class="col-3">
                    <img src="{{ $product->image_url }}" class="img-thumbnail cursor-pointer" onclick="changeImage(this.src)">
                </div>
                <div class="col-3">
                    <img src="{{ $product->thumbnail_url }}" class="img-thumbnail cursor-pointer" onclick="changeImage(this.src)">
                </div>
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="col-lg-7">
            <h1 class="h2 mb-3">{{ $product->name }}</h1>
            
            <div class="mb-4">
                @if($product->sale_price)
                    <span class="h4 text-muted text-decoration-line-through me-3">{{ number_format($product->regular_price, 0, ',', '.') }}đ</span>
                    <span class="h3 text-danger fw-bold">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                    <span class="badge bg-danger ms-2">Giảm {{ round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100) }}%</span>
                @else
                    <span class="h3 text-danger fw-bold">{{ number_format($product->regular_price, 0, ',', '.') }}đ</span>
                @endif
            </div>

            <p class="text-muted mb-4">{{ $product->description }}</p>

            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-4">
                @csrf
                <div class="row align-items-center mb-3">
                    <div class="col-auto">
                        <label class="form-label mb-0">Số lượng:</label>
                    </div>
                    <div class="col-auto">
                        <div class="input-group" style="width: 130px;">
                            <button type="button" class="btn btn-outline-secondary" onclick="decreaseQty()">-</button>
                            <input type="number" name="quantity" id="quantity" class="form-control text-center" value="1" min="1">
                            <button type="button" class="btn btn-outline-secondary" onclick="increaseQty()">+</button>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg me-2">
                    <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ hàng
                </button>
                <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-shopping-cart me-2"></i> Xem giỏ hàng
                </a>
            </form>

            <hr>

            <div class="mb-3">
                <strong>Danh mục:</strong> 
                <a href="{{ route('products.index', ['category' => $product->category->slug ?? '']) }}">
                    {{ $product->category->name ?? 'Chưa phân loại' }}
                </a>
            </div>

            <div class="mb-3">
                <strong>Trạng thái:</strong>
                <span class="badge bg-success">Còn hàng</span>
            </div>

            <div class="d-flex gap-2 mb-4">
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="fab fa-facebook-f"></i> Chia sẻ
                </a>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="fab fa-twitter"></i> Tweet
                </a>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="fab fa-pinterest"></i> Pin
                </a>
            </div>
        </div>
    </div>

    <!-- Product Description -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">
                        Mô tả chi tiết
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                        Đánh giá
                    </button>
                </li>
            </ul>
            <div class="tab-content p-4 border border-top-0" id="productTabsContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    {!! $product->content !!}
                </div>
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h3 class="mb-4">Sản phẩm liên quan</h3>
        <div class="row">
            @foreach($relatedProducts as $related)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card card-product h-100">
                    <a href="{{ route('products.show', $related->slug) }}">
                        <img src="{{ $related->image_url }}" class="card-img-top" alt="{{ $related->name }}">
                    </a>
                    <div class="card-body">
                        <h6 class="card-title">
                            <a href="{{ route('products.show', $related->slug) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($related->name, 40) }}
                            </a>
                        </h6>
                        <div class="mb-2">
                            @if($related->sale_price)
                                <span class="price-original small">{{ number_format($related->regular_price, 0, ',', '.') }}đ</span>
                                <span class="price-sale">{{ number_format($related->sale_price, 0, ',', '.') }}đ</span>
                            @else
                                <span class="price-sale">{{ number_format($related->regular_price, 0, ',', '.') }}đ</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function changeImage(src) {
    document.getElementById('mainImage').src = src;
}

function increaseQty() {
    var qty = document.getElementById('quantity');
    qty.value = parseInt(qty.value) + 1;
}

function decreaseQty() {
    var qty = document.getElementById('quantity');
    if (parseInt(qty.value) > 1) {
        qty.value = parseInt(qty.value) - 1;
    }
}
</script>
@endpush
