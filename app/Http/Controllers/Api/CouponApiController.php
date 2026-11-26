<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyCouponRequest;
use App\Services\CouponService;
use Illuminate\Http\JsonResponse;

class CouponApiController extends Controller
{
    public function __construct(private readonly CouponService $couponService) {}

    /**
     * API kiểm tra và tính toán giảm giá voucher cho Giỏ hàng / Checkout.
     */
    public function apply(ApplyCouponRequest $request): JsonResponse
    {
        $code = (string) $request->string('code');
        $subtotal = (float) $request->input('subtotal', 0);
        $userId = $request->integer('user_id') ?: $request->user()?->id;

        $result = $this->couponService->validateAndCalculate($code, $subtotal, $userId);

        if (! $result->isValid) {
            return response()->json([
                'success' => false,
                'message' => $result->message,
                'error_code' => $result->errorCode,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $result->message,
            'data' => $result->toArray(),
        ]);
    }
}
