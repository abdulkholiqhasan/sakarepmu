<?php $title = $media->filename; ?>
<x-layouts.app :title="$title ?? null">
    <div class="bg-white dark:bg-zinc-900 min-h-screen">
        <!-- Admin-style header -->
        <div class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-700 px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <h1 class="text-xl sm:text-2xl font-normal text-gray-900 dark:text-white">Media Details</h1>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <button 
                        onclick="window.location.href='{{ route('media.index') }}'"
                        class="inline-flex items-center px-3 py-1.5 text-xs sm:text-sm font-medium text-gray-600 hover:text-gray-800 dark:text-zinc-400 dark:hover:text-zinc-300 bg-gray-50 hover:bg-gray-100 dark:bg-zinc-700 dark:hover:bg-zinc-600 rounded-md transition-colors"
                    >
                        <span class="hidden sm:inline">← Back to Media</span>
                        <span class="sm:hidden">← Back</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="bg-white dark:bg-zinc-800 shadow-sm rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden">
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Media Preview -->
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Preview</h2>
                            <div class="w-full bg-gray-100 dark:bg-zinc-700 rounded-lg overflow-hidden flex items-center justify-center" style="min-height: 400px;">
                                @if(str_starts_with($media->mime_type, 'image/'))
                                    <img src="{{ Storage::url($media->path) }}" alt="{{ $media->filename }}" class="w-full h-full object-contain max-h-96" />
                                @else
                                    <div class="text-center p-8">
                                        <div class="text-6xl font-bold text-gray-400 dark:text-zinc-500 mb-4">
                                            {{ strtoupper(pathinfo($media->filename, PATHINFO_EXTENSION)) }}
                                        </div>
                                        <p class="text-lg text-gray-500 dark:text-zinc-400">{{ $media->mime_type ?? 'Unknown type' }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Media Information -->
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">File Information</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Filename</h3>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-zinc-400 break-all">{{ $media->filename }}</p>
                                </div>

                                @if($media->alt_text)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Alt Text</h3>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-zinc-400">{{ $media->alt_text }}</p>
                                </div>
                                @endif

                                @if($media->description)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Description</h3>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-zinc-400">{{ $media->description }}</p>
                                </div>
                                @endif

                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">File Type</h3>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-zinc-400">{{ $media->mime_type ?? 'Unknown' }}</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">File Size</h3>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-zinc-400">{{ number_format($media->size / 1024, 1) }} KB ({{ number_format($media->size ?? 0) }} bytes)</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Upload Date</h3>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-zinc-400">{{ $media->created_at->format('F d, Y \a\t g:i A') }}</p>
                                </div>

                                @if($media->updated_at != $media->created_at)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Last Modified</h3>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-zinc-400">{{ $media->updated_at->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                                @endif

                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">File URL</h3>
                                    <div class="mt-1">
                                        <input 
                                            type="text" 
                                            value="{{ Storage::url($media->path) }}" 
                                            readonly 
                                            class="w-full text-xs bg-gray-50 dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded px-3 py-2 text-gray-600 dark:text-zinc-400" 
                                            onclick="this.select()"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-zinc-700">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Actions</h3>
                                <div class="flex flex-wrap gap-3">
                                    <a 
                                        href="{{ Storage::url($media->path) }}" 
                                        target="_blank"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 rounded-md transition-colors"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download
                                    </a>
                                    <button 
                                        onclick="window.location.href='{{ route('media.edit', $media) }}'"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-800 dark:text-zinc-400 dark:hover:text-zinc-300 bg-gray-50 hover:bg-gray-100 dark:bg-zinc-700 dark:hover:bg-zinc-600 rounded-md transition-colors"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('media.destroy', $media) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this media file? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 rounded-md transition-colors"
                                        >
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
