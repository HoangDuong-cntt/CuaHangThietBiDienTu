<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\VnpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private readonly VnpayService $vnpayService) {}

    public function returned(Request $request): View
    {
        $result = $this->vnpayService->handleCallback($request->query->all());

        return view('store.payments.result', [
            'order' => $result['order'],
            'message' => $result['message'],
            'isPaid' => $result['response_code'] === '00' && $result['order']?->payment_status === 'paid',
        ]);
    }

    public function ipn(Request $request): JsonResponse
    {
        $result = $this->vnpayService->handleCallback($request->query->all());

        return response()->json([
            'RspCode' => $result['response_code'],
            'Message' => $result['message'],
        ]);
    }

    public function retry(Request $request, string $orderCode): RedirectResponse
    {
        if (! $this->vnpayService->isConfigured()) {
            return back()->withErrors([
                'payment' => 'Cổng VNPay chưa được cấu hình thông tin Sandbox.',
            ]);
        }

        $order = Order::query()
            ->where('order_code', $orderCode)
            ->where('user_id', $request->user()->getKey())
            ->firstOrFail();

        if ($order->payment_method !== 'vnpay') {
            abort(409, 'Đơn hàng này không sử dụng VNPay.');
        }

        $transaction = $this->vnpayService->createRetryTransaction($order);

        return redirect()->away($this->vnpayService->paymentUrl($order, $transaction, (string) $request->ip()));
    }
}
