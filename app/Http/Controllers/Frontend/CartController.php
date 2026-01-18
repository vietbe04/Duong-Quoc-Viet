<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'subtotal' => $details['price'] * $details['quantity'],
                ];
                $total += $details['price'] * $details['quantity'];
            }
        }

        return view('frontend.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, $product_id = null)
    {
        // Lấy product_id từ URL parameter hoặc request
        $product_id = $product_id ?? $request->input('product_id');

        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($product_id);
        $quantity = $request->quantity ?? 1;

        $cart = session()->get('cart', []);

        // Lấy giá hiện tại của sản phẩm (sale_price nếu có, nếu không thì regular_price)
        $price = $product->current_price ?? $product->sale_price ?? $product->regular_price ?? 0;
        $price = (float) $price;

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
            // Cập nhật giá mới nhất
            $cart[$product->id]['price'] = $price;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $price,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
                'cartCount' => $this->getCartCount(),
            ]);
        }

        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }

    public function update(Request $request, $product_id = null)
    {
        // Lấy product_id từ URL parameter hoặc request
        $product_id = $product_id ?? $request->input('product_id');

        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = session()->get('cart', []);

        if ($request->quantity == 0) {
            unset($cart[$product_id]);
        } elseif (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] = $request->quantity;
        }

        session()->put('cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Giỏ hàng đã được cập nhật!',
                'cartCount' => $this->getCartCount(),
                'total' => $this->getCartTotal(),
            ]);
        }

        return redirect()->back()->with('success', 'Giỏ hàng đã được cập nhật!');
    }

    public function remove(Request $request, $product_id = null)
    {
        // Lấy product_id từ URL parameter hoặc request
        $product_id = $product_id ?? $request->input('product_id');

        $request->validate([
            'product_id' => 'nullable|exists:products,id',
        ]);

        $cart = session()->get('cart', []);
        
        if (isset($cart[$product_id])) {
            unset($cart[$product_id]);
        }

        session()->put('cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng!',
                'cartCount' => $this->getCartCount(),
                'total' => $this->getCartTotal(),
            ]);
        }

        return redirect()->back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng!');
    }

    public function clear()
    {
        session()->forget('cart');

        return redirect()->back()->with('success', 'Giỏ hàng đã được xóa!');
    }

    private function getCartCount()
    {
        $cart = session()->get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }

    private function getCartTotal()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return number_format($total, 0, ',', '.') . ' đ';
    }

    public function getCart()
    {
        return response()->json([
            'count' => $this->getCartCount(),
            'total' => $this->getCartTotal(),
        ]);
    }
}
