@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="course-header-section py-5 bg-white border-bottom border-light">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-secondary text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('home', ['category' => $course->category_id]) }}" class="text-secondary text-decoration-none">{{ $course->category->name ?? 'Danh mục' }}</a></li>
                <li class="breadcrumb-item active fw-bold text-primary">{{ Str::limit($course->title, 40) }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-lg-8">
                <h1 class="display-5 fw-800 mb-3" style="letter-spacing: -0.02em;">{{ $course->title }}</h1>
                <p class="lead text-secondary mb-4 opacity-75">{{ $course->short_description }}</p>
                
                <div class="d-flex flex-wrap align-items-center gap-4 mb-4">
                    <div class="d-flex align-items-center">
                        <span class="rating text-warning me-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= round($averageRating) ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </span>
                        <span class="fw-bold fs-5">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-secondary small ms-2">({{ $totalReviews }} đánh giá)</span>
                    </div>
                    <div class="d-flex align-items-center text-secondary">
                        <i class="fas fa-user-graduate me-2"></i>
                        <span class="fw-medium">{{ number_format($course->students()->count()) }} học viên</span>
                    </div>
                    <div class="d-flex align-items-center text-secondary">
                        <i class="fas fa-play-circle me-2"></i>
                        <span class="fw-medium">{{ $course->lessons->count() }} bài học</span>
                    </div>
                </div>

                <div class="d-flex align-items-center p-3 bg-light rounded-4" style="width: fit-content;">
                    <img src="{{ $course->instructor->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name ?? 'I') }}" 
                         class="rounded-circle me-3" 
                         style="width: 48px; height: 48px; object-fit: cover; border: 2px solid #fff;">
                    <div>
                        <div class="text-secondary small fw-bold text-uppercase">Giảng viên chuyên môn</div>
                        <div class="fw-bold text-dark">{{ $course->instructor->name ?? 'Chuyên gia' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Thumbnail for Mobile -->
            <div class="mb-5 d-lg-none">
                <div class="card border-0 shadow-sm overflow-hidden rounded-5">
                    <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : 'https://placehold.co/800x450?text='.urlencode($course->title) }}" class="img-fluid" alt="{{ $course->title }}">
                </div>
            </div>

            <!-- Course Info Tabs-like Section -->
            <div class="mb-5">
                <h4 class="fw-800 mb-4 d-flex align-items-center">
                    <i class="fas fa-info-circle text-primary me-2"></i>Mô tả khóa học
                </h4>
                <div class="course-description p-4 bg-white rounded-5 shadow-sm border border-light">
                    {!! nl2br(e($course->content)) !!}
                </div>
            </div>

            <!-- Curriculum -->
            <div class="mb-5">
                <h4 class="fw-800 mb-4 d-flex align-items-center">
                    <i class="fas fa-list-ul text-primary me-2"></i>Nội dung học tập
                    <span class="ms-auto badge bg-indigo-soft text-primary rounded-pill small" style="background: rgba(99, 102, 241, 0.1);">{{ $course->lessons->count() }} bài học</span>
                </h4>
                <div class="curriculum-list bg-white rounded-5 shadow-sm border border-light overflow-hidden">
                    @forelse($course->lessons as $index => $lesson)
                        <div class="curriculum-item d-flex align-items-center p-4 border-bottom last-child-no-border hover-bg-light transition-all">
                            <div class="lesson-number bg-light rounded-circle d-flex align-items-center justify-content-center fw-bold me-4" style="width: 40px; height: 40px; min-width: 40px; font-size: 0.9rem;">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-600">{{ $lesson->title }}</h6>
                                @if($lesson->is_preview)
                                    <span class="badge bg-success bg-opacity-10 text-success border-0 px-2 py-1 mt-1" style="font-size: 0.65rem;">
                                        <i class="fas fa-eye me-1"></i>Xem trước
                                    </span>
                                @endif
                            </div>
                            <div class="text-secondary small ms-3">
                                @if($lesson->duration)
                                    <i class="far fa-clock me-1"></i>{{ $lesson->duration }} phút
                                @else
                                    <i class="fas fa-video me-1"></i>Video
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center text-secondary">Chưa có bài học nào được đăng tải.</div>
                    @endforelse
                </div>
            </div>

            <!-- Reviews -->
            <div class="mb-5">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="fw-800 mb-0"><i class="fas fa-comment-alt text-primary me-2"></i>Phản hồi của học viên</h4>
                    @auth
                        @if($hasPurchased && !$course->reviews()->where('user_id', auth()->id())->exists())
                            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                <i class="fas fa-edit me-2"></i>Gửi đánh giá
                            </button>
                        @endif
                    @endauth
                </div>
                
                <div class="bg-white rounded-5 shadow-sm border border-light p-4">
                    @forelse($course->reviews as $review)
                        <div class="review-item d-flex mb-4">
                            <img src="{{ $review->user->avatar_url }}" class="rounded-4 me-3" style="width: 56px; height: 56px; object-fit: cover; border: 2px solid #f1f5f9;">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $review->user->name }}</h6>
                                        <div class="rating text-warning mb-1" style="font-size: 0.8rem;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="text-secondary small">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-secondary mb-0" style="font-size: 0.95rem;">{{ $review->comment }}</p>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr class="opacity-25 my-4">
                        @endif
                    @empty
                        <div class="text-center py-5">
                            <img src="https://illustrations.popsy.co/slate/shaking-hands.svg" style="width: 150px;" class="mb-3 opacity-50">
                            <p class="text-secondary mb-0">Chưa có đánh giá nào cho khóa học này.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-2xl sticky-top overflow-hidden" style="top: 100px; border-radius: 32px;">
                <div class="d-none d-lg-block position-relative">
                    <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : 'https://placehold.co/800x450?text='.urlencode($course->title) }}" class="img-fluid" alt="{{ $course->title }}">
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 backdrop-blur" style="backdrop-filter: blur(10px);">
                            <i class="fas fa-play text-white fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 p-xl-5">
                    <!-- Price -->
                    <div class="mb-4">
                        @if($course->sale_price)
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="h2 fw-800 text-primary mb-0">{{ number_format($course->sale_price) }}đ</span>
                                <span class="text-secondary text-decoration-line-through small">{{ number_format($course->price) }}đ</span>
                            </div>
                            <span class="badge bg-danger rounded-pill px-3 py-2" style="font-size: 0.75rem;">Tiết kiệm {{ round((($course->price - $course->sale_price) / $course->price) * 100) }}% ngay hôm nay</span>
                        @else
                            <h2 class="fw-800 text-primary mb-0">{{ number_format($course->price) }}đ</h2>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-3 mb-5">
                        @auth
                            @if($hasPurchased)
                                <a href="{{ route('learn.course', $course->slug) }}" class="btn btn-success btn-lg rounded-4 py-3 shadow-lg">
                                    <i class="fas fa-play me-2"></i>Bắt đầu học ngay
                                </a>
                            @else
                                @if(auth()->user()->hasInCart($course))
                                    <a href="{{ route('cart.index') }}" class="btn btn-light btn-lg rounded-4 py-3 border-light">
                                        <i class="fas fa-shopping-cart me-2"></i>Đã trong giỏ hàng
                                    </a>
                                @else
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <button type="submit" class="btn btn-primary btn-lg rounded-4 py-3 shadow-lg">
                                            <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ hàng
                                        </button>
                                    </form>
                                @endif
                                <button class="btn btn-outline-primary btn-lg rounded-4 py-3">Mua ngay</button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg rounded-4 py-3 shadow-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập để đăng ký
                            </a>
                        @endauth
                    </div>
                    
                    <!-- Course Includes -->
                    <div class="course-includes">
                        <h6 class="fw-bold text-dark mb-4">Khóa học này bao gồm:</h6>
                        <ul class="list-unstyled d-grid gap-3 mb-0">
                            <li class="d-flex align-items-center">
                                <div class="bg-indigo-soft p-2 rounded-3 me-3 text-primary" style="background: rgba(99, 102, 241, 0.1);">
                                    <i class="fas fa-infinity fs-6"></i>
                                </div>
                                <span class="text-secondary small fw-medium">Truy cập trọn đời</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="bg-indigo-soft p-2 rounded-3 me-3 text-primary" style="background: rgba(99, 102, 241, 0.1);">
                                    <i class="fas fa-mobile-alt fs-6"></i>
                                </div>
                                <span class="text-secondary small fw-medium">Học trên mọi thiết bị</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="bg-indigo-soft p-2 rounded-3 me-3 text-primary" style="background: rgba(99, 102, 241, 0.1);">
                                    <i class="fas fa-certificate fs-6"></i>
                                </div>
                                <span class="text-secondary small fw-medium">Chứng chỉ hoàn thành</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="bg-indigo-soft p-2 rounded-3 me-3 text-primary" style="background: rgba(99, 102, 241, 0.1);">
                                    <i class="fas fa-question-circle fs-6"></i>
                                </div>
                                <span class="text-secondary small fw-medium">Hỗ trợ trực tiếp 1-1</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Courses -->
    @if($relatedCourses->count() > 0)
    <div class="mt-5 pt-5">
        <h3 class="fw-800 mb-5">Khóa học học viên cũng xem</h3>
        <div class="row g-4">
            @foreach($relatedCourses as $related)
                <div class="col-md-6 col-lg-3">
                    <article class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                        <a href="{{ route('courses.detail', $related->slug) }}" class="overflow-hidden" style="height: 150px;">
                            <img src="{{ $related->thumbnail ? asset('storage/' . $related->thumbnail) : 'https://placehold.co/400x250' }}" class="card-img-top h-100 w-100 object-fit-cover" alt="{{ $related->title }}">
                        </a>
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3"><a href="{{ route('courses.detail', $related->slug) }}" class="text-dark text-decoration-none hover-text-primary">{{ Str::limit($related->title, 45) }}</a></h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-800 text-primary">{{ number_format($related->sale_price ?? $related->price) }}đ</span>
                                <span class="text-secondary small"><i class="fas fa-users me-1"></i>{{ $related->students()->count() }}</span>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Review Modal -->
