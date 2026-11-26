<?php

use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('store.home');
});

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/items/{variantId}', [CartController::class, 'update'])->whereNumber('variantId')->name('cart.items.update');
Route::delete('/cart/items/{variantId}', [CartController::class, 'remove'])->whereNumber('variantId')->name('cart.items.destroy');
Route::get('/orders/track', [OrderController::class, 'trackingForm'])->name('orders.track.form');
Route::post('/orders/track', [OrderController::class, 'track'])->name('orders.track');
Route::get('/warranty', [OrderController::class, 'warrantyForm'])->name('warranty.form');
Route::post('/warranty', [OrderController::class, 'warrantyLookup'])->name('warranty.lookup');
Route::get('/payments/vnpay/return', [PaymentController::class, 'returned'])->name('payments.vnpay.return');
Route::get('/payments/vnpay/ipn', [PaymentController::class, 'ipn'])->name('payments.vnpay.ipn');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders/{orderCode}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{orderCode}/payment/retry', [PaymentController::class, 'retry'])->name('orders.payment.retry');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
        Route::get('/orders/{orderCode}/invoice', [OrderManagementController::class, 'invoice'])->name('orders.invoice');
        Route::get('/orders/{orderCode}', [OrderManagementController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{orderCode}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.status.update');
        Route::get('/inventory/low-stock', [OrderManagementController::class, 'lowStock'])->name('inventory.low-stock');
    });
});
