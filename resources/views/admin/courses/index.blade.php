@extends('layouts.admin')

@section('title', 'Quản lý khóa học')
@section('page-title', 'Quản lý khóa học')

@section('content')
<div class="card table-card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <form action="{{ route('admin.courses.index') }}" method="GET" class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select form-select-sm">
                            <option value="">-- Danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">-- Trạng thái --</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Nháp</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-filter me-1"></i>Lọc</button>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times"></i></a>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Thêm khóa học
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Khóa học</th>
                        <th>Danh mục</th>
                        <th>Giảng viên</th>
                        <th>Giá</th>
                        <th>Trạng thái</th>
                        <th width="180">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" class="rounded me-2" style="width: 60px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded me-2" style="width: 60px; height: 40px;"></div>
                                    @endif
                                    <div>
                                        <strong>{{ Str::limit($course->title, 35) }}</strong>
                                        <br><small class="text-muted">{{ $course->lessons()->count() }} bài học</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $course->category->name ?? '-' }}</td>
                            <td>{{ $course->instructor->name ?? '-' }}</td>
                            <td>
                                @if($course->sale_price)
                                    <span class="text-decoration-line-through text-muted">{{ number_format($course->price) }}đ</span><br>
                                    <span class="text-primary fw-bold">{{ number_format($course->sale_price) }}đ</span>
                                @else
                                    <span class="text-primary fw-bold">{{ number_format($course->price) }}đ</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $course->status === 'published' ? 'success' : 'secondary' }}">
                                    {{ $course->status === 'published' ? 'Đã xuất bản' : 'Nháp' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.courses.lessons.index', $course) }}" class="btn btn-sm btn-outline-info" title="Bài học">
                                    <i class="fas fa-list"></i>
                                </a>
                                <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-sm btn-outline-secondary" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-outline-primary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa khóa học này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Không có khóa học nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $courses->links() }}
    </div>
</div>
@endsection
