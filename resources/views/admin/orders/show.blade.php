@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng')
@section('page-title', 'Chi tiết đơn hàng #' . $order->order_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
    <li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin sản phẩm</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="80">Hình</th>
                            <th>Sản phẩm</th>
                            <th class="text-right">Đơn giá</th>
                            <th class="text-center">SL</th>
                            <th class="text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <img src="{{ $item->product_image_url }}" alt="{{ $item->product_name }}" 
                                     width="60" height="60" style="object-fit: cover;">
                            </td>
                            <td>
                                {{ $item->product_name }}
                                @if($item->product)
                                    <br><small class="text-muted">
                                        <a href="{{ route('admin.products.edit', $item->product_id) }}">Xem sản phẩm</a>
                                    </small>
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->total, 0, ',', '.') }} đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Tạm tính:</strong></td>
                            <td class="text-right">{{ number_format($order->subtotal, 0, ',', '.') }} đ</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Phí vận chuyển:</strong></td>
                            <td class="text-right">{{ number_format($order->shipping_fee, 0, ',', '.') }} đ</td>
                        </tr>
                        @if($order->discount > 0)
                        <tr>
                            <td colspan="4" class="text-right"><strong>Giảm giá:</strong></td>
                            <td class="text-right text-danger">-{{ number_format($order->discount, 0, ',', '.') }} đ</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-right"><strong>Tổng cộng:</strong></td>
                            <td class="text-right"><strong class="text-primary" style="font-size: 18px;">{{ number_format($order->total, 0, ',', '.') }} đ</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($order->notes)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ghi chú từ khách hàng</h3>
            </div>
            <div class="card-body">
                {{ $order->notes }}
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin đơn hàng</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Mã đơn:</th>
                        <td>{{ $order->order_number }}</td>
                    </tr>
                    <tr>
                        <th>Ngày đặt:</th>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>PTTT:</th>
                        <td>{{ $order->payment_method_label }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cập nhật trạng thái</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mb-3">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Trạng thái đơn hàng</label>
                        <select name="status" class="form-control">
                            @foreach($statuses as $key => $value)
                                <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Cập nhật</button>
                </form>

                <form action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Trạng thái thanh toán</label>
                        <select name="payment_status" class="form-control">
                            @foreach($paymentStatuses as $key => $value)
                                <option value="{{ $key }}" {{ $order->payment_status == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Cập nhật</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin khách hàng</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Họ tên:</th>
                        <td>{{ $order->customer_name }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $order->customer_email }}</td>
                    </tr>
                    <tr>
                        <th>SĐT:</th>
                        <td>{{ $order->customer_phone }}</td>
                    </tr>
                    <tr>
                        <th>Địa chỉ:</th>
                        <td>{{ $order->shipping_address }}</td>
                    </tr>
                    @if($order->user)
                    <tr>
                        <th>Tài khoản:</th>
                        <td><a href="{{ route('admin.users.show', $order->user_id) }}">{{ $order->user->email }}</a></td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>
</div>
@endsection
