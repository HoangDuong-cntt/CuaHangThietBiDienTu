<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VnpayService
{
    public function isConfigured(): bool
    {
        return filled(config('services.vnpay.tmn_code'))
            && filled(config('services.vnpay.hash_secret'))
            && filled(config('services.vnpay.payment_url'))
            && filled(config('services.vnpay.return_url'));
    }

    public function paymentUrl(Order $order, PaymentTransaction $transaction, string $ipAddress): string
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('VNPay Sandbox credentials are not configured.');
        }

        $now = now('Asia/Ho_Chi_Minh');
        $parameters = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => config('services.vnpay.tmn_code'),
            'vnp_Amount' => (string) $this->toMinorUnits((string) $transaction->amount),
            'vnp_CreateDate' => $now->format('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $ipAddress,
            'vnp_Locale' => 'vn',
            'vnp_OrderInfo' => 'Thanh toan don hang '.$order->order_code,
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => config('services.vnpay.return_url'),
            'vnp_TxnRef' => $transaction->transaction_ref,
            'vnp_ExpireDate' => $now->addMinutes(15)->format('YmdHis'),
        ];

        ksort($parameters);
        $query = http_build_query($parameters, '', '&', PHP_QUERY_RFC1738);
        $signature = hash_hmac('sha512', $query, (string) config('services.vnpay.hash_secret'));

        return config('services.vnpay.payment_url').'?'.$query.'&vnp_SecureHash='.$signature;
    }

    /**
     * @param  array<string, mixed>  $parameters
     * @return array{response_code: string, message: string, order: Order|null}
     */
    public function handleCallback(array $parameters): array
    {
        if (! $this->hasValidSignature($parameters)) {
            return [
                'response_code' => '97',
                'message' => 'Invalid signature',
                'order' => null,
            ];
        }

        $transactionReference = (string) ($parameters['vnp_TxnRef'] ?? '');
        $transaction = PaymentTransaction::query()
            ->where('transaction_ref', $transactionReference)
            ->first();

        if ($transaction === null || $transaction->gateway !== 'vnpay') {
            return [
                'response_code' => '01',
                'message' => 'Order not found',
                'order' => null,
            ];
        }

        $amountParameter = $parameters['vnp_Amount'] ?? null;

        if (! is_string($amountParameter) || ! ctype_digit($amountParameter)) {
            return [
                'response_code' => '04',
                'message' => 'Invalid amount',
                'order' => $transaction->order,
            ];
        }

        $reportedAmount = (int) $amountParameter;
        $expectedAmount = $this->toMinorUnits((string) $transaction->amount);

        if ($reportedAmount !== $expectedAmount) {
            return [
                'response_code' => '04',
                'message' => 'Invalid amount',
                'order' => $transaction->order,
            ];
        }

        return DB::transaction(function () use ($parameters, $transaction): array {
            $order = Order::query()
                ->lockForUpdate()
                ->findOrFail($transaction->order_id);
            $lockedTransaction = PaymentTransaction::query()
                ->lockForUpdate()
                ->findOrFail($transaction->id);

            if (
                $lockedTransaction->status !== 'pending'
                || $order->payment_status === 'paid'
                || $order->status === 'cancelled'
            ) {
                return [
                    'response_code' => '02',
                    'message' => 'Order already confirmed',
                    'order' => $order,
                ];
            }

            $isPaid = ($parameters['vnp_ResponseCode'] ?? null) === '00'
                && ($parameters['vnp_TransactionStatus'] ?? null) === '00';

            $lockedTransaction->update([
                'status' => $isPaid ? 'success' : 'failed',
                'gateway_response' => $this->safeResponse($parameters),
            ]);

            $order->update([
                'payment_status' => $isPaid ? 'paid' : 'failed',
            ]);

            return [
                'response_code' => '00',
                'message' => 'Confirm Success',
                'order' => $order,
            ];
        }, attempts: 3);
    }

    public function createRetryTransaction(Order $order): PaymentTransaction
    {
        return DB::transaction(function () use ($order): PaymentTransaction {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id);

            if ($lockedOrder->payment_method !== 'vnpay'
                || $lockedOrder->status !== 'pending'
                || $lockedOrder->payment_status === 'paid') {
                abort(409, 'Đơn hàng hiện không thể thanh toán lại.');
            }

            PaymentTransaction::query()
                ->where('order_id', $lockedOrder->id)
                ->where('status', 'pending')
                ->update(['status' => 'expired']);

            return $lockedOrder->paymentTransactions()->create([
                'transaction_ref' => 'PAY'.Str::upper(Str::random(17)),
                'gateway' => 'vnpay',
                'amount' => $lockedOrder->total_amount,
                'status' => 'pending',
            ]);
        }, attempts: 3);
    }

    private function hasValidSignature(array $parameters): bool
    {
        $receivedSignature = $parameters['vnp_SecureHash'] ?? null;

        if (
            ! is_string($receivedSignature)
            || $receivedSignature === ''
            || ! filled(config('services.vnpay.hash_secret'))
            || collect($parameters)->contains(fn (mixed $value): bool => ! is_scalar($value) && $value !== null)
        ) {
            return false;
        }

        unset($parameters['vnp_SecureHash'], $parameters['vnp_SecureHashType']);
        ksort($parameters);
        $query = http_build_query($parameters, '', '&', PHP_QUERY_RFC1738);
        $expectedSignature = hash_hmac('sha512', $query, (string) config('services.vnpay.hash_secret'));

        return hash_equals(strtolower($expectedSignature), strtolower($receivedSignature));
    }

    private function toMinorUnits(string $amount): int
    {
        if (! preg_match('/^\d+(?:\.\d{1,2})?$/D', $amount)) {
            throw new \UnexpectedValueException('Payment transaction amount must be a non-negative decimal amount.');
        }

        [$whole, $fraction] = array_pad(explode('.', $amount, 2), 2, '0');

        return ((int) $whole * 100) + (int) str_pad(substr($fraction, 0, 2), 2, '0');
    }

    /**
     * @param  array<string, mixed>  $parameters
     * @return array<string, scalar|null>
     */
    private function safeResponse(array $parameters): array
    {
        unset($parameters['vnp_SecureHash'], $parameters['vnp_SecureHashType']);

        return array_filter(
            $parameters,
            static fn (mixed $value): bool => is_scalar($value) || $value === null,
        );
    }
}
