<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login'))->with('error', 'Vui lòng đăng nhập bằng tài khoản quản trị.');
        }

        $user = Auth::user();

        // Kiểm tra tài khoản có bị khóa không
        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.');
        }

        // Chỉ cho phép admin hoặc staff truy cập
        if (! $user->isStaff()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền truy cập khu vực quản trị.',
                ], 403);
            }

            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập khu vực quản trị.');
        }

        return $next($request);
    }
}
