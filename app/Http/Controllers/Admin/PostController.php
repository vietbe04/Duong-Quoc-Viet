<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        if (!hasPermission('posts.view')) {
            abort(403, 'Bạn không có quyền xem danh sách bài viết.');
        }

        $query = Post::with(['category', 'user']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $posts = $query->latest()->paginate(10);
        $categories = Category::postType()->active()->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        if (!hasPermission('posts.create')) {
            abort(403, 'Bạn không có quyền tạo bài viết.');
        }

        $categories = Category::postType()->active()->get();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!hasPermission('posts.create')) {
            abort(403, 'Bạn không có quyền tạo bài viết.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'category_id' => 'nullable|exists:categories,id',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->except('image');
        $data['slug'] = $request->slug ?: Str::slug($request->name);
        $data['user_id'] = auth()->id();

        // Nếu status là active và chưa có published_at, tự động đặt thành now()
        if ($data['status'] === 'active' && !$data['published_at']) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($data);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Bài viết đã được tạo thành công!');
    }

    public function show(Post $post)
    {
        if (!hasPermission('posts.view')) {
            abort(403, 'Bạn không có quyền xem chi tiết bài viết.');
        }

        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        if (!hasPermission('posts.edit')) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết.');
        }

        $categories = Category::postType()->active()->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        if (!hasPermission('posts.edit')) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $post->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'category_id' => 'nullable|exists:categories,id',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->except('image');
        $data['slug'] = $request->slug ?: Str::slug($request->name);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Bài viết đã được cập nhật thành công!');
    }

    public function destroy(Post $post)
    {
        if (!hasPermission('posts.delete')) {
            abort(403, 'Bạn không có quyền xóa bài viết.');
        }

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Bài viết đã được xóa thành công!');
    }
}
