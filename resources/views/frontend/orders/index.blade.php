@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-list me-2"></i> Đơn hàng của tôi</h2>

    @if($orders->count() > 0)
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th width="100"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $order->items->count() }} sản phẩm</td>
                        <td class="text-danger fw-bold">{{ number_format($order->total, 0, ',', '.') }}đ</td>
                        <td>
                            @switch($order->payment_status)
                                @case('paid')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning">Chờ thanh toán</span>
                                    @break
                                @case('failed')
                                    <span class="badge bg-danger">Thất bại</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            @switch($order->status)
                                @case('pending')
                                    <span class="badge bg-secondary">Chờ xử lý</span>
                                    @break
                                @case('confirmed')
                                    <span class="badge bg-info">Đã xác nhận</span>
                                    @break
                                @case('processing')
                                    <span class="badge bg-primary">Đang xử lý</span>
                                    @break
                                @case('shipping')
                                    <span class="badge bg-warning">Đang giao hàng</span>
                                    @break
                                @case('delivered')
                                    <span class="badge bg-success">Đã giao</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger">Đã hủy</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-shopping-bag fa-5x text-muted mb-4"></i>
        <h3>Chưa có đơn hàng nào</h3>
        <p class="text-muted">Bạn chưa có đơn hàng nào. Hãy mua sắm ngay!</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="fas fa-shopping-cart me-2"></i> Mua sắm ngay
        </a>
    </div>
    @endif
</div>
@endsection
