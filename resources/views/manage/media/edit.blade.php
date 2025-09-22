<?php $title = __('Edit Media'); ?>
<x-layouts.app :title="$title ?? null">
    <div class="bg-white dark:bg-zinc-900 min-h-screen">
        <!-- Admin-style header -->
        <div class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-700 px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <h1 class="text-xl sm:text-2xl font-normal text-gray-900 dark:text-white">Edit Media</h1>
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
            @if($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 rounded-r-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">There were errors with your submission:</h3>
                            <div class="mt-2">
                                <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-zinc-800 shadow-sm rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden">
                <div class="px-6 py-6">
                    <div class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Media Details</h2>
                        <p class="text-sm text-gray-500 dark:text-zinc-400">Update file metadata and properties.</p>
                    </div>

                    <form method="POST" action="{{ route('media.update', $media) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- File Preview -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Preview</h3>
                                <div class="w-full h-64 bg-gray-100 dark:bg-zinc-700 rounded-lg overflow-hidden flex items-center justify-center">
                                    @if(str_starts_with($media->mime_type, 'image/'))
                                        <img src="{{ Storage::url($media->path) }}" alt="{{ $media->filename }}" class="w-full h-full object-contain" />
                                    @else
                                        <div class="text-center">
                                            <div class="text-4xl font-bold text-gray-400 dark:text-zinc-500 mb-2">
                                                {{ strtoupper(pathinfo($media->filename, PATHINFO_EXTENSION)) }}
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-zinc-400">{{ $media->mime_type ?? 'Unknown type' }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-3 text-sm text-gray-500 dark:text-zinc-400">
                                    <p><strong>Size:</strong> {{ number_format($media->size / 1024, 1) }} KB</p>
                                    <p><strong>Type:</strong> {{ $media->mime_type ?? 'Unknown' }}</p>
                                </div>
                            </div>

                            <!-- Edit Form -->
                            <div class="space-y-4">
                                <div>
                                    <label for="filename" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Filename</label>
                                    <input 
                                        type="text" 
                                        id="filename"
                                        name="filename" 
                                        value="{{ old('filename', $media->filename) }}" 
                                        class="w-full border-gray-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-zinc-700 text-gray-900 dark:text-white" 
                                        required 
                                    />
                                    @error('filename') 
                                        <div class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</div> 
                                    @enderror
                                </div>

                                <div>
                                    <label for="alt_text" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Alt Text</label>
                                    <input 
                                        type="text" 
                                        id="alt_text"
                                        name="alt_text" 
                                        value="{{ old('alt_text', $media->alt_text) }}" 
                                        class="w-full border-gray-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-zinc-700 text-gray-900 dark:text-white" 
                                        placeholder="Describe this image..."
                                    />
                                    @error('alt_text') 
                                        <div class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</div> 
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-zinc-400">Alternative text for screen readers and accessibility.</p>
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Description</label>
                                    <textarea 
                                        id="description"
                                        name="description" 
                                        rows="3"
                                        class="w-full border-gray-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-zinc-700 text-gray-900 dark:text-white" 
                                        placeholder="Optional description..."
                                    >{{ old('description', $media->description) }}</textarea>
                                    @error('description') 
                                        <div class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</div> 
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-zinc-700">
                            <button 
                                onclick="window.location.href='{{ route('media.index') }}'"
                                type="button"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-zinc-300 dark:hover:text-zinc-100 bg-white hover:bg-gray-50 dark:bg-zinc-700 dark:hover:bg-zinc-600 border border-gray-300 dark:border-zinc-600 rounded-md transition-colors"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium rounded-md text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
