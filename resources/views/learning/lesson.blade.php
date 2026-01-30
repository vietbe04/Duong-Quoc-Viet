@extends('layouts.app')

@section('title', 'Học: ' . $course->title)

@section('content')
<div class="learning-container bg-white">
    <div class="row g-0 h-100">
        <!-- Main Content (Video & Details) -->
        <div class="col-lg-9 h-100 overflow-auto border-end border-light content-pane">
            <div class="p-4 p-xl-5">
                <!-- Navigation & Title -->
                <nav aria-label="breadcrumb" class="mb-4 d-lg-none">
                    <a href="{{ route('courses.detail', $course->slug) }}" class="text-secondary text-decoration-none small">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại khóa học
                    </a>
                </nav>

                @if(isset($lesson))
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h2 class="fw-800 text-dark mb-0">{{ $lesson->title }}</h2>
                        <div class="d-flex gap-2">
                             @if($prevLesson)
                                <a href="{{ route('learn.lesson', [$course->slug, $prevLesson->slug]) }}" class="btn btn-light rounded-pill px-3 shadow-sm border-light">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif
                            @if($nextLesson)
                                <a href="{{ route('learn.lesson', [$course->slug, $nextLesson->slug]) }}" class="btn btn-light rounded-pill px-3 shadow-sm border-light">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Player Area -->
                    @if($lesson->video_url && $lesson->video_type !== 'text')
                        <div class="video-player-wrapper shadow-2xl rounded-5 overflow-hidden mb-5 bg-black">
                            <div class="ratio ratio-16x9">
                                @if(filter_var($lesson->video_url, FILTER_VALIDATE_URL))
                                    @if(str_contains($lesson->video_url, 'youtube.com') || str_contains($lesson->video_url, 'youtu.be'))
                                        @php
                                            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/', $lesson->video_url, $matches);
                                            $youtubeId = $matches[1] ?? '';
                                        @endphp
                                        <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&modestbranding=1" 
                                                allowfullscreen 
                                                class="border-0"></iframe>
                                    @else
                                        <video controls class="w-100 h-100">
                                            <source src="{{ $lesson->video_url }}" type="video/mp4">
                                            Trình duyệt không hỗ trợ video.
                                        </video>
                                    @endif
                                @else
                                    <video controls class="w-100 h-100">
                                        <source src="{{ asset('storage/' . $lesson->video_url) }}" type="video/mp4">
                                        Trình duyệt không hỗ trợ video.
                                    </video>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <!-- Lesson Body -->
                    <div class="lesson-article-body mb-5">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <span class="badge bg-indigo-soft text-primary px-3 py-2 rounded-pill fw-bold" style="background: rgba(99, 102, 241, 0.1);">
                                <i class="far fa-file-alt me-2"></i>{{ $lesson->video_type === 'text' ? 'Bài đọc' : 'Video bài giảng' }}
                            </span>
                            <span class="text-secondary small fw-medium">
                                <i class="far fa-clock me-1"></i> {{ $lesson->duration ?? 5 }} phút học tập
                            </span>
                        </div>
                        
                        <div class="course-content-viewer p-4 p-xl-5 bg-light rounded-5 border border-light-subtle">
                            @if($lesson->video_type === 'text')
                                <div class="rich-text-content fs-5 text-dark lh-lg">
                                    {!! str_replace(["\n## ", "\n# ", "\n- "], ["\n<h4 class='fw-bold mt-4 mb-3'>", "\n<h2 class='fw-800 mt-5 mb-4'>", "\n<li class='mb-2'>"], nl2br(e($lesson->content))) !!}
                                </div>
                            @else
                                <div class="fs-5 text-secondary lh-lg">
                                    {!! nl2br(e($lesson->content)) !!}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Footer Actions -->
                    <div class="footer-lesson-actions p-4 bg-white border-top border-light shadow-sm rounded-5 d-flex flex-wrap align-items-center justify-content-between gap-3 mb-5">
                        <div class="d-flex gap-2">
                            @if($prevLesson)
                                <a href="{{ route('learn.lesson', [$course->slug, $prevLesson->slug]) }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold border-light-subtle">
                                    <i class="fas fa-chevron-left me-2"></i> Bài cũ
                                </a>
                            @endif
                        </div>
                        
                        <div class="flex-grow-1 text-center">
                            @if(isset($progress[$lesson->id]) && $progress[$lesson->id])
                                <button class="btn btn-success rounded-pill px-5 py-3 fw-bold shadow-lg transition-all btn-mark-complete" data-lesson-id="{{ $lesson->id }}" data-completed="1">
                                    <i class="fas fa-check-circle me-2"></i> Bài này đã xong
                                </button>
                            @else
                                <button class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-lg transition-all btn-mark-complete" data-lesson-id="{{ $lesson->id }}" data-completed="0" data-has-quiz="{{ $lesson->quizQuestions->count() > 0 ? '1' : '0' }}">
                                    <i class="fas fa-graduation-cap me-2"></i> Đánh dấu hoàn thành
                                </button>
                            @endif
                        </div>
                        
                        <div class="d-flex gap-2">
                             @if($nextLesson)
                                <a href="{{ route('learn.lesson', [$course->slug, $nextLesson->slug]) }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                                    Bài tiếp <i class="fas fa-chevron-right ms-2"></i>
                                </a>
                            @else
                                <div class="text-success fw-800">
                                    <i class="fas fa-trophy me-2"></i> Khóa học hoàn tất
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-5 my-5">
                        <img src="https://illustrations.popsy.co/slate/shaking-hands.svg" style="width: 280px;" class="mb-5">
                        <h2 class="fw-800 mb-3">Sẵn sàng để bắt đầu chưa?</h2>
                        <p class="text-secondary lead mb-5">Chào mừng bạn đến với khóa học <strong>{{ $course->title }}</strong>. Hãy bắt đầu từ bài học đầu tiên nhé!</p>
                        @if($currentLesson)
                            <a href="{{ route('learn.lesson', [$course->slug, $currentLesson->slug]) }}" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg">
                                <i class="fas fa-play me-2"></i> Bắt đầu bài giảng đầu tiên
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar - Lesson List -->
        <div class="col-lg-3 sidebar-pane h-100 bg-light border-start border-light d-none d-lg-block">
            <div class="d-flex flex-column h-100">
                <div class="p-4 border-bottom border-light bg-white">
                    <a href="{{ route('courses.detail', $course->slug) }}" class="text-secondary text-decoration-none small fw-bold mb-3 d-block">
                        <i class="fas fa-chevron-left me-1"></i> CHI TIẾT KHÓA HỌC
                    </a>
                    <h6 class="fw-800 text-dark line-clamp-2 mb-3">{{ $course->title }}</h6>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="progress flex-grow-1 rounded-pill" style="height: 6px;">
                            <div class="progress-bar bg-success rounded-pill" style="width: {{ $progressPercent }}%"></div>
                        </div>
                        <span class="small fw-bold text-success">{{ $progressPercent }}%</span>
                    </div>
                    <div class="text-secondary small fw-medium">
                        <i class="fas fa-check-circle text-success me-1"></i> {{ $completedLessons }}/{{ $totalLessons }} bài học đã xong
                    </div>
                </div>

                <div class="flex-grow-1 overflow-auto lesson-list py-3">
                    @foreach($course->lessons as $lessonItem)
                        @php
                            $isCurrent = isset($lesson) && $lesson->id === $lessonItem->id;
                            $isDone = isset($progress[$lessonItem->id]) && $progress[$lessonItem->id];
                        @endphp
                        <a href="{{ route('learn.lesson', [$course->slug, $lessonItem->slug]) }}" 
                           class="lesson-nav-item d-flex align-items-center p-3 px-4 text-decoration-none transition-all {{ $isCurrent ? 'active border-start border-primary border-4' : '' }}">
                            <div class="lesson-status-icon me-3">
                                @if($isDone)
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 24px; height: 24px;">
                                        <i class="fas fa-check small" style="font-size: 0.7rem;"></i>
                                    </div>
                                @elseif($isCurrent)
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 24px; height: 24px;">
                                        <i class="fas fa-play small" style="font-size: 0.7rem;"></i>
                                    </div>
                                @else
                                    <div class="bg-white border text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                        <span style="font-size: 0.7rem;">{{ $loop->iteration }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="lesson-nav-info overflow-hidden">
                                <div class="small fw-bold {{ $isCurrent ? 'text-primary' : ($isDone ? 'text-dark' : 'text-secondary') }} line-clamp-1">
                                    {{ $lessonItem->title }}
                                </div>
                                <div class="text-secondary opacity-50 small fw-medium" style="font-size: 0.65rem;">
                                    <i class="far fa-clock me-1"></i> {{ $lessonItem->duration ?? 5 }} phút
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quiz Modal -->
<div class="modal fade" id="quizModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 32px;">
            <div class="modal-header border-0 p-4 px-xl-5 pt-xl-5 pb-0">
                <div class="d-flex align-items-center">
                    <div class="bg-indigo-soft p-3 rounded-4 me-3 text-primary" style="background: rgba(99, 102, 241, 0.1);">
                        <i class="fas fa-clipboard-check fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-800 mb-0">Kiểm tra kiến thức</h5>
                        <p class="text-secondary small mb-0">Hoàn thành bài tập để qua bài học này</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 p-xl-5" id="quizContent">
                <!-- Content via JS -->
            </div>
            <div class="modal-footer border-0 p-4 px-xl-5 pb-xl-5 pt-0" id="quizFooter" style="display: none;">
                <button type="button" class="btn btn-light rounded-pill px-4 py-2" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-lg" id="submitQuiz">
                    <i class="fas fa-paper-plane me-2"></i> Nộp bài kiểm tra
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .learning-container { height: calc(100vh - 74px); border-radius: 0; }
    .fw-800 { font-weight: 800; }
    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .lesson-nav-item:hover { background-color: #f1f5f9; }
    .lesson-nav-item.active { background-color: #e2e8f0; }
    .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .transition-all { transition: all 0.3s ease; }
    .content-pane::-webkit-scrollbar, .lesson-list::-webkit-scrollbar { width: 4px; }
    .content-pane::-webkit-scrollbar-thumb, .lesson-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .rich-text-content img { max-width: 100%; height: auto; border-radius: 1rem; margin: 1.5rem 0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let currentLessonId = null;
    let quizQuestions = [];
    
    $('.btn-mark-complete').click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var lessonId = btn.data('lesson-id');
        var isCompleted = btn.data('completed');
        var hasQuiz = btn.data('has-quiz');
        
        if (isCompleted == 1) {
            toggleComplete(lessonId, true);
            return;
        }
        
        if (hasQuiz == '1' || hasQuiz === 1) {
            currentLessonId = lessonId;
            loadQuiz(lessonId);
        } else {
            toggleComplete(lessonId, false);
        }
    });
    
    function loadQuiz(lessonId) {
        $('#quizModal').modal('show');
        $('#quizContent').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3 text-secondary">Đang chuẩn bị câu hỏi...</p>
            </div>
        `);
        $('#quizFooter').hide();
        
        $.ajax({
            url: '/api/lessons/' + lessonId + '/quiz',
            method: 'GET',
            success: function(response) {
                if (response.success && response.questions.length > 0) {
                    quizQuestions = response.questions;
                    displayQuiz(response.questions);
                } else {
                    $('#quizModal').modal('hide');
                    toggleComplete(lessonId, false);
                }
            }
        });
    }
    
    function displayQuiz(questions) {
        let html = '<div class="alert bg-primary bg-opacity-10 border-0 text-primary mb-4 p-3 rounded-4" style="font-size: 0.9rem;">';
        html += '<i class="fas fa-info-circle me-2"></i> Trả lời đúng ít nhất 70% để hoàn tất bài học này.';
        html += '</div>';
        
        questions.forEach((question, index) => {
            html += `<div class="mb-5 quiz-q-item p-4 bg-light rounded-4 border border-light-subtle">
                <h6 class="fw-800 text-dark mb-4"><span class="text-primary me-2">#${index + 1}</span> ${question.question}</h6>
                <div class="d-grid gap-3">`;
            
            ['a', 'b', 'c', 'd'].forEach(opt => {
                let optVal = question['option_' + opt];
                if(optVal) {
                    html += `<div class="form-check custom-quiz-radio">
                        <input class="form-check-input" type="radio" name="question_${question.id}" id="q${question.id}_${opt}" value="${opt}">
                        <label class="form-check-label w-100 p-3 bg-white border rounded-4 cursor-pointer hover-bg-light transition-all" for="q${question.id}_${opt}">
                            <span class="fw-bold me-2 text-primary">${opt.toUpperCase()}.</span> ${optVal}
                        </label>
                    </div>`;
                }
            });
            html += '</div></div>';
        });
        
        $('#quizContent').html(html);
        $('#quizFooter').show();
    }
    
    $('#submitQuiz').click(function() {
        let answers = {};
        let allAnswered = true;
        
        quizQuestions.forEach(question => {
            let answer = $('input[name="question_' + question.id + '"]:checked').val();
            if (!answer) allAnswered = false;
            answers[question.id] = answer;
        });
        
        if (!allAnswered) {
             Swal.fire({ icon: 'warning', title: 'Chưa xong!', text: 'Vui lòng trả lời hết các câu hỏi.', confirmButtonColor: '#6366f1' });
             return;
        }
        
        $.ajax({
            url: '/api/lessons/' + currentLessonId + '/quiz',
            method: 'POST',
            data: { answers: answers, _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) displayResults(response);
            }
        });
    });
    
    function displayResults(response) {
        let html = '<div class="text-center mb-5">';
        if (response.passed) {
            html += `<div class="bg-success bg-opacity-10 text-success p-5 rounded-5 mb-4 border border-success border-opacity-10">
                <i class="fas fa-medal fa-4x mb-4 bounce-in"></i>
                <h2 class="fw-800">TUYỆT VỜI!</h2>
                <p class="lead mb-0">Bạn đã vượt qua bài kiểm tra với ${response.score} điểm!</p>
            </div>`;
        } else {
            html += `<div class="bg-danger bg-opacity-10 text-danger p-5 rounded-5 mb-4 border border-danger border-opacity-10">
                <i class="fas fa-redo-alt fa-4x mb-4"></i>
                <h2 class="fw-800">CỐ GẮNG LÀM LẠI!</h2>
                <p class="lead mb-0">Điểm của bạn: ${response.score}. Cần đạt tối thiểu 70.</p>
            </div>`;
        }
        html += '</div>';

        html += '<h6 class="fw-800 mb-4 px-2">XEM LẠI CÂU TRẢ LỜI</h6>';
        response.results.forEach((result, index) => {
            html += `<div class="mb-4 p-4 rounded-5 border ${result.is_correct ? 'border-success bg-success bg-opacity-5' : 'border-danger bg-danger bg-opacity-5'}">
                <div class="fw-bold mb-3 d-flex justify-content-between">
                    <span>${index + 1}. ${result.question}</span>
                    <i class="fas ${result.is_correct ? 'fa-check text-success' : 'fa-times text-danger'} fw-900"></i>
                </div>
                <div class="small mb-1">Của bạn: <span class="${result.is_correct ? 'text-success' : 'text-danger'} fw-bold">${result.user_answer.toUpperCase()}</span></div>
                <div class="small">Đáp án: <span class="text-success fw-bold">${result.correct_answer.toUpperCase()}</span></div>
            </div>`;
        });
        
        $('#quizContent').html(html);
        
        if (response.passed) {
            $('#quizFooter').html(`
                <button type="button" class="btn btn-success border-0 rounded-pill px-5 py-3 fw-bold shadow-lg" id="completeAndNext">
                    <i class="fas fa-chevron-right me-2"></i> HOÀN THÀNH & TỚI BÀI TIẾP
                </button>
            `);
            $('#completeAndNext').click(function() { toggleComplete(currentLessonId, false, true); });
        } else {
            $('#quizFooter').html(`
                <button type="button" class="btn btn-primary border-0 rounded-pill px-5 py-3 fw-bold shadow-lg" id="retryQuiz">
                    <i class="fas fa-redo me-2"></i> THỬ LẠI NGAY
                </button>
            `);
            $('#retryQuiz').click(function() { loadQuiz(currentLessonId); });
        }
    }
    
    function toggleComplete(lessonId, isCompleted, redirectNext = false) {
        var url = isCompleted ? '{{ route("learn.incomplete", ":id") }}' : '{{ route("learn.complete", ":id") }}';
        url = url.replace(':id', lessonId);
        
        $.ajax({
            url: url,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    if (redirectNext) {
                        @if($nextLesson)
                            window.location.href = '{{ route("learn.lesson", [$course->slug, $nextLesson->slug]) }}';
                        @else
                            window.location.href = '{{ route("learn.course", $course->slug) }}';
                        @endif
                    } else location.reload();
                }
            }
        });
    }
});
</script>
@endpush
