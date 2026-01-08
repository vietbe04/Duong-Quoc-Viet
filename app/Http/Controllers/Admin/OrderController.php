<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if (!hasPermission('orders.view')) {
            abort(403, 'Bạn không có quyền xem danh sách đơn hàng.');
        }

        $query = Order::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                    ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                    ->orWhere('customer_email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->latest()->paginate(10);
        $statuses = Order::getStatuses();
        $paymentStatuses = Order::getPaymentStatuses();

        return view('admin.orders.index', compact('orders', 'statuses', 'paymentStatuses'));
    }

    public function show(Order $order)
    {
        if (!hasPermission('orders.view')) {
            abort(403, 'Bạn không có quyền xem chi tiết đơn hàng.');
        }

        $order->load(['user', 'items.product']);
        $statuses = Order::getStatuses();
        $paymentStatuses = Order::getPaymentStatuses();
        
        return view('admin.orders.show', compact('order', 'statuses', 'paymentStatuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if (!hasPermission('orders.edit')) {
            abort(403, 'Bạn không có quyền cập nhật trạng thái đơn hàng.');
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        if (!hasPermission('orders.edit')) {
            abort(403, 'Bạn không có quyền cập nhật trạng thái thanh toán.');
        }

        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return redirect()->back()
            ->with('success', 'Trạng thái thanh toán đã được cập nhật!');
    }

    public function destroy(Order $order)
    {
        if (!hasPermission('orders.delete')) {
            abort(403, 'Bạn không có quyền xóa đơn hàng.');
        }

        $order->items()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đơn hàng đã được xóa!');
    }
}
