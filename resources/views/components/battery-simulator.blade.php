@props(['mah' => 5000, 'wh' => 70])

<!-- BATTERY LIFE SIMULATOR WIDGET (THÀNH VIÊN 3) -->
<div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs battery-simulator-widget" data-mah="{{ $mah }}" data-wh="{{ $wh }}">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shadow-xs border border-emerald-200/60">
                <i class="fa-solid fa-battery-three-quarters"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Mô Phỏng Thời Lượng Pin Thực Tế</h4>
                <p class="text-[11px] text-slate-400">Ước tính giờ dùng theo nhu cầu sử dụng</p>
            </div>
        </div>
        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
            {{ $wh > 0 ? $wh . ' Wh' : number_format($mah, 0, ',', '.') . ' mAh' }}
        </span>
    </div>

    <!-- Active Task Tabs -->
    <div class="flex p-1 bg-slate-100 rounded-xl mb-4 text-xs font-semibold">
        <button type="button" class="flex-1 py-1.5 rounded-lg task-btn active bg-white text-emerald-700 shadow-xs transition-all" data-mode="office">
            📄 Văn Phòng
        </button>
        <button type="button" class="flex-1 py-1.5 rounded-lg task-btn text-slate-600 hover:text-slate-900 transition-all" data-mode="video">
            🎬 Xem Phim
        </button>
        <button type="button" class="flex-1 py-1.5 rounded-lg task-btn text-slate-600 hover:text-slate-900 transition-all" data-mode="gaming">
            🎮 Gaming / Đồ Họa
        </button>
    </div>

    <!-- Estimated Result Card -->
    <div class="p-4 rounded-xl bg-gradient-to-br from-emerald-500/10 via-teal-500/5 to-transparent border border-emerald-500/20 mb-3 flex items-center justify-between">
        <div>
            <div class="text-[11px] text-slate-500 font-medium">Thời gian Onscreen dự kiến:</div>
            <div class="text-2xl font-black text-slate-800 mt-0.5">
                <span id="estimated-hours">11.5</span> <span class="text-xs font-bold text-slate-500">giờ</span>
            </div>
        </div>
        <div class="text-right">
            <span id="battery-status-badge" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                Thoải mái cả ngày
            </span>
            <div id="task-desc" class="text-[10px] text-slate-400 mt-1">Soạn thảo Word, lướt 10 tab web</div>
        </div>
    </div>

    <!-- Interactive Brightness Slider -->
    <div class="pt-2 border-t border-slate-100">
        <div class="flex justify-between text-[11px] text-slate-500 mb-1 font-medium">
            <span>Độ sáng màn hình:</span>
            <span id="brightness-label" class="font-bold text-slate-700">70%</span>
        </div>
        <input 
            type="range" 
            min="30" 
            max="100" 
            value="70" 
            step="5"
            class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-600 brightness-slider"
        >
    </div>
</div>

<script>
(function() {
    document.querySelectorAll('.battery-simulator-widget').forEach(widget => {
        const wh = parseFloat(widget.dataset.wh) || (parseFloat(widget.dataset.mah) * 3.85 / 1000) || 60;
        const hoursEl = widget.querySelector('#estimated-hours');
        const badgeEl = widget.querySelector('#battery-status-badge');
        const descEl = widget.querySelector('#task-desc');
        const brightnessSlider = widget.querySelector('.brightness-slider');
        const brightnessLabel = widget.querySelector('#brightness-label');
        const taskBtns = widget.querySelectorAll('.task-btn');

        let currentMode = 'office';
        let currentBrightness = 70;

        const profiles = {
            office: {
                basePowerWatts: 5.5,
                desc: 'Soạn thảo Word, lướt 10 tab web, gõ văn bản',
                badgeText: 'Thoải mái cả ngày',
                badgeClass: 'bg-emerald-100 text-emerald-700 border-emerald-200'
            },
            video: {
                basePowerWatts: 7.8,
                desc: 'Xem Netflix / YouTube 1080p, loa 50%',
                badgeText: 'Xem liên tục nhiều tập',
                badgeClass: 'bg-blue-100 text-blue-700 border-blue-200'
            },
            gaming: {
                basePowerWatts: 38.0,
                desc: 'Chơi game 3D, quạt chạy công suất cao',
                badgeText: 'Nên cắm sạc kèm theo',
                badgeClass: 'bg-amber-100 text-amber-700 border-amber-200'
            }
        };

        function calculateHours() {
            const profile = profiles[currentMode];
            // Độ sáng tăng thêm điện năng từ 0 - 2.5W
            const brightnessPenalty = ((currentBrightness - 50) / 50) * 1.8;
            const totalPower = Math.max(3, profile.basePowerWatts + brightnessPenalty);
            
            const hours = (wh / totalPower).toFixed(1);
            hoursEl.textContent = hours;
            descEl.textContent = profile.desc;
            badgeEl.textContent = profile.badgeText;
            badgeEl.className = `px-2 py-0.5 rounded-full text-[10px] font-bold border ${profile.badgeClass}`;
        }

        taskBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                taskBtns.forEach(b => {
                    b.classList.remove('bg-white', 'text-emerald-700', 'shadow-xs');
                    b.classList.add('text-slate-600');
                });
                this.classList.add('bg-white', 'text-emerald-700', 'shadow-xs');
                this.classList.remove('text-slate-600');

                currentMode = this.dataset.mode;
                calculateHours();
            });
        });

        brightnessSlider.addEventListener('input', function() {
            currentBrightness = parseInt(this.value, 10);
            brightnessLabel.textContent = currentBrightness + '%';
            calculateHours();
        });

        calculateHours();
    });
})();
</script>
