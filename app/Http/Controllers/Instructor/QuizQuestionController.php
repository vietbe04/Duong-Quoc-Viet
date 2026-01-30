<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\QuizQuestion;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class QuizQuestionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Hiển thị danh sách câu hỏi của bài học
     */
    public function index($courseId, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $course = $lesson->course;
        
        // Kiểm tra quyền
        $this->authorize('update', $course);
        
        $questions = $lesson->quizQuestions()->orderBy('order')->get();
        
        return view('instructor.quiz.index', compact('lesson', 'course', 'questions'));
    }
    
    /**
     * Hiển thị form tạo câu hỏi
     */
    public function create($courseId, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $course = $lesson->course;
        
        // Kiểm tra quyền
        $this->authorize('update', $course);
        
        return view('instructor.quiz.create', compact('lesson', 'course'));
    }
    
    /**
     * Lưu câu hỏi mới
     */
    public function store(Request $request, $courseId, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $course = $lesson->course;
        
        // Kiểm tra quyền
        $this->authorize('update', $course);
        
        $validated = $request->validate([
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:a,b,c,d',
            'explanation' => 'nullable|string',
        ]);
        
        $order = QuizQuestion::where('lesson_id', $lessonId)->max('order') + 1;
        
        QuizQuestion::create([
            'lesson_id' => $lessonId,
            'order' => $order,
            ...$validated,
        ]);
        
        return redirect()->route('instructor.courses.lessons.quiz.index', [$courseId, $lessonId])
            ->with('success', 'Thêm câu hỏi thành công!');
    }
    
    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($courseId, $lessonId, $questionId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $course = $lesson->course;
        $quizQuestion = QuizQuestion::findOrFail($questionId);
        
        // Kiểm tra quyền
        $this->authorize('update', $course);
        
        return view('instructor.quiz.edit', compact('lesson', 'course', 'quizQuestion'));
    }
    
    /**
     * Cập nhật câu hỏi
     */
    public function update(Request $request, $courseId, $lessonId, $questionId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $course = $lesson->course;
        $question = QuizQuestion::findOrFail($questionId);
        
        // Kiểm tra quyền
        $this->authorize('update', $course);
        
        $validated = $request->validate([
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:a,b,c,d',
            'explanation' => 'nullable|string',
        ]);
        
        $question->update($validated);
        
        return redirect()->route('instructor.courses.lessons.quiz.index', [$courseId, $lessonId])
            ->with('success', 'Cập nhật câu hỏi thành công!');
    }
    
    /**
     * Xóa câu hỏi
     */
    public function destroy($courseId, $lessonId, $questionId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $course = $lesson->course;
        $question = QuizQuestion::findOrFail($questionId);
        
        // Kiểm tra quyền
        $this->authorize('update', $course);
        
        $question->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Xóa câu hỏi thành công!']);
        }
        
        return redirect()->route('instructor.courses.lessons.quiz.index', [$courseId, $lessonId])
            ->with('success', 'Xóa câu hỏi thành công!');
    }
}
