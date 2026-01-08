@extends('layouts.app')

@section('title', 'Sản phẩm')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="sidebar-widget">
                <h5><i class="fas fa-filter me-2"></i> Lọc sản phẩm</h5>
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                </form>
            </div>

            <div class="sidebar-widget">
                <h5><i class="fas fa-th-list me-2"></i> Danh mục</h5>
                <ul class="list-unstyled category-list">
                    <li>
                        <a href="{{ route('products.index') }}" class="text-decoration-none {{ !request('category') ? 'fw-bold text-primary' : '' }}">
                            Tất cả sản phẩm
                        </a>
                    </li>
                    @foreach($categories as $category)
                    <li>
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                           class="text-decoration-none {{ request('category') == $category->slug ? 'fw-bold text-primary' : '' }}">
                            {{ $category->name }} <span class="text-muted">({{ $category->products_count }})</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="sidebar-widget">
                <h5><i class="fas fa-sort me-2"></i> Sắp xếp</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'latest'])) }}" 
                           class="text-decoration-none {{ request('sort', 'latest') == 'latest' ? 'fw-bold text-primary' : '' }}">
                            Mới nhất
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_asc'])) }}" 
                           class="text-decoration-none {{ request('sort') == 'price_asc' ? 'fw-bold text-primary' : '' }}">
                            Giá thấp đến cao
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_desc'])) }}" 
                           class="text-decoration-none {{ request('sort') == 'price_desc' ? 'fw-bold text-primary' : '' }}">
                            Giá cao đến thấp
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'name'])) }}" 
                           class="text-decoration-none {{ request('sort') == 'name' ? 'fw-bold text-primary' : '' }}">
                            Theo tên A-Z
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Product List -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    @if(request('category'))
                        {{ $categories->where('slug', request('category'))->first()->name ?? 'Sản phẩm' }}
                    @else
                        Tất cả sản phẩm
                    @endif
                </h4>
                <span class="text-muted">{{ $products->total() }} sản phẩm</span>
            </div>

            <div class="row">
                @forelse($products as $product)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card card-product h-100">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                        </a>
                        @if($product->sale_price)
                            <span class="badge bg-danger position-absolute" style="top: 10px; right: 10px;">
                                -{{ round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100) }}%
                            </span>
                        @endif
                        <div class="card-body">
                            <p class="text-muted small mb-1">{{ $product->category->name ?? 'Chưa phân loại' }}</p>
                            <h5 class="card-title">
                                <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($product->name, 40) }}
                                </a>
                            </h5>
                            <p class="card-text text-muted small">{{ Str::limit($product->description, 60) }}</p>
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
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i> Không tìm thấy sản phẩm nào.
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
