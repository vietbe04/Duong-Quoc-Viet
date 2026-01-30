@extends('layouts.app')

@section('title', 'Đăng xuất')

@section('content')
<div class="logout-container">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center py-5">
                        <!-- Icon -->
                        <div class="mb-4">
                            <i class="fas fa-sign-out-alt fa-5x text-primary opacity-50"></i>
                        </div>
                        
                        <!-- Message -->
                        <h2 class="mb-3">Đã đăng xuất thành công!</h2>
                        <p class="text-muted mb-4">Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.</p>
                        
                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-home me-2"></i>Trang chủ
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập lại
                            </a>
                        </div>
                        
                        <!-- Additional Info -->
                        <div class="mt-5 pt-4 border-top">
                            <p class="text-muted small mb-3">Bạn có các khóa học chưa hoàn thành?</p>
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-right me-2"></i>Quay lại để tiếp tục học tập
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .logout-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: calc(100vh - 76px);
        display: flex;
        align-items: center;
        padding: 40px 0;
    }
    
    .logout-container .card {
        border-radius: 15px;
        animation: slideUp 0.5s ease-out;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush
