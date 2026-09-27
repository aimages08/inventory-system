@if ($paginator->hasPages())
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="text-sm text-slate-500">
            Showing <strong>{{ $paginator->firstItem() }}</strong>
            to <strong>{{ $paginator->lastItem() }}</strong>
            of <strong>{{ $paginator->total() }}</strong> results
        </div>

        <div class="flex items-center gap-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 text-sm bg-slate-100 text-slate-400 rounded-lg cursor-not-allowed">
                    <i class="bi bi-chevron-left"></i> Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-3 py-1.5 text-sm bg-white hover:bg-slate-50 text-slate-700 rounded-lg border border-slate-200">
                    <i class="bi bi-chevron-left"></i> Prev
                </a>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-1.5 text-sm text-slate-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3 py-1.5 text-sm bg-white hover:bg-slate-50 text-slate-700 rounded-lg border border-slate-200">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-3 py-1.5 text-sm bg-white hover:bg-slate-50 text-slate-700 rounded-lg border border-slate-200">
                    Next <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span class="px-3 py-1.5 text-sm bg-slate-100 text-slate-400 rounded-lg cursor-not-allowed">
                    Next <i class="bi bi-chevron-right"></i>
                </span>
            @endif
        </div>
    </div>
@endif