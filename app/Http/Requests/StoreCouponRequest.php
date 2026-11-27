<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasPermission('manage_coupons');
    }

    /**
     * Chuẩn hóa dữ liệu trước khi validate (ví dụ: tự động viết hoa mã code).
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('code')) {
            $this->merge([
                'code' => strtoupper(trim((string) $this->input('code'))),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $couponId = $this->route('id');

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coupons', 'code')->ignore($couponId),
            ],
            'discount_type' => ['required', 'in:percentage,fixed_amount'],
            'discount_value' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) {
                    if ($this->input('discount_type') === 'percentage' && $value > 100) {
                        $fail('Mức giảm theo phần trăm không được vượt quá 100%.');
                    }
                },
            ],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'usage_limit_per_user' => ['required', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.unique' => 'Mã giảm giá này đã tồn tại trong hệ thống.',
            'discount_type.required' => 'Vui lòng chọn loại hình giảm giá.',
            'discount_type.in' => 'Loại hình giảm giá không hợp lệ.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm giá.',
            'discount_value.min' => 'Giá trị giảm giá phải lớn hơn 0.',
            'usage_limit_per_user.required' => 'Vui lòng nhập giới hạn lượt dùng mỗi khách hàng.',
            'expires_at.after_or_equal' => 'Ngày hết hạn phải diễn ra sau hoặc cùng ngày bắt đầu.',
        ];
    }
}
