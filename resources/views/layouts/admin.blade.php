<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - {{ config('app.name', 'E-Learning') }} Admin</title>
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --secondary-color: #64748b;
            --bg-body: #f8fafc;
            --sidebar-bg: #0f172a;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --card-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --glass-bg: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.3);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1050;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 2rem 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .brand-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), #818cf8);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            margin-right: 0.75rem;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
        
        .brand-name {
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.025em;
        }
        
        .nav-container {
            padding: 0 1rem;
            height: calc(100vh - 120px);
            overflow-y: auto;
        }
        
        .nav-container::-webkit-scrollbar {
            width: 4px;
        }
        
        .nav-container::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }
        
        .nav-header {
            color: #64748b;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 1.5rem 1rem 0.5rem;
        }
        
        .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
            transition: all 0.2s;
            text-decoration: none;
            font-weight: 500;
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1.1rem;
            transition: transform 0.2s;
        }
        
        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
        }
        
        .nav-link:hover i {
            transform: translateX(3px);
        }
        
        .nav-link.active {
            color: white;
            background: var(--primary-color);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }
        
        .nav-link.active i {
            color: white;
        }

        /* Content Wrapper */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        /* Top Header */
        .top-header {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border);
            padding: 0.75rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-title h4 {
            margin-bottom: 0;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.025em;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .user-profile:hover {
            background: rgba(0,0,0,0.03);
        }
        
        .user-info {
            text-align: right;
            margin-right: 0.75rem;
        }
        
        .user-name {
            display: block;
            font-weight: 600;
            font-size: 0.875rem;
            color: #0f172a;
        }
        
        .user-role {
            display: block;
            font-size: 0.75rem;
            color: #64748b;
        }
        
        .user-avatar {
            position: relative;
        }
        
        .user-avatar img {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 0 0 1px #e2e8f0;
        }
        
        .avatar-status {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 12px;
            height: 12px;
            background: #22c55e;
            border: 2px solid white;
            border-radius: 50%;
        }

        /* Global UI Elements */
        .content-body {
            padding: 2rem;
        }
        
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
            background: white;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }
        
        .table thead th {
            background: #f8fafc;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 1rem 1.5rem;
            border-top: none;
        }
        
        .table tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            color: #334155;
            border-bottom-color: #f1f5f9;
        }
        
        .btn {
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
        
        .badge {
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="btn btn-primary d-lg-none position-fixed" style="bottom: 20px; right: 20px; z-index: 1100; border-radius: 50%; width: 50px; height: 50px;" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="brand-logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <span class="brand-name">DV E-Learning</span>
        </div>
        
        <div class="nav-container">
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-grid-2"></i> Dashboard
                </a>
                
                <div class="nav-header">Quản lý</div>
                
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-folder"></i> Danh mục
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}">
                    <i class="fas fa-book-open"></i> Khóa học
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i> Người dùng
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-shopping-bag"></i> Đơn hàng
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                    <i class="fas fa-star"></i> Đánh giá
                </a>
                
                <div class="nav-header">Hệ thống</div>
                
                <a class="nav-link" href="{{ route('home') }}" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Xem trang chủ
                </a>
                
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-power-off"></i> Đăng xuất
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </nav>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-title">
                <h4>@yield('page-title', 'Dashboard')</h4>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="user-profile dropdown">
                    <div class="user-info d-none d-md-block">
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="user-role">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                    <div class="user-avatar" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar">
                        <span class="avatar-status"></span>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2">
                        <li><a class="dropdown-item py-2 px-3" href="{{ route('profile.index') }}"><i class="fas fa-user-edit me-2 opacity-50"></i> Hồ sơ</a></li>
                        <li><hr class="dropdown-divider opacity-50"></li>
                        <li>
                            <a class="dropdown-item py-2 px-3 text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-dropdown').submit();">
                                <i class="fas fa-power-off me-2 opacity-50"></i> Đăng xuất
                            </a>
                        </li>
                    </ul>
                    <form id="logout-form-dropdown" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </header>
        
        <!-- Content Body -->
        <div class="content-body">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4">
                    <i class="fas fa-check-circle me-3 fs-4"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4">
                    <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        $(document).ready(function() {
            // Sidebar Toggle
            $('#sidebarToggle').on('click', function() {
                $('#sidebar').toggleClass('active');
            });
            
            // CSRF Setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
