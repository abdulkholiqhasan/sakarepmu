<?php $title = 'Media Library'; ?>
<x-layouts.app :title="$title ?? null">
    <div class="bg-white dark:bg-zinc-900 min-h-screen">
        <!-- Admin-style header -->
        <div class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-700 px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Media Library</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Manage uploaded files — upload, view, and delete media</p>
                </div>
                <div>
                    @permission('upload files')
                        <button 
                            onclick="window.location.href='{{ route('media.create') }}'"
                            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors"
                        >
                            Upload Media
                        </button>
                    @endpermission
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 rounded-r-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Search -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-1 text-sm">
                    <span class="px-3 py-1.5 rounded-md bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium">
                        All Media
                    </span>
                </div>

                <!-- Search -->
                <div class="w-full sm:w-80">
                    <form method="GET" action="{{ route('media.index') }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input 
                                name="q" 
                                placeholder="Search media..." 
                                type="text" 
                                value="{{ request('q') }}" 
                                class="pl-10 w-full border-gray-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200" 
                                autocomplete="off" 
                                enterkeyhint="search" 
                            />
                        </div>
                    </form>
                </div>
            </div>

            <!-- Media table -->
            <div class="bg-white dark:bg-zinc-800 shadow-sm rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden">
                @if($media->isEmpty())
                    <div class="text-center py-12">
                        <div class="mx-auto h-12 w-12 text-gray-400 dark:text-zinc-500 mb-4">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25M18.75 8.25l-7.5 7.5M3.75 12h16.5m-16.5 3.75h16.5" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No media found</h3>
                        <p class="text-gray-500 dark:text-zinc-400 mb-6 max-w-sm mx-auto">
                            @if(request('q'))
                                No media matches your search criteria. Try adjusting your search terms.
                            @else
                                Get started by uploading your first media file.
                            @endif
                        </p>
                        @if(!request('q'))
                            @permission('upload files')
                                <button 
                                    onclick="window.location.href='{{ route('media.create') }}'"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Upload your first file
                                </button>
                            @endpermission
                        @endif
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                            <thead class="bg-gray-50 dark:bg-zinc-700/50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-zinc-300 uppercase tracking-wider">
                                        File
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-zinc-300 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-zinc-300 uppercase tracking-wider">
                                        Size
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-zinc-300 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-zinc-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                                @foreach($media as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 dark:bg-zinc-700 flex-shrink-0">
                                                    @if(str_starts_with($item->mime_type, 'image/'))
                                                        <img src="{{ Storage::url($item->path) }}" alt="{{ $item->filename }}" class="w-full h-full object-cover" />
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-xs font-medium text-gray-500 dark:text-zinc-400">
                                                            {{ strtoupper(pathinfo($item->filename, PATHINFO_EXTENSION)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                        {{ $item->filename }}
                                                    </p>
                                                    @if($item->alt_text)
                                                        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1 truncate">
                                                            Alt: {{ $item->alt_text }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-zinc-700 text-gray-800 dark:text-zinc-300">
                                                {{ $item->mime_type ?? 'Unknown' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-zinc-400">
                                                {{ number_format($item->size / 1024, 1) }} KB
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-zinc-400">
                                                <p class="font-medium">{{ $item->created_at->format('M d, Y') }}</p>
                                                <p class="text-xs">{{ $item->created_at->format('g:i A') }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button 
                                                    onclick="window.location.href='{{ route('media.show', $item) }}'"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-800 dark:text-zinc-400 dark:hover:text-zinc-300 bg-gray-50 hover:bg-gray-100 dark:bg-zinc-700 dark:hover:bg-zinc-600 rounded-md transition-colors"
                                                >
                                                    View
                                                </button>
                                                @permission('upload files|edit media')
                                                    <button 
                                                        onclick="window.location.href='{{ route('media.edit', $item) }}'"
                                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 rounded-md transition-colors"
                                                    >
                                                        Edit
                                                    </button>
                                                @endpermission
                                                @permission('delete media')
                                                    <form action="{{ route('media.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this media file?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button 
                                                            type="submit" 
                                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 rounded-md transition-colors"
                                                        >
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endpermission
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced pagination -->
                    <div class="bg-gray-50 dark:bg-zinc-700/30 px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center text-sm text-gray-500 dark:text-zinc-400">
                                <span class="mr-2">Showing</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $media->firstItem() ?? 0 }}</span>
                                <span class="mx-1">to</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $media->lastItem() ?? 0 }}</span>
                                <span class="mx-1">of</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $media->total() }}</span>
                                <span class="ml-1">files</span>
                            </div>
                            <div class="flex items-center">
                                {{ $media->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
