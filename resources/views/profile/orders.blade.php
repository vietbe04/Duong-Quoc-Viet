@extends('layouts.app')

@section('title', 'Lịch sử đơn hàng')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    <h5>{{ auth()->user()->name }}</h5>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                </div>
            </div>
            
            <div class="list-group">
                <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-user me-2"></i>Thông tin cá nhân
                </a>
                <a href="{{ route('profile.orders') }}" class="list-group-item list-group-item-action active">
                    <i class="fas fa-receipt me-2"></i>Lịch sử đơn hàng
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Lịch sử đơn hàng</h5>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-primary fw-bold">{{ number_format($order->total) }}đ</td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <a href="{{ route('profile.orders.detail', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $orders->links() }}
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-receipt fa-4x mb-3"></i>
                            <p>Bạn chưa có đơn hàng nào</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">Mua khóa học ngay</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
