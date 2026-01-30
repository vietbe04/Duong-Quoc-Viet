@extends('layouts.instructor')

@section('title', 'Thêm Câu hỏi Quiz')
@section('page-title', 'Thêm Câu hỏi Quiz')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-white">
                <a href="{{ route('instructor.courses.lessons.quiz.index', [$course, $lesson]) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
                <span class="ms-2">
                    <strong>Khóa học:</strong> {{ $course->title }}<br>
                    <strong>Bài học:</strong> {{ $lesson->title }}
                </span>
            </div>
            <div class="card-body">
                <form action="{{ route('instructor.courses.lessons.quiz.store', [$course, $lesson]) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Câu hỏi <span class="text-danger">*</span></label>
                        <textarea name="question" class="form-control @error('question') is-invalid @enderror" rows="3" required>{{ old('question') }}</textarea>
                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Lựa chọn A <span class="text-danger">*</span></label>
                                <input type="text" name="option_a" class="form-control @error('option_a') is-invalid @enderror" required value="{{ old('option_a') }}">
                                @error('option_a')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Lựa chọn B <span class="text-danger">*</span></label>
                                <input type="text" name="option_b" class="form-control @error('option_b') is-invalid @enderror" required value="{{ old('option_b') }}">
                                @error('option_b')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Lựa chọn C <span class="text-danger">*</span></label>
                                <input type="text" name="option_c" class="form-control @error('option_c') is-invalid @enderror" required value="{{ old('option_c') }}">
                                @error('option_c')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Lựa chọn D <span class="text-danger">*</span></label>
                                <input type="text" name="option_d" class="form-control @error('option_d') is-invalid @enderror" required value="{{ old('option_d') }}">
                                @error('option_d')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Đáp án đúng <span class="text-danger">*</span></label>
                        <select name="correct_answer" class="form-select @error('correct_answer') is-invalid @enderror" required>
                            <option value="">-- Chọn đáp án đúng --</option>
                            <option value="a" {{ old('correct_answer') === 'a' ? 'selected' : '' }}>A</option>
                            <option value="b" {{ old('correct_answer') === 'b' ? 'selected' : '' }}>B</option>
                            <option value="c" {{ old('correct_answer') === 'c' ? 'selected' : '' }}>C</option>
                            <option value="d" {{ old('correct_answer') === 'd' ? 'selected' : '' }}>D</option>
                        </select>
                        @error('correct_answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Giải thích (tùy chọn)</label>
                        <textarea name="explanation" class="form-control @error('explanation') is-invalid @enderror" rows="3">{{ old('explanation') }}</textarea>
                        <small class="text-muted">Giải thích sẽ hiển thị cho học viên sau khi họ trả lời</small>
                        @error('explanation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-1"></i>Thêm Câu hỏi
                        </button>
                        <a href="{{ route('instructor.courses.lessons.quiz.index', [$course, $lesson]) }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    // Disable submit button to prevent double submission
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang lưu...';
});
</script>
@endsection
