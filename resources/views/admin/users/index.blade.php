@extends('layouts.admin')

@section('title', 'Quản lý người dùng')
@section('page-title', 'Danh sách người dùng')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius: 20px;">
    <div class="card-header bg-transparent border-0 pt-4 px-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-search text-secondary"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-0" placeholder="Tìm tên, email..." value="{{ request('search') }}" style="border-radius: 0 10px 10px 0;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="role" class="form-select bg-light border-0" style="border-radius: 10px;">
                            <option value="">Tất cả vai trò</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                            <option value="instructor" {{ request('role') === 'instructor' ? 'selected' : '' }}>Giảng viên</option>
                            <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Học viên</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-3 shadow-sm"><i class="fas fa-filter me-1"></i> Lọc</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light px-3"><i class="fas fa-redo-alt"></i></a>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm px-4">
                    <i class="fas fa-plus-circle me-1"></i> Thêm người dùng
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="ps-4">Người dùng</th>
                        <th>Liên hệ</th>
                        <th>Vai trò</th>
                        <th class="text-center">Số khóa học</th>
                        <th>Ngày tham gia</th>
                        <th class="pe-4 text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        <img src="{{ $user->avatar_url }}" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                        <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle" style="width: 10px; height: 10px;"></span>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                                        <div class="text-secondary small">ID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark">{{ $user->email }}</div>
                                <div class="text-secondary small">{{ $user->phone ?? 'Chưa cập nhật' }}</div>
                            </td>
                            <td>
                                @switch($user->role)
                                    @case('admin')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border-0">Quản trị viên</span>
                                        @break
                                    @case('instructor')
                                        <span class="badge bg-info bg-opacity-10 text-info border-0">Giảng viên</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border-0">Học viên</span>
                                @endswitch
                            </td>
                            <td class="text-center">
                                <span class="fw-bold">{{ $user->enrolled_courses_count ?? 0 }}</span>
                            </td>
                            <td>
                                <div class="text-dark small">{{ $user->created_at->format('d M, Y') }}</div>
                                <div class="text-secondary" style="font-size: 0.7rem;">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border-0 rounded-3 px-2" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-secondary"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-3">
                                        <li><a href="{{ route('admin.users.show', $user) }}" class="dropdown-item rounded-2"><i class="fas fa-eye me-2 opacity-50"></i> Chi tiết</a></li>
                                        <li><a href="{{ route('admin.users.edit', $user) }}" class="dropdown-item rounded-2"><i class="fas fa-edit me-2 opacity-50"></i> Chỉnh sửa</a></li>
                                        @if(auth()->id() !== $user->id)
                                            <li><hr class="dropdown-divider opacity-50"></li>
                                            <li>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger rounded-2">
                                                        <i class="fas fa-trash-alt me-2 opacity-50"></i> Xóa tài khoản
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/slate/abstract-art-4.svg" style="width: 200px;" class="mb-3 opacity-50">
                                <div class="text-secondary">Không tìm thấy người dùng nào phù hợp</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($users->hasPages())
        <div class="card-footer bg-transparent border-0 px-4 py-4">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
