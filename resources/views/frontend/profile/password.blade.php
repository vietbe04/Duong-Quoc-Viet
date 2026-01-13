@extends('frontend.profile.layout')

@section('profile-content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-lock me-2"></i> Đổi mật khẩu</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('profile.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="current_password" class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                <input type="password" name="current_password" id="current_password" 
                       class="form-control @error('current_password') is-invalid @enderror" required>
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                <input type="password" name="password" id="password" 
                       class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Tối thiểu 8 ký tự.</small>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i> Đổi mật khẩu
            </button>
        </form>
    </div>
</div>
@endsection
