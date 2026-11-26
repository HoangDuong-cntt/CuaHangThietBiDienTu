<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('phone') && $this->phone !== null) {
            $phone = preg_replace('/[^\d+]/', '', (string) $this->phone);
            if (str_starts_with($phone, '+84')) {
                $phone = '0'.substr($phone, 3);
            }
            $this->merge(['phone' => $phone ?: null]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^(0[3|5|7|8|9])[0-9]{8}$/'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Họ và tên không được để trống.',
            'phone.regex' => 'Số điện thoại không hợp lệ (cần đúng 10 số đầu 03, 05, 07, 08, 09).',
            'avatar.image' => 'Tệp tải lên phải là hình ảnh.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB.',
        ];
    }
}
