@extends('layouts.app')

@section('title', 'Tài khoản của tôi')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                         class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <p class="text-muted small">Thành viên từ {{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="card">
                <div class="list-group list-group-flush">
                    <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.index') ? 'active' : '' }}">
                        <i class="fas fa-user me-2"></i> Thông tin cá nhân
                    </a>
                    <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi
                    </a>
                    <a href="{{ route('profile.password') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.password') ? 'active' : '' }}">
                        <i class="fas fa-lock me-2"></i> Đổi mật khẩu
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            @yield('profile-content')
        </div>
    </div>
</div>
@endsection
