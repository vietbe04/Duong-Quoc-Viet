<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission): Response
    {
        if (!Auth::check()) {
            return redirect()->route('unified.login');
        }

        /** @var User $user */
        $user = Auth::user();

        if (!$user->hasPermission($permission) && !$user->isSuperAdmin()) {
            return response()->view('errors.403', [
                'message' => 'Bạn không có quyền truy cập trang này.'
            ], 403);
        }

        return $next($request);
    }
}
