<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Trang chủ - Hiển thị danh sách khóa học
     */
    public function index(Request $request)
    {
        $query = Course::with(['category', 'instructor', 'lessons'])
            ->where('status', 'published');

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Sắp xếp
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'popular':
                $query->withCount('students')->orderBy('students_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $courses = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', 'active')->get();

        return view('home', compact('courses', 'categories'));
    }

    /**
     * Trang chi tiết khóa học
     */
    public function courseDetail($slug)
    {
        $course = Course::with(['category', 'instructor', 'lessons' => function ($query) {
            $query->orderBy('order');
        }, 'reviews' => function ($query) {
            $query->where('status', 'approved')->with('user')->latest();
        }])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Kiểm tra user đã mua khóa học chưa
        $hasPurchased = false;
        if (auth()->check()) {
            $hasPurchased = auth()->user()->purchasedCourses()->where('course_id', $course->id)->exists();
        }

        // Tính rating trung bình
        $averageRating = $course->reviews()->where('status', 'approved')->avg('rating') ?? 0;
        $totalReviews = $course->reviews()->where('status', 'approved')->count();

        // Khóa học liên quan
        $relatedCourses = Course::where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->where('status', 'published')
            ->limit(4)
            ->get();

        return view('courses.detail', compact(
            'course',
            'hasPurchased',
            'averageRating',
            'totalReviews',
            'relatedCourses'
        ));
    }
}
