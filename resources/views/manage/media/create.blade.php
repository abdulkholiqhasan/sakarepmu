<x-layouts.app :title="__('Upload Media')">
    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold">Upload Media</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">Drag & drop a file here or click to choose. Images show a preview.</p>
            </div>
            <a href="{{ route('media.index') }}" class="btn btn-outline">Back to list</a>
        </div>

        <div class="bg-white dark:bg-zinc-900 shadow-sm rounded p-6 border border-zinc-200 dark:border-zinc-700">
            <form id="upload-form" method="POST" action="{{ route('media.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="sr-only">File</label>
                    <div id="dropzone" class="mt-1 border-2 border-dashed border-zinc-200 dark:border-zinc-700 rounded-lg p-6 text-center cursor-pointer bg-white dark:bg-zinc-900">
                        <input id="file-input" type="file" name="file" class="hidden" />
                        <div id="drop-content" class="flex flex-col items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-zinc-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9.414A2 2 0 0016.586 8L12 3.414A2 2 0 0010.586 3H4zM9 7a1 1 0 112 0v3.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 011.414-1.414L9 10.586V7z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-sm text-zinc-600 dark:text-zinc-300">Drop a file here or <button type="button" id="browse-btn" class="underline">browse</button></div>
                            <div id="drop-hint" class="text-xs text-zinc-500 dark:text-zinc-400">Max 10MB</div>
                        </div>
                    </div>
                    @error('file') <div class="text-red-600 text-sm mt-2">{{ $message }}</div> @enderror
                </div>

                <div id="preview-area" class="hidden mt-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4">
                    <div class="flex items-start gap-4">
                        <div id="thumb" class="w-28 h-28 bg-zinc-100 dark:bg-zinc-800 rounded overflow-hidden flex items-center justify-center text-zinc-500"></div>
                        <div class="flex-1">
                            <div id="file-name" class="font-medium text-zinc-900 dark:text-zinc-50"></div>
                            <div id="file-meta" class="text-sm text-zinc-500 dark:text-zinc-400"></div>
                            <div class="mt-3">
                                <button id="remove-btn" type="button" class="btn">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button id="upload-btn" class="btn-primary" disabled>Upload</button>
                    <a href="{{ route('media.index') }}" class="btn">Cancel</a>
                </div>
            </form>
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
