@props(['product', 'summary' => null])

@php
    $aiData = $summary ?? $product->ai_summary ?? [
        'pros' => ['Cấu hình mạnh mẽ, độ hoàn thiện cao chính hãng.', 'Màn hình sắc nét, thời lượng pin ổn định.'],
        'cons' => ['Nên sử dụng củ sạc nhanh và phụ kiện chính hãng.'],
        'target_users' => 'Người dùng học tập, làm việc văn phòng và giải trí đa phương tiện.'
    ];
@endphp

<!-- AI SUMMARY CARD COMPONENT -->
<div class="bg-gradient-to-br from-[#F5F3FF] via-white to-[#EDE9FE]/50 rounded-3xl p-6 border border-purple-100 shadow-2xs relative overflow-hidden my-6">
    <!-- Decorative background glow -->
    <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-gradient-to-br from-purple-400/10 to-violet-400/10 rounded-full blur-2xl pointer-events-none"></div>

    <!-- Header -->
    <div class="flex items-center justify-between pb-4 border-b border-purple-100/80 mb-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-[#7C3AED] text-white flex items-center justify-center shadow-md shadow-purple-500/20">
                <i class="fa-solid fa-wand-magic-sparkles text-lg"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="font-extrabold text-sm text-slate-900 tracking-tight">Tóm Tắt Đánh Giá Bằng Trợ Lý AI</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider bg-purple-100 text-purple-700 border border-purple-200 uppercase">D&D AI</span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Phân tích tự động dựa trên thông số kỹ thuật và phản hồi thực tế</p>
            </div>
        </div>

        @if(isset($product))
        <button 
            type="button" 
            onclick="refreshAiSummary({{ $product->id }})"
            id="refresh-summary-btn-{{ $product->id }}"
            class="text-xs text-[#7C3AED] hover:text-[#6D28D9] font-semibold flex items-center gap-1.5 px-3.5 py-1.5 rounded-full hover:bg-white transition-colors border border-transparent hover:border-purple-200 cursor-pointer"
            title="Tạo lại tóm tắt mới"
        >
            <i class="fa-solid fa-arrows-rotate text-[11px]"></i>
            <span class="hidden sm:inline">Phân tích lại</span>
        </button>
        @endif
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Ưu điểm (Pros) -->
        <div class="bg-white/80 backdrop-blur-xs rounded-2xl p-4 border border-emerald-100/80 shadow-2xs">
            <div class="flex items-center gap-2 text-emerald-700 font-bold text-xs uppercase tracking-wider mb-3">
                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-check"></i>
                </span>
                <span>Ưu điểm nổi bật</span>
            </div>
            <ul class="space-y-2 text-xs text-slate-700">
                @foreach(($aiData['pros'] ?? []) as $pro)
                <li class="flex items-start gap-2 leading-relaxed">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-[11px] mt-0.5 shrink-0"></i>
                    <span>{{ $pro }}</span>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Nhược điểm / Lưu ý (Cons) -->
        <div class="bg-white/80 backdrop-blur-xs rounded-2xl p-4 border border-amber-100/80 shadow-2xs">
            <div class="flex items-center gap-2 text-amber-700 font-bold text-xs uppercase tracking-wider mb-3">
                <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </span>
                <span>Điểm cần lưu ý</span>
            </div>
            <ul class="space-y-2 text-xs text-slate-700">
                @forelse(($aiData['cons'] ?? []) as $con)
                <li class="flex items-start gap-2 leading-relaxed">
                    <i class="fa-solid fa-circle-exclamation text-amber-500 text-[11px] mt-0.5 shrink-0"></i>
                    <span>{{ $con }}</span>
                </li>
                @empty
                <li class="text-xs text-slate-400 italic">Không có nhược điểm lớn trong tầm giá này.</li>
                @endforelse
            </ul>
        </div>

        <!-- Đối tượng phù hợp (Target Users) -->
        <div class="bg-white/80 backdrop-blur-xs rounded-2xl p-4 border border-blue-100/80 shadow-2xs">
            <div class="flex items-center gap-2 text-blue-700 font-bold text-xs uppercase tracking-wider mb-3">
                <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-user-check"></i>
                </span>
                <span>Phù hợp với ai?</span>
            </div>
            <p class="text-xs text-slate-700 leading-relaxed">
                {{ $aiData['target_users'] ?? 'Khách hàng tìm kiếm thiết bị ổn định, bền bỉ và chính hãng.' }}
            </p>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Bạn cần tư vấn thêm?</span>
                <button type="button" onclick="window.SmartTechAI && window.SmartTechAI.openChat()" class="text-blue-600 hover:text-blue-700 font-bold">Hỏi AI ngay &rarr;</button>
            </div>
        </div>
    </div>
</div>

<script>
function refreshAiSummary(productId) {
    const btn = document.getElementById(`refresh-summary-btn-${productId}`);
    if (!btn) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-[11px]"></i> Đang phân tích...';

    fetch(`/api/ai/summary/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Không thể tạo lại tóm tắt lúc này. Vui lòng thử lại sau.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-arrows-rotate text-[11px]"></i> Phân tích lại';
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-arrows-rotate text-[11px]"></i> Phân tích lại';
    });
}
</script>
