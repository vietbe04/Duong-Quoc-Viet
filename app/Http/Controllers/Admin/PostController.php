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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'category_id' => 'nullable|exists:categories,id',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->except(['image', 'images']);
        $data['slug'] = $request->slug ?: Str::slug($request->name);
        $data['user_id'] = auth()->id();

        // Nếu status là active và chưa có published_at, tự động đặt thành now()
        if ($data['status'] === 'active' && !$data['published_at']) {
            $data['published_at'] = now();
        }

        $imageService = app(\App\Services\ImageService::class);

        // Upload nhiều ảnh
        if ($request->hasFile('images')) {
            $uploadedImages = $imageService->uploadMultiple(
                $request->file('images'),
                $data['slug']
            );
            $data['images'] = $uploadedImages;
            
            // TỰ ĐỘNG: Lấy ảnh đầu tiên làm Avatar 300x300
            if (isset($uploadedImages[0])) {
                // Đường dẫn thumbnail là cùng tên nhưng ở thư mục thumbnails/
                $filename = basename($uploadedImages[0]);
                $data['image'] = 'uploads/thumbnails/' . $filename;
            }
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'nullable|string',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'category_id' => 'nullable|exists:categories,id',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->except(['image', 'images', 'remove_images']);
        $data['slug'] = $request->slug ?: Str::slug($request->name);

        $imageService = app(\App\Services\ImageService::class);

        $imageService = app(\App\Services\ImageService::class);
        $currentImages = $post->images ?? [];
        
        // Xử lý xóa ảnh khỏi album
        if ($request->filled('remove_images')) {
            $removeImages = $request->remove_images;
            
            foreach ($removeImages as $imagePath) {
                if (in_array($imagePath, $currentImages)) {
                    $imageService->delete($imagePath);
                }
            }
            
            $currentImages = array_values(array_diff($currentImages, $removeImages));
        }

        // Upload thêm ảnh mới vào album
        if ($request->hasFile('images')) {
            $newImages = $imageService->uploadMultiple(
                $request->file('images'),
                $data['slug']
            );
            $currentImages = array_merge($currentImages, $newImages);
        }

        $data['images'] = $currentImages;

        // TỰ ĐỘNG CẬP NHẬT AVATAR:
        // Nếu sếp vừa xóa ảnh đang làm avatar, hoặc bài viết chưa có avatar, chọn ảnh đầu tiên còn lại
        if (!empty($currentImages)) {
            $firstImagePath = $currentImages[0];
            $filename = basename($firstImagePath);
            $newAvatarPath = 'uploads/thumbnails/' . $filename;
            
            // Chỉ cập nhật nếu avatar thay đổi hoặc chưa có
            if ($post->image != $newAvatarPath) {
                $data['image'] = $newAvatarPath;
            }
        } else {
            // Nếu xóa hết album thì xóa luôn avatar
            $data['image'] = null;
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
