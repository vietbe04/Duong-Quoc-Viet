@extends('layouts.app')

@section('title', 'Trang cá nhân')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 24px;">
                <div class="card-body p-4 text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <img src="{{ $user->avatar_url }}" class="rounded-circle shadow-sm border border-light p-1" style="width: 110px; height: 110px; object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border border-4 border-white" style="width: 32px; height: 32px; font-size: 0.8rem;">
                            <i class="fas fa-camera"></i>
                        </span>
                    </div>
                    <h5 class="fw-800 mb-1 text-dark">{{ $user->name }}</h5>
                    <p class="text-secondary small mb-3">{{ $user->email }}</p>
                    <div class="badge bg-indigo-soft text-primary px-3 py-2 rounded-pill fw-bold" style="background: rgba(99, 102, 241, 0.1);">
                        {{ ucfirst($user->role) }}
                    </div>
                </div>
                <div class="list-group list-group-flush border-top border-light">
                    <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4 {{ request()->routeIs('profile.index') ? 'active bg-primary border-0' : 'text-secondary bg-transparent' }}">
                        <i class="fas fa-id-card me-3 opacity-75"></i> Thông tin cá nhân
                    </a>
                    <a href="{{ route('profile.orders') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4 {{ request()->routeIs('profile.orders') ? 'active bg-primary border-0' : 'text-secondary bg-transparent' }}">
                        <i class="fas fa-shopping-bag me-3 opacity-75"></i> Lịch sử đơn hàng
                    </a>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 24px;">
                <div class="card-body p-4">
                    <h6 class="text-secondary small fw-bold text-uppercase mb-4">Thống kê học tập</h6>
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom border-light">
                        <div class="text-secondary small">Khóa học đã tham gia</div>
                        <div class="fw-bold fs-5 text-dark">{{ $purchasedCourses->count() }}</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-secondary small">Cấp độ tài khoản</div>
                        <div class="fw-bold text-primary">Standard</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-8 col-xl-9">
            <!-- Update Profile -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 24px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-800 mb-0 d-flex align-items-center">
                        <i class="fas fa-edit text-primary me-3 fs-4"></i> Cập nhật hồ sơ
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold text-uppercase">Họ và tên</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 px-3 text-secondary"><i class="fas fa-user-circle"></i></span>
                                    <input type="text" name="name" class="form-control bg-light border-0 py-2 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold text-uppercase">Địa chỉ Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 px-3 text-secondary"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-0 py-2 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold text-uppercase">Số điện thoại</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 px-3 text-secondary"><i class="fas fa-phone"></i></span>
                                    <input type="text" name="phone" class="form-control bg-light border-0 py-2 @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold text-uppercase">Ảnh đại diện mới</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 px-3 text-secondary"><i class="fas fa-image"></i></span>
                                    <input type="file" name="avatar" class="form-control bg-light border-0 py-2 @error('avatar') is-invalid @enderror" accept="image/*">
                                </div>
                                @error('avatar')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-5">
                            <button type="submit" class="btn btn-primary rounded-4 px-5 py-2 shadow-lg">
                                <i class="fas fa-save me-2"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Change Password -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 24px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-800 mb-0 d-flex align-items-center">
                        <i class="fas fa-shield-alt text-warning me-3 fs-4"></i> Bảo mật tài khoản
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold text-uppercase">Mật khẩu hiện tại</label>
                                <input type="password" name="current_password" class="form-control bg-light border-0 py-2 @error('current_password') is-invalid @enderror" required>
                                @error('current_password')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold text-uppercase">Mật khẩu mới</label>
                                <input type="password" name="password" class="form-control bg-light border-0 py-2 @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold text-uppercase">Xác nhận</label>
                                <input type="password" name="password_confirmation" class="form-control bg-light border-0 py-2" required>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning text-white rounded-4 px-5 py-2">
                                <i class="fas fa-key me-2"></i> Đổi mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Purchased Courses -->
            <div class="card border-0 shadow-sm" style="border-radius: 24px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-800 mb-0 d-flex align-items-center">
                        <i class="fas fa-graduation-cap text-success me-3 fs-4"></i> Khóa học đang học
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($purchasedCourses->count() > 0)
                        <div class="row g-4">
                            @foreach($purchasedCourses as $course)
                                <div class="col-md-6 col-xl-4">
                                    <div class="card h-100 border-0 bg-light rounded-4 overflow-hidden shadow-sm hover-translate-y transition-all">
                                        <div class="position-relative" style="height: 120px;">
                                            <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : 'https://placehold.co/400x250' }}" class="h-100 w-100 object-fit-cover shadow-sm">
                                            <div class="position-absolute bottom-0 end-0 m-2">
                                                <span class="badge bg-white text-dark small">{{ $course->progress_percent }}%</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <h6 class="fw-bold text-dark lh-base mb-3" style="min-height: 2.8rem; font-size: 0.95rem;">{{ Str::limit($course->title, 45) }}</h6>
                                            <div class="progress mb-2 rounded-pill" style="height: 6px;">
                                                <div class="progress-bar bg-success rounded-pill" style="width: {{ $course->progress_percent }}%"></div>
                                            </div>
                                            <div class="d-grid mt-3">
                                                <a href="{{ route('learn.course', $course->slug) }}" class="btn btn-white btn-sm fw-bold border-light bg-white rounded-3 py-2 shadow-sm text-primary">
                                                    Tiếp tục học
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="https://illustrations.popsy.co/slate/reading-a-book.svg" style="width: 180px;" class="mb-4 opacity-50">
                            <h6 class="text-secondary fw-bold mb-3">Bạn chưa tham gia khóa học nào</h6>
                            <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-5 py-2">Khám phá ngay</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .object-fit-cover { object-fit: cover; }
    .hover-translate-y:hover { transform: translateY(-3px); }
    .transition-all { transition: all 0.3s ease; }
    .input-group-text { border-radius: 12px 0 0 12px; }
    .form-control { border-radius: 0 12px 12px 0; }
    .form-control:only-child { border-radius: 12px; }
    .btn-white:hover { background-color: #f1f5f9 !important; border-color: #cbd5e1 !important; }
</style>
@endsection
