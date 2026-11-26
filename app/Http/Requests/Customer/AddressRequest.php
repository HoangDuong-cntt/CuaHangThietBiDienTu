<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('receiver_phone') && $this->receiver_phone !== null) {
            $phone = preg_replace('/[^\d+]/', '', (string) $this->receiver_phone);
            if (str_starts_with($phone, '+84')) {
                $phone = '0'.substr($phone, 3);
            }
            $this->merge(['receiver_phone' => $phone]);
        }

        // Tự động gán district bằng province nếu ở mô hình 2 cấp (34 tỉnh thành sau sáp nhập)
        if (empty($this->district) && ! empty($this->province)) {
            $this->merge(['district' => $this->province]);
        }
    }

    public function rules(): array
    {
        return [
            'receiver_name' => ['required', 'string', 'max:100'],
            'receiver_phone' => ['required', 'string', 'regex:/^(0[3|5|7|8|9])[0-9]{8}$/'],
            'province' => ['required', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'ward' => ['required', 'string', 'max:100'],
            'street_address' => ['required', 'string', 'max:255'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_name.required' => 'Vui lòng nhập tên người nhận.',
            'receiver_phone.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'receiver_phone.regex' => 'Số điện thoại không hợp lệ (cần đúng 10 số đầu 03, 05, 07, 08, 09).',
            'province.required' => 'Vui lòng chọn hoặc nhập Tỉnh / Thành phố.',
            'district.required' => 'Vui lòng nhập Quận / Huyện.',
            'ward.required' => 'Vui lòng nhập Phường / Xã.',
            'street_address.required' => 'Vui lòng nhập số nhà, tên đường.',
        ];
    }
}
