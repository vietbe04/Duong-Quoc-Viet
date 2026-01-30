<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Instructor Dashboard
     */
    public function index()
    {
        $user = auth()->user();

        // Khóa học của instructor
        $courses = Course::where('instructor_id', $user->id)
            ->withCount(['students', 'lessons', 'reviews'])
            ->get();

        // Thống kê
        $stats = [
            'total_courses' => $courses->count(),
            'total_students' => $courses->sum('students_count'),
            'total_lessons' => $courses->sum('lessons_count'),
            'total_reviews' => $courses->sum('reviews_count'),
            'published_courses' => $courses->where('status', 'published')->count(),
        ];

        // Khóa học gần đây
        $recentCourses = Course::where('instructor_id', $user->id)
            ->withCount('students')
            ->latest()
            ->limit(5)
            ->get();

        // Đánh giá gần đây
        $recentReviews = Review::whereIn('course_id', $courses->pluck('id'))
            ->with(['user', 'course'])
            ->latest()
            ->limit(5)
            ->get();

        return view('instructor.dashboard', compact('stats', 'recentCourses', 'courses', 'recentReviews'));
    }
}
