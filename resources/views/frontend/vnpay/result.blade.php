@extends('layouts.app')

@section('title', $success ? 'Thanh toán thành công' : 'Thanh toán thất bại')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    @if($success)
                        <!-- Success -->
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                        </div>
                        <h2 class="text-success mb-3">Thanh toán thành công!</h2>
                        <p class="text-muted mb-4">{{ $message }}</p>
                        
                        @if($order)
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Thông tin đơn hàng</h5>
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="text-muted">Mã đơn hàng:</td>
                                            <td class="fw-bold">{{ $order->order_number }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Số tiền:</td>
                                            <td class="fw-bold text-danger">{{ number_format($amount ?? $order->total, 0, ',', '.') }} VNĐ</td>
                                        </tr>
                                        @if(isset($transaction_no))
                                        <tr>
                                            <td class="text-muted">Mã giao dịch VNPay:</td>
                                            <td>{{ $transaction_no }}</td>
                                        </tr>
                                        @endif
                                        @if(isset($bank_code))
                                        <tr>
                                            <td class="text-muted">Ngân hàng:</td>
                                            <td>{{ $bank_code }}</td>
                                        </tr>
                                        @endif
                                        @if(isset($pay_date))
                                        <tr>
                                            <td class="text-muted">Thời gian:</td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('YmdHis', $pay_date)->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td class="text-muted">Trạng thái:</td>
                                            <td><span class="badge bg-success">Đã thanh toán</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-envelope me-2"></i>
                                Email xác nhận đã được gửi đến <strong>{{ $order->customer_email }}</strong>
                            </div>
                        @endif

                        <div class="d-flex justify-content-center gap-3">
                            @if($order)
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">
                                    <i class="fas fa-eye me-2"></i>Xem đơn hàng
                                </a>
                            @endif
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i>Về trang chủ
                            </a>
                        </div>
                    @else
                        <!-- Failed -->
                        <div class="mb-4">
                            <i class="fas fa-times-circle text-danger" style="font-size: 80px;"></i>
                        </div>
                        <h2 class="text-danger mb-3">Thanh toán thất bại!</h2>
                        <p class="text-muted mb-4">{{ $message }}</p>

                        @if(isset($response_code))
                            <p class="text-muted">Mã lỗi: <strong>{{ $response_code }}</strong></p>
                        @endif

                        @if($order)
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Thông tin đơn hàng</h5>
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="text-muted">Mã đơn hàng:</td>
                                            <td class="fw-bold">{{ $order->order_number }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Số tiền:</td>
                                            <td class="fw-bold text-danger">{{ number_format($order->total, 0, ',', '.') }} VNĐ</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Trạng thái:</td>
                                            <td><span class="badge bg-warning">Chờ thanh toán</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle me-2"></i>
                                Bạn có thể thử thanh toán lại hoặc chọn phương thức thanh toán khác.
                            </div>

                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('vnpay.create', ['order_id' => $order->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-redo me-2"></i>Thử lại
                                </a>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>Xem đơn hàng
                                </a>
                            </div>
                        @else
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fas fa-home me-2"></i>Về trang chủ
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
