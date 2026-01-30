@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')
@section('page-title', 'Chi tiết đơn hàng')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Khóa học trong đơn hàng</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
            <div class="card-body">
                @foreach($order->items as $item)
                    <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        @if($item->course && $item->course->thumbnail)
                            <img src="{{ asset('storage/' . $item->course->thumbnail) }}" class="rounded me-3" style="width: 100px; height: 65px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded me-3" style="width: 100px; height: 65px;"></div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->course_title }}</h6>
                            <small class="text-muted">Giảng viên: {{ $item->course->instructor->name ?? '-' }}</small>
                        </div>
                        <div class="text-end">
                            <div class="text-primary fw-bold">{{ number_format($item->price) }}đ</div>
                        </div>
                    </div>
                @endforeach
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <strong>Tổng cộng:</strong>
                    <span class="text-primary fs-5 fw-bold">{{ number_format($order->total) }}đ</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Order Info -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thông tin đơn hàng</h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Mã đơn:</span>
                    <strong>{{ $order->order_number }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Trạng thái:</span>
                    @switch($order->status)
                        @case('pending')
                            <span class="badge bg-warning">Chờ xử lý</span>
                            @break
                        @case('completed')
                            <span class="badge bg-success">Hoàn thành</span>
                            @break
                        @case('cancelled')
                            <span class="badge bg-danger">Đã hủy</span>
                            @break
                    @endswitch
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Thanh toán:</span>
                    <strong>{{ ucfirst($order->payment_method ?? 'N/A') }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Ngày tạo:</span>
                    <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong>
                </li>
            </ul>
            <div class="card-body">
                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary w-100">
                    <i class="fas fa-edit me-1"></i>Cập nhật trạng thái
                </a>
            </div>
        </div>
        
        <!-- Customer Info -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thông tin khách hàng</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $order->user->avatar_url }}" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                    <div>
                        <h6 class="mb-1">{{ $order->user->name }}</h6>
                        <small class="text-muted">{{ $order->user->email }}</small>
                    </div>
                </div>
                @if($order->user->phone)
                    <p class="mb-1"><i class="fas fa-phone me-2"></i>{{ $order->user->phone }}</p>
                @endif
                <a href="{{ route('admin.users.show', $order->user) }}" class="btn btn-sm btn-outline-primary mt-2">
                    <i class="fas fa-user me-1"></i>Xem hồ sơ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
