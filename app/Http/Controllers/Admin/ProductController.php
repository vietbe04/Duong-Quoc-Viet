<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if (!hasPermission('products.view')) {
            abort(403, 'Bạn không có quyền xem danh sách sản phẩm.');
        }

        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::productType()->active()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        if (!hasPermission('products.create')) {
            abort(403, 'Bạn không có quyền tạo sản phẩm.');
        }

        $categories = Category::productType()->active()->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!hasPermission('products.create')) {
            abort(403, 'Bạn không có quyền tạo sản phẩm.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:regular_price',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'category_id' => 'nullable|exists:categories,id',
            'quantity' => 'nullable|integer|min:0',
            'published_at' => 'nullable|date',
        ], [
            'sale_price.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá gốc.',
        ]);

        $data = $request->except(['image', 'thumbnail']);
        $data['slug'] = $request->slug ?: Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    public function show(Product $product)
    {
        if (!hasPermission('products.view')) {
            abort(403, 'Bạn không có quyền xem chi tiết sản phẩm.');
        }

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if (!hasPermission('products.edit')) {
            abort(403, 'Bạn không có quyền chỉnh sửa sản phẩm.');
        }

        $categories = Category::productType()->active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if (!hasPermission('products.edit')) {
            abort(403, 'Bạn không có quyền chỉnh sửa sản phẩm.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:regular_price',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'category_id' => 'nullable|exists:categories,id',
            'quantity' => 'nullable|integer|min:0',
            'published_at' => 'nullable|date',
        ], [
            'sale_price.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá gốc.',
        ]);

        $data = $request->except(['image', 'thumbnail']);
        $data['slug'] = $request->slug ?: Str::slug($request->name);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    public function destroy(Product $product)
    {
        if (!hasPermission('products.delete')) {
            abort(403, 'Bạn không có quyền xóa sản phẩm.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được xóa thành công!');
    }
}
