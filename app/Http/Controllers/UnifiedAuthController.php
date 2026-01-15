<?php

namespace App\Http\Controllers;

use App\Jobs\SendWelcomeEmailJob;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UnifiedAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.unified-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required|in:user,admin',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');
        $role = $request->role;

        if (Auth::attempt($credentials, $remember)) {
            /** @var User $user */
            $user = Auth::user();

            // Kiểm tra role phù hợp
            if ($role === 'admin' && !$user->isAdmin()) {
                Auth::logout();
                return redirect()->route('unified.login')
                    ->with('error', 'Bạn không có quyền truy cập vào trang quản trị!');
            }

            if ($role === 'user' && $user->isAdmin()) {
                Auth::logout();
                return redirect()->route('unified.login')
                    ->with('error', 'Tài khoản admin không thể đăng nhập tại đây!');
            }

            // Kiểm tra trạng thái tài khoản
            if ($user->status !== 'active') {
                Auth::logout();
                return redirect()->route('unified.login')
                    ->with('error', 'Tài khoản của bạn đã bị khóa!');
            }

            $request->session()->regenerate();

            // Redirect theo vai trò
            if ($role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                return redirect()->intended(route('home'));
            }
        }

        return redirect()->route('unified.login')
            ->withInput($request->only('email', 'remember', 'role'))
            ->with('error', 'Email hoặc mật khẩu không đúng!');
    }

    public function showRegisterForm()
    {
        return view('auth.unified-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'status' => 'active',
        ]);

        // Gán role "user" cho tài khoản mới
        $userRole = Role::where('slug', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole);
        }

        // Dispatch welcome email job
        SendWelcomeEmailJob::dispatch($user->id)->onQueue('emails');

        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Đăng ký tài khoản thành công!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('unified.login')
            ->with('success', 'Bạn đã đăng xuất thành công!');
    }
}
