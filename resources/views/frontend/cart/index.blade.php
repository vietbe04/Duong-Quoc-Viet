@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i> Giỏ hàng của bạn</h2>

    @if(count($cartItems) > 0)
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="100">Hình ảnh</th>
                                <th>Sản phẩm</th>
                                <th width="120">Đơn giá</th>
                                <th width="150">Số lượng</th>
                                <th width="120">Tổng</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $id => $item)
                            <tr>
                                <td>
                                    <img src="{{ $item['product']->image_url ?? 'https://via.placeholder.com/80' }}" alt="{{ $item['product']->name }}" 
                                         width="80" height="80" class="rounded" style="object-fit: cover;">
                                </td>
                                <td>
                                    <h6 class="mb-0">{{ $item['product']->name }}</h6>
                                </td>
                                <td>{{ number_format($item['price'], 0, ',', '.') }}đ</td>
                                <td>
                                    <form action="{{ route('cart.update', $item['product']->id) }}" method="POST" class="d-flex">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group input-group-sm">
                                            <button type="button" class="btn btn-outline-secondary" 
                                                    onclick="this.parentNode.querySelector('input').stepDown(); this.form.submit();">-</button>
                                            <input type="number" name="quantity" class="form-control text-center" 
                                                   value="{{ $item['quantity'] }}" min="1" style="width: 50px;">
                                            <button type="button" class="btn btn-outline-secondary" 
                                                    onclick="this.parentNode.querySelector('input').stepUp(); this.form.submit();">+</button>
                                        </div>
                                    </form>
                                </td>
                                <td class="fw-bold text-danger">{{ number_format($item['subtotal'], 0, ',', '.') }}đ</td>
                                <td>
                                    <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST" 
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i> Tiếp tục mua hàng
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa tất cả?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-2"></i> Xóa tất cả
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i> Tổng đơn hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td>Tạm tính:</td>
                            <td class="text-end">{{ number_format($total, 0, ',', '.') }}đ</td>
                        </tr>
                        <tr>
                            <td>Phí vận chuyển:</td>
                            <td class="text-end">Miễn phí</td>
                        </tr>
                        <tr class="border-top">
                            <th>Tổng cộng:</th>
                            <th class="text-end text-danger h4">{{ number_format($total, 0, ',', '.') }}đ</th>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-credit-card me-2"></i> Tiến hành thanh toán
                    </a>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-tag me-2"></i> Mã giảm giá</h6>
                    <form>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Nhập mã giảm giá">
                            <button class="btn btn-outline-secondary" type="submit">Áp dụng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
        <h3>Giỏ hàng trống</h3>
        <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="fas fa-shopping-bag me-2"></i> Bắt đầu mua sắm
        </a>
    </div>
    @endif
</div>
@endsection
