@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{!! __('Pagination Navigation') !!}" class="flex justify-between items-center gap-3">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center justify-center min-w-[44px] min-h-[44px] px-3 py-3 text-sm font-medium text-zinc-500 bg-white border border-zinc-300 cursor-default leading-5 rounded-lg dark:text-zinc-600 dark:bg-zinc-800 dark:border-zinc-600 touch-manipulation">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="hidden sm:inline text-sm ml-2">{!! __('pagination.previous') !!}</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center justify-center min-w-[44px] min-h-[44px] px-3 py-3 text-sm font-medium text-zinc-700 bg-white border border-zinc-300 leading-5 rounded-lg hover:text-zinc-500 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300 active:bg-zinc-100 active:text-zinc-700 transition ease-in-out duration-150 dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-700 dark:focus:border-blue-700 dark:active:bg-zinc-700 dark:active:text-zinc-300 touch-manipulation">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="hidden sm:inline text-sm ml-2">{!! __('pagination.previous') !!}</span>
            </a>
        @endif

        <div class="flex items-center gap-1 text-xs text-zinc-500 dark:text-zinc-400">
            <span>{{ $paginator->currentPage() }}</span>
            <span>/</span>
            <span>{{ $paginator->lastPage() }}</span>
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center justify-center min-w-[44px] min-h-[44px] px-3 py-3 text-sm font-medium text-zinc-700 bg-white border border-zinc-300 leading-5 rounded-lg hover:text-zinc-500 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300 active:bg-zinc-100 active:text-zinc-700 transition ease-in-out duration-150 dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-700 dark:focus:border-blue-700 dark:active:bg-zinc-700 dark:active:text-zinc-300 touch-manipulation">
                <span class="hidden sm:inline text-sm mr-2">{!! __('pagination.next') !!}</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        @else
            <span class="relative inline-flex items-center justify-center min-w-[44px] min-h-[44px] px-3 py-3 text-sm font-medium text-zinc-500 bg-white border border-zinc-300 cursor-default leading-5 rounded-lg dark:text-zinc-600 dark:bg-zinc-800 dark:border-zinc-600 touch-manipulation">
                <span class="hidden sm:inline text-sm mr-2">{!! __('pagination.next') !!}</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </span>
        @endif
    </nav>
@endif
