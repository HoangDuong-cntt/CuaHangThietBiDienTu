<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    /**
     * Danh sách mã giảm giá và thống kê chiến dịch khuyến mãi.
     */
    public function index(Request $request): View
    {
        $query = Coupon::query();

        // 1. Tìm kiếm theo mã code
        if ($request->filled('search')) {
            $search = strtoupper(trim((string) $request->string('search')));
            $query->where('code', 'like', "%{$search}%");
        }

        // 2. Lọc theo hình thức giảm giá
        if ($request->filled('discount_type')) {
            $query->where('discount_type', $request->string('discount_type'));
        }

        // 3. Lọc theo trạng thái
        if ($request->filled('status')) {
            $status = $request->string('status');
            $now = now();

            if ($status === 'active') {
                $query->where('is_active', true)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
                    })
                    ->where(function ($q) {
                        $q->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit');
                    });
            } elseif ($status === 'expired') {
                $query->where('expires_at', '<', $now);
            } elseif ($status === 'exhausted') {
                $query->whereNotNull('usage_limit')->whereColumn('used_count', '>=', 'usage_limit');
            } elseif ($status === 'disabled') {
                $query->where('is_active', false);
            }
        }

        $coupons = $query->latest('id')->paginate(12)->withQueryString();

        // 4. Thống kê KPI tổng quan
        $now = now();
        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::where('is_active', true)
                ->where(function ($q) use ($now) {
                    $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
                })
                ->where(function ($q) {
                    $q->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit');
                })
                ->count(),
            'expired' => Coupon::where('expires_at', '<', $now)->count(),
            'exhausted' => Coupon::whereNotNull('usage_limit')->whereColumn('used_count', '>=', 'usage_limit')->count(),
            'total_used' => (int) Coupon::sum('used_count'),
        ];

        return view('admin.coupons.index', [
            'title' => 'Quản lý Mã Giảm Giá & Voucher',
            'coupons' => $coupons,
            'stats' => $stats,
        ]);
    }

    /**
     * Tạo mới một mã giảm giá.
     */
    public function store(StoreCouponRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active', true);

        $coupon = Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', "Đã tạo mã giảm giá [{$coupon->code}] thành công!");
    }

    /**
     * Cập nhật thông tin mã giảm giá.
     */
    public function update(StoreCouponRequest $request, int $id): RedirectResponse
    {
        $coupon = Coupon::findOrFail($id);
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active', true);

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', "Đã cập nhật mã giảm giá [{$coupon->code}] thành công!");
    }

    /**
     * Bật / Tắt trạng thái kích hoạt của voucher nhanh.
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['is_active' => ! $coupon->is_active]);

        $statusText = $coupon->is_active ? 'Kích hoạt' : 'Tạm tắt';

        return redirect()->route('admin.coupons.index')
            ->with('success', "Đã {$statusText} mã giảm giá [{$coupon->code}] thành công!");
    }

    /**
     * Xóa một mã giảm giá khỏi hệ thống.
     */
    public function destroy(int $id): RedirectResponse
    {
        $coupon = Coupon::withCount('usages')->findOrFail($id);

        if ($coupon->usages_count > 0) {
            return back()->with('error', "Mã [{$coupon->code}] đã có {$coupon->usages_count} lượt sử dụng trong các đơn hàng, vui lòng Tạm tắt thay vì xóa bản ghi.");
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', "Đã xóa mã giảm giá [{$coupon->code}] thành công!");
    }
}
