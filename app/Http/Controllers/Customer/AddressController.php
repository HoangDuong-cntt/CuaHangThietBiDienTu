<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddressRequest;
use App\Services\VietnamAddressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * API trả về danh sách địa chỉ của người dùng (phục vụ Thành viên 4 chọn địa chỉ khi Checkout).
     */
    public function apiList(): JsonResponse
    {
        $addresses = Auth::user()
            ->addresses()
            ->orderBy('is_default', 'desc')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $addresses,
        ]);
    }

    /**
     * Thêm mới địa chỉ nhận hàng (tự động chuẩn hóa về 34 tỉnh thành và chống tạo trùng lặp).
     */
    public function store(AddressRequest $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();
        $isDefault = $request->boolean('is_default');

        // Nếu người dùng chưa có địa chỉ nào -> tự động đặt làm mặc định
        if ($user->addresses()->count() === 0) {
            $isDefault = true;
        }

        // Tự động chuẩn hóa tên tỉnh thành về chuẩn thống nhất 34 Tỉnh Thành
        $rawProvince = $request->string('province')->toString();
        $normalizedProvince = VietnamAddressService::normalizeTo34($rawProvince);

        // Chuẩn 34 tỉnh thành áp dụng 2 cấp: Tỉnh -> Phường/Xã
        $district = $normalizedProvince;

        $ward = $request->string('ward')->toString();
        $streetAddress = $request->string('street_address')->toString();
        $receiverName = $request->string('receiver_name')->toString();
        $receiverPhone = $request->string('receiver_phone')->toString();

        // Kiểm tra xem địa chỉ này đã tồn tại trong sổ địa chỉ của người dùng chưa
        $existing = $user->addresses()
            ->where('receiver_name', $receiverName)
            ->where('receiver_phone', $receiverPhone)
            ->where('province', $normalizedProvince)
            ->where('street_address', $streetAddress)
            ->first();

        if ($existing) {
            if ($isDefault && ! $existing->is_default) {
                $user->addresses()->update(['is_default' => false]);
                $existing->update(['is_default' => true]);
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Địa chỉ này đã có sẵn trong sổ địa chỉ của bạn.',
                    'data' => $existing,
                ]);
            }

            return redirect()->route('profile.edit', ['tab' => 'addresses'])
                ->with('info', 'Địa chỉ này đã có sẵn trong sổ địa chỉ của bạn (đã tự động đồng bộ về chuẩn 34 tỉnh thành).')
                ->with('active_tab', 'addresses');
        }

        // Nếu địa chỉ này là mặc định -> hạ các địa chỉ cũ về 0
        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $address = $user->addresses()->create([
            'receiver_name' => $receiverName,
            'receiver_phone' => $receiverPhone,
            'province' => $normalizedProvince,
            'district' => $district,
            'ward' => $ward,
            'street_address' => $streetAddress,
            'is_default' => $isDefault,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm địa chỉ nhận hàng thành công (đã chuẩn hóa theo 34 tỉnh thành)!',
                'data' => $address,
            ]);
        }

        return redirect()->route('profile.edit', ['tab' => 'addresses'])
            ->with('success', 'Đã thêm địa chỉ nhận hàng mới thành công (thống nhất theo chuẩn 34 tỉnh thành)!')
            ->with('active_tab', 'addresses');
    }

    /**
     * Cập nhật địa chỉ nhận hàng.
     */
    public function update(AddressRequest $request, int $id): RedirectResponse|JsonResponse
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            Auth::user()->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $rawProvince = $request->string('province')->toString();
        $normalizedProvince = VietnamAddressService::normalizeTo34($rawProvince);

        $address->update([
            'receiver_name' => $request->string('receiver_name')->toString(),
            'receiver_phone' => $request->string('receiver_phone')->toString(),
            'province' => $normalizedProvince,
            'district' => $normalizedProvince,
            'ward' => $request->string('ward')->toString(),
            'street_address' => $request->string('street_address')->toString(),
            'is_default' => $isDefault,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật địa chỉ thành công!',
                'data' => $address,
            ]);
        }

        return redirect()->route('profile.edit', ['tab' => 'addresses'])
            ->with('success', 'Đã cập nhật thông tin địa chỉ thành công!')
            ->with('active_tab', 'addresses');
    }

    /**
     * Xóa địa chỉ nhận hàng.
     */
    public function destroy(int $id): RedirectResponse|JsonResponse
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        // Nếu vừa xóa địa chỉ mặc định -> tự động lấy địa chỉ đầu tiên còn lại làm mặc định
        if ($wasDefault) {
            $firstRemaining = Auth::user()->addresses()->first();
            if ($firstRemaining) {
                $firstRemaining->update(['is_default' => true]);
            }
        }

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa địa chỉ thành công!',
            ]);
        }

        return redirect()->route('profile.edit', ['tab' => 'addresses'])
            ->with('success', 'Đã xóa địa chỉ nhận hàng thành công!')
            ->with('active_tab', 'addresses');
    }

    /**
     * Đặt nhanh một địa chỉ làm mặc định.
     */
    public function setDefault(int $id): RedirectResponse|JsonResponse
    {
        $user = Auth::user();
        $user->addresses()->update(['is_default' => false]);

        $address = $user->addresses()->findOrFail($id);
        $address->update(['is_default' => true]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã đặt làm địa chỉ mặc định!',
                'data' => $address,
            ]);
        }

        return redirect()->route('profile.edit', ['tab' => 'addresses'])
            ->with('success', 'Đã đặt làm địa chỉ nhận hàng mặc định!')
            ->with('active_tab', 'addresses');
    }

    /**
     * API xem trước kết quả đồng bộ toàn bộ địa chỉ về chuẩn 34 tỉnh thành.
     */
    public function previewSync(): JsonResponse
    {
        $user = Auth::user();
        $preview = VietnamAddressService::previewDeduplicateAndNormalize($user);

        return response()->json([
            'success' => true,
            'data' => $preview,
        ]);
    }

    /**
     * API chuyển đổi một địa chỉ cụ thể sang chuẩn 34 tỉnh realtime.
     */
    public function convert(Request $request): JsonResponse
    {
        $converted = VietnamAddressService::convert63To34(
            $request->input('province'),
            $request->input('district'),
            $request->input('ward'),
            $request->input('street_address')
        );

        return response()->json([
            'success' => true,
            'data' => $converted,
        ]);
    }

    /**
     * API bóc tách chuỗi địa chỉ tự do và chuyển đổi sang chuẩn 34 tỉnh realtime.
     */
    public function parseConvert(Request $request): JsonResponse
    {
        $raw = $request->input('raw_address', '');
        $converted = VietnamAddressService::parseAndConvertAddress($raw);

        return response()->json([
            'success' => true,
            'data' => $converted,
        ]);
    }

    /**
     * Tính năng chuẩn hóa toàn bộ sổ địa chỉ của người dùng về chuẩn 34 Tỉnh Thành và loại bỏ trùng lặp.
     */
    public function normalizeAllTo34(Request $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();
        $result = VietnamAddressService::deduplicateAndNormalizeUserAddresses($user);

        $msg = "Đã chuẩn hóa {$result['normalized_count']} địa chỉ về chuẩn 34 tỉnh thành!";
        if ($result['duplicates_removed'] > 0) {
            $msg .= " Đã tự động gộp và loại bỏ {$result['duplicates_removed']} địa chỉ trùng lặp.";
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'data' => $result,
            ]);
        }

        return redirect()->route('profile.edit', ['tab' => 'addresses'])
            ->with('success', $msg)
            ->with('active_tab', 'addresses');
    }
}
