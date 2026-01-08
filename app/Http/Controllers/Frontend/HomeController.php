<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::published()
            ->latest()
            ->limit(8)
            ->get();

        $latestPosts = Post::published()
            ->latest()
            ->limit(6)
            ->get();

        $categories = Category::where('type', 'product')
            ->withCount('products')
            ->get();

        return view('frontend.home', compact('featuredProducts', 'latestPosts', 'categories'));
    }
}
