@section('title', __('Settings') . ' / ' . __('Themes'))
<x-layouts.app :title="__('Settings') . ' / ' . __('Themes')">
    <div class="bg-white dark:bg-zinc-900 min-h-screen">
        <!-- Admin-style header -->
        <div class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-700 px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('Themes') }}</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">{{ __('Manage installed themes and activate one for the site') }}</p>
                </div>
                <div>
                    @permission('manage settings')
                        <!-- Placeholder for potential upload/install action -->
                        <button type="button" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors" disabled>
                            {{ __('Install Theme') }}
                        </button>
                    @endpermission
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @if(session('status'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 rounded-r-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 rounded-r-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-2-8a2 2 0 114 0 2 2 0 01-4 0zM9 7a1 1 0 012 0v4a1 1 0 11-2 0V7z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-zinc-800 shadow-sm rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden">
                @if(empty($themes))
                    <div class="text-center py-12">
                        <div class="mx-auto h-12 w-12 text-gray-400 dark:text-zinc-500 mb-4">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m8.66-12.66l-.707.707M4.05 19.95l-.707.707M21 12h-1M4 12H3m16.95 7.95l-.707-.707M6.757 6.757l-.707-.707M12 7a5 5 0 100 10 5 5 0 000-10z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No themes installed</h3>
                        <p class="text-gray-500 dark:text-zinc-400 mb-6 max-w-sm mx-auto">Install themes into <code class="bg-gray-100 dark:bg-zinc-700 px-1 rounded">resources/themes/&lt;theme&gt;</code> to have them appear here.</p>
                    </div>
                @else
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($themes as $theme)
                                <div class="bg-white dark:bg-zinc-900 rounded-lg overflow-hidden border border-gray-200 dark:border-zinc-700 shadow-sm">
                                    @if($theme['screenshot'])
                                        <img src="{{ $theme['screenshot'] }}" alt="{{ $theme['name'] }} screenshot" class="w-full h-40 object-cover" />
                                    @else
                                        <div class="w-full h-40 bg-gray-100 dark:bg-zinc-700 flex items-center justify-center text-gray-500">No preview</div>
                                    @endif
                                    <div class="p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="pr-4">
                                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $theme['name'] }}</h3>
                                                @if($theme['description'])
                                                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">{{ $theme['description'] }}</p>
                                                @endif

                                                {{-- Theme metadata: author, version and url (neatly) --}}
                                                @php
                                                    $hasMeta = !empty($theme['author']) || !empty($theme['url']) || !empty($theme['version']);
                                                @endphp
                                                @if($hasMeta)
                                                    <div class="mt-2 text-xs text-gray-500 dark:text-zinc-400">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center space-x-3">
                                                                @if(!empty($theme['version']))
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 dark:bg-zinc-700 text-gray-700 dark:text-zinc-200 font-medium">v{{ $theme['version'] }}</span>
                                                                @endif

                                                                @if(!empty($theme['author']))
                                                                    @php
                                                                        $authorUrl = $theme['author_url'] ?? null;
                                                                    @endphp
                                                                    <div class="flex flex-col">
                                                                        <div class="flex items-center space-x-2">
                                                                            <span class="text-gray-700 dark:text-zinc-200 font-medium">Author:</span>
                                                                            @if(!empty($authorUrl))
                                                                                <a href="{{ $authorUrl }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $theme['author'] }}</a>
                                                                            @else
                                                                                <span>{{ $theme['author'] }}</span>
                                                                            @endif
                                                                        </div>

                                                                        @if(!empty($authorUrl))
                                                                            @php
                                                                                try {
                                                                                    $parsedAuthor = parse_url($authorUrl);
                                                                                    $authorHost = $parsedAuthor['host'] ?? $authorUrl;
                                                                                } catch (\Exception $e) {
                                                                                    $authorHost = $authorUrl;
                                                                                }
                                                                            @endphp
                                                                           
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            {{-- Only show theme.url on the right if author_url is not available --}}
                                                            @if(empty($theme['author_url']) && !empty($theme['url']))
                                                                @php
                                                                    try {
                                                                        $parsed = parse_url($theme['url']);
                                                                        $host = $parsed['host'] ?? $theme['url'];
                                                                    } catch (\Exception $e) {
                                                                        $host = $theme['url'];
                                                                    }
                                                                @endphp
                                                                <div class="flex items-center space-x-2">
                                                                    <a href="{{ $theme['url'] }}" target="_blank" rel="noopener noreferrer" class="text-xs text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M12.293 2.293a1 1 0 011.414 0l4 4A1 1 0 0117.707 8.707L16 7.414V13a1 1 0 11-2 0V6.586L12.293 5.293a1 1 0 010-1.414z"/><path d="M3 5a2 2 0 012-2h5a1 1 0 110 2H5v10h10v-5a1 1 0 112 0v5a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"/></svg>
                                                                        {{ $host }}
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                @permission('manage settings')
                                                    <form method="POST" action="{{ route('appearance.themes.activate') }}">
                                                        @csrf
                                                        <input type="hidden" name="theme" value="{{ $theme['dir'] }}">
                                                        <button type="submit" class="px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">Activate</button>
                                                    </form>
                                                @else
                                                    <button type="button" class="px-3 py-2 rounded bg-blue-600 text-white text-sm opacity-60 cursor-not-allowed" disabled>Activate</button>
                                                @endpermission

                                                @if($active === $theme['dir'])
                                                    <span class="text-sm text-green-600">Active</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
