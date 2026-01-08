<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('unified.login')
                ->with('error', 'Vui lòng đăng nhập để tiếp tục!');
        }

        /** @var User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            Auth::logout();
            return redirect()->route('unified.login')
                ->with('error', 'Bạn không có quyền truy cập vào trang quản trị!');
        }

        if ($user->status !== 'active') {
            Auth::logout();
            return redirect()->route('unified.login')
                ->with('error', 'Tài khoản của bạn đã bị khóa!');
        }

        return $next($request);
    }
}
