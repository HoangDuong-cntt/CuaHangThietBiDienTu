@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between gap-3 py-3">
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-medium text-slate-300 bg-white border border-slate-200/80 rounded-xl cursor-not-allowed select-none shadow-2xs">
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                <span>Trang trước</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200/80 rounded-xl hover:bg-slate-50 hover:text-blue-600 hover:border-slate-300 transition-all shadow-2xs active:scale-95">
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                <span>Trang trước</span>
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200/80 rounded-xl hover:bg-slate-50 hover:text-blue-600 hover:border-slate-300 transition-all shadow-2xs active:scale-95">
                <span>Trang sau</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
        @else
            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-medium text-slate-300 bg-white border border-slate-200/80 rounded-xl cursor-not-allowed select-none shadow-2xs">
                <span>Trang sau</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </span>
        @endif
    </nav>
@endif
