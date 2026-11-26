@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ addRoleModal: false }">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-zinc-850">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-white flex items-center gap-2.5">
                <span>Ma trận Phân quyền &amp; Vai trò (RBAC)</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20">
                    MATRIX GRID
                </span>
            </h1>
            <p class="text-xs text-zinc-400 mt-1 font-mono">
                Cấu hình phân quyền động theo từng vị trí công việc. Thay đổi có hiệu lực ngay lập tức khi lưu ma trận.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button @click="addRoleModal = true" type="button" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-md bg-zinc-900 hover:bg-zinc-800 text-zinc-200 border border-zinc-750 text-xs font-semibold transition-colors">
                <svg class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Thêm vai trò mới</span>
            </button>

            <button type="submit" form="matrixForm" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Lưu ma trận phân quyền</span>
            </button>
        </div>
    </div>

    <!-- Instructions / Security Banner -->
    <div class="p-3.5 rounded-md bg-zinc-900/60 border border-zinc-800 text-xs text-zinc-400 flex items-start gap-3">
        <svg class="w-4 h-4 text-blue-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="space-y-1">
            <div class="text-zinc-200 font-semibold">Quy tắc bảo mật phân quyền (Dynamic RBAC Principles):</div>
            <div class="text-[11px] text-zinc-400 leading-relaxed font-mono">
                &bull; <strong class="text-rose-400">Quản trị viên (Admin)</strong>: Luôn sở hữu toàn quyền trên toàn hệ thống (Superuser Override), không thể gỡ bỏ quyền hạn.<br>
                &bull; <strong class="text-zinc-300">Các vai trò khác</strong>: Được cấp quyền chi tiết theo từng module. Nhân sự thuộc vai trò chỉ có thể truy cập những màn hình/tính năng đã được đánh dấu tích chọn bên dưới.
            </div>
        </div>
    </div>

    <!-- The RBAC Interactive Matrix Form -->
    <form id="matrixForm" action="{{ route('admin.roles.update-matrix') }}" method="POST">
        @csrf

        <div class="rounded-md border border-zinc-850 bg-zinc-950 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    
                    <!-- Table Header: Roles -->
                    <thead>
                        <tr class="bg-zinc-900 border-b border-zinc-800 font-mono text-zinc-300">
                            <th class="py-4 px-5 min-w-[280px] text-xs uppercase tracking-wider font-bold border-r border-zinc-850">
                                <span>Phân hệ / Quyền hạn chi tiết</span>
                            </th>

                            @foreach($roles as $role)
                                <th class="py-4 px-4 min-w-[170px] text-center border-r border-zinc-850 last:border-r-0">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs font-bold text-white tracking-wide">{{ $role->display_name }}</span>
                                            @if($role->name === 'admin')
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-mono font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">ROOT</span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] font-mono text-zinc-400 uppercase">slug: {{ $role->name }}</span>

                                        <!-- Quick column selectors or Delete button for custom roles -->
                                        <div class="mt-2 flex items-center gap-1.5">
                                            @if($role->name !== 'admin')
                                                <button type="button" 
                                                        @click="document.querySelectorAll('.role-check-{{ $role->id }}').forEach(el => el.checked = true)"
                                                        class="text-[9px] font-mono px-1.5 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-300 transition-colors">
                                                    Chọn hết
                                                </button>
                                                <button type="button" 
                                                        @click="document.querySelectorAll('.role-check-{{ $role->id }}').forEach(el => el.checked = false)"
                                                        class="text-[9px] font-mono px-1.5 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-400 transition-colors">
                                                    Bỏ hết
                                                </button>
                                            @else
                                                <span class="text-[9px] font-mono text-zinc-400">Full Access</span>
                                            @endif
                                        </div>

                                        <!-- Delete Custom Role Button -->
                                        @if($role->name !== 'admin')
                                            <div class="mt-1 pt-1 border-t border-zinc-850/60 w-full text-center">
                                                <button type="button" 
                                                        onclick="if(confirm('Bạn có chắc chắn muốn xóa vai trò [{{ $role->display_name }}]? Các nhân viên mang vai trò này sẽ bị gỡ liên kết.')) { document.getElementById('delete-role-{{ $role->id }}').submit(); }"
                                                        class="text-[10px] text-rose-400 hover:text-rose-300 font-mono transition-colors">
                                                    &times; Xóa vai trò
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <!-- Table Body: Grouped Permissions -->
                    <tbody class="divide-y divide-zinc-850">
                        @foreach($permissionsByGroup as $groupName => $permissions)
                            
                            <!-- Group Title Row -->
                            <tr class="bg-zinc-900/40 font-mono">
                                <td colspan="{{ count($roles) + 1 }}" class="py-2.5 px-5 text-[11px] font-bold text-zinc-300 uppercase tracking-wider bg-zinc-900/60 border-y border-zinc-850">
                                    <div class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                        <span>Phân hệ: {{ $groupName }}</span>
                                        <span class="text-zinc-400 text-[10px] font-normal">({{ count($permissions) }} quyền)</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- Individual Permission Rows -->
                            @foreach($permissions as $permission)
                                <tr class="hover:bg-zinc-900/30 transition-colors">
                                    <!-- Permission Name & Description -->
                                    <td class="py-3 px-5 border-r border-zinc-850">
                                        <div class="font-medium text-white text-xs">
                                            {{ $permission->display_name }}
                                        </div>
                                        <div class="text-[11px] text-zinc-400 mt-0.5">
                                            {{ $permission->description }}
                                        </div>
                                        <div class="text-[9px] font-mono text-zinc-400 mt-1">
                                            code: <code class="text-zinc-300 font-semibold">{{ $permission->name }}</code>
                                        </div>
                                    </td>

                                    <!-- Roles Checkboxes -->
                                    @foreach($roles as $role)
                                        @php
                                            $hasPerm = $role->permissions->contains('id', $permission->id) || $role->name === 'admin';
                                        @endphp
                                        <td class="py-3 px-4 text-center border-r border-zinc-850 last:border-r-0">
                                            @if($role->name === 'admin')
                                                <!-- Admin is locked checked -->
                                                <input type="checkbox" 
                                                       checked 
                                                       disabled 
                                                       class="w-4 h-4 rounded border-zinc-700 bg-zinc-800 text-blue-600 cursor-not-allowed opacity-80">
                                                <input type="hidden" name="matrix[{{ $role->id }}][]" value="{{ $permission->id }}">
                                            @else
                                                <input type="checkbox" 
                                                       name="matrix[{{ $role->id }}][]" 
                                                       value="{{ $permission->id }}"
                                                       {{ $hasPerm ? 'checked' : '' }}
                                                       class="role-check-{{ $role->id }} w-4 h-4 rounded border-zinc-700 bg-zinc-900 text-blue-600 focus:ring-0 focus:ring-offset-0 cursor-pointer transition-colors">
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Bottom Floating Action Bar -->
            <div class="p-4 bg-zinc-900 border-t border-zinc-850 flex items-center justify-between">
                <span class="text-xs font-mono text-zinc-400">
                    * Lưu ý: Click "Lưu ma trận phân quyền" để đồng bộ dữ liệu vào cơ sở dữ liệu.
                </span>

                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Lưu Ma Trận Phân Quyền</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Hidden DELETE Forms for Roles -->
    @foreach($roles as $role)
        @if($role->name !== 'admin')
            <form id="delete-role-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif
    @endforeach

    <!-- Modal: Thêm Vai trò mới (Sharp Industrial Modal) -->
    <div x-show="addRoleModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div @click.outside="addRoleModal = false" class="w-full max-w-md bg-zinc-900 border border-zinc-800 rounded-md shadow-2xl p-6 space-y-4">
            
            <div class="flex items-center justify-between pb-3 border-b border-zinc-800">
                <h3 class="text-sm font-bold uppercase tracking-wider text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tạo Vai Trò Mới</span>
                </h3>
                <button @click="addRoleModal = false" class="text-zinc-500 hover:text-white text-lg leading-none">&times;</button>
            </div>

            <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">
                        Tên hiển thị vai trò <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="display_name" 
                           required 
                           placeholder="Ví dụ: Nhân viên Chăm sóc Khách hàng"
                           class="w-full px-3 py-2 rounded-md bg-zinc-950 border border-zinc-750 text-white text-xs placeholder:text-zinc-600 focus:outline-none focus:border-blue-500">
                    <p class="text-[11px] text-zinc-400 mt-1 font-mono">Tên hiển thị công khai trên giao diện quản trị.</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">
                        Mã định danh (Slug / Không bắt buộc)
                    </label>
                    <input type="text" 
                           name="name" 
                           placeholder="customer_care (tự động tạo nếu để trống)"
                           class="w-full px-3 py-2 rounded-md bg-zinc-950 border border-zinc-750 text-white text-xs placeholder:text-zinc-600 focus:outline-none focus:border-blue-500 font-mono">
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-zinc-800">
                    <button @click="addRoleModal = false" type="button" class="px-3 py-1.5 rounded-md text-xs font-medium text-zinc-400 hover:text-white bg-zinc-800 transition-colors">
                        Hủy bỏ
                    </button>
                    <button type="submit" class="px-4 py-1.5 rounded-md text-xs font-semibold text-white bg-blue-600 hover:bg-blue-500 transition-colors shadow-sm">
                        Khởi tạo vai trò
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
