@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Chi tiết đơn hàng #{{ $order->order_number }}</h5>
                    <a href="{{ route('profile.orders') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <!-- Order Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Mã đơn hàng:</strong></p>
                            <p class="text-muted">{{ $order->order_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Ngày đặt:</strong></p>
                            <p class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Phương thức thanh toán:</strong></p>
                            <p class="text-muted">
                                @switch($order->payment_method)
                                    @case('cod')
                                        Thanh toán khi nhận hàng
                                        @break
                                    @case('bank_transfer')
                                        Chuyển khoản ngân hàng
                                        @break
                                    @case('momo')
                                        Ví MoMo
                                        @break
                                @endswitch
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Trạng thái:</strong></p>
                            <p>
                                @switch($order->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Chờ xử lý</span>
                                        @break
                                    @case('processing')
                                        <span class="badge bg-info">Đang xử lý</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Hoàn thành</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Đã hủy</span>
                                        @break
                                @endswitch
                            </p>
                        </div>
                    </div>
                    
                    <!-- Order Items -->
                    <h6 class="mb-3">Khóa học đã mua:</h6>
                    <ul class="list-group mb-4">
                        @foreach($order->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    @if($item->course->thumbnail)
                                        <img src="{{ asset('storage/' . $item->course->thumbnail) }}" class="rounded me-3" style="width: 60px; height: 40px; object-fit: cover;">
                                    @else
                                        <img src="https://via.placeholder.com/60x40" class="rounded me-3">
                                    @endif
                                    <div>
                                        <strong>{{ $item->course->title }}</strong>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">{{ number_format($item->price) }}đ</div>
                                    @if($order->status === 'completed')
                                        <a href="{{ route('learn.course', $item->course->slug) }}" class="btn btn-sm btn-success mt-1">
                                            <i class="fas fa-play me-1"></i>Học
                                        </a>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    
                    <!-- Total -->
                    <div class="bg-light p-3 rounded">
                        <div class="d-flex justify-content-between">
                            <strong>Tổng cộng:</strong>
                            <strong class="text-primary fs-5">{{ number_format($order->total) }}đ</strong>
                        </div>
                    </div>
                    
                    @if($order->notes)
                        <div class="mt-3">
                            <strong>Ghi chú:</strong>
                            <p class="text-muted mb-0">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
