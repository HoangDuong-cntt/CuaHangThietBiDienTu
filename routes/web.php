<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Hệ Thống Thương Mại Điện Tử SmartTech
|--------------------------------------------------------------------------
*/

// Trang chủ tạm thời
Route::get('/', function () {
    return view('welcome');
})->name('home');

// =========================================================================
// PHÂN HỆ XÁC THỰC (GUEST ONLY)
// =========================================================================
Route::middleware('guest')->group(function () {
    // Đăng nhập
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Đăng ký
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // Quên & Đặt lại mật khẩu
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Đăng nhập qua mạng xã hội (Google OAuth2)
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.google.callback');
});

// =========================================================================
// PHÂN HỆ XÁC THỰC EMAIL & NGƯỜI DÙNG (YÊU CẦU ĐĂNG NHẬP)
// =========================================================================
Route::middleware('auth')->group(function () {
    // Xác thực Email (Email Verification)
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->intended('/')->with('success', 'Xác thực địa chỉ email thành công!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Đăng xuất an toàn
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Hồ sơ cá nhân
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Sổ địa chỉ nhận hàng
    Route::post('/profile/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/profile/addresses/{id}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/profile/addresses/{id}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::patch('/profile/addresses/{id}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
    Route::post('/profile/addresses/normalize-34', [AddressController::class, 'normalizeAllTo34'])->name('addresses.normalize-34');

    // API Sổ địa chỉ (Cung cấp cho Thành viên 4 chọn nhanh khi Checkout)
    Route::get('/api/user/addresses', [AddressController::class, 'apiList'])->name('api.user.addresses');
});

// =========================================================================
// PHÂN HỆ QUẢN TRỊ ADMIN (YÊU CẦU ĐĂNG NHẬP & VAI TRÒ ADMIN/STAFF)
// =========================================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard tổng quan
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:view_dashboard')
        ->name('dashboard');

    // Ma trận Phân quyền & Vai trò (RBAC)
    Route::get('/roles', [RolePermissionController::class, 'index'])
        ->middleware('permission:manage_roles')
        ->name('roles.index');
    Route::post('/roles/matrix', [RolePermissionController::class, 'updateMatrix'])
        ->middleware('permission:manage_roles')
        ->name('roles.update-matrix');
    Route::post('/roles', [RolePermissionController::class, 'storeRole'])
        ->middleware('permission:manage_roles')
        ->name('roles.store');
    Route::delete('/roles/{id}', [RolePermissionController::class, 'destroyRole'])
        ->middleware('permission:manage_roles')
        ->name('roles.destroy');

    // Quản lý Nhân sự & Phân vai trò
    Route::get('/staff', [StaffController::class, 'index'])
        ->middleware('permission:manage_users')
        ->name('staff.index');
    Route::get('/staff/search-users', [StaffController::class, 'searchUsers'])
        ->middleware('permission:manage_users')
        ->name('staff.search-users');
    Route::post('/staff/assign-existing', [StaffController::class, 'assignExisting'])
        ->middleware('permission:manage_users')
        ->name('staff.assign-existing');
    Route::post('/staff', [StaffController::class, 'store'])
        ->middleware('permission:manage_users')
        ->name('staff.store');
    Route::put('/staff/{id}', [StaffController::class, 'update'])
        ->middleware('permission:manage_users')
        ->name('staff.update');
    Route::post('/staff/{id}/toggle-status', [StaffController::class, 'toggleStatus'])
        ->middleware('permission:manage_users')
        ->name('staff.toggle-status');
    Route::post('/staff/{id}/demote', [StaffController::class, 'demote'])
        ->middleware('permission:manage_users')
        ->name('staff.demote');
});
