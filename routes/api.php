<?php

use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductComparisonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Trợ lý AI (Gemini)
Route::prefix('ai')->group(function () {
    Route::post('/chat', [AiAssistantController::class, 'chat']);
    Route::post('/summary/{productId}', [AiAssistantController::class, 'summary']);
    Route::post('/generate-description', [AiAssistantController::class, 'generateDescription']);
});

// Tìm kiếm, Bộ lọc & So sánh
Route::prefix('products')->group(function () {
    Route::get('/autocomplete', [CatalogController::class, 'autocomplete']);
    Route::get('/featured-tabs', [CatalogController::class, 'apiFeaturedTabs']);
    Route::get('/filter', [CatalogController::class, 'filterJson']);
    Route::get('/compare', [ProductComparisonController::class, 'apiCompare']);
    Route::get('/compare-search', [ProductComparisonController::class, 'searchForCompare']);
});

// Yêu thích (Wishlist) & Xem gần đây (Recently Viewed)
Route::middleware(['web'])->group(function () {
    Route::get('/wishlist', [CatalogController::class, 'getWishlist']);
    Route::post('/wishlist/toggle', [CatalogController::class, 'toggleWishlist']);
    Route::post('/recently-viewed/record', [CatalogController::class, 'recordRecentlyViewed']);
    Route::get('/recently-viewed', [CatalogController::class, 'getRecentlyViewed']);
});
