@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Tổng quan hệ thống')

@section('content')
<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-users text-primary fs-4"></i>
                    </div>
                    <div>
                        <div class="text-secondary small fw-medium text-uppercase">Người dùng</div>
                        <h3 class="mb-0 fw-bold">{{ number_format($stats['total_users']) }}</h3>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-success small fw-bold me-2"><i class="fas fa-arrow-up me-1"></i>12%</span>
                    <span class="text-secondary small">so với tháng trước</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-3 bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-book-open text-success fs-4"></i>
                    </div>
                    <div>
                        <div class="text-secondary small fw-medium text-uppercase">Khóa học</div>
                        <h3 class="mb-0 fw-bold">{{ number_format($stats['total_courses']) }}</h3>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-success small fw-bold me-2">{{ $stats['published_courses'] }}</span>
                    <span class="text-secondary small">khóa học đã xuất bản</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-3 bg-warning bg-opacity-10 p-3 me-3">
                        <i class="fas fa-shopping-bag text-warning fs-4"></i>
                    </div>
                    <div>
                        <div class="text-secondary small fw-medium text-uppercase">Đơn hàng</div>
                        <h3 class="mb-0 fw-bold">{{ number_format($stats['total_orders']) }}</h3>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-warning small fw-bold me-2">{{ $stats['completed_orders'] }}</span>
                    <span class="text-secondary small">đơn đã hoàn thành</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-3 bg-indigo bg-opacity-10 p-3 me-3" style="background-color: rgba(99, 102, 241, 0.1);">
                        <i class="fas fa-wallet fs-4" style="color: #6366f1;"></i>
                    </div>
                    <div>
                        <div class="text-secondary small fw-medium text-uppercase">Doanh thu</div>
                        <h3 class="mb-0 fw-bold">{{ number_format($stats['total_revenue']) }}đ</h3>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-success small fw-bold me-2"><i class="fas fa-arrow-up me-1"></i>8.5%</span>
                    <span class="text-secondary small">tăng trưởng</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Revenue Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Doanh thu & Đơn hàng</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border-0 px-3" type="button" data-bs-toggle="dropdown">
                        6 tháng gần nhất <i class="fas fa-chevron-down ms-1 small"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-3">
                        <li><a class="dropdown-item rounded-2" href="#">3 tháng gần nhất</a></li>
                        <li><a class="dropdown-item rounded-2" href="#">6 tháng gần nhất</a></li>
                        <li><a class="dropdown-item rounded-2" href="#">12 tháng gần nhất</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-4">
                <div style="height: 350px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- New Users -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Người dùng mới</h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold">Tất cả</a>
            </div>
            <div class="card-body p-4">
                @forelse($recentUsers as $user)
                    <div class="d-flex align-items-center mb-4 last-child-mb-0">
                        <div class="position-relative me-3">
                            <img src="{{ $user->avatar_url }}" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            @if($user->role === 'admin')
                                <span class="position-absolute bottom-0 end-0 bg-danger border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                            @elseif($user->role === 'instructor')
                                <span class="position-absolute bottom-0 end-0 bg-info border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                            @else
                                <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold" style="font-size: 0.95rem;">{{ $user->name }}</h6>
                            <small class="text-secondary">{{ $user->email }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'instructor' ? 'bg-info' : 'bg-secondary') }} bg-opacity-10 {{ $user->role === 'admin' ? 'text-danger' : ($user->role === 'instructor' ? 'text-info' : 'text-secondary') }} border-0 px-2 py-1" style="font-size: 0.7rem;">
                                {{ strtoupper($user->role) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <img src="https://illustrations.popsy.co/slate/shaking-hands.svg" style="width: 150px;" class="mb-3 opacity-50">
                        <p class="text-secondary mb-0">Chưa có người dùng mới</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Đơn hàng vừa qua</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Toàn bộ</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="ps-4">Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th class="pe-4">Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-decoration-none text-primary">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="fw-medium">{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-dark">{{ number_format($order->total) }}đ</td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning bg-opacity-10 text-warning border-0">Chờ xử lý</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success bg-opacity-10 text-success border-0">Hoàn thành</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger bg-opacity-10 text-danger border-0">Đã hủy</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="pe-4 text-secondary small">{{ $order->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-secondary">Không có đơn hàng nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Best Selling Courses -->
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Khóa học mới nhất</h5>
            </div>
            <div class="card-body p-4">
                @forelse($recentCourses as $course)
                    <div class="d-flex align-items-start mb-4 last-child-mb-0">
                        <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : 'https://placehold.co/600x400?text=Course' }}" class="rounded-3 me-3" style="width: 80px; height: 50px; object-fit: cover; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="mb-1 fw-bold text-truncate" style="font-size: 0.9rem;">
                                <a href="{{ route('admin.courses.show', $course) }}" class="text-dark text-decoration-none">{{ $course->title }}</a>
                            </h6>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-primary fw-bold small">{{ number_format($course->sale_price ?? $course->price) }}đ</span>
                                <span class="text-secondary small ms-2"><i class="fas fa-layer-group me-1"></i>{{ $course->category->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-secondary py-5">Chưa có khóa học nào</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Prepare data from monthlyStats
        const monthlyData = @json($monthlyStats);
        const labels = monthlyData.map(item => `Tháng ${item.month}/${item.year}`);
        const revenue = monthlyData.map(item => item.revenue);
        const orders = monthlyData.map(item => item.orders);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Doanh thu (VNĐ)',
                        data: revenue,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Số đơn hàng',
                        data: orders,
                        borderColor: '#10b981',
                        backgroundColor: 'transparent',
                        borderWidth: 3,
                        borderDash: [5, 5],
                        tension: 0.4,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 6,
                            padding: 20,
                            font: { family: 'Inter', size: 12, weight: 600 }
                        }
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { family: 'Inter', size: 13, weight: 700 },
                        bodyFont: { family: 'Inter', size: 12 },
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: { drawBorder: false, color: '#f1f5f9' },
                        ticks: {
                            callback: value => value.toLocaleString() + 'đ',
                            color: '#94a3b8',
                            font: { family: 'Inter', size: 11 }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { family: 'Inter', size: 11 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { family: 'Inter', size: 11 }
                        }
                    }
                }
            }
        });
    });
</script>

<style>
    .last-child-mb-0:last-child { margin-bottom: 0 !important; }
    .bg-indigo { background-color: #6366f1 !important; }
    .bg-opacity-10 { --bs-bg-opacity: 0.1; }
</style>
@endpush
