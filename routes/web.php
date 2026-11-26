<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductComparisonController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'home'])->name('home');

// Trang Cửa Hàng & Bộ Lọc Nâng Cao
Route::get('/shop', [CatalogController::class, 'index'])->name('shop.index');

// Trang So Sánh Đối Chiếu Sản Phẩm
Route::get('/compare', [ProductComparisonController::class, 'index'])->name('compare.index');
