<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Learning') - Nền tảng học trực tuyến</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --secondary: #64748b;
            --dark: #0f172a;
            --light: #f8fafc;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            --nav-height: 72px;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
            padding-top: var(--nav-height);
        }

        /* Navbar Modern Styles */
        .header-nav {
            height: var(--nav-height);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: -0.025em;
            color: var(--dark) !important;
            display: flex;
            align-items: center;
        }

        .navbar-brand i {
            color: var(--primary);
            background: linear-gradient(135deg, var(--primary), #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            font-weight: 500;
            color: #475569 !important;
            padding: 0.5rem 1rem !important;
            transition: color 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary) !important;
        }

        /* Search Bar */
        .search-container {
            position: relative;
            max-width: 400px;
            width: 100%;
        }

        .search-container .form-control {
            border-radius: 99px;
            padding-left: 2.5rem;
            background: #f1f5f9;
            border: 1px solid transparent;
            font-size: 0.9rem;
            height: 42px;
            transition: all 0.2s ease;
        }

        .search-container .form-control:focus {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        /* Buttons */
        .btn {
            border-radius: 12px;
            padding: 0.6rem 1.4rem;
            font-weight: 600;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }

        .btn-light {
            background: #fff;
            border: 1px solid #e2e8f0;
            color: var(--dark);
        }

        .btn-light:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* Cards */
        .card {
            border-radius: 20px;
            border: 1px solid rgba(226, 232, 240, 0.7);
            background: #fff;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Badges */
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 600;
            border-radius: 8px;
        }

        /* User Dropdown */
        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .dropdown-menu {
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            padding: 0.5rem;
            margin-top: 10px !important;
        }

        .dropdown-item {
            border-radius: 10px;
            padding: 0.6rem 1rem;
            color: #475569;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: var(--primary);
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 12px;
            opacity: 0.6;
            font-size: 0.95rem;
        }

        /* Cart Badge */
        .cart-icon-wrapper {
            position: relative;
            padding: 0.5rem;
            color: #475569;
            font-size: 1.2rem;
            transition: color 0.2s;
        }

        .cart-icon-wrapper:hover {
            color: var(--primary);
        }

        .cart-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--danger);
            color: white;
            font-size: 0.65rem;
            font-weight: 800;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        /* Footer */
        .footer {
            background: var(--dark);
            color: #94a3b8;
            padding: 80px 0 40px;
            margin-top: 100px;
        }

        .footer h5 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .footer-link {
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.2s;
            display: block;
            margin-bottom: 12px;
        }

        .footer-link:hover {
            color: var(--primary);
            padding-left: 4px;
        }

        .social-link {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            color: #fff;
            margin-right: 10px;
            transition: all 0.2s;
        }

        .social-link:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-2px);
        }

        /* Utils */
        .text-indigo { color: var(--primary); }
        .bg-indigo-soft { background-color: rgba(99, 102, 241, 0.1); }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg header-nav fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-graduation-cap me-2"></i>E-LEARN
            </a>
            
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars text-dark"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Search -->
                <div class="mx-lg-auto my-3 my-lg-0 w-100 d-flex justify-content-center">
                    <form class="search-container" action="{{ route('home') }}" method="GET">
                        <i class="fas fa-search search-icon"></i>
                        <input class="form-control" type="search" name="search" placeholder="Khám phá khóa học..." value="{{ request('search') }}">
                    </form>
                </div>
                
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Khóa học</a>
                    </li>
                    
                    @auth
                        <li class="nav-item mx-lg-2">
                            <a class="cart-icon-wrapper" href="{{ route('cart.index') }}">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cart-badge" id="cart-count">{{ auth()->user()->cartItems()->count() }}</span>
                            </a>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center pe-0" href="#" id="userDropdown" data-bs-toggle="dropdown">
                                <img src="{{ auth()->user()->avatar_url }}" class="user-avatar me-2">
                                <span class="d-none d-xl-inline text-dark">{{ explode(' ', auth()->user()->name)[0] }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                <div class="px-3 py-2 mb-2 d-xl-none">
                                    <h6 class="mb-0 fw-bold">{{ auth()->user()->name }}</h6>
                                    <small class="text-secondary">{{ auth()->user()->email }}</small>
                                </div>
                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-shield-alt"></i>Quản trị hệ thống</a></li>
                                @endif
                                @if(auth()->user()->isInstructor())
                                    <li><a class="dropdown-item" href="{{ route('instructor.dashboard') }}"><i class="fas fa-chalkboard-teacher"></i>Bảng giảng viên</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fas fa-user-circle"></i>Hồ sơ cá nhân</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.orders') }}"><i class="fas fa-receipt"></i>Lịch sử đơn hàng</a></li>
                                <li><hr class="dropdown-divider opacity-50"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i>Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-primary btn-sm px-4" href="{{ route('register') }}">Bắt đầu ngay</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success') || session('error') || session('warning'))
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-3 fs-4 text-success"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 alert-dismissible fade show">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-3 fs-4 text-danger"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
    @endif

    <!-- Content -->
    <main class="min-vh-100">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <a class="navbar-brand text-white mb-4" href="{{ route('home') }}">
                        <i class="fas fa-graduation-cap me-2 text-white"></i>E-LEARN
                    </a>
                    <p class="mb-4 text-secondary">Nâng tầm kiến thức của bạn với những khóa học chất lượng cao từ các chuyên gia hàng đầu. Học mọi lúc, mọi nơi, không giới hạn.</p>
                    <div class="d-flex">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <h5>Nền tảng</h5>
                    <a href="{{ route('home') }}" class="footer-link">Tất cả khóa học</a>
                    <a href="#" class="footer-link">Giảng viên</a>
                    <a href="#" class="footer-link">Blog kiến thức</a>
                    <a href="#" class="footer-link">Chứng chỉ</a>
                </div>
                <div class="col-lg-2 col-6">
                    <h5>Hỗ trợ</h5>
                    <a href="#" class="footer-link">Trung tâm trợ giúp</a>
                    <a href="#" class="footer-link">Điều khoản dịch vụ</a>
                    <a href="#" class="footer-link">Chính sách bảo mật</a>
                    <a href="#" class="footer-link">FAQ</a>
                </div>
                <div class="col-lg-4">
                    <h5>Đăng ký tin tức</h5>
                    <p class="small mb-3">Nhận thông báo về các khóa học mới và ưu đãi hấp dẫn.</p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control bg-white bg-opacity-10 border-0 text-white" placeholder="Email của bạn" style="height: 50px; border-radius: 12px 0 0 12px;">
                        <button class="btn btn-primary" type="button" style="border-radius: 0 12px 12px 0;">Đăng ký</button>
                    </div>
                </div>
            </div>
            <hr class="mt-5 mb-4 border-secondary opacity-25">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 small">&copy; {{ date('Y') }} E-LEARN. Design with ❤️ by Antigravity.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" height="20" class="me-3 opacity-50 gray-scale">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" height="15" class="me-3 opacity-50 gray-scale">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" height="20" class="opacity-50 gray-scale">
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Sticky Navbar Effect
        $(window).scroll(function() {
            if ($(this).scrollTop() > 10) {
                $('.header-nav').addClass('shadow-sm bg-white').removeClass('rgba(255, 255, 255, 0.85)');
            } else {
                $('.header-nav').removeClass('shadow-sm');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
