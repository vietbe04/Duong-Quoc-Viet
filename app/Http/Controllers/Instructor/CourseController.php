<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CourseController extends Controller
{
    use AuthorizesRequests;
    /**
     * Danh sách khóa học của instructor
     */
    public function index(Request $request)
    {
        $query = Course::with('category')
            ->where('instructor_id', auth()->id());

        // Tìm kiếm
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $courses = $query->withCount(['students', 'lessons'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('instructor.courses.index', compact('courses'));
    }

    /**
     * Form tạo khóa học
     */
    public function create()
    {
        $categories = Category::where('status', 'active')->get();

        return view('instructor.courses.create', compact('categories'));
    }

    /**
     * Lưu khóa học mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published',
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

        $validated['instructor_id'] = auth()->id();

        Course::create($validated);

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Tạo khóa học thành công!');
    }

    /**
     * Chi tiết khóa học
     */
    public function show(Course $course)
    {
        $this->authorize('update', $course);

        $course->load(['category', 'lessons' => function ($query) {
            $query->orderBy('order');
        }, 'students', 'reviews']);

        return view('instructor.courses.show', compact('course'));
    }

    /**
     * Form sửa khóa học
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);

        $categories = Category::where('status', 'active')->get();

        return view('instructor.courses.edit', compact('course', 'categories'));
    }

    /**
     * Cập nhật khóa học
     */
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
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
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course->update($validated);

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Cập nhật khóa học thành công!');
    }

    /**
     * Xóa khóa học
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        if ($course->students()->count() > 0) {
            return back()->with('error', 'Không thể xóa khóa học đã có học viên!');
        }

        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->lessons()->delete();
        $course->delete();

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Xóa khóa học thành công!');
    }
}
