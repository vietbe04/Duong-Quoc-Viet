@extends('layouts.admin')

@section('title', 'Cập nhật đơn hàng')
@section('page-title', 'Cập nhật đơn hàng')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
                <span class="ms-2"><strong>Đơn hàng:</strong> {{ $order->order_number }}</span>
            </div>
            <div class="card-body">
                <!-- Order Summary -->
                <div class="bg-light rounded p-3 mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted">Khách hàng</small>
                            <div class="fw-bold">{{ $order->user->name }}</div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Số khóa học</small>
                            <div class="fw-bold">{{ $order->items->count() }}</div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Tổng tiền</small>
                            <div class="fw-bold text-primary">{{ number_format($order->total) }}đ</div>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Trạng thái đơn hàng <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="completed" {{ old('status', $order->status) === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ old('status', $order->status) === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="3">{{ old('note', $order->note) }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Lưu ý:</strong> Khi chuyển trạng thái sang "Hoàn thành", học viên sẽ được cấp quyền truy cập các khóa học trong đơn hàng.
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Cập nhật
                        </button>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
