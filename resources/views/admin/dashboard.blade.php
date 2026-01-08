@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Người dùng</span>
                <span class="info-box-number">{{ number_format($totalUsers) }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-newspaper"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Bài viết</span>
                <span class="info-box-number">{{ number_format($totalPosts) }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-box"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sản phẩm</span>
                <span class="info-box-number">{{ number_format($totalProducts) }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-shopping-cart"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Đơn hàng</span>
                <span class="info-box-number">{{ number_format($totalOrders) }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="info-box bg-gradient-primary">
            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Đơn hàng chờ xử lý</span>
                <span class="info-box-number">{{ number_format($pendingOrders) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="info-box bg-gradient-success">
            <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tổng doanh thu</span>
                <span class="info-box-number">{{ number_format($totalRevenue, 0, ',', '.') }} đ</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Orders -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Đơn hàng gần đây</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-tool">Xem tất cả</a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->order_number }}</a>
                            </td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ number_format($order->total, 0, ',', '.') }} đ</td>
                            <td>
                                @switch($order->status)
                                    @case('pending')
                                        <span class="badge badge-warning">Chờ xử lý</span>
                                        @break
                                    @case('confirmed')
                                        <span class="badge badge-info">Đã xác nhận</span>
                                        @break
                                    @case('processing')
                                        <span class="badge badge-primary">Đang xử lý</span>
                                        @break
                                    @case('shipped')
                                        <span class="badge badge-secondary">Đang giao</span>
                                        @break
                                    @case('delivered')
                                        <span class="badge badge-success">Đã giao</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge badge-danger">Đã hủy</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Chưa có đơn hàng nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Người dùng mới</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-tool">Xem tất cả</a>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="users-list clearfix">
                    @forelse($recentUsers as $user)
                    <li>
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                        <a class="users-list-name" href="{{ route('admin.users.show', $user->id) }}">{{ Str::limit($user->name, 15) }}</a>
                        <span class="users-list-date">{{ $user->created_at->diffForHumans() }}</span>
                    </li>
                    @empty
                    <li class="text-center py-3">Chưa có người dùng</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
