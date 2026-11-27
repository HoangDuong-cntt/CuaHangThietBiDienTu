<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService) {}

    public function index(Request $request): View
    {
        $items = $this->cartService->contents($request);

        return view('store.cart.index', [
            'items' => $items,
            'subtotal' => $items->sum('lineTotal'),
        ]);
    }

    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:99'],
        ]);

        $this->cartService->add(
            $request,
            (int) $validated['variant_id'],
            (int) ($validated['quantity'] ?? 1),
        );

        if ($request->expectsJson()) {
            return response()->json(array_merge(
                ['message' => 'Đã thêm sản phẩm vào giỏ hàng.'],
                $this->cartService->summary($request),
            ));
        }

        return redirect()->route('cart.index')->with('status', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(Request $request, int $variantId): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $this->cartService->update($request, $variantId, (int) $validated['quantity']);

        if ($request->expectsJson()) {
            return response()->json(array_merge(
                ['message' => 'Đã cập nhật giỏ hàng.'],
                $this->cartService->summary($request),
            ));
        }

        return redirect()->route('cart.index')->with('status', 'Đã cập nhật giỏ hàng.');
    }

    public function remove(Request $request, int $variantId): JsonResponse|RedirectResponse
    {
        $this->cartService->remove($request, $variantId);

        if ($request->expectsJson()) {
            return response()->json(array_merge(
                ['message' => 'Đã xóa sản phẩm khỏi giỏ hàng.'],
                $this->cartService->summary($request),
            ));
        }

        return redirect()->route('cart.index')->with('status', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}
