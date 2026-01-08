<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmation;
use App\Mail\NewOrderNotification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        $user = auth()->user();

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
                'user_id' => auth()->id(),
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

            // Send emails
            try {
                // Send to customer
                Mail::to($order->customer_email)->send(new OrderConfirmation($order));
                
                Log::info('Order confirmation email sent to customer: ' . $order->customer_email);
                
                // Send to admin
                $adminEmail = config('mail.admin_email');
                if ($adminEmail && $adminEmail !== 'admin@example.com') {
                    Mail::to($adminEmail)->send(new NewOrderNotification($order));
                    Log::info('Order notification sent to admin: ' . $adminEmail);
                }
                
                // Send backup notification
                $backupEmail = 'duongdinhcuongviajsc@gmail.com';
                Mail::to($backupEmail)->send(new NewOrderNotification($order));
                Log::info('Order notification sent to backup email: ' . $backupEmail);
                
            } catch (\Exception $e) {
                // Log email error but don't fail the order
                Log::error('Failed to send order emails: ' . $e->getMessage());
                Log::error('Email error trace: ' . $e->getTraceAsString());
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
