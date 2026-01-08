@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-credit-card me-2"></i> Thanh toán</h2>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-7">
                <!-- Billing Information -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i> Thông tin khách hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                            <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror" required>{{ old('address', auth()->user()->address ?? '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea name="note" id="note" rows="3" class="form-control" placeholder="Ghi chú về đơn hàng (tùy chọn)">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> Phương thức thanh toán</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                            <label class="form-check-label" for="cod">
                                <i class="fas fa-truck me-2"></i> Thanh toán khi nhận hàng (COD)
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="bank" value="bank_transfer">
                            <label class="form-check-label" for="bank">
                                <i class="fas fa-university me-2"></i> Chuyển khoản ngân hàng
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="card" value="credit_card">
                            <label class="form-check-label" for="card">
                                <i class="fas fa-credit-card me-2"></i> Thanh toán bằng thẻ
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <!-- Order Summary -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i> Đơn hàng của bạn</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-end">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        {{ $item['product']->name }} <br>
                                        <small class="text-muted">x {{ $item['quantity'] }}</small>
                                    </td>
                                    <td class="text-end">{{ number_format($item['subtotal'], 0, ',', '.') }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Tạm tính:</td>
                                    <td class="text-end">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                                </tr>
                                <tr>
                                    <td>Phí vận chuyển:</td>
                                    <td class="text-end">Miễn phí</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>Tổng cộng:</td>
                                    <td class="text-end text-danger h5">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="agree" name="agree" required>
                            <label class="form-check-label" for="agree">
                                Tôi đã đọc và đồng ý với <a href="#">điều khoản sử dụng</a>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100" id="checkout-btn">
                            <i class="fas fa-check me-2"></i> Đặt hàng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#checkout-btn').on('click', function(e) {
        e.preventDefault();
        
        // Check if user is logged in
        <?php if(auth()->check()): ?>
            // User is logged in, submit form
            $(this).closest('form').submit();
        <?php else: ?>
            // User not logged in, show alert and redirect to login
            alert('Bạn cần đăng nhập để đặt hàng!');
            window.location.href = '{{ route("login") }}';
            return false;
        <?php endif; ?>
    });
});
</script>
@endpush