@auth
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px;">
            <form action="{{ route('reviews.store', $course->id) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="fw-800 mb-0">Chia sẻ cảm nhận của bạn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4 text-center">
                        <label class="form-label d-block text-secondary small fw-bold text-uppercase mb-3">Bạn đánh giá khóa học này mấy sao?</label>
                        <div class="rating-input d-flex justify-content-center flex-row-reverse gap-2">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="btn-check" required>
                                <label for="star{{ $i }}" class="cursor-pointer">
                                    <i class="fas fa-star fa-2x star-icon"></i>
                                </label>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold text-uppercase">Nội dung đánh giá</label>
                        <textarea name="comment" class="form-control bg-light border-0 rounded-4 p-3" rows="4" required minlength="10" placeholder="Hãy giúp các học viên khác hiểu hơn về khóa học này nhé..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-modal="Hủy" data-bs-dismiss="modal">Để sau</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-lg">Gửi đánh giá ngay</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth

<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
    .course-header-section { margin-top: -1px; }
    .last-child-no-border:last-child { border-bottom: none !important; }
    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .star-icon { color: #e2e8f0; transition: color 0.2s ease; cursor: pointer; }
    .rating-input input:checked ~ label .star-icon,
    .rating-input label:hover .star-icon,
    .rating-input label:hover ~ label .star-icon {
        color: #f59e0b;
    }
    .object-fit-cover { object-fit: cover; }
    .transition-all { transition: all 0.3s ease; }
    .hover-bg-light:hover { background-color: #f8fafc; }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection
