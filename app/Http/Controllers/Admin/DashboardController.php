<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->hasPermission('dashboard.view') && !$user->isSuperAdmin()) {
            abort(403, 'Bạn không có quyền xem dashboard.');
        }

        $data = [
            'totalUsers' => User::count(),
            'totalPosts' => Post::count(),
            'totalProducts' => Product::count(),
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'totalRevenue' => Order::where('status', 'delivered')->sum('total'),
            'recentOrders' => Order::with('user')->latest()->limit(5)->get(),
            'recentUsers' => User::latest()->limit(5)->get(),
        ];

        return view('admin.dashboard', $data);
    }
}
