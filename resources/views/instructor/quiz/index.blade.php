@extends('layouts.instructor')

@section('title', 'Quản lý Câu hỏi Quiz')
@section('page-title', 'Quản lý Câu hỏi Quiz')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('instructor.courses.lessons.index', $course) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                    </a>
                    <span class="ms-3">
                        <strong>Khóa học:</strong> {{ $course->title }}<br>
                        <strong>Bài học:</strong> {{ $lesson->title }}
                    </span>
                </div>
                <a href="{{ route('instructor.courses.lessons.quiz.create', [$course, $lesson]) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Thêm Câu hỏi
                </a>
            </div>
            <div class="card-body">
                @if($questions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Câu hỏi</th>
                                    <th>Đáp án đúng</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $question)
                                    <tr>
                                        <td><strong>{{ $question->order }}</strong></td>
                                        <td>{{ Str::limit($question->question, 60) }}</td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ strtoupper($question->correct_answer) }}. 
                                                {{ match($question->correct_answer) {
                                                    'a' => $question->option_a,
                                                    'b' => $question->option_b,
                                                    'c' => $question->option_c,
                                                    'd' => $question->option_d,
                                                } }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('instructor.courses.lessons.quiz.edit', [$course, $lesson, $question]) }}" class="btn btn-sm btn-primary" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('instructor.courses.lessons.quiz.destroy', [$course, $lesson, $question]) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa câu hỏi này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Chưa có câu hỏi nào. <a href="{{ route('instructor.courses.lessons.quiz.create', [$course, $lesson]) }}">Thêm câu hỏi mới</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
