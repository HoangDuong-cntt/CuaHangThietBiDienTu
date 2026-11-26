<?php

namespace App\DTOs;

readonly class CouponCalculationResult
{
    public function __construct(
        public bool $isValid,
        public string $message,
        public ?int $couponId = null,
        public ?string $code = null,
        public ?string $discountType = null,
        public float $discountValue = 0.0,
        public float $discountAmount = 0.0,
        public float $subtotal = 0.0,
        public float $finalTotal = 0.0,
        public ?string $errorCode = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'coupon_id' => $this->couponId,
            'code' => $this->code,
            'discount_type' => $this->discountType,
            'discount_value' => $this->discountValue,
            'discount_amount' => $this->discountAmount,
            'formatted_discount' => number_format($this->discountAmount, 0, ',', '.').' đ',
            'subtotal' => $this->subtotal,
            'final_total' => $this->finalTotal,
            'formatted_final_total' => number_format($this->finalTotal, 0, ',', '.').' đ',
        ];
    }
}
