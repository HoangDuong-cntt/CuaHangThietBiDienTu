@extends('layouts.client')

@section('title', 'Hồ sơ cá nhân & Sổ địa chỉ - D&D.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="addressManager()">
    
    <!-- User Overview Hero Banner (High-end Studio Design) -->
    <div class="p-6 sm:p-8 rounded-lg border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <!-- Avatar with Fallback Initials -->
            <div class="w-16 h-16 rounded-md bg-zinc-900 text-white dark:bg-zinc-800 flex items-center justify-center font-bold text-xl uppercase border border-zinc-200 dark:border-zinc-700 shadow-inner overflow-hidden shrink-0">
                @if($user->avatar)
                    <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    {{ substr($user->name, 0, 1) }}
                @endif
            </div>

            <div class="space-y-1">
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">{{ $user->name }}</h1>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase tracking-wider
                        {{ $user->isAdmin() ? 'bg-rose-500/10 text-rose-600 border border-rose-500/20' : ($user->isStaff() ? 'bg-blue-500/10 text-blue-600 border border-blue-500/20' : 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20') }}">
                        {{ $user->role }}
                    </span>
                </div>
                <p class="text-xs font-mono text-zinc-500">{{ $user->email }} &bull; Đã tham gia: {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="flex items-center gap-4 w-full md:w-auto border-t md:border-t-0 pt-4 md:pt-0 border-zinc-100 dark:border-zinc-800">
            <div class="text-left md:text-right px-3 py-1.5 rounded bg-zinc-50 dark:bg-zinc-950/50 border border-zinc-200/60 dark:border-zinc-800">
                <p class="text-[10px] font-mono uppercase text-zinc-400">Sổ địa chỉ</p>
                <p class="text-sm font-bold text-zinc-900 dark:text-white">{{ $addresses->count() }} địa chỉ</p>
            </div>
            <div class="text-left md:text-right px-3 py-1.5 rounded bg-zinc-50 dark:bg-zinc-950/50 border border-zinc-200/60 dark:border-zinc-800">
                <p class="text-[10px] font-mono uppercase text-zinc-400">Trạng thái Email</p>
                <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400">✓ Đã xác thực</p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-zinc-200 dark:border-zinc-800 mt-8">
        <button @click="activeTab = 'profile'; history.replaceState(null, null, '?tab=profile')" 
                :class="activeTab === 'profile' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400 font-bold' : 'border-transparent text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-300 font-medium'"
                class="py-3 px-4 border-b-2 text-xs uppercase tracking-wider transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Thông tin cá nhân &amp; Đổi mật khẩu</span>
        </button>

        <button @click="activeTab = 'addresses'; history.replaceState(null, null, '?tab=addresses')" 
                :class="activeTab === 'addresses' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400 font-bold' : 'border-transparent text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-300 font-medium'"
                class="py-3 px-4 border-b-2 text-xs uppercase tracking-wider transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            </svg>
            <span>Sổ địa chỉ nhận hàng ({{ $addresses->count() }})</span>
        </button>
    </div>

    <!-- TAB 1: THÔNG TIN CÁ NHÂN & MẬT KHẨU -->
    <div x-show="activeTab === 'profile'" class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Cập nhật thông tin -->
        <div class="p-6 rounded-lg border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm space-y-4">
            <div>
                <h2 class="text-sm font-bold text-zinc-900 dark:text-white uppercase tracking-tight">
                    Cập nhật thông tin định danh
                </h2>
                <p class="text-xs text-zinc-500">Thông tin liên hệ của bạn trên hệ thống SmartTech.</p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- File Avatar -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">Thay đổi ảnh đại diện</label>
                    <input type="file" name="avatar" accept="image/*" class="text-xs text-zinc-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-zinc-900 file:text-white hover:file:bg-zinc-800 dark:file:bg-zinc-800 cursor-pointer">
                    @error('avatar') <p class="text-[11px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">Họ và tên</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
                    @error('name') <p class="text-[11px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">Email đăng nhập (Cố định)</label>
                    <input type="email" value="{{ $user->email }}" readonly
                           class="w-full px-3.5 py-2.5 rounded-md border border-zinc-200 dark:border-zinc-800 text-xs bg-zinc-100 dark:bg-zinc-900 text-zinc-500 cursor-not-allowed outline-none font-mono">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">Số điện thoại</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="0901234567"
                           class="w-full px-3.5 py-2.5 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
                    @error('phone') <p class="text-[11px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="py-2.5 px-5 rounded-md bg-zinc-900 hover:bg-zinc-800 text-white dark:bg-white dark:text-zinc-950 text-xs font-bold transition-all shadow-sm">
                    Lưu thông tin cá nhân
                </button>
            </form>
        </div>

        <!-- Đổi mật khẩu -->
        <div class="p-6 rounded-lg border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm space-y-4">
            <div>
                <h2 class="text-sm font-bold text-zinc-900 dark:text-white uppercase tracking-tight">
                    Bảo mật &amp; Đổi mật khẩu
                </h2>
                <p class="text-xs text-zinc-500">Nên sử dụng mật khẩu mạnh kết hợp chữ, số và ký tự đặc biệt.</p>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" required placeholder="••••••••"
                           class="w-full px-3.5 py-2.5 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
                    @error('current_password') <p class="text-[11px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">Mật khẩu mới</label>
                    <input type="password" name="password" required placeholder="Tối thiểu 8 ký tự"
                           class="w-full px-3.5 py-2.5 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
                    @error('password') <p class="text-[11px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" required placeholder="Nhập lại mật khẩu mới"
                           class="w-full px-3.5 py-2.5 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
                </div>

                <button type="submit" class="py-2.5 px-5 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all shadow-sm">
                    Cập nhật mật khẩu mới
                </button>
            </form>
        </div>

    </div>

    <!-- TAB 2: SỔ ĐỊA CHỈ NHẬN HÀNG -->
    <div x-show="activeTab === 'addresses'" class="mt-6 space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-zinc-900 dark:text-white uppercase tracking-tight flex items-center gap-2">
                    <span>Sổ địa chỉ giao hàng</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-blue-500/10 text-blue-500 border border-blue-500/20">
                        CHUẨN 34 TỈNH THÀNH
                    </span>
                </h2>
                <p class="text-xs text-zinc-500">Mọi địa chỉ tự động được đồng bộ về 34 tỉnh thành thống nhất để tránh trùng lặp.</p>
            </div>
            
            <div class="flex items-center gap-2">
                @if(!$addresses->isEmpty())
                    <form action="{{ route('addresses.normalize-34') }}" method="POST" onsubmit="return confirm('Hệ thống sẽ quy đổi toàn bộ các địa chỉ cũ sang chuẩn 34 tỉnh thành và tự động gộp các địa chỉ trùng lặp. Bạn có muốn thực hiện?');">
                        @csrf
                        <button type="submit" 
                                title="Quy đổi tất cả địa chỉ cũ (63 tỉnh) về chuẩn 34 tỉnh thành và xóa trùng lặp"
                                class="py-2.5 px-3 rounded-md bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-blue-600 dark:text-blue-400 text-xs font-semibold flex items-center gap-1.5 transition-all border border-zinc-200 dark:border-zinc-700">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>⚡ Chuẩn hóa 34 Tỉnh thành</span>
                        </button>
                    </form>
                @endif

                <button @click="openAddModal()" 
                        type="button" 
                        class="py-2.5 px-4 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold flex items-center gap-2 transition-all shadow-sm shrink-0">
                    <span>+ Thêm địa chỉ mới</span>
                </button>
            </div>
        </div>

        @if($addresses->isEmpty())
            <div class="p-12 text-center rounded-lg border border-dashed border-zinc-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 space-y-3">
                <svg class="w-8 h-8 text-zinc-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                </svg>
                <p class="text-xs text-zinc-500 font-medium">Bạn chưa lưu địa chỉ nhận hàng nào.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($addresses as $addr)
                    <div class="p-5 rounded-lg border {{ $addr->is_default ? 'border-blue-600 bg-blue-50/20 dark:border-blue-500 dark:bg-blue-950/20' : 'border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900' }} shadow-sm flex flex-col justify-between space-y-4">
                        
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-sm text-zinc-900 dark:text-white">{{ $addr->receiver_name }}</span>
                                @if($addr->is_default)
                                    <span class="px-2.5 py-0.5 rounded bg-blue-600 text-white text-[10px] font-mono font-bold uppercase tracking-wider shadow-sm">
                                        MẶC ĐỊNH
                                    </span>
                                @endif
                            </div>

                            <p class="text-xs font-mono text-zinc-500 flex items-center gap-1.5">
                                <span>📞</span> {{ $addr->receiver_phone }}
                            </p>
                            <p class="text-xs text-zinc-700 dark:text-zinc-300 leading-relaxed flex items-start gap-1.5">
                                <span class="shrink-0">📍</span>
                                <span>{{ $addr->full_address }}</span>
                            </p>
                        </div>

                        <!-- Action Row -->
                        <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <button type="button" 
                                        @click="openEditModal({{ json_encode($addr) }})"
                                        class="font-semibold text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                                    Chỉnh sửa
                                </button>
                                
                                <form action="{{ route('addresses.destroy', $addr->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-rose-600 hover:text-rose-700 transition-colors">
                                        Xóa
                                    </button>
                                </form>
                            </div>

                            @if(!$addr->is_default)
                                <form action="{{ route('addresses.default', $addr->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">
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

    <!-- MODAL THÊM / SỬA ĐỊA CHỈ -->
    <div x-show="showAddressModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/70 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div class="w-full max-w-lg p-6 sm:p-8 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-2xl space-y-4"
             @click.outside="showAddressModal = false">
            
            <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white uppercase tracking-tight" 
                    x-text="editMode ? 'Chỉnh sửa địa chỉ' : 'Thêm địa chỉ nhận hàng mới'"></h3>
                <button @click="showAddressModal = false" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white text-xl leading-none">&times;</button>
            </div>

            <form :action="editMode ? '/profile/addresses/' + currentAddress.id : '{{ route('addresses.store') }}'" method="POST" class="space-y-3.5">
                @csrf
                <input type="hidden" name="_method" :value="editMode ? 'PUT' : 'POST'">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                            Tên người nhận <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="receiver_name" x-model="currentAddress.receiver_name" required placeholder="Nguyễn Văn A"
                               class="w-full px-3.5 py-2 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                            Số điện thoại nhận hàng <span class="text-rose-500">*</span>
                        </label>
                        <input type="tel" name="receiver_phone" x-model="currentAddress.receiver_phone" required placeholder="0901234567"
                               class="w-full px-3.5 py-2 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
                    </div>
                </div>

                <!-- Administrative Location Header & Toggle -->
                <div class="pt-1.5 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5 text-xs text-zinc-600 dark:text-zinc-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        <span class="font-semibold text-[11px] uppercase tracking-wider">Khu vực hành chính</span>
                    </div>

                    <!-- Segmented Mode Control -->
                    <div class="inline-flex p-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-[10px] font-medium border border-zinc-200 dark:border-zinc-700">
                        <button type="button" @click="setMode('34')" 
                                :class="mode === '34' ? 'bg-white dark:bg-zinc-900 text-blue-600 dark:text-blue-400 font-bold shadow-xs' : 'text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200'" 
                                class="px-2.5 py-1 rounded transition-all">
                            ⭐ 34 Tỉnh thành (Mới)
                        </button>
                        <button type="button" @click="setMode('63')" 
                                :class="mode === '63' ? 'bg-white dark:bg-zinc-900 text-blue-600 dark:text-blue-400 font-bold shadow-xs' : 'text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200'" 
                                class="px-2.5 py-1 rounded transition-all">
                            63 Tỉnh thành
                        </button>
                        <button type="button" @click="setMode('manual')" 
                                :class="mode === 'manual' ? 'bg-white dark:bg-zinc-900 text-blue-600 dark:text-blue-400 font-bold shadow-xs' : 'text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200'" 
                                class="px-2.5 py-1 rounded transition-all">
                            Nhập tay
                        </button>
                    </div>

                    <!-- Smart Normalization Hint -->
                    <div class="w-full text-[10px] font-mono text-zinc-500 flex items-center gap-1.5 pt-0.5">
                        <span class="text-blue-500">ℹ️</span>
                        <span>Mọi địa chỉ nhập vào đều tự động chuẩn hóa theo chuẩn 34 Tỉnh Thành sau sáp nhập để tránh trùng lặp.</span>
                    </div>
                </div>

                <!-- MODE 1: 34 TỈNH THÀNH (Sau sáp nhập: 2 Cấp Tỉnh/Thành -> Phường/Xã) -->
                <div x-show="mode === '34'" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="hidden" :name="mode === '34' ? 'district' : null" :value="currentAddress.province">

                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                            Tỉnh / Thành phố (34 Tỉnh Thành) <span class="text-rose-500">*</span>
                        </label>
                        <select :name="mode === '34' ? 'province' : null" 
                                x-model="currentAddress.province" 
                                @change="onProvince34Change($event.target.value)" 
                                :disabled="mode !== '34'"
                                :required="mode === '34'"
                                class="w-full px-3 py-2 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
                            <option value="">-- Chọn 1 trong 34 Tỉnh / TP --</option>
                            <template x-for="p in provinces34" :key="p.code">
                                <option :value="p.name" x-text="p.name" :selected="currentAddress.province === p.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                            Phường / Xã <span class="text-rose-500">*</span>
                        </label>
                        <select :name="mode === '34' ? 'ward' : null" 
                                x-model="currentAddress.ward" 
                                :disabled="mode !== '34' || wards34.length === 0"
                                :required="mode === '34'"
                                class="w-full px-3 py-2 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none disabled:opacity-50 disabled:cursor-not-allowed">
                            <option value="" x-text="wards34.length === 0 ? '-- Chọn Tỉnh trước --' : '-- Chọn Phường / Xã --'"></option>
                            <template x-for="w in wards34" :key="w.code">
                                <option :value="w.name" x-text="w.name" :selected="currentAddress.ward === w.name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <!-- MODE 2: 63 TỈNH THÀNH TRUYỀN THỐNG (3 Cấp Tỉnh -> Huyện -> Xã) -->
                <div x-show="mode === '63'" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                            Tỉnh / TP <span class="text-rose-500">*</span>
                        </label>
                        <select :name="mode === '63' ? 'province' : null" 
                                x-model="currentAddress.province" 
                                @change="onProvince63Change($event.target.value)" 
                                :disabled="mode !== '63'"
                                :required="mode === '63'"
                                class="w-full px-3 py-2 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
                            <option value="">-- Chọn Tỉnh / TP --</option>
                            <template x-for="p in provinces63" :key="p.Id">
                                <option :value="p.Name" x-text="p.Name" :selected="currentAddress.province === p.Name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                            Quận / Huyện <span class="text-rose-500">*</span>
                        </label>
                        <select :name="mode === '63' ? 'district' : null" 
                                x-model="currentAddress.district" 
                                @change="onDistrict63Change($event.target.value)" 
                                :disabled="mode !== '63' || districts63.length === 0"
                                :required="mode === '63'"
                                class="w-full px-3 py-2 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none disabled:opacity-50 disabled:cursor-not-allowed">
                            <option value="" x-text="districts63.length === 0 ? '-- Chọn Tỉnh trước --' : '-- Chọn Quận / Huyện --'"></option>
                            <template x-for="d in districts63" :key="d.Id">
                                <option :value="d.Name" x-text="d.Name" :selected="currentAddress.district === d.Name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                            Phường / Xã <span class="text-rose-500">*</span>
                        </label>
                        <select :name="mode === '63' ? 'ward' : null" 
                                x-model="currentAddress.ward" 
                                :disabled="mode !== '63' || wards63.length === 0"
                                :required="mode === '63'"
                                class="w-full px-3 py-2 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none disabled:opacity-50 disabled:cursor-not-allowed">
                            <option value="" x-text="wards63.length === 0 ? '-- Chọn Huyện trước --' : '-- Chọn Phường / Xã --'"></option>
                            <template x-for="w in wards63" :key="w.Id">
                                <option :value="w.Name" x-text="w.Name" :selected="currentAddress.ward === w.Name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <!-- MODE 3: MANUAL INPUT (Dự phòng gõ tay) -->
                <div x-show="mode === 'manual'" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">Tỉnh / Thành phố <span class="text-rose-500">*</span></label>
                        <input type="text" :name="mode === 'manual' ? 'province' : null" x-model="currentAddress.province" :disabled="mode !== 'manual'" :required="mode === 'manual'" placeholder="TP. Hồ Chí Minh"
                               class="w-full px-3.5 py-2 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">Quận / Huyện</label>
                        <input type="text" :name="mode === 'manual' ? 'district' : null" x-model="currentAddress.district" :disabled="mode !== 'manual'" placeholder="Quận 1"
                               class="w-full px-3.5 py-2 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">Phường / Xã <span class="text-rose-500">*</span></label>
                        <input type="text" :name="mode === 'manual' ? 'ward' : null" x-model="currentAddress.ward" :disabled="mode !== 'manual'" :required="mode === 'manual'" placeholder="Bến Nghé"
                               class="w-full px-3.5 py-2 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                        Số nhà, tên đường, ngõ ngách <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="street_address" x-model="currentAddress.street_address" required placeholder="Số 123 Đường Lê Lợi"
                           class="w-full px-3.5 py-2 rounded-md border border-zinc-200 dark:border-zinc-750 text-xs bg-zinc-50 dark:bg-zinc-950/50 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
                </div>

                <div class="pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_default" value="1" x-model="currentAddress.is_default" class="w-4 h-4 rounded-sm border-zinc-300 text-blue-600">
                        <span class="text-xs text-zinc-600 dark:text-zinc-400">Đặt làm địa chỉ nhận hàng mặc định</span>
                    </label>
                </div>

                <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-end gap-2">
                    <button type="button" @click="showAddressModal = false" class="px-4 py-2 text-xs font-semibold text-zinc-600 hover:text-zinc-900 dark:text-zinc-400">
                        Hủy
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm">
                        Lưu địa chỉ
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Alpine.js Address Manager Component Script -->
<script>
document.addEventListener('alpine:init', () => {
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

            // Tìm tỉnh trong danh sách 34 tỉnh thành
            let p34 = this.provinces34.find(item => item.name === addr.province);
            if (!p34 && addr.province) {
                const slug = addr.province.toLowerCase().replace(/^(tỉnh|thành phố|tp\.?)\s+/i, '').trim();
                p34 = this.provinces34.find(item => item.name.toLowerCase().includes(slug));
            }

            if (p34) {
                this.currentAddress.province = p34.name;
                this.currentAddress.district = p34.name;
                this.wards34 = [...(p34.wards || [])];

                // Nếu phường cũ chưa có trong danh mục 34 tỉnh mới, thêm tạm để select vẫn hiển thị đúng
                if (addr.ward && !this.wards34.some(w => w.name === addr.ward)) {
                    this.wards34.unshift({ code: 'saved', name: addr.ward });
                }
            }
        }
    }));
});
</script>
@endsection
