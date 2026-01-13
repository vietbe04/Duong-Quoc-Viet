@extends('admin.layouts.app')

@section('title', 'Chi tiết người dùng')
@section('page-title', 'Chi tiết người dùng')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
    <li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         style="width: 100px; height: 100px; object-fit: cover;">
                </div>
                <h3 class="profile-username text-center">{{ $user->name }}</h3>
                <p class="text-muted text-center">
                    @if($user->role)
                        <span class="badge badge-info">{{ $user->role->name }}</span>
                    @else
                        <span class="badge badge-secondary">Chưa có vai trò</span>
                    @endif
                </p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>SĐT</b> <a class="float-right">{{ $user->phone ?? 'N/A' }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Trạng thái</b>
                        <span class="float-right">
                            @if($user->status == 'active')
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item">
                        <b>Đơn hàng</b> <a class="float-right">{{ $user->orders->count() }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Ngày tạo</b> <a class="float-right">{{ $user->created_at->format('d/m/Y') }}</a>
                    </li>
                </ul>

                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-block"><b>Sửa thông tin</b></a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin chi tiết</h3>
            </div>
            <div class="card-body">
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Địa chỉ</strong>
                <p class="text-muted">{{ $user->address ?? 'Chưa cập nhật' }}</p>
                <hr>

                <strong><i class="fas fa-user-shield mr-1"></i> Vai trò & Quyền hạn</strong>
                <div class="mt-2">
                    @foreach($user->roles as $role)
                        <div class="mb-2">
                            <span class="badge badge-primary">{{ $role->name }}</span>
                            <small class="text-muted">- {{ $role->description }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if($user->orders->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Đơn hàng gần đây</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->orders->take(5) as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->order_number }}</a></td>
                            <td>{{ number_format($order->total, 0, ',', '.') }} đ</td>
                            <td>
                                @switch($order->status)
                                    @case('pending')
                                        <span class="badge badge-warning">Chờ xử lý</span>
                                        @break
                                    @case('delivered')
                                        <span class="badge badge-success">Đã giao</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge badge-danger">Đã hủy</span>
                                        @break
                                    @default
                                        <span class="badge badge-info">{{ $order->status_label }}</span>
                                @endswitch
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
