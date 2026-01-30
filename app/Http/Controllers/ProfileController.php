<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Trang cá nhân
     */
    public function index()
    {
        $user = auth()->user();

        // Khóa học đã mua
        $purchasedCourses = $user->purchasedCourses()
            ->with('category')
            ->withPivot('enrolled_at')
            ->latest('course_user.enrolled_at')
            ->get();

        // Tính tiến độ học cho từng khóa học
        foreach ($purchasedCourses as $course) {
            $totalLessons = $course->lessons()->where('status', 'active')->count();
            $completedLessons = $course->lessons()
                ->where('status', 'active')
                ->whereHas('progress', function ($query) use ($user) {
                    $query->where('user_id', $user->id)->where('is_completed', true);
                })
                ->count();

            $course->progress_percent = $totalLessons > 0
                ? round(($completedLessons / $totalLessons) * 100)
                : 0;
        }

        return view('profile.index', compact('user', 'purchasedCourses'));
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'avatar.image' => 'File phải là hình ảnh',
            'avatar.max' => 'Kích thước ảnh tối đa 2MB',
        ]);

        // Upload avatar nếu có
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ]);

        $user = auth()->user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    /**
     * Lịch sử đơn hàng
     */
    public function orders()
    {
        $orders = auth()->user()
            ->orders()
            ->with('items.course')
            ->latest()
            ->paginate(10);

        return view('profile.orders', compact('orders'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function orderDetail($id)
    {
        $order = auth()->user()
            ->orders()
            ->with('items.course')
            ->findOrFail($id);

        return view('profile.order-detail', compact('order'));
    }
}
