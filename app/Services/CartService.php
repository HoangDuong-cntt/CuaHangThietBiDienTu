<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    private const SESSION_KEY = 'store_cart';

    public function count(Request $request): int
    {
        if (! $request->hasSession()) {
            return 0;
        }

        if ($request->user() === null) {
            $items = $request->session()->get(self::SESSION_KEY, []);

            return is_array($items) ? array_sum(array_map('intval', $items)) : 0;
        }

        return (int) $this->userCart($request, $request->user())
            ->items()
            ->sum('quantity');
    }

    /**
     * @return array{cart_count: int, subtotal: string, line_totals: array<int, string>}
     */
    public function summary(Request $request): array
    {
        $items = $this->contents($request);
        $subtotalMinorUnits = 0;
        $lineTotals = [];

        foreach ($items as $item) {
            $lineTotal = $this->toMinorUnits($item['variant']->currentPrice()) * $item['quantity'];
            $subtotalMinorUnits += $lineTotal;
            $lineTotals[$item['variant']->id] = $this->fromMinorUnits($lineTotal);
        }

        return [
            'cart_count' => $items->sum('quantity'),
            'subtotal' => $this->fromMinorUnits($subtotalMinorUnits),
            'line_totals' => $lineTotals,
        ];
    }

    /**
     * @return Collection<int, array{variant: ProductVariant, quantity: int, lineTotal: float}>
     */
    public function contents(Request $request): Collection
    {
        if ($request->user() !== null) {
            $cart = $this->userCart($request, $request->user());

            return $cart->items()
                ->with('variant.product')
                ->orderBy('id')
                ->get()
                ->map(fn (CartItem $item): array => [
                    'variant' => $item->variant,
                    'quantity' => $item->quantity,
                    'lineTotal' => $this->lineTotal($item->variant, $item->quantity),
                ]);
        }

        $sessionItems = $request->session()->get(self::SESSION_KEY, []);

        if (! is_array($sessionItems) || $sessionItems === []) {
            return collect();
        }

        $variants = ProductVariant::query()
            ->with('product')
            ->whereKey(array_keys($sessionItems))
            ->get()
            ->keyBy('id');

        return collect($sessionItems)
            ->map(function (int|string $quantity, int|string $variantId) use ($variants): ?array {
                $variant = $variants->get((int) $variantId);

                if ($variant === null) {
                    return null;
                }

                $quantity = (int) $quantity;

                return [
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'lineTotal' => $this->lineTotal($variant, $quantity),
                ];
            })
            ->filter()
            ->values();
    }

    public function add(Request $request, int $variantId, int $quantity): void
    {
        $variant = ProductVariant::query()->findOrFail($variantId);

        if (! $variant->is_active || $quantity > $variant->stock_quantity) {
            throw ValidationException::withMessages([
                'quantity' => 'Sản phẩm không hoạt động hoặc số lượng vượt quá tồn kho.',
            ]);
        }

        if ($request->user() === null) {
            $items = $request->session()->get(self::SESSION_KEY, []);
            $nextQuantity = (int) ($items[$variantId] ?? 0) + $quantity;

            if ($nextQuantity > $variant->stock_quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'Số lượng trong giỏ vượt quá tồn kho hiện tại.',
                ]);
            }

            $items[$variantId] = $nextQuantity;
            $request->session()->put(self::SESSION_KEY, $items);

            return;
        }

        DB::transaction(function () use ($request, $variantId, $quantity): void {
            $cart = $this->userCart($request, $request->user());
            $variant = ProductVariant::query()->lockForUpdate()->findOrFail($variantId);
            $item = $cart->items()->where('product_variant_id', $variantId)->lockForUpdate()->first();
            $nextQuantity = ($item?->quantity ?? 0) + $quantity;

            if (! $variant->is_active || $nextQuantity > $variant->stock_quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'Sản phẩm không hoạt động hoặc số lượng vượt quá tồn kho.',
                ]);
            }

            if ($item === null) {
                $cart->items()->create([
                    'product_variant_id' => $variantId,
                    'quantity' => $quantity,
                ]);

                return;
            }

            $item->update(['quantity' => $nextQuantity]);
        }, attempts: 3);
    }

    public function update(Request $request, int $variantId, int $quantity): void
    {
        $variant = ProductVariant::query()->findOrFail($variantId);

        if (! $variant->is_active || $quantity > $variant->stock_quantity) {
            throw ValidationException::withMessages([
                'quantity' => 'Sản phẩm không hoạt động hoặc số lượng vượt quá tồn kho hiện tại.',
            ]);
        }

        if ($request->user() === null) {
            $items = $request->session()->get(self::SESSION_KEY, []);

            if (! is_array($items) || ! array_key_exists($variantId, $items)) {
                abort(404);
            }

            $items[$variantId] = $quantity;
            $request->session()->put(self::SESSION_KEY, $items);

            return;
        }

        DB::transaction(function () use ($request, $variantId, $quantity): void {
            $cart = $this->userCart($request, $request->user());
            $variant = ProductVariant::query()->lockForUpdate()->findOrFail($variantId);

            if (! $variant->is_active || $quantity > $variant->stock_quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'Sản phẩm không hoạt động hoặc số lượng vượt quá tồn kho hiện tại.',
                ]);
            }

            $cart->items()
                ->where('product_variant_id', $variantId)
                ->lockForUpdate()
                ->firstOrFail()
                ->update(['quantity' => $quantity]);
        }, attempts: 3);
    }

    public function remove(Request $request, int $variantId): void
    {
        if ($request->user() === null) {
            $items = $request->session()->get(self::SESSION_KEY, []);
            $items = is_array($items) ? $items : [];
            unset($items[$variantId]);
            $request->session()->put(self::SESSION_KEY, $items);

            return;
        }

        $this->userCart($request, $request->user())
            ->items()
            ->where('product_variant_id', $variantId)
            ->delete();
    }

    public function clear(User $user): void
    {
        $user->cart?->items()->delete();
    }

    private function userCart(Request $request, User $user): Cart
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => $user->getKey()]);
        $guestItems = $request->session()->get(self::SESSION_KEY, []);

        if (! is_array($guestItems) || $guestItems === []) {
            return $cart;
        }

        DB::transaction(function () use ($cart, $guestItems): void {
            foreach ($guestItems as $variantId => $quantity) {
                $variant = ProductVariant::query()->find((int) $variantId);

                if ($variant === null) {
                    continue;
                }

                $item = $cart->items()->where('product_variant_id', $variant->id)->first();
                $mergedQuantity = ($item?->quantity ?? 0) + (int) $quantity;

                if ($item === null) {
                    $cart->items()->create([
                        'product_variant_id' => $variant->id,
                        'quantity' => $mergedQuantity,
                    ]);

                    continue;
                }

                $item->update(['quantity' => $mergedQuantity]);
            }
        }, attempts: 3);

        $request->session()->forget(self::SESSION_KEY);

        return $cart;
    }

    private function lineTotal(ProductVariant $variant, int $quantity): float
    {
        return (float) $this->fromMinorUnits($this->toMinorUnits($variant->currentPrice()) * $quantity);
    }

    private function toMinorUnits(string $amount): int
    {
        if (! preg_match('/^\d+(?:\.\d{1,2})?$/D', $amount)) {
            throw new \UnexpectedValueException('Product variant price must be a non-negative decimal amount.');
        }

        [$whole, $fraction] = array_pad(explode('.', $amount, 2), 2, '0');

        return ((int) $whole * 100) + (int) str_pad(substr($fraction, 0, 2), 2, '0');
    }

    private function fromMinorUnits(int $amount): string
    {
        return intdiv($amount, 100).'.'.str_pad((string) ($amount % 100), 2, '0', STR_PAD_LEFT);
    }
}
