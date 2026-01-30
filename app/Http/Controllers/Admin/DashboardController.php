<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function index()
    {
        // Thống kê tổng quan
        $stats = [
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_instructors' => User::where('role', 'instructor')->count(),
            'total_courses' => Course::count(),
            'published_courses' => Course::where('status', 'published')->count(),
            'total_orders' => Order::count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
        ];

        // Đơn hàng gần đây
        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->limit(5)
            ->get();

        // User mới đăng ký
        $recentUsers = User::latest()
            ->limit(5)
            ->get();

        // Khóa học mới
        $recentCourses = Course::with('category')
            ->latest()
            ->limit(5)
            ->get();

        // Thống kê theo tháng (6 tháng gần nhất)
        $monthlyStats = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'recentUsers',
            'recentCourses',
            'monthlyStats'
        ));
    }
}
