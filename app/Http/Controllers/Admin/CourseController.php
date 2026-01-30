<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Danh sách khóa học
     */
    public function index(Request $request)
    {
        $query = Course::with(['category', 'instructor']);

        // Tìm kiếm
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo instructor
        if ($request->filled('instructor')) {
            $query->where('instructor_id', $request->instructor);
        }

        $courses = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();
        $instructors = User::where('role', 'instructor')->get();

        return view('admin.courses.index', compact('courses', 'categories', 'instructors'));
    }

    /**
     * Form tạo khóa học
     */
    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        $instructors = User::where('role', 'instructor')->where('status', 'active')->get();

        return view('admin.courses.create', compact('categories', 'instructors'));
    }

    /**
     * Lưu khóa học mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'instructor_id' => 'required|exists:users,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ], [
            'title.required' => 'Vui lòng nhập tên khóa học',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'instructor_id.required' => 'Vui lòng chọn giảng viên',
            'price.required' => 'Vui lòng nhập giá',
            'price.min' => 'Giá phải lớn hơn hoặc bằng 0',
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc',
            'thumbnail.image' => 'File phải là hình ảnh',
            'thumbnail.max' => 'Kích thước ảnh tối đa 2MB',
        ]);

        // Tạo slug
        $validated['slug'] = Str::slug($validated['title']);
        $count = Course::where('slug', $validated['slug'])->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        // Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        Course::create($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Tạo khóa học thành công!');
    }

    /**
     * Chi tiết khóa học
     */
    public function show(Course $course)
    {
        $course->load(['category', 'instructor', 'lessons' => function ($query) {
            $query->orderBy('order');
        }, 'students', 'reviews']);

        return view('admin.courses.show', compact('course'));
    }

    /**
     * Form sửa khóa học
     */
    public function edit(Course $course)
    {
        $categories = Category::where('status', 'active')->get();
        $instructors = User::where('role', 'instructor')->where('status', 'active')->get();

        return view('admin.courses.edit', compact('course', 'categories', 'instructors'));
    }

    /**
     * Cập nhật khóa học
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'instructor_id' => 'required|exists:users,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ]);

        // Cập nhật slug nếu tên thay đổi
        if ($course->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
            $count = Course::where('slug', $validated['slug'])
                ->where('id', '!=', $course->id)
                ->count();
            if ($count > 0) {
                $validated['slug'] .= '-' . ($count + 1);
            }
        }

        // Upload thumbnail mới
        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course->update($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Cập nhật khóa học thành công!');
    }

    /**
     * Xóa khóa học
     */
    public function destroy(Course $course)
    {
        // Kiểm tra có học viên không
        if ($course->students()->count() > 0) {
            return back()->with('error', 'Không thể xóa khóa học đã có học viên!');
        }

        // Xóa thumbnail
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        // Xóa các bài học
        $course->lessons()->delete();

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Xóa khóa học thành công!');
    }
}
