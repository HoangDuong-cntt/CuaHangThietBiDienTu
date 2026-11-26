@props(['grams' => 1240])

<!-- VISUAL WEIGHT CONVERTER WIDGET (THÀNH VIÊN 3) -->
<div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs weight-converter-widget" data-initial-grams="{{ $grams }}">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm shadow-xs border border-amber-200/60">
                <i class="fa-solid fa-feather-pointed"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Quy Đổi Trọng Lượng Thực Tế</h4>
                <p class="text-[11px] text-slate-400">Trực quan hóa độ nặng nhẹ của thiết bị</p>
            </div>
        </div>
        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800" id="weight-display-badge">
            {{ $grams < 1000 ? $grams . 'g' : number_format($grams / 1000, 2) . 'kg' }}
        </span>
    </div>

    <!-- Real-world Equivalence Cards -->
    <div class="grid grid-cols-3 gap-2.5 mb-4 text-center">
        <!-- Chai nước suối 500ml -->
        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center">
            <span class="text-xl mb-1">🧴</span>
            <span class="text-xs font-bold text-slate-700" id="bottle-equiv">~2.5 chai</span>
            <span class="text-[10px] text-slate-400">Nước 500ml</span>
        </div>

        <!-- Cuốn sách văn phòng -->
        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center">
            <span class="text-xl mb-1">📚</span>
            <span class="text-xs font-bold text-slate-700" id="book-equiv">~4 cuốn</span>
            <span class="text-[10px] text-slate-400">Sổ tay A5</span>
        </div>

        <!-- Đánh giá mức độ cơ động -->
        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center">
            <span class="text-xl mb-1" id="mobility-icon">🪶</span>
            <span class="text-xs font-bold text-slate-700" id="mobility-tag">Siêu Cơ Động</span>
            <span class="text-[10px] text-slate-400">Đánh giá</span>
        </div>
    </div>

    <!-- Interactive Slider to Test Other Weights -->
    <div class="pt-2 border-t border-slate-100">
        <div class="flex justify-between text-[11px] text-slate-500 mb-1.5 font-medium">
            <span>Kéo thử trọng lượng khác:</span>
            <span id="slider-grams-label" class="font-bold text-blue-600">{{ $grams }}g</span>
        </div>
        <input 
            type="range" 
            min="200" 
            max="3500" 
            step="50" 
            value="{{ $grams }}"
            class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-blue-600 weight-slider"
        >
        <div class="flex justify-between text-[9px] text-slate-400 mt-1">
            <span>200g (Điện thoại)</span>
            <span>1.3kg (Mỏng nhẹ)</span>
            <span>2.5kg+ (Gaming/Máy trạm)</span>
        </div>
    </div>
</div>

<script>
(function() {
    document.querySelectorAll('.weight-converter-widget').forEach(widget => {
        const slider = widget.querySelector('.weight-slider');
        const badge = widget.querySelector('#weight-display-badge');
        const label = widget.querySelector('#slider-grams-label');
        const bottle = widget.querySelector('#bottle-equiv');
        const book = widget.querySelector('#book-equiv');
        const mobilityTag = widget.querySelector('#mobility-tag');
        const mobilityIcon = widget.querySelector('#mobility-icon');

        function updateWeight(g) {
            g = parseInt(g, 10);
            badge.textContent = g < 1000 ? g + 'g' : (g / 1000).toFixed(2) + 'kg';
            label.textContent = g + 'g';

            // 1 chai nước 500ml ~ 520g (cả vỏ)
            const bottles = (g / 520).toFixed(1);
            bottle.textContent = `~${bottles} chai`;

            // 1 cuốn sổ tay ~ 300g
            const books = (g / 300).toFixed(1);
            book.textContent = `~${books} cuốn`;

            if (g < 1300) {
                mobilityTag.textContent = 'Siêu Nhẹ';
                mobilityIcon.textContent = '🪶';
            } else if (g <= 1800) {
                mobilityTag.textContent = 'Tiêu Chuẩn';
                mobilityIcon.textContent = '💼';
            } else {
                mobilityTag.textContent = 'Đầm Tay';
                mobilityIcon.textContent = '🏋️';
            }
        }

        slider.addEventListener('input', e => updateWeight(e.target.value));
        updateWeight(widget.dataset.initialGrams || 1240);
    });
})();
</script>
