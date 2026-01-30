@extends('layouts.admin')

@section('title', $course->title)
@section('page-title', 'Chi tiết khóa học')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Course Info -->
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Thông tin khóa học</h5>
                <div>
                    <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-1"></i>Sửa
                    </a>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" class="img-fluid rounded mb-3" style="max-height: 300px;">
                @endif
                
                <h4>{{ $course->title }}</h4>
                <p class="text-muted">{{ $course->short_description }}</p>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Danh mục:</strong> {{ $course->category->name ?? '-' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Giảng viên:</strong> {{ $course->instructor->name ?? '-' }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Giá:</strong> 
                        @if($course->sale_price)
                            <span class="text-decoration-line-through text-muted">{{ number_format($course->price) }}đ</span>
                            <span class="text-primary fw-bold">{{ number_format($course->sale_price) }}đ</span>
                        @else
                            <span class="text-primary fw-bold">{{ number_format($course->price) }}đ</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong>Trạng thái:</strong> 
                        <span class="badge bg-{{ $course->status === 'published' ? 'success' : 'secondary' }}">
                            {{ $course->status === 'published' ? 'Đã xuất bản' : 'Nháp' }}
                        </span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Nội dung chi tiết:</strong>
                    <div class="mt-2 p-3 bg-light rounded">
                        {!! nl2br(e($course->content)) !!}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lessons -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách bài học ({{ $course->lessons->count() }})</h5>
                <a href="{{ route('admin.courses.lessons.create', $course) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>Thêm bài học
                </a>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($course->lessons as $index => $lesson)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-secondary me-2">{{ $lesson->order }}</span>
                            {{ $lesson->title }}
                            @if($lesson->is_preview)
                                <span class="badge bg-info ms-1">Preview</span>
                            @endif
                            @if($lesson->status !== 'active')
                                <span class="badge bg-warning ms-1">Ẩn</span>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-muted text-center">Chưa có bài học</li>
                @endforelse
            </ul>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Stats -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thống kê</h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Số bài học</span>
                    <strong>{{ $course->lessons->count() }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Học viên</span>
                    <strong>{{ $course->students->count() }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Đánh giá</span>
                    <strong>{{ $course->reviews->count() }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Ngày tạo</span>
                    <strong>{{ $course->created_at->format('d/m/Y') }}</strong>
                </li>
            </ul>
        </div>
        
        <!-- Recent Students -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Học viên gần đây</h5>
            </div>
            <div class="card-body">
                @forelse($course->students->take(5) as $student)
                    <div class="d-flex align-items-center mb-2">
                        <img src="{{ $student->avatar_url }}" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;">
                        <div>
                            <div>{{ $student->name }}</div>
                            <small class="text-muted">{{ $student->pivot->enrolled_at ? \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('d/m/Y') : '-' }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Chưa có học viên</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
