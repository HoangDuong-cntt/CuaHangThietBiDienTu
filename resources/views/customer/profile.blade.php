@extends('layouts.client', ['title' => 'Hồ sơ cá nhân & Sổ địa chỉ - D&D.'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10" x-data="addressManager()">

    <!-- User Overview Hero Banner (Harmonious Level 1 Container #f4f6f8) -->
    <div class="p-6 sm:p-8 rounded-3xl bg-[#f4f6f8] border border-slate-200/80 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-2xs">
        <div class="flex items-center gap-5">
            <!-- Avatar with Fallback Initials -->
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white border-2 border-white shadow-md flex items-center justify-center font-black text-2xl uppercase text-slate-800 overflow-hidden shrink-0">
                @if($user->avatar)
                    <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-blue-600 font-extrabold">{{ mb_substr($user->name, 0, 1) }}</span>
                @endif
            </div>

            <div class="space-y-1">
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-slate-900">{{ $user->name }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                        {{ $user->isAdmin() ? 'bg-rose-100 text-rose-700 border border-rose-200' : ($user->isStaff() ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200') }}">
                        {{ $user->isAdmin() ? 'Quản trị viên' : ($user->isStaff() ? 'Nhân viên' : 'Thành viên') }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium">
                    {{ $user->email }} &bull; Đã tham gia: {{ $user->created_at->format('d/m/Y') }}
                </p>
            </div>
        </div>

        <!-- Quick Stats Cards (Clean White Elevated Surface) -->
        <div class="flex items-center gap-3 w-full md:w-auto border-t md:border-t-0 pt-4 md:pt-0 border-slate-200/80">
            <div class="flex-1 md:flex-none px-4 py-2.5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs text-left md:text-right">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Sổ địa chỉ</p>
                <p class="text-sm font-black text-slate-900 mt-0.5">{{ $addresses->count() }} địa chỉ</p>
            </div>
            <div class="flex-1 md:flex-none px-4 py-2.5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs text-left
            md:text-right">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tài khoản</p>
                @if($user->hasVerifiedEmail())
                    <p class="text-xs font-bold text-emerald-600 mt-0.5 flex items-center md:justify-end gap-1">
                        <i class="fa-solid fa-circle-check text-[11px]"></i>
                        <span>Đã xác thực</span>
                    </p>
                @else
                    <a href="{{ route('verification.notice') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700
                    mt-0.5 flex items-center md:justify-end gap-1">
                        <i class="fa-solid fa-triangle-exclamation text-[11px]"></i>
                        <span class="underline">Chưa xác thực email</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Flash Session Alerts -->
    @if(session('success'))
        <div class="mt-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs sm:text-sm flex items-start gap-3 shadow-2xs">
            <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 shrink-0 text-base"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="mt-6 p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-800 text-xs sm:text-sm flex items-start gap-3 shadow-2xs">
            <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-base"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if(session('info'))
        <div class="mt-6 p-4 rounded-2xl bg-blue-50 border border-blue-200/80 text-blue-800 text-xs sm:text-sm flex items-start gap-3 shadow-2xs">
            <i class="fa-solid fa-circle-info text-blue-600 mt-0.5 shrink-0 text-base"></i>
            <div>{{ session('info') }}</div>
        </div>
    @endif

    <!-- Clean Apple-style Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 mt-8">
        <button @click="activeTab = 'profile'; history.replaceState(null, null, '?tab=profile')"
                :class="activeTab === 'profile' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 font-medium'"
                class="py-3 px-4 border-b-2 text-xs sm:text-sm transition-all flex items-center gap-2 cursor-pointer">
            <i class="fa-regular fa-user"></i>
            <span>Thông tin cá nhân &amp; Đổi mật khẩu</span>
        </button>

        <button @click="activeTab = 'addresses'; history.replaceState(null, null, '?tab=addresses')"
                :class="activeTab === 'addresses' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 font-medium'"
                class="py-3 px-4 border-b-2 text-xs sm:text-sm transition-all flex items-center gap-2 cursor-pointer">
            <i class="fa-solid fa-location-dot"></i>
            <span>Sổ địa chỉ nhận hàng ({{ $addresses->count() }})</span>
        </button>
    </div>

    <!-- TAB 1: THÔNG TIN CÁ NHÂN & MẬT KHẨU -->
    <div x-show="activeTab === 'profile'" class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Cập nhật thông tin -->
        <div class="p-6 sm:p-8 rounded-3xl border border-slate-200/80 bg-white shadow-xs space-y-5">
            <div>
                <h2 class="text-base font-bold text-slate-900 tracking-tight">
                    Cập nhật thông tin định danh
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Thông tin liên hệ của bạn trên hệ sinh thái D&D.</p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- File Avatar -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Thay đổi ảnh đại diện</label>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200 flex items-center justify-center font-bold text-slate-600">
                            @if($user->avatar)
                                <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                {{ mb_substr($user->name, 0, 1) }}
                            @endif
                        </div>
                        <input type="file" name="avatar" accept="image/*" class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-blue-600 file:transition-colors cursor-pointer">
                    </div>
                    @error('avatar') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Họ và tên</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all">
                    @error('name') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email đăng nhập (Cố định)</label>
                    <input type="email" value="{{ $user->email }}" readonly
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-slate-100/70 text-slate-500 cursor-not-allowed outline-none font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Số điện thoại</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="0901234567"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all">
                    @error('phone') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="py-2.5 px-5 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs font-bold uppercase tracking-wider transition-all shadow-sm active:scale-[0.99] cursor-pointer">
                    Lưu thông tin cá nhân
                </button>
            </form>
        </div>

        <!-- Đổi mật khẩu -->
        <div class="p-6 sm:p-8 rounded-3xl border border-slate-200/80 bg-white shadow-xs space-y-5">
            <div>
                <h2 class="text-base font-bold text-slate-900 tracking-tight">
                    Bảo mật &amp; Đổi mật khẩu
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Nên sử dụng mật khẩu mạnh kết hợp chữ, số và ký tự đặc biệt.</p>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mật khẩu hiện tại</label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" required placeholder="••••••••"
                               class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all">
                        <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 cursor-pointer">
                            <i class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                    @error('current_password') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mật khẩu mới</label>
                    <div class="relative">
                        <input type="password" id="new_password" name="password" required placeholder="Tối thiểu 8 ký tự"
                               class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all">
                        <button type="button" onclick="togglePasswordVisibility('new_password', this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 cursor-pointer">
                            <i class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Xác nhận mật khẩu mới</label>
                    <div class="relative">
                        <input type="password" id="new_password_confirmation" name="password_confirmation" required placeholder="Nhập lại mật khẩu mới"
                               class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all">
                        <button type="button" onclick="togglePasswordVisibility('new_password_confirmation', this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 cursor-pointer">
                            <i class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="py-2.5 px-5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-wider transition-all shadow-sm active:scale-[0.99] cursor-pointer">
                    Cập nhật mật khẩu mới
                </button>
            </form>
        </div>

    </div>

    <!-- TAB 2: SỔ ĐỊA CHỈ NHẬN HÀNG -->
    <div x-show="activeTab === 'addresses'" class="mt-6 space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div>
                <h2 class="text-base font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <span>Sổ địa chỉ giao hàng</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-200">
                        CHUẨN 34 TỈNH THÀNH
                    </span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Địa chỉ được đồng bộ về 34 tỉnh thành thống nhất để đảm bảo giao hàng chính xác.</p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                @if(!$addresses->isEmpty())
                    <form action="{{ route('addresses.normalize-34') }}" method="POST" onsubmit="return confirm('Hệ thống sẽ quy đổi toàn bộ các địa chỉ cũ sang chuẩn 34 tỉnh thành và tự động gộp các địa chỉ trùng lặp. Bạn có muốn thực hiện?');">
                        @csrf
                        <button type="submit"
                                title="Quy đổi tất cả địa chỉ cũ (63 tỉnh) về chuẩn 34 tỉnh thành và xóa trùng lặp"
                                class="py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-colors border border-slate-200 cursor-pointer">
                            <i class="fa-solid fa-arrows-rotate text-blue-600 text-[11px]"></i>
                            <span>Chuẩn hóa 34 Tỉnh thành</span>
                        </button>
                    </form>
                @endif

                <button @click="openAddModal()"
                        type="button"
                        class="py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold flex items-center gap-1.5 transition-all shadow-sm shrink-0 cursor-pointer">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Thêm địa chỉ mới</span>
                </button>
            </div>
        </div>

        @if($addresses->isEmpty())
            <div class="p-12 text-center rounded-3xl border border-dashed border-slate-200 bg-white space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-xl">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <p class="text-xs font-medium text-slate-500">Bạn chưa lưu địa chỉ nhận hàng nào.</p>
                <button @click="openAddModal()" type="button" class="text-xs font-bold text-blue-600 hover:text-blue-700 underline cursor-pointer">
                    + Thêm địa chỉ nhận hàng đầu tiên
                </button>
            </div>
        @else
            <!-- Address Cards Grid (Crisp Elevated Surfaces with High Contrast) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($addresses as $addr)
                    <div class="p-6 rounded-2xl border transition-all shadow-2xs flex flex-col justify-between space-y-4
                                {{ $addr->is_default ? 'border-2 border-blue-600 bg-blue-50/20' : 'border-slate-200/90 bg-white hover:border-slate-300' }}">

                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-bold text-base text-slate-900 tracking-tight">{{ $addr->receiver_name }}</span>
                                @if($addr->is_default)
                                    <span class="px-2.5 py-0.5 rounded-full bg-blue-600 text-white text-[10px] font-bold uppercase tracking-wider shadow-xs">
                                        MẶC ĐỊNH
                                    </span>
                                @endif
                            </div>

                            <p class="text-xs font-semibold text-slate-600 flex items-center gap-2">
                                <i class="fa-solid fa-phone text-slate-400 text-xs"></i>
                                <span>{{ $addr->receiver_phone }}</span>
                            </p>

                            <p class="text-xs sm:text-sm text-slate-800 font-medium leading-relaxed flex items-start gap-2 bg-white/70 p-2.5 rounded-xl border border-slate-100">
                                <i class="fa-solid fa-location-dot text-blue-600 text-sm mt-0.5 shrink-0"></i>
                                <span>{{ $addr->full_address }}</span>
                            </p>
                        </div>

                        <!-- Action Row -->
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <button type="button"
                                        @click="openEditModal({{ json_encode($addr) }})"
                                        class="font-bold text-blue-600 hover:text-blue-700 transition-colors flex items-center gap-1 cursor-pointer">
                                    <i class="fa-regular fa-pen-to-square text-[11px]"></i>
                                    <span>Chỉnh sửa</span>
                                </button>

                                <form action="{{ route('addresses.destroy', $addr->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-rose-600 hover:text-rose-700 transition-colors flex items-center gap-1 cursor-pointer">
                                        <i class="fa-regular fa-trash-can text-[11px]"></i>
                                        <span>Xóa</span>
                                    </button>
                                </form>
                            </div>

                            @if(!$addr->is_default)
                                <form action="{{ route('addresses.default', $addr->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-bold text-slate-600 hover:text-blue-600 transition-colors cursor-pointer">
                                        Đặt làm mặc định
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <!-- MODAL THÊM / SỬA ĐỊA CHỈ (Alpine.js Dialog với Backdrop Blur) -->
    <div x-show="showAddressModal"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-xs overflow-y-auto"
         @keydown.escape.window="showAddressModal = false">

        <div class="w-full max-w-lg bg-white rounded-3xl border border-slate-200/90 shadow-2xl p-6 sm:p-8 space-y-5 my-8"
             @click.away="showAddressModal = false">

            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight" x-text="editMode ? 'Chỉnh sửa địa chỉ' : 'Thêm địa chỉ mới'"></h3>
                    <p class="text-xs text-slate-500">Chuẩn hóa địa chỉ giúp đơn hàng được giao nhanh nhất</p>
                </div>
                <button type="button" @click="showAddressModal = false" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Segmented Mode Selector: 34 Tỉnh thành vs 63 Tỉnh thành vs Gõ tay -->
            <div class="flex rounded-xl bg-slate-100 p-1 text-xs font-semibold">
                <button type="button"
                        @click="setMode('34')"
                        :class="mode === '34' ? 'bg-white text-blue-600 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900'"
                        class="flex-1 py-1.5 px-2 rounded-lg transition-all text-center cursor-pointer">
                    34 Tỉnh Mới
                </button>
                <button type="button"
                        @click="setMode('63')"
                        :class="mode === '63' ? 'bg-white text-blue-600 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900'"
                        class="flex-1 py-1.5 px-2 rounded-lg transition-all text-center cursor-pointer">
                    63 Tỉnh Cũ
                </button>
                <button type="button"
                        @click="setMode('manual')"
                        :class="mode === 'manual' ? 'bg-white text-blue-600 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900'"
                        class="flex-1 py-1.5 px-2 rounded-lg transition-all text-center cursor-pointer">
                    Nhập tay
                </button>
            </div>

            <form :action="editMode ? '/profile/addresses/' + currentAddress.id : '{{ route('addresses.store') }}'"
                  method="POST"
                  class="space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Người nhận <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="receiver_name" x-model="currentAddress.receiver_name" required placeholder="Nguyễn Văn A"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Số điện thoại <span class="text-rose-500">*</span>
                        </label>
                        <input type="tel" name="receiver_phone" x-model="currentAddress.receiver_phone" required placeholder="0901234567"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all">
                    </div>
                </div>

                <!-- MODE 1: CHUẨN 34 TỈNH THÀNH -->
                <div x-show="mode === '34'" class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Tỉnh / Thành phố <span class="text-rose-500">*</span>
                        </label>
                        <select :name="mode === '34' ? 'province' : null"
                                x-model="currentAddress.province"
                                @change="onProvince34Change($event.target.value)"
                                :disabled="mode !== '34'"
                                :required="mode === '34'"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all">
                            <option value="">-- Chọn Tỉnh / Thành phố --</option>
                            <template x-for="p in provinces34" :key="p.code || p.name">
                                <option :value="p.name" x-text="p.name" :selected="currentAddress.province === p.name"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Phường / Xã <span class="text-rose-500">*</span>
                        </label>
                        <select :name="mode === '34' ? 'ward' : null"
                                x-model="currentAddress.ward"
                                :disabled="mode !== '34' || wards34.length === 0"
                                :required="mode === '34'"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all disabled:opacity-50">
                            <option value="" x-text="wards34.length === 0 ? '-- Chọn Tỉnh trước --' : '-- Chọn Phường / Xã --'"></option>
                            <template x-for="w in wards34" :key="w.code || w.name">
                                <option :value="w.name" x-text="w.name" :selected="currentAddress.ward === w.name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <!-- MODE 2: CHUẨN 63 TỈNH CŨ -->
                <div x-show="mode === '63'" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Tỉnh / TP <span class="text-rose-500">*</span>
                        </label>
                        <select :name="mode === '63' ? 'province' : null"
                                x-model="currentAddress.province"
                                @change="onProvince63Change($event.target.value)"
                                :disabled="mode !== '63'"
                                :required="mode === '63'"
                                class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 outline-none">
                            <option value="">-- Chọn Tỉnh --</option>
                            <template x-for="p in provinces63" :key="p.Id">
                                <option :value="p.Name" x-text="p.Name" :selected="currentAddress.province === p.Name"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Quận / Huyện <span class="text-rose-500">*</span>
                        </label>
                        <select :name="mode === '63' ? 'district' : null"
                                x-model="currentAddress.district"
                                @change="onDistrict63Change($event.target.value)"
                                :disabled="mode !== '63' || districts63.length === 0"
                                :required="mode === '63'"
                                class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 outline-none disabled:opacity-50">
                            <option value="" x-text="districts63.length === 0 ? '-- Chọn Tỉnh trước --' : '-- Chọn Huyện --'"></option>
                            <template x-for="d in districts63" :key="d.Id">
                                <option :value="d.Name" x-text="d.Name" :selected="currentAddress.district === d.Name"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Phường / Xã <span class="text-rose-500">*</span>
                        </label>
                        <select :name="mode === '63' ? 'ward' : null"
                                x-model="currentAddress.ward"
                                :disabled="mode !== '63' || wards63.length === 0"
                                :required="mode === '63'"
                                class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 outline-none disabled:opacity-50">
                            <option value="" x-text="wards63.length === 0 ? '-- Chọn Huyện trước --' : '-- Chọn Phường --'"></option>
                            <template x-for="w in wards63" :key="w.Id">
                                <option :value="w.Name" x-text="w.Name" :selected="currentAddress.ward === w.Name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <!-- MODE 3: MANUAL INPUT (Dự phòng gõ tay) -->
                <div x-show="mode === 'manual'" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tỉnh / TP <span class="text-rose-500">*</span></label>
                        <input type="text" :name="mode === 'manual' ? 'province' : null" x-model="currentAddress.province" :disabled="mode !== 'manual'" :required="mode === 'manual'" placeholder="TP. Hồ Chí Minh"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Quận / Huyện</label>
                        <input type="text" :name="mode === 'manual' ? 'district' : null" x-model="currentAddress.district" :disabled="mode !== 'manual'" placeholder="Quận 1"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Phường / Xã <span class="text-rose-500">*</span></label>
                        <input type="text" :name="mode === 'manual' ? 'ward' : null" x-model="currentAddress.ward" :disabled="mode !== 'manual'" :required="mode === 'manual'" placeholder="Bến Nghé"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Số nhà, tên đường, ngõ ngách <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="street_address" x-model="currentAddress.street_address" required placeholder="Số 123 Đường Lê Lợi"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-slate-50/50 hover:bg-white focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all">
                </div>

                <div class="pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="is_default" value="1" x-model="currentAddress.is_default" class="w-4 h-4 rounded-md border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        <span class="text-xs text-slate-700 font-medium">Đặt làm địa chỉ nhận hàng mặc định</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="showAddressModal = false" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors cursor-pointer">
                        Hủy bỏ
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-wider shadow-sm transition-all active:scale-[0.99] cursor-pointer">
                        Lưu địa chỉ
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Alpine.js Address Manager Component Script -->
<script>
function registerAddressManager() {
    if (window.Alpine && !window._addressManagerRegistered) {
        window._addressManagerRegistered = true;
        Alpine.data('addressManager', () => ({
            activeTab: (new URLSearchParams(window.location.search)).get('tab') || @js(session('active_tab', 'profile')) || 'profile',
            showAddressModal: false,
            editMode: false,
            mode: '34', // Mặc định 34 Tỉnh Thành sau sáp nhập
            currentAddress: {
                id: null,
                receiver_name: @js($user->name),
                receiver_phone: @js($user->phone ?? ''),
                province: '',
                district: '',
                ward: '',
                street_address: '',
                is_default: false
            },
            provinces34: [],
            wards34: [],
            provinces63: [],
            districts63: [],
            wards63: [],

            init() {
                this.fetchData34();
            },

            async setMode(m) {
                this.mode = m;
                if (m === '34' && this.provinces34.length === 0) {
                    await this.fetchData34();
                } else if (m === '63' && this.provinces63.length === 0) {
                    await this.fetchData63();
                }
            },

            async fetchData34() {
                if (this.provinces34.length > 0) return;
                try {
                    const res = await fetch('/data/vietnam-34-provinces.json');
                    this.provinces34 = await res.json();
                } catch (err) {
                    console.error('Lỗi đọc dữ liệu 34 tỉnh thành:', err);
                }
            },

            async fetchData63() {
                if (this.provinces63.length > 0) return;
                try {
                    const res = await fetch('/data/vietnam-provinces.json');
                    this.provinces63 = await res.json();
                } catch (err) {
                    console.error('Lỗi đọc dữ liệu 63 tỉnh thành:', err);
                }
            },

            onProvince34Change(provinceName) {
                this.currentAddress.province = provinceName;
                this.currentAddress.district = provinceName;
                this.currentAddress.ward = '';
                this.wards34 = [];

                if (!provinceName) return;
                const p = this.provinces34.find(item => item.name === provinceName);
                if (p && p.wards) {
                    this.wards34 = p.wards;
                }
            },

            onProvince63Change(provinceName) {
                this.currentAddress.province = provinceName;
                this.currentAddress.district = '';
                this.currentAddress.ward = '';
                this.districts63 = [];
                this.wards63 = [];

                if (!provinceName) return;
                const prov = this.provinces63.find(p => p.Name === provinceName);
                if (prov && prov.Districts) {
                    this.districts63 = prov.Districts;
                }
            },

            onDistrict63Change(districtName) {
                this.currentAddress.district = districtName;
                this.currentAddress.ward = '';
                this.wards63 = [];

                if (!districtName) return;
                const dist = this.districts63.find(d => d.Name === districtName);
                if (dist && dist.Wards) {
                    this.wards63 = dist.Wards;
                }
            },

            openAddModal() {
                this.editMode = false;
                this.mode = '34';
                this.currentAddress = {
                    id: null,
                    receiver_name: @js($user->name),
                    receiver_phone: @js($user->phone ?? ''),
                    province: '',
                    district: '',
                    ward: '',
                    street_address: '',
                    is_default: false
                };
                this.wards34 = [];
                this.districts63 = [];
                this.wards63 = [];
                this.showAddressModal = true;
                this.fetchData34();
            },

            async openEditModal(addr) {
                this.editMode = true;
                this.currentAddress = { ...addr };
                this.showAddressModal = true;
                this.mode = '34'; // Luôn ưu tiên hiển thị theo chuẩn 34 Tỉnh thành

                await this.fetchData34();

                let p34 = this.provinces34.find(item => item.name === addr.province);
                if (!p34 && addr.province) {
                    const slug = addr.province.toLowerCase().replace(/^(tỉnh|thành phố|tp\.?)\s+/i, '').trim();
                    p34 = this.provinces34.find(item => item.name.toLowerCase().includes(slug));
                }

                if (p34) {
                    this.currentAddress.province = p34.name;
                    this.currentAddress.district = p34.name;
                    this.wards34 = [...(p34.wards || [])];

                    if (addr.ward && !this.wards34.some(w => w.name === addr.ward)) {
                        this.wards34.unshift({ code: 'saved', name: addr.ward });
                    }
                }
            }
        }));
    }
}

document.addEventListener('alpine:init', registerAddressManager);
if (window.Alpine) {
    registerAddressManager();
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    const icon = btn.querySelector('i');
    if (icon) {
        icon.className = isPassword ? 'fa-regular fa-eye-slash text-xs' : 'fa-regular fa-eye text-xs';
    }
}
</script>
@endsection
