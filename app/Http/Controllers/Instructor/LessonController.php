<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LessonController extends Controller
{
    use AuthorizesRequests;
    /**
     * Danh sách bài học
     */
    public function index(Course $course)
    {
        $this->authorize('update', $course);

        $lessons = $course->lessons()->orderBy('order')->get();

        return view('instructor.lessons.index', compact('course', 'lessons'));
    }

    /**
     * Form tạo bài học
     */
    public function create(Course $course)
    {
        $this->authorize('update', $course);

        $nextOrder = $course->lessons()->max('order') + 1;

        return view('instructor.lessons.create', compact('course', 'nextOrder'));
    }

    /**
     * Lưu bài học mới
     */
    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

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

        $validated['slug'] = Str::slug($validated['title']);
        $count = Lesson::where('course_id', $course->id)
            ->where('slug', $validated['slug'])
            ->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        if ($request->hasFile('video_file')) {
            $validated['video_url'] = $request->file('video_file')->store('lessons', 'public');
        }

        $validated['course_id'] = $course->id;
        $validated['is_preview'] = $request->boolean('is_preview');

        Lesson::create($validated);

        return redirect()->route('instructor.courses.lessons.index', $course)
            ->with('success', 'Tạo bài học thành công!');
    }

    /**
     * Form sửa bài học
     */
    public function edit(Course $course, Lesson $lesson)
    {
        $this->authorize('update', $course);

        return view('instructor.lessons.edit', compact('course', 'lesson'));
    }

    /**
     * Cập nhật bài học
     */
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $this->authorize('update', $course);

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

        if ($request->hasFile('video_file')) {
            if ($lesson->video_url && !filter_var($lesson->video_url, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($lesson->video_url);
            }
            $validated['video_url'] = $request->file('video_file')->store('lessons', 'public');
        }

        $validated['is_preview'] = $request->boolean('is_preview');

        $lesson->update($validated);

        return redirect()->route('instructor.courses.lessons.index', $course)
            ->with('success', 'Cập nhật bài học thành công!');
    }

    /**
     * Xóa bài học
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        $this->authorize('update', $course);

        if ($lesson->video_url && !filter_var($lesson->video_url, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($lesson->video_url);
        }

        $lesson->progress()->delete();
        $lesson->delete();

        return redirect()->route('instructor.courses.lessons.index', $course)
            ->with('success', 'Xóa bài học thành công!');
    }

    /**
     * Cập nhật thứ tự bài học
     */
    public function updateOrder(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        foreach ($request->lessons as $item) {
            Lesson::where('id', $item['id'])
                ->where('course_id', $course->id)
                ->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}
