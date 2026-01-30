<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LearningController extends Controller
{
    use AuthorizesRequests;
    /**
     * Trang học khóa học
     */
    public function course($slug)
    {
        $course = Course::with(['lessons' => function ($query) {
            $query->where('status', 'active')->orderBy('order');
        }])
            ->where('slug', $slug)
            ->firstOrFail();

        // Kiểm tra quyền truy cập
        $this->authorize('learn', $course);

        // Lấy tiến độ học
        $progress = LessonProgress::where('user_id', auth()->id())
            ->whereIn('lesson_id', $course->lessons->pluck('id'))
            ->pluck('is_completed', 'lesson_id')
            ->toArray();

        // Tính phần trăm hoàn thành
        $totalLessons = $course->lessons->count();
        $completedLessons = count(array_filter($progress));
        $progressPercent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        // Tính tổng thời lượng (phút)
        $totalDuration = $course->lessons->sum('duration') ?? 0;
        $completedCount = $completedLessons;

        // Bài học đầu tiên chưa hoàn thành
        $currentLesson = $course->lessons->first(function ($lesson) use ($progress) {
            return !isset($progress[$lesson->id]) || !$progress[$lesson->id];
        }) ?? $course->lessons->first();

        return view('learning.course', compact(
            'course',
            'progress',
            'progressPercent',
            'completedLessons',
            'totalLessons',
            'currentLesson',
            'totalDuration',
            'completedCount'
        ));
    }

    /**
     * Xem bài học
     */
    public function lesson($courseSlug, $lessonSlug)
    {
        $course = Course::with(['lessons' => function ($query) {
            $query->where('status', 'active')->orderBy('order');
        }])
            ->where('slug', $courseSlug)
            ->firstOrFail();

        // Kiểm tra quyền truy cập
        $this->authorize('learn', $course);

        $lesson = $course->lessons->where('slug', $lessonSlug)->firstOrFail();
        
        // Load quiz questions for this lesson
        $lesson->load('quizQuestions');

        // Lấy tiến độ học
        $progress = LessonProgress::where('user_id', auth()->id())
            ->whereIn('lesson_id', $course->lessons->pluck('id'))
            ->pluck('is_completed', 'lesson_id')
            ->toArray();

        // Tính phần trăm hoàn thành
        $totalLessons = $course->lessons->count();
        $completedLessons = count(array_filter($progress));
        $progressPercent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        // Bài học trước và sau
        $currentIndex = $course->lessons->search(function ($item) use ($lesson) {
            return $item->id === $lesson->id;
        });

        $prevLesson = $currentIndex > 0 ? $course->lessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $course->lessons->count() - 1 ? $course->lessons[$currentIndex + 1] : null;

        return view('learning.lesson', compact(
            'course',
            'lesson',
            'progress',
            'progressPercent',
            'completedLessons',
            'totalLessons',
            'prevLesson',
            'nextLesson'
        ));
    }

    /**
     * Đánh dấu hoàn thành bài học
     */
    public function markComplete(Request $request, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);

        // Kiểm tra quyền
        $this->authorize('learn', $lesson->course);

        LessonProgress::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'lesson_id' => $lessonId,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Đã đánh dấu hoàn thành bài học!',
        ]);
    }

    /**
     * Bỏ đánh dấu hoàn thành
     */
    public function markIncomplete(Request $request, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);

        // Kiểm tra quyền
        $this->authorize('learn', $lesson->course);

        LessonProgress::where('user_id', auth()->id())
            ->where('lesson_id', $lessonId)
            ->update([
                'is_completed' => false,
                'completed_at' => null,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã bỏ đánh dấu hoàn thành!',
        ]);
    }
}
