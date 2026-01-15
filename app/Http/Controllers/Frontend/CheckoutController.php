<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Jobs\SendOrderConfirmationEmailJob;
use App\Jobs\SendAdminNotificationJob;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('success');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'subtotal' => $details['price'] * $details['quantity'],
                ];
                $subtotal += $details['price'] * $details['quantity'];
            }
        }

        $user = Auth::user();

        return view('frontend.checkout.index', compact('cartItems', 'subtotal', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'note' => 'nullable|string',
            'payment_method' => 'required|in:cod,bank_transfer,credit_card',
            'agree' => 'required|accepted',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        DB::beginTransaction();
        try {
            // Calculate subtotal
            $subtotal = 0;
            foreach ($cart as $id => $details) {
                $subtotal += $details['price'] * $details['quantity'];
            }

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $request->name,
                'customer_email' => $request->email,
                'customer_phone' => $request->phone,
                'shipping_address' => $request->address,
                'notes' => $request->note,
                'subtotal' => $subtotal,
                'shipping_fee' => 0,
                'discount' => 0,
                'total' => $subtotal,
                'status' => Order::STATUS_PENDING,
                'payment_method' => $request->payment_method,
                'payment_status' => Order::PAYMENT_STATUS_PENDING,
            ]);

            // Create order items
            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                if ($product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_image' => $product->image,
                        'price' => $details['price'],
                        'quantity' => $details['quantity'],
                        'total' => $details['price'] * $details['quantity'],
                    ]);

                    // Decrease product quantity
                    if ($product->quantity >= $details['quantity']) {
                        $product->decrement('quantity', $details['quantity']);
                    }
                }
            }

            // Clear cart
            session()->forget('cart');

            DB::commit();

            // Dispatch order confirmation email via queue (non-blocking)
            try {
                SendOrderConfirmationEmailJob::dispatch($order->id)->onQueue('emails');
                Log::info('Order confirmation email job dispatched: ' . $order->order_number);
            } catch (\Exception $e) {
                Log::error('Failed to dispatch order confirmation: ' . $e->getMessage());
            }

            // Dispatch admin notifications via queue (non-blocking)
            try {
                $adminEmail = config('mail.from.address');
                if ($adminEmail && $adminEmail !== 'admin@example.com') {
                    SendAdminNotificationJob::dispatch($order->id, $adminEmail)->onQueue('emails');
                    Log::info('Admin notification job dispatched: ' . $adminEmail);
                }
                
                $backupEmail = 'duongdinhcuongviajsc@gmail.com';
                SendAdminNotificationJob::dispatch($order->id, $backupEmail)->onQueue('emails');
                Log::info('Admin notification job dispatched: ' . $backupEmail);
            } catch (\Exception $e) {
                Log::error('Failed to dispatch admin notifications: ' . $e->getMessage());
            }

            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại!')
                ->withInput();
        }
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items')
            ->firstOrFail();

        return view('frontend.checkout.success', compact('order'));
    }
}
