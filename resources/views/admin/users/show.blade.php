@extends('layouts.admin')

@section('title', $user->name)
@section('page-title', 'Chi tiết người dùng')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- User Info Card -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <img src="{{ $user->avatar_url }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                <h5>{{ $user->name }}</h5>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'instructor' ? 'info' : 'secondary') }} mb-3">
                    {{ ucfirst($user->role) }}
                </span>
                
                @if($user->bio)
                    <p class="mt-3">{{ $user->bio }}</p>
                @endif
                
                <hr>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Số điện thoại:</span>
                    <strong>{{ $user->phone ?? '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Ngày đăng ký:</span>
                    <strong>{{ $user->created_at->format('d/m/Y') }}</strong>
                </div>
                
                <hr>
                
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>Sửa
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Enrolled Courses -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Khóa học đã đăng ký ({{ $user->enrolledCourses->count() }})</h5>
            </div>
            <div class="card-body">
                @forelse($user->enrolledCourses as $course)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" class="rounded me-3" style="width: 80px; height: 50px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded me-3" style="width: 80px; height: 50px;"></div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $course->title }}</h6>
                            <small class="text-muted">
                                Đăng ký: {{ $course->pivot->enrolled_at ? \Carbon\Carbon::parse($course->pivot->enrolled_at)->format('d/m/Y') : '-' }}
                            </small>
                        </div>
                        <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-sm btn-outline-primary">Xem</a>
                    </div>
                @empty
                    <p class="text-muted mb-0 text-center">Chưa đăng ký khóa học nào</p>
                @endforelse
            </div>
        </div>
        
        <!-- Orders -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Đơn hàng ({{ $user->orders->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td class="text-primary fw-bold">{{ number_format($order->total) }}đ</td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Chờ xử lý</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">Hoàn thành</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Chưa có đơn hàng</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
