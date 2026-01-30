@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-credit-card me-2"></i>Thanh toán</h2>
    
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <!-- Order Items -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Đơn hàng của bạn ({{ $cartItems->count() }} khóa học)</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($cartItems as $item)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        @if($item->course->thumbnail)
                                            <img src="{{ asset('storage/' . $item->course->thumbnail) }}" class="rounded me-3" style="width: 60px; height: 40px; object-fit: cover;">
                                        @else
                                            <img src="https://via.placeholder.com/60x40" class="rounded me-3">
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $item->course->title }}</h6>
                                            <small class="text-muted">{{ $item->course->instructor->name ?? 'Unknown' }}</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">{{ number_format($item->course->sale_price ?? $item->course->price) }}đ</div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Phương thức thanh toán</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" value="cod" id="cod" checked>
                            <label class="form-check-label" for="cod">
                                <i class="fas fa-money-bill-wave text-success me-2"></i>
                                <strong>Thanh toán khi nhận hàng (COD)</strong>
                                <p class="text-muted small mb-0">Thanh toán ngay sau khi hoàn tất đơn hàng</p>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" value="bank_transfer" id="bank">
                            <label class="form-check-label" for="bank">
                                <i class="fas fa-university text-primary me-2"></i>
                                <strong>Chuyển khoản ngân hàng</strong>
                                <p class="text-muted small mb-0">Chuyển khoản đến tài khoản ngân hàng của chúng tôi</p>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" value="momo" id="momo">
                            <label class="form-check-label" for="momo">
                                <i class="fas fa-wallet text-danger me-2"></i>
                                <strong>Ví MoMo</strong>
                                <p class="text-muted small mb-0">Thanh toán qua ví điện tử MoMo</p>
                            </label>
                        </div>
                        @error('payment_method')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Notes -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Ghi chú</h5>
                    </div>
                    <div class="card-body">
                        <textarea name="notes" class="form-control" rows="3" placeholder="Ghi chú cho đơn hàng (tùy chọn)"></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 80px;">
                    <div class="card-body">
                        <h5 class="card-title">Tổng đơn hàng</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính ({{ $cartItems->count() }} khóa học)</span>
                            <span>{{ number_format($total) }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Giảm giá</span>
                            <span class="text-success">0đ</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Tổng cộng</strong>
                            <strong class="text-primary fs-4">{{ number_format($total) }}đ</strong>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-lock me-2"></i>Hoàn tất đơn hàng
                        </button>
                        
                        <p class="text-muted small text-center mt-3 mb-0">
                            <i class="fas fa-shield-alt me-1"></i>
                            Thanh toán an toàn và bảo mật
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
