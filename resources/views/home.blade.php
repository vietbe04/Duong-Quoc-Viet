@extends('layouts.app')

@section('title', 'Học tập không giới hạn')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5 mb-5 overflow-hidden position-relative" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); min-height: 500px; display: flex; align-items: center;">
    <div class="position-absolute w-100 h-100 top-0 start-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-lg-start">
                <span class="badge bg-indigo-soft text-primary px-3 py-2 mb-3 rounded-pill fw-bold" style="background: rgba(99, 102, 241, 0.15); color: #818cf8 !important;">
                    <i class="fas fa-rocket me-2"></i>Nâng tầm sự nghiệp của bạn
                </span>
                <h1 class="display-3 fw-800 text-white mb-4" style="line-height: 1.1; letter-spacing: -0.04em;">
                    Học tập <span class="text-primary" style="color: #818cf8 !important;">thông minh</span><br>theo phong cách của bạn
                </h1>
                <p class="lead text-secondary mb-5 opacity-75" style="font-size: 1.25rem;">Khám phá hàng nghìn khóa học được giảng dạy bởi các chuyên gia hàng đầu thế giới. Bắt đầu ngay hôm nay để trở thành phiên bản tốt hơn.</p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                    <a href="#courses" class="btn btn-primary btn-lg px-5 py-3 rounded-4 shadow-lg">
                        Bắt đầu học ngay <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-lg px-5 py-3 rounded-4 border-opacity-25 hover-bg-light">
                        Xem lộ trình <i class="fas fa-map-signs ms-2"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="position-relative">
                    <div class="glass-card p-4 rounded-5 shadow-2xl position-relative z-2" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1);">
                        <img src="https://illustrations.popsy.co/white/student-going-to-school.svg" class="img-fluid" alt="Hero Image">
                    </div>
                    <div class="position-absolute top-0 end-0 translate-middle mt-5 me-n5 bg-primary rounded-circle opacity-20" style="width: 300px; height: 300px; filter: blur(80px);"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Courses Section -->
