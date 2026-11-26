<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RolePermissionController extends Controller
{
    /**
     * Hiển thị Ma trận phân quyền giữa Vai trò và Quyền hạn.
     */
    public function index(): View
    {
        $roles = Role::with('permissions')->get();
        $permissionsByGroup = Permission::all()->groupBy('group_name');

        return view('admin.roles.index', [
            'title' => 'Ma trận Phân quyền & Vai trò (RBAC)',
            'roles' => $roles,
            'permissionsByGroup' => $permissionsByGroup,
        ]);
    }

    /**
     * Lưu cập nhật hàng loạt Ma trận phân quyền.
     */
    public function updateMatrix(Request $request): RedirectResponse
    {
        $matrix = $request->input('matrix', []);
        $roles = Role::all();

        foreach ($roles as $role) {
            // Admin tối cao luôn sở hữu mọi quyền, không cho phép bỏ tick
            if ($role->name === 'admin') {
                $role->permissions()->sync(Permission::pluck('id'));

                continue;
            }

            $permissionIds = $matrix[$role->id] ?? [];
            $role->permissions()->sync($permissionIds);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Đã cập nhật Ma trận phân quyền hệ thống thành công!');
    }

    /**
     * Tạo thêm một vai trò công việc mới.
     */
    public function storeRole(Request $request): RedirectResponse
    {
        $request->validate([
            'display_name' => ['required', 'string', 'max:100', 'unique:roles,display_name'],
            'name' => ['nullable', 'string', 'max:50', 'unique:roles,name'],
        ], [
            'display_name.required' => 'Vui lòng nhập tên hiển thị vai trò.',
            'display_name.unique' => 'Tên vai trò này đã tồn tại.',
        ]);

        $name = $request->filled('name')
            ? Str::slug($request->string('name'), '_')
            : Str::slug($request->string('display_name'), '_');

        Role::create([
            'name' => $name,
            'display_name' => $request->string('display_name'),
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Đã tạo thêm vai trò mới thành công!');
    }

    /**
     * Xóa một vai trò (không cho phép xóa Admin).
     */
    public function destroyRole(int $id): RedirectResponse
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'admin') {
            return back()->with('error', 'Không thể xóa vai trò Quản trị viên tối cao (Admin).');
        }

        // Hủy liên kết role của các nhân viên đang mang vai trò này
        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Đã xóa vai trò thành công!');
    }
}
