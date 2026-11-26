<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApplyCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.string' => 'Mã giảm giá không hợp lệ.',
            'subtotal.required' => 'Thiếu thông tin tổng tiền giỏ hàng.',
            'subtotal.numeric' => 'Tổng tiền giỏ hàng phải là một con số.',
            'subtotal.min' => 'Tổng tiền giỏ hàng không thể là số âm.',
            'user_id.exists' => 'Tài khoản người dùng không tồn tại.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
            'error_code' => 'VALIDATION_ERROR',
            'errors' => $validator->errors(),
        ], 422));
    }
}
