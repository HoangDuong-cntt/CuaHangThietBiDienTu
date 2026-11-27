<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Services\VnpayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VnpayPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_callback_signature_does_not_mark_order_paid(): void
    {
        $transaction = $this->createVnpayTransaction();
        config(['services.vnpay.hash_secret' => 'sandbox-secret']);

        $result = app(VnpayService::class)->handleCallback([
            'vnp_TxnRef' => $transaction->transaction_ref,
            'vnp_Amount' => '10000000',
            'vnp_ResponseCode' => '00',
            'vnp_TransactionStatus' => '00',
            'vnp_SecureHash' => 'invalid',
        ]);

        $this->assertSame('97', $result['response_code']);
        $this->assertDatabaseHas('orders', [
            'id' => $transaction->order_id,
            'payment_status' => 'unpaid',
        ]);
        $this->assertDatabaseHas('payment_transactions', [
            'id' => $transaction->id,
            'status' => 'pending',
        ]);
    }

    public function test_payment_url_signs_urlencoded_parameter_values(): void
    {
        $transaction = $this->createVnpayTransaction();
        config([
            'services.vnpay.tmn_code' => 'sandbox-merchant',
            'services.vnpay.hash_secret' => 'sandbox-secret',
            'services.vnpay.payment_url' => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
            'services.vnpay.return_url' => 'https://example.test/payments/vnpay/return',
        ]);

        $url = app(VnpayService::class)->paymentUrl(
            $transaction->order,
            $transaction,
            '127.0.0.1',
        );
        $query = (string) parse_url($url, PHP_URL_QUERY);
        parse_str($query, $parameters);
        $signature = $parameters['vnp_SecureHash'];
        unset($parameters['vnp_SecureHash']);

        $this->assertStringContainsString('vnp_OrderInfo=Thanh+toan+don+hang', $query);
        $this->assertSame($this->signature($parameters), $signature);
    }

    public function test_valid_callback_confirms_payment_once(): void
    {
        $transaction = $this->createVnpayTransaction();
        config(['services.vnpay.hash_secret' => 'sandbox-secret']);
        $parameters = $this->signedSuccessParameters($transaction->transaction_ref);
        $service = app(VnpayService::class);

        $result = $service->handleCallback($parameters);

        $this->assertSame('00', $result['response_code']);
        $this->assertDatabaseHas('orders', [
            'id' => $transaction->order_id,
            'payment_status' => 'paid',
        ]);
        $this->assertDatabaseHas('payment_transactions', [
            'id' => $transaction->id,
            'status' => 'success',
        ]);

        $duplicate = $service->handleCallback($parameters);

        $this->assertSame('02', $duplicate['response_code']);
    }

    public function test_callback_with_changed_amount_is_rejected(): void
    {
        $transaction = $this->createVnpayTransaction();
        config(['services.vnpay.hash_secret' => 'sandbox-secret']);
        $parameters = $this->signedSuccessParameters($transaction->transaction_ref);
        $parameters['vnp_Amount'] = '10000001';
        $parameters['vnp_SecureHash'] = $this->signature($parameters);

        $result = app(VnpayService::class)->handleCallback($parameters);

        $this->assertSame('04', $result['response_code']);
        $this->assertDatabaseHas('orders', [
            'id' => $transaction->order_id,
            'payment_status' => 'unpaid',
        ]);
    }

    private function createVnpayTransaction(): PaymentTransaction
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'payment_method' => 'vnpay',
            'total_amount' => 100000,
        ]);

        return $order->paymentTransactions()->create([
            'transaction_ref' => 'PAYTEST1234567890',
            'gateway' => 'vnpay',
            'amount' => 100000,
            'status' => 'pending',
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function signedSuccessParameters(string $transactionReference): array
    {
        $parameters = [
            'vnp_Amount' => '10000000',
            'vnp_OrderInfo' => 'Thanh toan don hang '.$transactionReference,
            'vnp_ResponseCode' => '00',
            'vnp_TxnRef' => $transactionReference,
            'vnp_TransactionStatus' => '00',
        ];
        $parameters['vnp_SecureHash'] = $this->signature($parameters);

        return $parameters;
    }

    /**
     * @param  array<string, string>  $parameters
     */
    private function signature(array $parameters): string
    {
        unset($parameters['vnp_SecureHash']);
        ksort($parameters);

        return hash_hmac(
            'sha512',
            http_build_query($parameters, '', '&', PHP_QUERY_RFC1738),
            'sandbox-secret',
        );
    }
}
