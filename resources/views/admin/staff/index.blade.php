@extends('layouts.admin')

@section('title', 'Quản trị Nhân sự')

@section('content')
<div class="space-y-6" x-data="{ 
    addStaffModal: false,
    editStaffModal: false,
    editData: { id: null, name: '', phone: '', role_id: '' }
}">

    <!-- Page Header & Quick Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 flex items-center gap-2.5">
                <span>Quản trị Danh sách Nhân sự</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                    PERSONNEL DIRECTORY
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Quản lý tài khoản nội bộ, gán vai trò công việc và điều khiển trạng thái hoạt động (Khóa / Mở).
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <button @click="addStaffModal = true" type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Thêm nhân viên mới</span>
            </button>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="p-3.5 rounded-xl bg-white border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.staff.index') }}" class="flex-1 w-full flex flex-col sm:flex-row items-center gap-3">
            
            <!-- Search Query Input -->
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Tìm kiếm theo Tên, Email hoặc Số điện thoại..." 
                       class="w-full pl-9 pr-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-900 text-xs placeholder:text-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <!-- Role Filter Select -->
            <div class="w-full sm:w-56">
                <select name="role_id" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-700 text-xs focus:outline-none focus:border-blue-500">
                    <option value="">-- Tất cả vai trò --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Filter Button -->
            <button type="submit" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition-colors shadow-2xs">
                Lọc dữ liệu
            </button>

            @if(request()->hasAny(['search', 'role_id']))
                <a href="{{ route('admin.staff.index') }}" class="text-xs text-slate-500 hover:text-slate-800 font-medium">
                    Đặt lại
                </a>
            @endif
        </form>

        <div class="text-xs text-slate-500 font-medium shrink-0">
            Tổng cộng: <strong class="text-slate-900">{{ $staffMembers->total() }}</strong> nhân sự
        </div>
    </div>

    <!-- Staff Personnel Directory Table -->
    <div class="rounded-xl border border-slate-200/80 bg-white overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold text-[10px] uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-5">Nhân viên</th>
                        <th class="py-3 px-4">Số điện thoại</th>
                        <th class="py-3 px-4">Vai trò (Role)</th>
                        <th class="py-3 px-4 text-center">Trạng thái</th>
                        <th class="py-3 px-4">Xác minh Email</th>
                        <th class="py-3 px-5 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($staffMembers as $staff)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            
                            <!-- Name & Email with Initial Avatar -->
                            <td class="py-3.5 px-5 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-xs text-slate-700 uppercase shrink-0">
                                    {{ substr($staff->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900 flex items-center gap-1.5">
                                        <span>{{ $staff->name }}</span>
                                        @if($staff->id === auth()->id())
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-mono font-bold bg-blue-50 text-blue-700 border border-blue-200">BẠN</span>
                                        @endif
                                        @if($staff->role === 'admin')
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-mono font-bold bg-rose-50 text-rose-700 border border-rose-200">ADMIN</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-slate-400">{{ $staff->email }}</div>
                                </div>
                            </td>

                            <!-- Phone -->
                            <td class="py-3.5 px-4 font-mono text-slate-600">
                                {{ $staff->phone ?? '---' }}
                            </td>

                            <!-- Role Badge -->
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $staff->roleModel->display_name ?? ($staff->role === 'admin' ? 'Quản trị viên' : 'Nhân viên') }}
                                </span>
                            </td>

                            <!-- Status Active/Locked -->
                            <td class="py-3.5 px-4 text-center">
                                @if($staff->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hoạt động
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Đã khóa
                                    </span>
                                @endif
                            </td>

                            <!-- Email Verified -->
                            <td class="py-3.5 px-4 text-[11px]">
                                @if($staff->email_verified_at)
                                    <span class="text-emerald-600 font-medium flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Đã xác minh
                                    </span>
                                @else
                                    <span class="text-slate-400">Chưa xác minh</span>
                                @endif
                            </td>

                            <!-- Action Controls -->
                            <td class="py-3.5 px-5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    
                                    <!-- Edit Button -->
                                    <button type="button" 
                                            @click="editData = { 
                                                id: {{ $staff->id }}, 
                                                name: '{{ addslashes($staff->name) }}', 
                                                phone: '{{ $staff->phone ?? '' }}', 
                                                role_id: '{{ $staff->role_id ?? '' }}' 
                                            }; editStaffModal = true;"
                                            class="p-1.5 rounded-md hover:bg-slate-100 text-slate-500 hover:text-blue-600 transition-colors"
                                            title="Sửa thông tin">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </button>

                                    <!-- Demote to Customer Button -->
                                    @if($staff->id !== auth()->id())
                                        <form action="{{ route('admin.staff.demote', $staff->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn GIÁNG CHỨC nhân sự [{{ $staff->name }}] về lại Khách hàng thông thường? Tài khoản sẽ mất quyền truy cập hệ thống quản trị.');" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="p-1.5 rounded-md hover:bg-amber-50 text-slate-400 hover:text-amber-600 transition-colors"
                                                    title="Giáng chức về Khách hàng">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Toggle Active/Lock Button -->
                                    @if($staff->id !== auth()->id())
                                        <form action="{{ route('admin.staff.toggle-status', $staff->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Bạn có chắc muốn {{ $staff->is_active ? 'KHÓA' : 'MỞ KHÓA' }} tài khoản {{ $staff->name }}?')"
                                                    class="p-1.5 rounded-md hover:bg-slate-100 {{ $staff->is_active ? 'text-slate-400 hover:text-rose-600' : 'text-slate-400 hover:text-emerald-600' }} transition-colors"
                                                    title="{{ $staff->is_active ? 'Khóa tài khoản' : 'Kích hoạt tài khoản' }}">
                                                @if($staff->is_active)
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                Không tìm thấy nhân sự nào phù hợp với điều kiện tìm kiếm.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staffMembers->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $staffMembers->links() }}
            </div>
        @endif
    </div>

    <!-- Modal: Thêm / Bổ Nhiệm Nhân Viên Mới -->
    <div x-show="addStaffModal" 
         x-cloak 
         x-data="{ staffTab: 'existing', searchUser: '', selectedUser: null }"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div @click.outside="addStaffModal = false" class="w-full max-w-lg bg-white border border-slate-200 rounded-2xl shadow-xl p-6 space-y-4">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span>Thêm &amp; Bổ nhiệm Nhân sự</span>
                </h3>
                <button @click="addStaffModal = false" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>

            <!-- Segmented Method Switcher -->
            <div class="grid grid-cols-2 p-1 rounded-lg bg-slate-100 text-xs font-semibold">
                <button type="button" 
                        @click="staffTab = 'existing'"
                        :class="staffTab === 'existing' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900'"
                        class="py-2 px-3 rounded-md transition-all flex items-center justify-center gap-1.5">
                    <span>⚡ Từ khách hàng có sẵn</span>
                </button>
                <button type="button" 
                        @click="staffTab = 'new'"
                        :class="staffTab === 'new' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900'"
                        class="py-2 px-3 rounded-md transition-all flex items-center justify-center gap-1.5">
                    <span>➕ Tạo tài khoản mới</span>
                </button>
            </div>

            <!-- TAB 1: BỔ NHIỆM TỪ TÀI KHOẢN CÓ SẴN -->
            <div x-show="staffTab === 'existing'" class="space-y-4">
                <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-200 text-xs text-blue-900 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Chỉ định vai trò nhân sự cho một tài khoản khách hàng đã đăng ký trước đó. Giữ nguyên mật khẩu, lịch sử và số điện thoại của họ.</span>
                </div>

                <form action="{{ route('admin.staff.assign-existing') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Chọn tài khoản khách hàng <span class="text-rose-500">*</span>
                        </label>
                        <select name="user_id" required class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-800 text-xs focus:outline-none focus:border-blue-500">
                            <option value="">-- Chọn tài khoản khách hàng --</option>
                            @foreach($availableCustomers as $cust)
                                <option value="{{ $cust->id }}">
                                    {{ $cust->name }} ({{ $cust->email }} - {{ $cust->phone ?? 'Chưa có SĐT' }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">Danh sách khách hàng đã đăng ký gần đây.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Bổ nhiệm vai trò <span class="text-rose-500">*</span>
                        </label>
                        <select name="role_id" required class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-800 text-xs focus:outline-none focus:border-blue-500">
                            <option value="">-- Chọn vai trò nhân sự --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }} ({{ $role->name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button @click="addStaffModal = false" type="button" class="px-3.5 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-800 bg-slate-100 transition-colors">
                            Hủy bỏ
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-xs">
                            Bổ nhiệm nhân sự ngay
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: TẠO TÀI KHOẢN MỚI TỪ ĐẦU -->
            <div x-show="staffTab === 'new'" class="space-y-4">
                <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Họ và tên nhân viên <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               required 
                               placeholder="Nguyễn Văn A" 
                               class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-900 text-xs placeholder:text-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Địa chỉ Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   required 
                                   placeholder="nhanvien@smarttech.vn" 
                                   class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-900 text-xs placeholder:text-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Số điện thoại <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   name="phone" 
                                   required 
                                   placeholder="0912345678" 
                                   class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-900 text-xs placeholder:text-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Vai trò phân nhiệm <span class="text-rose-500">*</span>
                            </label>
                            <select name="role_id" required class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-800 text-xs focus:outline-none focus:border-blue-500">
                                <option value="">-- Chọn vai trò --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->display_name }} ({{ $role->name }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Mật khẩu khởi tạo <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   required 
                                   placeholder="Tối thiểu 8 ký tự" 
                                   class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-900 text-xs placeholder:text-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-500">
                        💡 Lưu ý: Nếu email nhập vào đã có tài khoản, hệ thống sẽ tự động thăng cấp người dùng đó thành nhân viên mà không báo lỗi trùng lặp.
                    </p>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button @click="addStaffModal = false" type="button" class="px-3.5 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-800 bg-slate-100 transition-colors">
                            Hủy bỏ
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-xs">
                            Tạo nhân viên
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Modal: Chỉnh Sửa Nhân Viên -->
    <div x-show="editStaffModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div @click.outside="editStaffModal = false" class="w-full max-w-lg bg-white border border-slate-200 rounded-2xl shadow-xl p-6 space-y-4">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Cập nhật Thông tin &amp; Vai trò</span>
                </h3>
                <button @click="editStaffModal = false" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>

            <form :action="'{{ url('/admin/staff') }}/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Họ và tên nhân viên <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           x-model="editData.name"
                           required 
                           class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-900 text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Số điện thoại <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="phone" 
                               x-model="editData.phone"
                               required 
                               class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-900 text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Vai trò phân nhiệm <span class="text-rose-500">*</span>
                        </label>
                        <select name="role_id" x-model="editData.role_id" required class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-800 text-xs focus:outline-none focus:border-blue-500">
                            <option value="customer" class="text-rose-600 font-bold">-- ⬇️ Giáng chức về Khách hàng (Customer) --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }} ({{ $role->name }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Đặt lại mật khẩu mới (Để trống nếu giữ nguyên)
                    </label>
                    <input type="password" 
                           name="password" 
                           placeholder="Nhập mật khẩu mới nếu cần đổi" 
                           class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-900 text-xs placeholder:text-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button @click="editStaffModal = false" type="button" class="px-3.5 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-800 bg-slate-100 transition-colors">
                        Hủy bỏ
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-xs">
                        Lưu thay đổi
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
