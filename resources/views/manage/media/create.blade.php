<?php $title = __('Upload Media'); ?>
<x-layouts.app :title="$title ?? null">
    <div class="bg-white dark:bg-zinc-900 min-h-screen">
        <!-- Admin-style header -->
        <div class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-700 px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <h1 class="text-xl sm:text-2xl font-normal text-gray-900 dark:text-white">Upload Media</h1>
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
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-2">File Upload</h2>
                        <p class="text-sm text-gray-500 dark:text-zinc-400">Drag & drop a file here or click to choose. Images show a preview.</p>
                    </div>
                    <form id="upload-form" method="POST" action="{{ route('media.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label class="sr-only">File</label>
                            <div id="dropzone" class="mt-1 border-2 border-dashed border-gray-300 dark:border-zinc-600 rounded-lg p-8 text-center cursor-pointer bg-gray-50 dark:bg-zinc-700/30 hover:bg-gray-100 dark:hover:bg-zinc-700/50 transition-colors">
                                <input id="file-input" type="file" name="file" class="hidden" />
                                <div id="drop-content" class="flex flex-col items-center justify-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-zinc-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9.414A2 2 0 0016.586 8L12 3.414A2 2 0 0010.586 3H4zM9 7a1 1 0 112 0v3.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 011.414-1.414L9 10.586V7z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="text-sm text-gray-600 dark:text-zinc-300">
                                        <span>Drop a file here or </span>
                                        <button type="button" id="browse-btn" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">browse</button>
                                    </div>
                                    <div id="drop-hint" class="text-xs text-gray-500 dark:text-zinc-400">Maximum file size: 10MB</div>
                                </div>
                            </div>
                            @error('file') 
                                <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div> 
                            @enderror
                        </div>

                        <div id="preview-area" class="hidden bg-gray-50 dark:bg-zinc-700/30 border border-gray-200 dark:border-zinc-600 rounded-lg p-6">
                            <div class="flex items-start gap-6">
                                <div id="thumb" class="w-32 h-32 bg-gray-100 dark:bg-zinc-700 rounded-lg overflow-hidden flex items-center justify-center text-gray-500 dark:text-zinc-400 flex-shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <div id="file-name" class="font-medium text-gray-900 dark:text-white text-lg mb-2"></div>
                                    <div id="file-meta" class="text-sm text-gray-500 dark:text-zinc-400 space-y-1"></div>
                                    <div class="mt-4">
                                        <button id="remove-btn" type="button" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 rounded-md transition-colors">
                                            Remove
                                        </button>
                                    </div>
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
                                id="upload-btn" 
                                type="submit"
                                disabled
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium rounded-md text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Upload Media
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const dropzone = document.getElementById('dropzone');
            const input = document.getElementById('file-input');
            const browseBtn = document.getElementById('browse-btn');
            const previewArea = document.getElementById('preview-area');
            const thumb = document.getElementById('thumb');
            const fileNameEl = document.getElementById('file-name');
            const fileMeta = document.getElementById('file-meta');
            const removeBtn = document.getElementById('remove-btn');
            const uploadBtn = document.getElementById('upload-btn');

            const MAX_BYTES = 10 * 1024 * 1024; // 10MB

            function prevent(e){ e.preventDefault(); e.stopPropagation(); }

            ['dragenter','dragover','dragleave','drop'].forEach(evt => {
                dropzone.addEventListener(evt, prevent);
            });

            dropzone.addEventListener('dragover', () => dropzone.classList.add('ring-2','ring-offset-2','ring-sky-400'));
            dropzone.addEventListener('dragleave', () => dropzone.classList.remove('ring-2','ring-offset-2','ring-sky-400'));

            dropzone.addEventListener('drop', (e) => {
                dropzone.classList.remove('ring-2','ring-offset-2','ring-sky-400');
                const f = e.dataTransfer.files[0];
                if (f) setFile(f);
            });

            browseBtn.addEventListener('click', () => input.click());
            dropzone.addEventListener('click', () => input.click());
            input.addEventListener('change', (e) => setFile(e.target.files[0]));

            removeBtn.addEventListener('click', () => clearFile());

            function setFile(f){
                if (!f) return;
                if (f.size > MAX_BYTES) { alert('File too large (max 10MB).'); input.value = ''; return; }

                // show preview for images
                if (f.type.startsWith('image/')){
                    const reader = new FileReader();
                    reader.onload = function(evt){
                        thumb.innerHTML = '<img src="'+evt.target.result+'" alt="preview" class="w-full h-full object-cover" />';
                    };
                    reader.readAsDataURL(f);
                } else {
                    thumb.textContent = f.name.split('.').pop().toUpperCase();
                }

                fileNameEl.textContent = f.name;
                fileMeta.textContent = f.type + ' • ' + f.size.toLocaleString() + ' bytes';
                previewArea.classList.remove('hidden');
                uploadBtn.disabled = false;
            }

            function clearFile(){
                input.value = '';
                previewArea.classList.add('hidden');
                thumb.innerHTML = '';
                fileNameEl.textContent = '';
                fileMeta.textContent = '';
                uploadBtn.disabled = true;
            }
        })();
    </script>
</x-layouts.app>
