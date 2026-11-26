<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password', ['title' => 'Quên mật khẩu']);
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate(
            ['email' => ['required', 'email']],
            [
                'email.required' => 'Vui lòng nhập địa chỉ email của bạn.',
                'email.email' => 'Địa chỉ email không đúng định dạng.',
            ]
        );

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Đường dẫn khôi phục mật khẩu đã được gửi đến hộp thư của bạn!');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
