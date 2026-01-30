@extends('layouts.instructor')

@section('title', 'Dashboard - Giảng viên')
@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome Message -->
<div class="alert alert-primary mb-4">
    <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Xin chào, {{ auth()->user()->name }}!</h5>
    <p class="mb-0 mt-1">Đây là trang quản lý khóa học của bạn.</p>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase mb-1">Khóa học của tôi</div>
                        <div class="stat-number">{{ number_format($stats['total_courses']) }}</div>
                    </div>
                    <div class="stat-icon bg-gradient-primary">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase mb-1">Tổng học viên</div>
                        <div class="stat-number">{{ number_format($stats['total_students']) }}</div>
                    </div>
                    <div class="stat-icon bg-gradient-success">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase mb-1">Đánh giá</div>
                        <div class="stat-number">{{ number_format($stats['total_reviews']) }}</div>
                    </div>
                    <div class="stat-icon bg-gradient-warning">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Courses -->
<div class="row">
    <div class="col-lg-8">
        <div class="card table-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Khóa học của tôi</h6>
                <a href="{{ route('instructor.courses.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>Thêm khóa học
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Khóa học</th>
                                <th>Học viên</th>
                                <th>Đánh giá</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courses as $course)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($course->thumbnail)
                                                <img src="{{ asset('storage/' . $course->thumbnail) }}" class="rounded me-2" style="width: 50px; height: 35px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ Str::limit($course->title, 35) }}</strong>
                                                <br><small class="text-muted">{{ $course->lessons->count() }} bài học</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $course->students->count() }}</td>
                                    <td>
                                        <span class="text-warning">
                                            <i class="fas fa-star"></i>
                                            {{ number_format($course->reviews->avg('rating'), 1) }}
                                        </span>
                                        <small class="text-muted">({{ $course->reviews->count() }})</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $course->status === 'published' ? 'success' : 'secondary' }}">
                                            {{ $course->status === 'published' ? 'Đã xuất bản' : 'Nháp' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Bạn chưa có khóa học nào. 
                                        <a href="{{ route('instructor.courses.create') }}">Tạo khóa học đầu tiên</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Recent Reviews -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">Đánh giá gần đây</h6>
            </div>
            <div class="card-body">
                @forelse($recentReviews as $review)
                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex align-items-center mb-2">
                            <img src="{{ $review->user->avatar_url }}" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;">
                            <div>
                                <strong>{{ $review->user->name }}</strong>
                                <br>
                                <span class="text-warning small">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </span>
                            </div>
                        </div>
                        <small class="text-muted">{{ Str::limit($review->course->title, 30) }}</small>
                        <p class="mb-0 small">{{ Str::limit($review->comment, 80) }}</p>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">Chưa có đánh giá</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
