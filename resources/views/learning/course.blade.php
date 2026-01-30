@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="learning-hub-header py-5 bg-white border-bottom border-light">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('profile.index') }}" class="text-secondary text-decoration-none">Khóa học của tôi</a></li>
                        <li class="breadcrumb-item active fw-bold text-primary">{{ Str::limit($course->title, 40) }}</li>
                    </ol>
                </nav>
                <h1 class="display-6 fw-800 text-dark mb-3">{{ $course->title }}</h1>
                <p class="text-secondary mb-4 opacity-75">{{ $course->short_description }}</p>
                
                <div class="d-flex flex-wrap align-items-center gap-4">
                    <div class="d-flex align-items-center">
                        <img src="{{ $course->instructor->avatar_url }}" class="rounded-circle me-3 border border-2 border-white shadow-sm" style="width: 48px; height: 48px; object-fit: cover;">
                        <div>
                            <div class="text-secondary small fw-bold text-uppercase" style="font-size: 0.65rem;">Giảng viên</div>
                            <div class="fw-bold text-dark">{{ $course->instructor->name }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                @if($progressPercent >= 100)
                    <div class="d-inline-flex align-items-center bg-success bg-opacity-10 text-success p-3 rounded-4 border border-success border-opacity-10">
                        <i class="fas fa-certificate fa-2x me-3"></i>
                        <div class="text-start">
                            <div class="fw-800 small text-uppercase">Hoàn thành</div>
                            <div class="small opacity-75">Tải chứng chỉ ngay</div>
                        </div>
                    </div>
                @elseif($currentLesson)
                    <a href="{{ route('learn.lesson', [$course->slug, $currentLesson->slug]) }}" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg">
                        <i class="fas fa-play me-2"></i> Tiếp tục bài học
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-8">
            <!-- Progress Section -->
            <div class="card border-0 shadow-sm rounded-5 mb-5 overflow-hidden">
                <div class="card-body p-4 p-xl-5">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h4 class="fw-800 mb-0">Tiến độ khóa học</h4>
                        <span class="badge bg-indigo-soft text-primary px-3 py-2 rounded-pill fw-bold" style="background: rgba(99, 102, 241, 0.1);">
                            {{ $completedCount }}/{{ $course->lessons->count() }} bài học
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                             <span class="small fw-bold text-secondary">{{ number_format($progressPercent, 0) }}% Hoàn tất</span>
                        </div>
                        <div class="progress rounded-pill shadow-inner" style="height: 12px; background: #f1f5f9;">
                            <div class="progress-bar bg-success rounded-pill shadow-sm" role="progressbar" style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>

                    @if($progressPercent >= 100)
                        <div class="alert bg-success bg-opacity-10 text-success border-0 p-4 rounded-4 mb-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-trophy fa-2x me-3"></i>
                                <div>
                                    <h6 class="fw-800 mb-1">Chúc mừng bạn!</h6>
                                    <p class="small mb-0">Bạn đã hoàn thành xuất sắc tất cả các bài học trong khóa học này.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detailed Stats -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4">
                        <div class="text-primary opacity-50 mb-3"><i class="far fa-clock fa-2x"></i></div>
                        <div class="fw-800 fs-4 text-dark">{{ $totalDuration }}</div>
                        <div class="text-secondary small fw-medium">Tổng số phút học</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4">
                        <div class="text-warning opacity-50 mb-3"><i class="far fa-star fa-2x"></i></div>
                        <div class="fw-800 fs-4 text-dark">{{ $course->reviews->count() }}</div>
                        <div class="text-secondary small fw-medium">Đánh giá từ bạn</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4">
                        <div class="text-success opacity-50 mb-3"><i class="far fa-check-circle fa-2x"></i></div>
                        <div class="fw-800 fs-4 text-dark">{{ $completedCount }}</div>
                        <div class="text-secondary small fw-medium">Bài học đã xong</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Lesson List -->
            <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-800 mb-0 d-flex align-items-center">
                        <i class="fas fa-list-ul text-primary me-2"></i> Lộ trình học tập
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="list-group list-group-flush rounded-4 overflow-hidden border-0">
                        @foreach($course->lessons as $index => $lesson)
                            @php
                                $isCompleted = isset($progress[$lesson->id]) && $progress[$lesson->id];
                            @endphp
                            <a href="{{ route('learn.lesson', [$course->slug, $lesson->slug]) }}" 
                               class="list-group-item list-group-item-action d-flex align-items-center py-3 border-0 rounded-4 mb-1 transition-all {{ $isCompleted ? 'bg-success bg-opacity-5' : '' }}">
                                <div class="lesson-num-marker me-3">
                                    @if($isCompleted)
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 28px; height: 28px;">
                                            <i class="fas fa-check small" style="font-size: 0.75rem;"></i>
                                        </div>
                                    @else
                                        <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                            {{ $index + 1 }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <div class="fw-bold text-dark lh-sm text-truncate {{ $isCompleted ? 'text-success' : '' }}">
                                        {{ $lesson->title }}
                                    </div>
                                    <div class="text-secondary small opacity-75" style="font-size: 0.7rem;">
                                        <i class="far fa-clock me-1"></i> {{ $lesson->duration ?? 0 }} phút
                                        @if($lesson->video_url)
                                            <i class="fas fa-play-circle ms-2 text-primary"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="ms-2">
                                    <i class="fas fa-chevron-right text-light" style="font-size: 0.8rem;"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05); }
    .transition-all { transition: all 0.3s ease; }
    .list-group-item:hover { background-color: #f8fafc; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .object-fit-cover { object-fit: cover; }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection
