@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success fa-5x"></i>
                    </div>
                    <h2 class="text-success mb-3">Đặt hàng thành công!</h2>
                    <p class="lead text-muted">Cảm ơn bạn đã mua khóa học tại E-Learning</p>
                    
                    <div class="bg-light p-4 rounded my-4">
                        <div class="row text-start">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Mã đơn hàng</small>
                                <div class="fw-bold">{{ $order->order_number }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Tổng tiền</small>
                                <div class="fw-bold text-primary">{{ number_format($order->total) }}đ</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Phương thức thanh toán</small>
                                <div class="fw-bold">
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
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Trạng thái</small>
                                <div>
                                    @if($order->status === 'completed')
                                        <span class="badge bg-success">Hoàn thành</span>
                                    @else
                                        <span class="badge bg-warning">Chờ xử lý</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Courses List -->
                    <div class="text-start mb-4">
                        <h5 class="mb-3">Khóa học đã mua:</h5>
                        <ul class="list-group">
                            @foreach($order->items as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $item->course->title }}</span>
                                    @if($order->status === 'completed')
                                        <a href="{{ route('learn.course', $item->course->slug) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-play me-1"></i>Học ngay
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('profile.orders') }}" class="btn btn-outline-primary">
                            <i class="fas fa-receipt me-2"></i>Xem đơn hàng
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Về trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
