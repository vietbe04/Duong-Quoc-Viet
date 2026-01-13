<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            /** @var User $user */
            $user = Auth::user();
            
            if (!$user->isAdmin()) {
                Auth::logout();
                return redirect()->route('admin.login')
                    ->with('error', 'Bạn không có quyền truy cập vào trang quản trị!');
            }

            if ($user->status !== 'active') {
                Auth::logout();
                return redirect()->route('admin.login')
                    ->with('error', 'Tài khoản của bạn đã bị khóa!');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->route('admin.login')
            ->withInput($request->only('email', 'remember'))
            ->with('error', 'Email hoặc mật khẩu không đúng!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}
