<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\UserAddress;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\VnpayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderService $orderService,
        private readonly VnpayService $vnpayService,
    ) {}

    public function create(Request $request): View|RedirectResponse
    {
        $items = $this->cartService->contents($request);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'cart' => 'Giỏ hàng đang trống.',
            ]);
        }

        $addresses = UserAddress::query()
            ->where('user_id', $request->user()->getKey())
            ->orderByDesc('is_default')
            ->get();
        $shippingMethods = config('store.shipping_methods');
        $selectedShippingMethod = (string) $request->old('shipping_method', 'standard');

        if (! array_key_exists($selectedShippingMethod, $shippingMethods)) {
            $selectedShippingMethod = 'standard';
        }

        return view('store.checkout.create', [
            'items' => $items,
            'subtotal' => $items->sum('lineTotal'),
            'shippingMethods' => $shippingMethods,
            'selectedShippingMethod' => $selectedShippingMethod,
            'shippingFee' => $shippingMethods[$selectedShippingMethod]['fee'],
            'addresses' => $addresses,
            'vnpayEnabled' => $this->vnpayService->isConfigured(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'address_id' => ['nullable', 'integer', 'exists:user_addresses,id'],
            'receiver_name' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'receiver_phone' => ['required_without:address_id', 'nullable', 'string', 'max:20'],
            'province' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'district' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'ward' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'street_address' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:2000'],
            'shipping_method' => ['required', 'string', Rule::in(array_keys(config('store.shipping_methods', [])))],
            'payment_method' => ['required', 'in:cod,vnpay'],
        ]);

        if ($validated['payment_method'] === 'vnpay' && ! $this->vnpayService->isConfigured()) {
            return back()->withErrors([
                'payment_method' => 'Cổng VNPay chưa được cấu hình thông tin Sandbox.',
            ])->withInput();
        }

        $order = $this->orderService->placeOrder($request->user(), $validated);
        Mail::to($request->user()->email)->queue(new OrderConfirmationMail($order));

        if ($order->payment_method === 'vnpay') {
            $transaction = $order->paymentTransactions->last();

            return redirect()->away($this->vnpayService->paymentUrl($order, $transaction, (string) $request->ip()));
        }

        return redirect()
            ->route('orders.show', ['orderCode' => $order->order_code])
            ->with('status', 'Đặt hàng thành công.');
    }
}
