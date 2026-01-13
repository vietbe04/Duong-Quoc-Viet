@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    <h2 class="text-success mb-3">Đặt hàng thành công!</h2>
                    <p class="text-muted mb-4">
                        Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đang được xử lý.<br>
                        Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.
                    </p>
                    
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Thông tin đơn hàng</h5>
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td>Mã đơn hàng:</td>
                                    <td class="fw-bold text-primary">{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <td>Ngày đặt:</td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td>Tổng tiền:</td>
                                    <td class="text-danger fw-bold">{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                </tr>
                                <tr>
                                    <td>Phương thức thanh toán:</td>
                                    <td>{{ $order->payment_method_label }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <p class="text-muted">
                        Email xác nhận đã được gửi đến <strong>{{ $order->email }}</strong>
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-primary">
                            <i class="fas fa-eye me-2"></i> Xem đơn hàng
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-shopping-bag me-2"></i> Tiếp tục mua hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
