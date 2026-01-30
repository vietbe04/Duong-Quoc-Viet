<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    /**
     * Trang thanh toán
     */
    public function index()
    {
        $cartItems = Cart::with('course')
            ->where('user_id', auth()->id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->course->sale_price ?? $item->course->price;
        });

        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * Xử lý thanh toán
     */
    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cod,bank_transfer,momo',
            'notes' => 'nullable|string|max:500',
        ], [
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ',
        ]);

        $cartItems = Cart::with('course')
            ->where('user_id', auth()->id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        // Kiểm tra lại xem có khóa học nào đã mua chưa
        $purchasedCourseIds = auth()->user()->purchasedCourses()->pluck('courses.id')->toArray();
        $cartCourseIds = $cartItems->pluck('course_id')->toArray();
        $duplicates = array_intersect($purchasedCourseIds, $cartCourseIds);

        if (!empty($duplicates)) {
            // Xóa các khóa học đã mua khỏi giỏ hàng
            Cart::where('user_id', auth()->id())
                ->whereIn('course_id', $duplicates)
                ->delete();

            return redirect()->route('cart.index')
                ->with('error', 'Một số khóa học bạn đã mua. Vui lòng kiểm tra lại giỏ hàng.');
        }

        DB::beginTransaction();

        try {
            // Tính tổng tiền
            $total = $cartItems->sum(function ($item) {
                return $item->course->sale_price ?? $item->course->price;
            });

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'payment_method' => $request->payment_method,
                'note' => $request->notes,
                'status' => $request->payment_method === 'cod' ? 'completed' : 'pending',
            ]);

            // Tạo chi tiết đơn hàng
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'course_id' => $item->course_id,
                    'price' => $item->course->sale_price ?? $item->course->price,
                ]);

                // Ghi nhận user đã mua khóa học (nếu thanh toán COD hoặc đã xác nhận)
                if ($order->status === 'completed') {
                    auth()->user()->purchasedCourses()->attach($item->course_id, [
                        'enrolled_at' => now(),
                    ]);
                }
            }

            // Xóa giỏ hàng
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            // Gửi email xác nhận (sử dụng queue)
            // Mail::to(auth()->user()->email)->queue(new OrderConfirmation($order));

            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Trang thanh toán thành công
     */
    public function success($orderId)
    {
        $order = Order::with(['items.course'])
            ->where('user_id', auth()->id())
            ->findOrFail($orderId);

        return view('checkout.success', compact('order'));
    }
}
