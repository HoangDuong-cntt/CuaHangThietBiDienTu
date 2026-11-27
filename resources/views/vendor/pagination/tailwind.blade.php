@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4">
        {{-- Results Counter --}}
        <div class="text-xs text-slate-500 font-medium order-2 sm:order-1">
            Hiển thị
            @if ($paginator->firstItem())
                <span class="font-bold text-slate-900">{{ $paginator->firstItem() }}</span>
                đến
                <span class="font-bold text-slate-900">{{ $paginator->lastItem() }}</span>
            @else
                {{ $paginator->count() }}
            @endif
            trong tổng số
            <span class="font-bold text-slate-900">{{ $paginator->total() }}</span>
            kết quả
        </div>

        {{-- Pagination Buttons --}}
        <div class="inline-flex items-center gap-1.5 order-1 sm:order-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center w-9 h-9 text-xs text-slate-300 bg-white border border-slate-200/80 rounded-xl cursor-not-allowed select-none shadow-2xs" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <i class="fa-solid fa-chevron-left text-[11px]"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-9 h-9 text-xs font-semibold text-slate-700 bg-white border border-slate-200/80 rounded-xl hover:bg-slate-50 hover:text-blue-600 hover:border-slate-300 transition-all shadow-2xs active:scale-95" aria-label="{{ __('pagination.previous') }}">
                    <i class="fa-solid fa-chevron-left text-[11px]"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="inline-flex items-center justify-center w-9 h-9 text-xs text-slate-400 select-none">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex items-center justify-center w-9 h-9 text-xs font-bold text-white bg-blue-600 border border-blue-600 rounded-xl shadow-xs select-none" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 text-xs font-semibold text-slate-700 bg-white border border-slate-200/80 rounded-xl hover:bg-slate-50 hover:text-blue-600 hover:border-slate-300 transition-all shadow-2xs active:scale-95" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-9 h-9 text-xs font-semibold text-slate-700 bg-white border border-slate-200/80 rounded-xl hover:bg-slate-50 hover:text-blue-600 hover:border-slate-300 transition-all shadow-2xs active:scale-95" aria-label="{{ __('pagination.next') }}">
                    <i class="fa-solid fa-chevron-right text-[11px]"></i>
                </a>
            @else
                <span class="inline-flex items-center justify-center w-9 h-9 text-xs text-slate-300 bg-white border border-slate-200/80 rounded-xl cursor-not-allowed select-none shadow-2xs" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <i class="fa-solid fa-chevron-right text-[11px]"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
