<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UpdatePasswordRequest;
use App\Http\Requests\Customer\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Hiển thị trang quản lý hồ sơ cá nhân và sổ địa chỉ.
     */
    public function edit(): View
    {
        $user = Auth::user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->latest()->get();

        return view('customer.profile', [
            'user' => $user,
            'addresses' => $addresses,
            'title' => 'Hồ sơ cá nhân & Sổ địa chỉ',
        ]);
    }

    /**
     * Cập nhật thông tin họ tên, số điện thoại, ảnh đại diện.
     */
    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $data = [
            'name' => $request->string('name'),
            'phone' => $request->filled('phone') ? $request->string('phone') : null,
        ];

        // Xử lý upload ảnh đại diện mới
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có và là ảnh cục bộ
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Thông tin tài khoản đã được cập nhật thành công!');
    }

    /**
     * Cập nhật mật khẩu mới.
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->string('password')),
        ]);

        return back()->with('success', 'Mật khẩu của bạn đã được thay đổi thành công!');
    }
}
