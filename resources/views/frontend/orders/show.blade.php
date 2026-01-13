@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Đơn hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i> Chi tiết đơn hàng</h5>
                    <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'primary') }} fs-6">
                        {{ $order->status_label }}
                    </span>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product_image_url ?? 'https://via.placeholder.com/60' }}" 
                                             alt="" width="60" height="60" class="rounded me-3" style="object-fit: cover;">
                                        <div>
                                            <h6 class="mb-0">{{ $item->product_name }}</h6>
                                            @if($item->product)
                                                <a href="{{ route('products.show', $item->product->slug) }}" class="small text-muted">Xem sản phẩm</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                <td class="text-end fw-bold">{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end">Tạm tính:</td>
                                <td class="text-end">{{ number_format($order->subtotal, 0, ',', '.') }}đ</td>
                            </tr>
                            @if($order->discount > 0)
                            <tr>
                                <td colspan="3" class="text-end">Giảm giá:</td>
                                <td class="text-end text-success">-{{ number_format($order->discount, 0, ',', '.') }}đ</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-end">Phí vận chuyển:</td>
                                <td class="text-end">{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . 'đ' : 'Miễn phí' }}</td>
                            </tr>
                            <tr class="fw-bold">
                                <td colspan="3" class="text-end">Tổng cộng:</td>
                                <td class="text-end text-danger h5">{{ number_format($order->total, 0, ',', '.') }}đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-truck me-2"></i> Theo dõi đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @php
                            $statuses = ['pending' => 'Đặt hàng', 'confirmed' => 'Xác nhận', 'processing' => 'Đang xử lý', 'shipping' => 'Đang giao', 'delivered' => 'Đã giao'];
                            $currentIndex = array_search($order->status, array_keys($statuses));
                            $progressPercentage = ($currentIndex / (count($statuses) - 1)) * 100;
                        @endphp
                        
                        <div class="d-flex justify-content-between mb-4">
                            @foreach($statuses as $key => $label)
                            @php $index = array_search($key, array_keys($statuses)); @endphp
                            <div class="text-center flex-fill">
                                <div class="rounded-circle d-inline-flex justify-content-center align-items-center {{ $index <= $currentIndex ? 'bg-success' : 'bg-secondary' }}" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                                <p class="small mt-2 mb-0 {{ $index <= $currentIndex ? 'text-success fw-bold' : 'text-muted' }}">{{ $label }}</p>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: <?php echo $progressPercentage; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless small">
                        <tr>
                            <td class="text-muted">Mã đơn hàng:</td>
                            <td class="fw-bold">{{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày đặt:</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Thanh toán:</td>
                            <td>{{ $order->payment_method_label }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Trạng thái TT:</td>
                            <td>
                                @switch($order->payment_status)
                                    @case('paid')
                                        <span class="badge bg-success">Đã thanh toán</span>
                                        @break
                                    @default
                                        <span class="badge bg-warning">Chờ thanh toán</span>
                                @endswitch
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> Địa chỉ giao hàng</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                    <p class="mb-1"><i class="fas fa-phone me-2 text-muted"></i> {{ $order->customer_phone }}</p>
                    <p class="mb-1"><i class="fas fa-envelope me-2 text-muted"></i> {{ $order->customer_email }}</p>
                    <p class="mb-0"><i class="fas fa-home me-2 text-muted"></i> {{ $order->shipping_address }}</p>
                    @if($order->notes)
                        <hr>
                        <p class="mb-0 text-muted"><strong>Ghi chú:</strong> {{ $order->notes }}</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if($order->status == 'pending')
            <div class="card mt-4">
                <div class="card-body">
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" 
                          onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-times me-2"></i> Hủy đơn hàng
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
