@extends('layouts.instructor')

@section('title', 'Thêm bài học')
@section('page-title', 'Thêm bài học mới')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-white">
                <a href="{{ route('instructor.courses.lessons.index', $course) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
                <span class="ms-2"><strong>Khóa học:</strong> {{ $course->title }}</span>
            </div>
            <div class="card-body">
                <form action="{{ route('instructor.courses.lessons.store', $course) }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Tiêu đề bài học <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">URL Video</label>
                                <input type="url" name="video_url" class="form-control @error('video_url') is-invalid @enderror" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Hỗ trợ YouTube, Vimeo hoặc đường dẫn video trực tiếp</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nội dung bài học</label>
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="8">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Thứ tự</label>
                                <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $course->lessons()->max('order') + 1) }}" min="1">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Thời lượng (phút)</label>
                                <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration') }}" min="0">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Ẩn</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_preview" id="is_preview" value="1" {{ old('is_preview') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_preview">
                                        Cho phép xem trước (Preview)
                                    </label>
                                </div>
                                <small class="text-muted">Bài học preview có thể xem mà không cần mua khóa học</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Lưu bài học
                        </button>
                        <a href="{{ route('instructor.courses.lessons.index', $course) }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
