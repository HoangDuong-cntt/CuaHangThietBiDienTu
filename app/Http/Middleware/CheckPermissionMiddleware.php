<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login'));
        }

        $user = Auth::user();

        // Admin luôn có toàn quyền, nhân viên kiểm tra theo quyền hạn
        if (! $user->hasPermission($permission)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Bạn không có quyền thực hiện thao tác này [{$permission}].",
                ], 403);
            }

            abort(403, "Bạn không có quyền truy cập chức năng này ({$permission}). Vui lòng liên hệ Quản trị viên để được cấp quyền.");
        }

        return $next($request);
    }
}
