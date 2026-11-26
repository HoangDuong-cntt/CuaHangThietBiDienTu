<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Hiển thị giao diện đăng ký tài khoản.
     */
    public function showRegistrationForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }

        return view('auth.register', ['title' => 'Đăng ký tài khoản']);
    }

    /**
     * Xử lý đăng ký tài khoản khách hàng mới.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->string('name'),
            'email' => $request->string('email'),
            'phone' => $request->filled('phone') ? $request->string('phone') : null,
            'password' => Hash::make($request->string('password')),
            'role' => 'customer',
            'is_active' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/')->with('success', 'Tạo tài khoản thành công! Chào mừng bạn đến với SmartTech.');
    }
}
