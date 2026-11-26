<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Permission;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Hiển thị bảng điều khiển tổng quan dành cho Quản trị viên & Nhân viên.
     */
    public function index(): View
    {
        $stats = [
            'total_customers' => User::where('role', 'customer')->count(),
            'total_staff' => User::where('role', 'staff')->count(),
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'active_coupons' => Coupon::where('is_active', true)->count(),
            'total_reviews' => Review::count(),
        ];

        $recentStaff = User::whereIn('role', ['admin', 'staff'])
            ->with('roleModel')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'title' => 'Bảng điều khiển Quản trị',
            'stats' => $stats,
            'recentStaff' => $recentStaff,
        ]);
    }
}
