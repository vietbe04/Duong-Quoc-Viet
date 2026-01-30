@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')
@section('page-title', 'Quản lý đánh giá')

@section('content')
<div class="card table-card">
    <div class="card-header">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-2">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Tên người dùng, khóa học..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="rating" class="form-select form-select-sm">
                    <option value="">-- Đánh giá --</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Trạng thái --</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-filter me-1"></i>Lọc</button>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times"></i></a>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Người đánh giá</th>
                        <th>Khóa học</th>
                        <th>Đánh giá</th>
                        <th>Nội dung</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th width="150">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $review->user->avatar_url }}" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;">
                                    <div>
                                        <strong>{{ $review->user->name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.courses.show', $review->course) }}">{{ Str::limit($review->course->title, 30) }}</a>
                            </td>
                            <td>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td>{{ Str::limit($review->comment, 50) }}</td>
                            <td>
                                @if($review->is_approved)
                                    <span class="badge bg-success">Đã duyệt</span>
                                @else
                                    <span class="badge bg-warning">Chờ duyệt</span>
                                @endif
                            </td>
                            <td>{{ $review->created_at->format('d/m/Y') }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $review->id }}" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if(!$review->is_approved)
                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Duyệt">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa đánh giá này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Chi tiết đánh giá</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="{{ $review->user->avatar_url }}" class="rounded-circle me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <strong>{{ $review->user->name }}</strong>
                                                <br><small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <strong>Khóa học:</strong> {{ $review->course->title }}
                                        </div>
                                        
                                        <div class="mb-3">
                                            <strong>Đánh giá:</strong>
                                            <span class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </span>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <strong>Nội dung:</strong>
                                            <p class="mt-1">{{ $review->comment }}</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Không có đánh giá nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $reviews->links() }}
    </div>
</div>
@endsection
