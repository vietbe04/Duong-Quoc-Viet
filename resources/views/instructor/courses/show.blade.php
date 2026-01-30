@extends('layouts.instructor')

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
                    <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-1"></i>Sửa
                    </a>
                    <a href="{{ route('instructor.courses.index') }}" class="btn btn-sm btn-outline-secondary">
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
                        <strong>Trạng thái:</strong> 
                        <span class="badge bg-{{ $course->status === 'published' ? 'success' : 'secondary' }}">
                            {{ $course->status === 'published' ? 'Đã xuất bản' : 'Nháp' }}
                        </span>
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
                        <strong>Ngày tạo:</strong> {{ $course->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lessons -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách bài học ({{ $course->lessons->count() }})</h5>
                <a href="{{ route('instructor.courses.lessons.create', $course) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>Thêm bài học
                </a>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($course->lessons as $lesson)
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
                        <a href="{{ route('instructor.courses.lessons.edit', [$course, $lesson]) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
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
                    <strong>
                        <span class="text-warning"><i class="fas fa-star"></i></span>
                        {{ number_format($course->reviews->avg('rating'), 1) }}
                        ({{ $course->reviews->count() }})
                    </strong>
                </li>
            </ul>
        </div>
        
        <!-- Recent Reviews -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Đánh giá gần đây</h5>
            </div>
            <div class="card-body">
                @forelse($course->reviews->take(5) as $review)
                    <div class="mb-3 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex align-items-center mb-1">
                            <img src="{{ $review->user->avatar_url }}" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                            <strong class="small">{{ $review->user->name }}</strong>
                        </div>
                        <div class="text-warning small mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="small mb-0">{{ Str::limit($review->comment, 80) }}</p>
                    </div>
                @empty
                    <p class="text-muted mb-0 text-center">Chưa có đánh giá</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