<section id="courses" class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Sidebar Filter -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px; border-radius: 24px;">
                    <div class="card-body p-4">
                        <h5 class="fw-800 mb-4 d-flex align-items-center">
                            <i class="fas fa-sliders-h text-primary me-2"></i>Bộ lọc
                        </h5>
                        
                        <form action="{{ route('home') }}" method="GET">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            
                            <!-- Category Filter -->
                            <div class="mb-5">
                                <label class="text-secondary small fw-bold text-uppercase mb-3 d-block">Danh mục</label>
                                <div class="nav flex-column gap-2">
                                    <a href="{{ route('home', request()->except('category')) }}" 
                                       class="nav-link px-3 py-2 rounded-3 {{ !request('category') ? 'bg-primary text-white' : 'text-secondary hover-bg-light' }}" 
                                       style="{{ !request('category') ? 'color: white !important;' : '' }}">
                                        Tất cả khóa học
                                    </a>
                                    @foreach($categories as $category)
                                        <a href="{{ route('home', array_merge(request()->except('category'), ['category' => $category->id])) }}" 
                                           class="nav-link px-3 py-2 rounded-3 {{ request('category') == $category->id ? 'bg-primary text-white' : 'text-secondary hover-bg-light' }}"
                                           style="{{ request('category') == $category->id ? 'color: white !important;' : '' }}">
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Sort -->
                            <div>
                                <label class="text-secondary small fw-bold text-uppercase mb-3 d-block">Sắp xếp theo</label>
                                <select name="sort" class="form-select border-light bg-light rounded-3 py-2" onchange="this.form.submit()">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Course List -->
            <div class="col-lg-9">
                @if(request('search'))
                    <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-3 rounded-4 shadow-sm border border-light">
                        <div class="text-secondary">
                            <i class="fas fa-search me-2"></i>Kết quả cho: <span class="fw-bold text-dark">"{{ request('search') }}"</span>
                        </div>
                        <a href="{{ route('home') }}" class="btn btn-sm btn-light text-primary rounded-pill px-3">Xóa lọc</a>
                    </div>
                @endif
                
                @if($courses->count() > 0)
                    <div class="row g-4">
                        @foreach($courses as $course)
                            <div class="col-md-6 col-xl-4">
                                <article class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                                    <div class="position-relative overflow-hidden" style="height: 180px;">
                                        <a href="{{ route('courses.detail', $course->slug) }}">
                                            <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : 'https://placehold.co/800x500?text=Course' }}" class="card-img-top h-100 w-100 object-fit-cover transition-all" style="transition: transform 0.5s;" alt="{{ $course->title }}">
                                        </a>
                                        <div class="position-absolute top-0 start-0 m-3">
                                            <span class="badge bg-white text-dark shadow-sm">{{ $course->category->name ?? 'Course' }}</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-4 d-flex flex-column">
                                        <h5 class="card-title fw-700 mb-2" style="font-size: 1.1rem; line-height: 1.4;">
                                            <a href="{{ route('courses.detail', $course->slug) }}" class="text-decoration-none text-dark hover-text-primary">
                                                {{ Str::limit($course->title, 55) }}
                                            </a>
                                        </h5>
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="{{ $course->instructor->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name ?? 'I') }}" class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">
                                            <span class="text-secondary small">{{ $course->instructor->name ?? 'Chuyên gia' }}</span>
                                        </div>
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex flex-column">
                                                    @if($course->sale_price)
                                                        <span class="text-secondary small text-decoration-line-through">{{ number_format($course->price) }}đ</span>
                                                        <span class="fw-800 text-primary fs-5">{{ number_format($course->sale_price) }}đ</span>
                                                    @else
                                                        <span class="fw-800 text-primary fs-5">{{ number_format($course->price) }}đ</span>
                                                    @endif
                                                </div>
                                                <div class="text-secondary small">
                                                    <i class="far fa-clock me-1"></i>{{ $course->lessons->count() }} bài
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-5">
                        {{ $courses->links() }}
                    </div>
                @else
                    <div class="text-center py-5 bg-white rounded-5 shadow-sm">
                        <img src="https://illustrations.popsy.co/slate/abstract-art-4.svg" style="width: 250px;" class="mb-4 opacity-75">
                        <h4 class="fw-bold">Không tìm thấy khóa học</h4>
                        <p class="text-secondary mb-4">Chúng tôi không tìm thấy kết quả phù hợp với tiêu chí của bạn.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2 rounded-pill">Quay lại trang chủ</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5" style="background-color: #f1f5f9;">
    <div class="container py-4">
        <div class="row text-center mb-5">
            <div class="col-lg-6 mx-auto">
                <h2 class="fw-800 mb-3">Tại sao hàng triệu học viên chọn E-LEARN?</h2>
                <p class="text-secondary">Chúng tôi cung cấp lộ trình học tập tối ưu cùng cộng đồng học viên năng động trên toàn quốc.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 p-4 h-100 text-center shadow-sm" style="border-radius: 28px;">
                    <div class="bg-indigo-soft text-primary rounded-4 d-inline-flex align-items-center justify-content-center mb-4 mx-auto" style="width: 70px; height: 70px; background: rgba(99, 102, 241, 0.1);">
                        <i class="fas fa-video fa-2x"></i>
                    </div>
                    <h5 class="fw-700">Video 4K chất lượng</h5>
                    <p class="text-secondary small mb-0">Hệ thống bài giảng video sắc nét, âm thanh sống động giúp bạn tiếp thu kiến thức tốt nhất.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 p-4 h-100 text-center shadow-sm" style="border-radius: 28px;">
                    <div class="bg-indigo-soft text-success rounded-4 d-inline-flex align-items-center justify-content-center mb-4 mx-auto" style="width: 70px; height: 70px; background: rgba(16, 185, 129, 0.1);">
                        <i class="fas fa-infinity fa-2x"></i>
                    </div>
                    <h5 class="fw-700">Truy cập trọn đời</h5>
                    <p class="text-secondary small mb-0">Chỉ thanh toán một lần và bạn sẽ sở hữu khóa học mãi mãi, học lại bất cứ khi nào cần.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 p-4 h-100 text-center shadow-sm" style="border-radius: 28px;">
                    <div class="bg-indigo-soft text-warning rounded-4 d-inline-flex align-items-center justify-content-center mb-4 mx-auto" style="width: 70px; height: 70px; background: rgba(245, 158, 11, 0.1);">
                        <i class="fas fa-headset fa-2x"></i>
                    </div>
                    <h5 class="fw-700">Hỗ trợ 24/7</h5>
                    <p class="text-secondary small mb-0">Đội ngũ trợ giảng luôn sẵn sàng giải đáp thắc mắc của bạn trong suốt quá trình học tập.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .hover-bg-light:hover { background-color: rgba(255,255,255,0.1) !important; color: white !important; }
    .hover-text-primary:hover { color: var(--primary) !important; }
    .object-fit-cover { object-fit: cover; }
    .gray-scale { filter: grayscale(1); }
    .hover-bg-light:hover { background-color: #f1f5f9; }
</style>
@endsection