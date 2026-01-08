<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()->with('category', 'user');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $posts = $query->latest()->paginate(12);
        $categories = Category::postType()->active()->get();
        $recentPosts = Post::published()->latest()->limit(5)->get();

        return view('frontend.posts.index', compact('posts', 'categories', 'recentPosts'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->published()
            ->with('category', 'user')
            ->firstOrFail();

        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->limit(4)
            ->get();

        // Lấy bài viết trước và sau
        $prevPost = Post::published()
            ->where('id', '<', $post->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextPost = Post::published()
            ->where('id', '>', $post->id)
            ->orderBy('id', 'asc')
            ->first();

        // Lấy danh mục với số lượng bài viết
        $categories = Category::postType()
            ->active()
            ->withCount(['posts' => function($query) {
                $query->published();
            }])
            ->get();

        return view('frontend.posts.show', compact('post', 'relatedPosts', 'prevPost', 'nextPost', 'categories'));
    }
}
