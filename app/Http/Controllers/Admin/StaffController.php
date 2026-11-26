<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Danh sách tài khoản nhân viên trong hệ thống.
     */
    public function index(Request $request): View
    {
        $query = User::whereIn('role', ['admin', 'staff'])->with('roleModel');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->integer('role_id'));
        }

        $staffMembers = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::all();

        // Danh sách tài khoản khách hàng có sẵn gần đây để tiện bổ nhiệm trực tiếp
        $availableCustomers = User::where('role', 'customer')
            ->latest()
            ->take(20)
            ->get(['id', 'name', 'email', 'phone']);

        return view('admin.staff.index', [
            'title' => 'Quản lý Nhân sự & Phân vai trò',
            'staffMembers' => $staffMembers,
            'roles' => $roles,
            'availableCustomers' => $availableCustomers,
        ]);
    }

    /**
     * Tìm kiếm nhanh người dùng có sẵn trong hệ thống qua AJAX để bổ nhiệm làm nhân sự.
     */
    public function searchUsers(Request $request): JsonResponse
    {
        $search = $request->string('q')->trim();

        if (empty($search)) {
            $users = User::where('role', 'customer')
                ->latest()
                ->take(10)
                ->get(['id', 'name', 'email', 'phone', 'role']);

            return response()->json(['users' => $users]);
        }

        $users = User::where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        })->take(15)->get(['id', 'name', 'email', 'phone', 'role', 'role_id']);

        return response()->json(['users' => $users]);
    }

    /**
     * Bổ nhiệm trực tiếp một tài khoản người dùng có sẵn thành nhân sự với vai trò được chọn.
     */
    public function assignExisting(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ], [
            'user_id.required' => 'Vui lòng chọn tài khoản người dùng có sẵn.',
            'user_id.exists' => 'Tài khoản người dùng không tồn tại.',
            'role_id.required' => 'Vui lòng chọn vai trò cho nhân sự.',
        ]);

        $user = User::findOrFail($request->integer('user_id'));
        $role = Role::findOrFail($request->integer('role_id'));
        $systemRole = $role->name === 'admin' ? 'admin' : 'staff';

        $user->update([
            'role' => $systemRole,
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', "Đã bổ nhiệm tài khoản {$user->name} ({$user->email}) thành nhân sự với vai trò [{$role->display_name}] thành công!");
    }

    /**
     * Thêm mới tài khoản nhân viên hoặc nâng cấp tài khoản có sẵn nếu email đã tồn tại.
     */
    public function store(Request $request): RedirectResponse
    {
        $email = $request->string('email')->trim()->toString();
        $existingUser = User::where('email', $email)->first();

        // NẾU TÀI KHOẢN ĐÃ TỒN TẠI: Tự động nâng cấp sang nhân sự thay vì báo lỗi trùng email!
        if ($existingUser) {
            $request->validate([
                'role_id' => ['required', 'exists:roles,id'],
                'phone' => ['nullable', 'string', 'regex:/^(0[3|5|7|8|9])[0-9]{8}$/'],
                'password' => ['nullable', 'string', 'min:8'],
            ]);

            $role = Role::findOrFail($request->integer('role_id'));
            $systemRole = $role->name === 'admin' ? 'admin' : 'staff';

            $updateData = [
                'role' => $systemRole,
                'role_id' => $role->id,
                'is_active' => true,
            ];

            if ($request->filled('name')) {
                $updateData['name'] = $request->string('name')->toString();
            }
            if ($request->filled('phone')) {
                $updateData['phone'] = $request->string('phone')->toString();
            }
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->string('password')->toString());
            }

            $existingUser->update($updateData);

            return redirect()->route('admin.staff.index')
                ->with('success', "Tài khoản có sẵn [{$existingUser->email}] đã được nâng cấp thành nhân viên với vai trò [{$role->display_name}] thành công!");
        }

        // NẾU TÀI KHOẢN CHƯA CÓ: Tạo mới từ đầu
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^(0[3|5|7|8|9])[0-9]{8}$/'],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên nhân viên.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ (cần 10 số đầu 03, 05, 07, 08, 09).',
            'password.required' => 'Vui lòng nhập mật khẩu khởi tạo.',
            'password.min' => 'Mật khẩu phải từ 8 ký tự trở lên.',
            'role_id.required' => 'Vui lòng chọn vai trò cho nhân viên.',
        ]);

        $role = Role::findOrFail($request->integer('role_id'));
        $systemRole = $role->name === 'admin' ? 'admin' : 'staff';

        User::create([
            'name' => $request->string('name')->toString(),
            'email' => $email,
            'phone' => $request->string('phone')->toString(),
            'password' => Hash::make($request->string('password')->toString()),
            'role' => $systemRole,
            'role_id' => $role->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Đã khởi tạo tài khoản nhân viên mới thành công!');
    }

    /**
     * Cập nhật vai trò và thông tin nhân viên (hỗ trợ chuyển về Khách hàng thông thường).
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $staff = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(0[3|5|7|8|9])[0-9]{8}$/'],
            'role_id' => ['required'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        // NẾU CHỌN HẠ QUYỀN / GIÁNG CHỨC VỀ KHÁCH HÀNG (CUSTOMER)
        if ($request->input('role_id') === 'customer' || $request->input('role_id') === '0') {
            if ($staff->id === auth()->id()) {
                return back()->with('error', 'Bạn không thể tự hạ quyền tài khoản của chính mình.');
            }

            $data = [
                'name' => $request->string('name')->toString(),
                'phone' => $request->string('phone')->toString(),
                'role' => 'customer',
                'role_id' => null,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->string('password')->toString());
            }

            $staff->update($data);

            return redirect()->route('admin.staff.index')
                ->with('success', "Đã giáng chức tài khoản {$staff->name} về Khách hàng thông thường thành công!");
        }

        $role = Role::findOrFail($request->integer('role_id'));
        $systemRole = $role->name === 'admin' ? 'admin' : 'staff';

        $data = [
            'name' => $request->string('name')->toString(),
            'phone' => $request->string('phone')->toString(),
            'role' => $systemRole,
            'role_id' => $role->id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->string('password')->toString());
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')->with('success', 'Đã cập nhật thông tin nhân viên thành công!');
    }

    /**
     * Giáng chức / Hạ quyền một nhân sự trở về Khách hàng thông thường (Customer).
     */
    public function demote(int $id): RedirectResponse
    {
        $staff = User::findOrFail($id);

        if ($staff->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể tự hạ quyền tài khoản của chính mình.');
        }

        $staff->update([
            'role' => 'customer',
            'role_id' => null,
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', "Đã giáng chức tài khoản {$staff->name} ({$staff->email}) về Khách hàng thông thường thành công!");
    }

    /**
     * Bật / Tắt trạng thái hoạt động (Khóa hoặc Kích hoạt tài khoản nhân viên).
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $staff = User::findOrFail($id);

        if ($staff->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể tự khóa tài khoản của chính mình.');
        }

        $staff->update([
            'is_active' => ! $staff->is_active,
        ]);

        $statusStr = $staff->is_active ? 'Kích hoạt' : 'Tạm khóa';

        return back()->with('success', "Đã {$statusStr} tài khoản {$staff->name} thành công!");
    }
}
