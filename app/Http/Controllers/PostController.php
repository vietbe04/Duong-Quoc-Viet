<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('categories')->latest()->paginate(15);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'thumbnail' => 'required|image',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Generate unique slug
        $slug = Str::slug($data['name']);
        $original = $slug;
        $i = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }

        $path = $request->file('thumbnail')->store('thumbnails', 'public');

        $post = Post::create([
            'name' => $data['name'],
            'slug' => $slug,
            'thumbnail' => $path,
            'description' => $data['description'],
            'content' => $data['content'] ?? null,
        ]);

        if (!empty($data['categories'])) {
            $post->categories()->sync($data['categories']);
        }

        return redirect()->route('posts.index')->with('success', 'Post created.');
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        $selected = $post->categories->pluck('id')->toArray();
        return view('posts.edit', compact('post','categories','selected'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'thumbnail' => 'nullable|image',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Update slug if name changed
        if ($data['name'] !== $post->name) {
            $slug = Str::slug($data['name']);
            $original = $slug;
            $i = 1;
            while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug = $original . '-' . $i++;
            }
            $post->slug = $slug;
        }

        if ($request->hasFile('thumbnail')) {
            // delete old
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $post->thumbnail = $path;
        }

        $post->name = $data['name'];
        $post->description = $data['description'];
        $post->content = $data['content'] ?? null;
        $post->save();

        $post->categories()->sync($data['categories'] ?? []);

        return redirect()->route('posts.index')->with('success', 'Post updated.');
    }

    public function destroy(Post $post)
    {
        // delete thumbnail
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }
        $post->categories()->detach();
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted.');
    }
}
