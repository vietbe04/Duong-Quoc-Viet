@extends('layouts.instructor')

@section('title', 'Bài học - ' . $course->title)
@section('page-title', 'Quản lý bài học')

@section('content')
<div class="card table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('instructor.courses.show', $course) }}" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i>
            </a>
            <strong>{{ Str::limit($course->title, 50) }}</strong>
        </div>
        <a href="{{ route('instructor.courses.lessons.create', $course) }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Thêm bài học
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="60">STT</th>
                        <th>Tiêu đề</th>
                        <th>Thời lượng</th>
                        <th>Preview</th>
                        <th>Trạng thái</th>
                        <th width="150">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lessons as $lesson)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">{{ $lesson->order }}</span>
                            </td>
                            <td>
                                <strong>{{ $lesson->title }}</strong>
                                @if($lesson->video_url)
                                    <br><small class="text-muted"><i class="fas fa-video me-1"></i>{{ Str::limit($lesson->video_url, 30) }}</small>
                                @endif
                            </td>
                            <td>{{ $lesson->duration ?? '-' }} phút</td>
                            <td>
                                @if($lesson->is_preview)
                                    <span class="badge bg-info">Có</span>
                                @else
                                    <span class="badge bg-secondary">Không</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $lesson->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ $lesson->status === 'active' ? 'Hoạt động' : 'Ẩn' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('instructor.courses.lessons.edit', [$course, $lesson]) }}" class="btn btn-sm btn-outline-primary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('instructor.courses.lessons.quiz.index', [$course, $lesson]) }}" class="btn btn-sm btn-outline-info" title="Quản lý Quiz">
                                    <i class="fas fa-question-circle"></i>
                                </a>
                                <form action="{{ route('instructor.courses.lessons.destroy', [$course, $lesson]) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa bài học này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Chưa có bài học nào.
                                <a href="{{ route('instructor.courses.lessons.create', $course) }}">Thêm bài học đầu tiên</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
