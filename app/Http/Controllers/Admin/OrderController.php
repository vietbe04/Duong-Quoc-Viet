<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        // Tìm kiếm theo mã đơn hàng hoặc tên khách hàng
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo phương thức thanh toán
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Lọc theo khoảng thời gian
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        // Thống kê
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'revenue' => Order::where('status', 'completed')->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.course']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Form chỉnh sửa đơn hàng
     */
    public function edit(Order $order)
    {
        $order->load(['user', 'items.course']);

        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Cập nhật đơn hàng
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
            'note' => 'nullable|string|max:500',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update([
            'status' => $newStatus,
            'note' => $request->note,
        ]);

        // Nếu chuyển sang completed, ghi nhận user đã mua khóa học
        if ($oldStatus !== 'completed' && $newStatus === 'completed') {
            foreach ($order->items as $item) {
                if (!$order->user->purchasedCourses()->where('course_id', $item->course_id)->exists()) {
                    $order->user->purchasedCourses()->attach($item->course_id, [
                        'enrolled_at' => now(),
                    ]);
                }
            }
        }

        // Nếu hủy đơn hàng từ trạng thái completed, gỡ khóa học đã mua
        if ($newStatus === 'cancelled' && $oldStatus === 'completed') {
            foreach ($order->items as $item) {
                $order->user->purchasedCourses()->detach($item->course_id);
            }
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Cập nhật đơn hàng thành công!');
    }

    /**
     * Cập nhật trạng thái đơn hàng (AJAX)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update(['status' => $newStatus]);

        // Nếu chuyển sang completed, ghi nhận user đã mua khóa học
        if ($oldStatus !== 'completed' && $newStatus === 'completed') {
            foreach ($order->items as $item) {
                // Kiểm tra nếu chưa có
                if (!$order->user->purchasedCourses()->where('course_id', $item->course_id)->exists()) {
                    $order->user->purchasedCourses()->attach($item->course_id, [
                        'enrolled_at' => now(),
                    ]);
                }
            }
        }

        // Nếu hủy đơn hàng, gỡ khóa học đã mua
        if ($newStatus === 'cancelled' && $oldStatus === 'completed') {
            foreach ($order->items as $item) {
                $order->user->purchasedCourses()->detach($item->course_id);
            }
        }

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    /**
     * Xóa đơn hàng
     */
    public function destroy(Order $order)
    {
        // Không cho xóa đơn hàng đã hoàn thành
        if ($order->status === 'completed') {
            return back()->with('error', 'Không thể xóa đơn hàng đã hoàn thành!');
        }

        // Xóa chi tiết đơn hàng
        $order->items()->delete();

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Xóa đơn hàng thành công!');
    }
}
