<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('frontend.orders.index', compact('orders'));
    }

    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with('items.product')
            ->firstOrFail();

        return view('frontend.orders.show', compact('order'));
    }

    public function cancel($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return redirect()->back()
                ->with('error', 'Không thể hủy đơn hàng này!');
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        return redirect()->back()
            ->with('success', 'Đơn hàng đã được hủy!');
    }
}
