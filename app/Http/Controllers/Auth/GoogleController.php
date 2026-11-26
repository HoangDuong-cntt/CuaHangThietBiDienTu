<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Chuyển hướng người dùng sang trang đăng nhập của Google.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')->with('error', 'Chức năng Google OAuth chưa được cấu hình GOOGLE_CLIENT_ID & GOOGLE_CLIENT_SECRET trong file .env.');
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Tiếp nhận phản hồi từ Google sau khi người dùng đồng ý cấp quyền.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Tìm tài khoản theo google_id hoặc email đã đăng ký
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Cập nhật google_id và avatar nếu chưa có
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $user->avatar ?? $googleUser->getAvatar(),
                ]);
            } else {
                // Tạo mới tài khoản khách hàng từ thông tin Google
                $user = User::create([
                    'name' => $googleUser->getName() ?? 'Google User',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(24)), // Mật khẩu ngẫu nhiên an toàn
                    'role' => 'customer',
                    'is_active' => true,
                    'email_verified_at' => now(), // Đã xác thực qua Google
                ]);
            }

            if (! $user->is_active) {
                return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.');
            }

            Auth::login($user, true);
            request()->session()->regenerate();

            return redirect()->intended('/')->with('success', "Đăng nhập với Google thành công! Xin chào {$user->name}.");
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Đăng nhập Google thất bại: '.$e->getMessage());
        }
    }
}
