<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReviewController extends Controller
{
    use AuthorizesRequests;
    /**
     * Tạo đánh giá mới
     */
    public function store(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);

        // Kiểm tra đã mua khóa học chưa
        $this->authorize('create', [Review::class, $course]);

        // Kiểm tra đã đánh giá chưa
        $existingReview = Review::where('user_id', auth()->id())
            ->where('course_id', $courseId)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Bạn đã đánh giá khóa học này rồi!');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá',
            'rating.min' => 'Đánh giá tối thiểu 1 sao',
            'rating.max' => 'Đánh giá tối đa 5 sao',
            'comment.required' => 'Vui lòng nhập nội dung đánh giá',
            'comment.min' => 'Nội dung đánh giá tối thiểu 10 ký tự',
            'comment.max' => 'Nội dung đánh giá tối đa 1000 ký tự',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'course_id' => $courseId,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'status' => 'pending', // Chờ duyệt
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được gửi và đang chờ duyệt!');
    }

    /**
     * Cập nhật đánh giá
     */
    public function update(Request $request, $id)
    {
        $review = Review::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'status' => 'pending', // Reset về chờ duyệt
        ]);

        return back()->with('success', 'Đánh giá đã được cập nhật!');
    }

    /**
     * Xóa đánh giá
     */
    public function destroy($id)
    {
        $review = Review::where('user_id', auth()->id())->findOrFail($id);
        $review->delete();

        return back()->with('success', 'Đã xóa đánh giá!');
    }
}
