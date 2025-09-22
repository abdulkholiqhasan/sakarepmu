@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between items-center flex-1 sm:hidden gap-3">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center justify-center min-w-[44px] min-h-[44px] px-3 py-3 text-sm font-medium text-zinc-500 bg-white border border-zinc-300 cursor-default leading-5 rounded-lg dark:text-zinc-600 dark:bg-zinc-800 dark:border-zinc-600 touch-manipulation">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center justify-center min-w-[44px] min-h-[44px] px-3 py-3 text-sm font-medium text-zinc-700 bg-white border border-zinc-300 leading-5 rounded-lg hover:text-zinc-500 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300 active:bg-zinc-100 active:text-zinc-700 transition ease-in-out duration-150 dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-700 dark:focus:border-blue-700 dark:active:bg-zinc-700 dark:active:text-zinc-300 touch-manipulation">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            <div class="flex items-center gap-1 text-xs text-zinc-500 dark:text-zinc-400">
                <span>{{ $paginator->currentPage() }}</span>
                <span>/</span>
                <span>{{ $paginator->lastPage() }}</span>
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center justify-center min-w-[44px] min-h-[44px] px-3 py-3 text-sm font-medium text-zinc-700 bg-white border border-zinc-300 leading-5 rounded-lg hover:text-zinc-500 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300 active:bg-zinc-100 active:text-zinc-700 transition ease-in-out duration-150 dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-700 dark:focus:border-blue-700 dark:active:bg-zinc-700 dark:active:text-zinc-300 touch-manipulation">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span class="relative inline-flex items-center justify-center min-w-[44px] min-h-[44px] px-3 py-3 text-sm font-medium text-zinc-500 bg-white border border-zinc-300 cursor-default leading-5 rounded-lg dark:text-zinc-600 dark:bg-zinc-800 dark:border-zinc-600 touch-manipulation">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            {{-- <div>
                <p class="text-sm text-zinc-700 leading-5 dark:text-zinc-400">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div> --}}

            <div>
                <span class="relative z-0 inline-flex rtl:flex-row-reverse shadow-sm rounded-lg overflow-hidden">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center justify-center min-w-[40px] min-h-[40px] px-3 py-2 text-sm font-medium text-zinc-500 bg-white border border-zinc-300 cursor-default leading-5 dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-400 touch-manipulation" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center justify-center min-w-[40px] min-h-[40px] px-3 py-2 text-sm font-medium text-zinc-500 bg-white border border-zinc-300 leading-5 hover:text-zinc-400 hover:bg-zinc-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300 active:bg-zinc-100 active:text-zinc-500 transition ease-in-out duration-150 dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-400 dark:hover:bg-zinc-700 dark:active:bg-zinc-700 dark:focus:border-blue-800 touch-manipulation" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center justify-center min-w-[40px] min-h-[40px] px-4 py-2 -ml-px text-sm font-medium text-zinc-700 bg-white border border-zinc-300 cursor-default leading-5 dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-400 touch-manipulation">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center justify-center min-w-[40px] min-h-[40px] px-4 py-2 -ml-px text-sm font-medium text-white bg-blue-600 border border-blue-600 cursor-default leading-5 dark:bg-blue-600 dark:border-blue-600 touch-manipulation">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center justify-center min-w-[40px] min-h-[40px] px-4 py-2 -ml-px text-sm font-medium text-zinc-700 bg-white border border-zinc-300 leading-5 hover:text-zinc-500 hover:bg-zinc-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300 active:bg-zinc-100 active:text-zinc-700 transition ease-in-out duration-150 dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-300 dark:hover:text-zinc-200 dark:hover:bg-zinc-700 dark:active:bg-zinc-700 dark:focus:border-blue-800 touch-manipulation" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center justify-center min-w-[40px] min-h-[40px] px-3 py-2 -ml-px text-sm font-medium text-zinc-500 bg-white border border-zinc-300 leading-5 hover:text-zinc-400 hover:bg-zinc-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300 active:bg-zinc-100 active:text-zinc-500 transition ease-in-out duration-150 dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-400 dark:hover:bg-zinc-700 dark:active:bg-zinc-700 dark:focus:border-blue-800 touch-manipulation" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center justify-center min-w-[40px] min-h-[40px] px-3 py-2 -ml-px text-sm font-medium text-zinc-500 bg-white border border-zinc-300 cursor-default leading-5 dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-400 touch-manipulation" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
