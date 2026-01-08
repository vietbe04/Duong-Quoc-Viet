@extends('admin.layouts.app')

@section('title', 'Quản lý đơn hàng')
@section('page-title', 'Quản lý đơn hàng')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Đơn hàng</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách đơn hàng</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Mã đơn, tên KH, email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">-- Trạng thái --</option>
                        @foreach($statuses as $key => $value)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="payment_status" class="form-control">
                        <option value="">-- Thanh toán --</option>
                        @foreach($paymentStatuses as $key => $value)
                            <option value="{{ $key }}" {{ request('payment_status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="Từ ngày">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="Đến ngày">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-info"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thanh toán</th>
                    <th>Ngày đặt</th>
                    <th width="120">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->order_number }}</a>
                    </td>
                    <td>
                        {{ $order->customer_name }}<br>
                        <small class="text-muted">{{ $order->customer_email }}</small>
                    </td>
                    <td class="text-right">{{ number_format($order->total, 0, ',', '.') }} đ</td>
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
                    <td>
                        @switch($order->payment_status)
                            @case('pending')
                                <span class="badge badge-warning">Chờ TT</span>
                                @break
                            @case('paid')
                                <span class="badge badge-success">Đã TT</span>
                                @break
                            @case('failed')
                                <span class="badge badge-danger">TT thất bại</span>
                                @break
                            @case('refunded')
                                <span class="badge badge-info">Hoàn tiền</span>
                                @break
                        @endswitch
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if(hasPermission('orders.view'))
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endif
                        @if(hasPermission('orders.delete'))
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Không có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection
