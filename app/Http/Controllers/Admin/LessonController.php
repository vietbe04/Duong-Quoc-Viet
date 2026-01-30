<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    /**
     * Danh sách bài học theo khóa học
     */
    public function index(Course $course)
    {
        $lessons = $course->lessons()->orderBy('order')->get();

        return view('admin.lessons.index', compact('course', 'lessons'));
    }

    /**
     * Form tạo bài học
     */
    public function create(Course $course)
    {
        $nextOrder = $course->lessons()->max('order') + 1;

        return view('admin.lessons.create', compact('course', 'nextOrder'));
    }

    /**
     * Lưu bài học mới
     */
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'nullable|url',
            'video_file' => 'nullable|mimes:mp4,avi,mov,wmv|max:102400', // 100MB
            'content' => 'nullable|string',
            'duration' => 'nullable|integer|min:0',
            'order' => 'required|integer|min:1',
            'is_preview' => 'boolean',
            'status' => 'required|in:active,inactive',
        ], [
            'title.required' => 'Vui lòng nhập tên bài học',
            'video_url.url' => 'URL video không hợp lệ',
            'video_file.mimes' => 'File video phải có định dạng mp4, avi, mov hoặc wmv',
            'video_file.max' => 'File video tối đa 100MB',
            'order.required' => 'Vui lòng nhập thứ tự',
        ]);

        // Tạo slug
        $validated['slug'] = Str::slug($validated['title']);
        $count = Lesson::where('course_id', $course->id)
            ->where('slug', $validated['slug'])
            ->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        // Upload video file nếu có
        if ($request->hasFile('video_file')) {
            $validated['video_url'] = $request->file('video_file')->store('lessons', 'public');
        }

        $validated['course_id'] = $course->id;
        $validated['is_preview'] = $request->boolean('is_preview');

        Lesson::create($validated);

        return redirect()->route('admin.courses.lessons.index', $course)
            ->with('success', 'Tạo bài học thành công!');
    }

    /**
     * Form sửa bài học
     */
    public function edit(Course $course, Lesson $lesson)
    {
        return view('admin.lessons.edit', compact('course', 'lesson'));
    }

    /**
     * Cập nhật bài học
     */
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'nullable|url',
            'video_file' => 'nullable|mimes:mp4,avi,mov,wmv|max:102400',
            'content' => 'nullable|string',
            'duration' => 'nullable|integer|min:0',
            'order' => 'required|integer|min:1',
            'is_preview' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        // Cập nhật slug nếu tên thay đổi
        if ($lesson->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
            $count = Lesson::where('course_id', $course->id)
                ->where('slug', $validated['slug'])
                ->where('id', '!=', $lesson->id)
                ->count();
            if ($count > 0) {
                $validated['slug'] .= '-' . ($count + 1);
            }
        }

        // Upload video file mới nếu có
        if ($request->hasFile('video_file')) {
            // Xóa video cũ nếu là file local
            if ($lesson->video_url && !filter_var($lesson->video_url, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($lesson->video_url);
            }
            $validated['video_url'] = $request->file('video_file')->store('lessons', 'public');
        }

        $validated['is_preview'] = $request->boolean('is_preview');

        $lesson->update($validated);

        return redirect()->route('admin.courses.lessons.index', $course)
            ->with('success', 'Cập nhật bài học thành công!');
    }

    /**
     * Xóa bài học
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        // Xóa video file nếu là local
        if ($lesson->video_url && !filter_var($lesson->video_url, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($lesson->video_url);
        }

        // Xóa tiến độ học
        $lesson->progress()->delete();

        $lesson->delete();

        return redirect()->route('admin.courses.lessons.index', $course)
            ->with('success', 'Xóa bài học thành công!');
    }

    /**
     * Cập nhật thứ tự bài học (AJAX)
     */
    public function updateOrder(Request $request, Course $course)
    {
        $request->validate([
            'lessons' => 'required|array',
            'lessons.*.id' => 'required|exists:lessons,id',
            'lessons.*.order' => 'required|integer|min:1',
        ]);

        foreach ($request->lessons as $item) {
            Lesson::where('id', $item['id'])
                ->where('course_id', $course->id)
                ->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true, 'message' => 'Cập nhật thứ tự thành công!']);
    }
}
