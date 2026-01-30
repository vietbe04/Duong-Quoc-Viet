@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Giỏ hàng</h2>
    
    @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span>{{ $cartItems->count() }} khóa học</span>
                        <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa toàn bộ giỏ hàng?')">
                                <i class="fas fa-trash me-1"></i>Xóa tất cả
                            </button>
                        </form>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($cartItems as $item)
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        @if($item->course->thumbnail)
                                            <img src="{{ asset('storage/' . $item->course->thumbnail) }}" class="img-fluid rounded" alt="{{ $item->course->title }}">
                                        @else
                                            <img src="https://via.placeholder.com/100x60" class="img-fluid rounded" alt="{{ $item->course->title }}">
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-1">
                                            <a href="{{ route('courses.detail', $item->course->slug) }}" class="text-decoration-none text-dark">
                                                {{ $item->course->title }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">{{ $item->course->instructor->name ?? 'Unknown' }}</small>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <div class="fw-bold text-primary">
                                            {{ number_format($item->course->sale_price ?? $item->course->price) }}đ
                                        </div>
                                        @if($item->course->sale_price)
                                            <small class="text-muted text-decoration-line-through">
                                                {{ number_format($item->course->price) }}đ
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tổng cộng</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tạm tính:</span>
                            <span>{{ number_format($total) }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Giảm giá:</span>
                            <span class="text-success">0đ</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Tổng:</strong>
                            <strong class="text-primary fs-4">{{ number_format($total) }}đ</strong>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-credit-card me-2"></i>Thanh toán
                        </a>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-body">
                        <h6><i class="fas fa-shield-alt me-2 text-success"></i>Cam kết</h6>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Thanh toán an toàn</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Truy cập trọn đời</li>
                            <li><i class="fas fa-check text-success me-2"></i>Hoàn tiền trong 7 ngày</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
            <h4>Giỏ hàng trống</h4>
            <p class="text-muted">Bạn chưa có khóa học nào trong giỏ hàng</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
            </a>
        </div>
    @endif
</div>
@endsection
