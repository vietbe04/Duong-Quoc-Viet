<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Course;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        $cartItems = Cart::with('course')
            ->where('user_id', auth()->id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->course->sale_price ?? $item->course->price;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Thêm khóa học vào giỏ hàng
     */
    public function add(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $courseId = $request->course_id;
        $userId = auth()->id();

        // Kiểm tra đã mua khóa học chưa
        if (auth()->user()->purchasedCourses()->where('course_id', $courseId)->exists()) {
            return back()->with('error', 'Bạn đã mua khóa học này rồi!');
        }

        // Kiểm tra đã có trong giỏ hàng chưa
        $exists = Cart::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();

        if ($exists) {
            return back()->with('warning', 'Khóa học đã có trong giỏ hàng!');
        }

        Cart::create([
            'user_id' => $userId,
            'course_id' => $courseId,
        ]);

        return back()->with('success', 'Đã thêm khóa học vào giỏ hàng!');
    }

    /**
     * Xóa khóa học khỏi giỏ hàng
     */
    public function remove($id)
    {
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        return back()->with('success', 'Đã xóa khóa học khỏi giỏ hàng!');
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        Cart::where('user_id', auth()->id())->delete();

        return back()->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    /**
     * Đếm số lượng trong giỏ hàng
     */
    public function count()
    {
        $count = Cart::where('user_id', auth()->id())->count();

        return response()->json(['count' => $count]);
    }
}
