<?php $title = __('Add Post'); ?>
<x-layouts.app :title="$title ?? null">
    <div class="p-6">
        <div class="mx-auto w-full max-w-6xl bg-white dark:bg-zinc-800 shadow rounded p-8">
            <h1 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Create Post</h1>

            @if($errors->any())
                <div class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-100 dark:border-red-800 text-red-700 dark:text-red-200 p-3 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('posts.store') }}" method="POST">
                @csrf
                <div class="mb-4 flex items-start justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Create a new post</p>
                    </div>
                    <div>
                        <flux:button :href="route('posts.index')" variant="ghost">Back to posts</flux:button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Main column -->
                    <div class="lg:col-span-3">
                        <div class="mb-4">
                            <input name="title" type="text" value="{{ old('title') }}" placeholder="Add title" class="w-full text-4xl font-extrabold px-4 py-4 rounded border bg-white dark:bg-zinc-900 dark:border-zinc-700" />
                            @error('title') <p class="text-red-600 dark:text-red-300 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center space-x-2">
                                <input name="slug" id="slug-input" type="text" value="{{ old('slug') }}" placeholder="slug (optional)" class="flex-1 px-3 py-2 rounded border bg-white dark:bg-zinc-900 dark:border-zinc-700" />
                                <button type="button" id="reset-slug" aria-label="Reset slug to title" class="px-2 py-1 rounded bg-gray-100 dark:bg-zinc-700 text-sm">Reset</button>
                            </div>
                            @error('slug') <p class="text-red-600 dark:text-red-300 mt-1">{{ $message }}</p> @enderror
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Auto-generated from title but editable.</p>
                        </div>

                        <div class="mb-4 bg-white dark:bg-zinc-900 rounded border p-4 dark:border-zinc-700">
                            @include('components.wysiwyg', ['name' => 'content', 'value' => old('content')])
                            @error('content') <p class="text-red-600 dark:text-red-300 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Right sidebar -->
                    <div class="space-y-4">
                        <div class="bg-white dark:bg-zinc-800 p-4 rounded border dark:border-zinc-700">
                            <h3 class="font-medium text-sm text-gray-700 dark:text-zinc-200 mb-2">Publish</h3>
                            <div class="text-sm text-gray-600 dark:text-zinc-400 mb-2">Status: <strong class="ml-2">{{ old('status', 'draft') }}</strong></div>
                            <div class="mb-2">
                                <flux:input name="published_at" type="datetime-local" value="{{ old('published_at') }}" />
                                @error('published_at') <p class="text-red-600 dark:text-red-300 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex space-x-2">
                                <flux:button type="submit" name="action" value="publish" variant="primary">Publish</flux:button>
                                <flux:button type="submit" name="action" value="draft" variant="ghost">Save as Draft</flux:button>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-zinc-800 p-4 rounded border dark:border-zinc-700">
                            <h3 class="font-medium text-sm text-gray-700 dark:text-zinc-200 mb-2">Categories</h3>
                                <div class="mt-2">
                                        <!-- Hidden native select to keep form compatibility -->
                                        <select id="categories-select" name="categories[]" multiple class="hidden"></select>

                                        <!-- Search input for categories (AJAX typeahead) -->
                                        <div class="relative">
                                            <input type="text" id="category-search" placeholder="Search categories..." class="w-full px-3 py-2 rounded-md border border-gray-200 bg-white dark:bg-zinc-900 dark:border-zinc-700" autocomplete="off" />
                                            <div id="category-suggestions" class="absolute left-0 right-0 mt-1 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded shadow max-h-48 overflow-auto hidden z-50"></div>
                                        </div>

                                        <!-- Selected chips -->
                                        <div id="selected-categories" class="mt-2 flex flex-wrap gap-2"></div>

                                        <script>
                                            (function(){
                                                const select = document.getElementById('categories-select');
                                                const container = document.getElementById('selected-categories');
                                                const search = document.getElementById('category-search');
                                                const suggestions = document.getElementById('category-suggestions');

                                                function renderSelected(){
                                                    container.innerHTML = '';
                                                    const selected = Array.from(select.selectedOptions).map(opt => ({id: opt.value, name: opt.text}));
                                                    selected.forEach(item => {
                                                        const span = document.createElement('span');
                                                        span.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300';
                                                        span.textContent = item.name;
                                                        // add remove button
                                                        const btn = document.createElement('button');
                                                        btn.type = 'button';
                                                        btn.className = 'ml-2 text-xs text-indigo-700 dark:text-indigo-300';
                                                        btn.textContent = '×';
                                                        btn.addEventListener('click', function(e){
                                                            e.preventDefault();
                                                            // unselect in native select
                                                            const opt = Array.from(select.options).find(o => o.value === item.id);
                                                            if(opt) opt.selected = false;
                                                            // update
                                                            renderSelected();
                                                        });
                                                        span.appendChild(btn);
                                                        container.appendChild(span);
                                                    });
                                                }

                                                async function fetchCategories(q){
                                                    const url = new URL('{{ route('categories.search') }}', window.location.origin);
                                                    if(q) url.searchParams.set('q', q);
                                                    const res = await fetch(url.toString());
                                                    if(!res.ok) return [];
                                                    return res.json();
                                                }

                                                let activeFetch = null;
                                                let lastQuery = '';

                                                function showSuggestions(items){
                                                    suggestions.innerHTML = '';
                                                    if(!items.length){ suggestions.classList.add('hidden'); return; }
                                                    suggestions.classList.remove('hidden');
                                                    items.forEach(it=>{
                                                        const el = document.createElement('div');
                                                        el.className = 'px-3 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 cursor-pointer flex justify-between items-center';
                                                        el.textContent = it.name;
                                                        el.dataset.id = it.id;
                                                        el.addEventListener('click', ()=>{
                                                            // ensure option exists in native select
                                                            let opt = Array.from(select.options).find(o=>o.value===String(it.id));
                                                            if(!opt){ opt = document.createElement('option'); opt.value = it.id; opt.text = it.name; opt.selected = true; select.appendChild(opt); }
                                                            opt.selected = true;
                                                            renderSelected();
                                                            suggestions.classList.add('hidden');
                                                            search.value = '';
                                                            lastQuery = '';
                                                        });
                                                        suggestions.appendChild(el);
                                                    });
                                                }

                                                let debounceTimer = null;
                                                search.addEventListener('input', function(e){
                                                    const q = e.target.value.trim();
                                                    if(q === lastQuery) return;
                                                    lastQuery = q;
                                                    clearTimeout(debounceTimer);
                                                    debounceTimer = setTimeout(async ()=>{
                                                        const items = await fetchCategories(q);
                                                        showSuggestions(items || []);
                                                    }, 200);
                                                });

                                                // close suggestions on outside click
                                                document.addEventListener('click', function(e){
                                                    if(!suggestions.classList.contains('hidden') && !suggestions.contains(e.target) && e.target !== search){
                                                        suggestions.classList.add('hidden');
                                                    }
                                                });

                                                // initialize display on load
                                                document.addEventListener('DOMContentLoaded', renderSelected);
                                                renderSelected();
                                            })();
                                        </script>
                                </div>
                        </div>

                        <div class="bg-white dark:bg-zinc-800 p-4 rounded border dark:border-zinc-700">
                            <h3 class="font-medium text-sm text-gray-700 dark:text-zinc-200 mb-2">Tags</h3>
                                <div class="mt-1">
                                    <!-- Hidden native select to keep form compatibility -->
                                    <select id="tags-select" name="tags[]" multiple class="hidden"></select>

                                    <!-- Tag search / typeahead -->
                                    <div class="relative">
                                        <input type="text" id="tag-search" placeholder="Search or add tags..." class="w-full px-3 py-2 rounded-md border border-gray-200 bg-white dark:bg-zinc-900 dark:border-zinc-700" autocomplete="off" />
                                        <div id="tag-suggestions" class="absolute left-0 right-0 mt-1 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded shadow max-h-48 overflow-auto hidden z-50"></div>
                                        <div class="mt-2">
                                            <flux:button type="button" id="tag-add-btn" class="relative z-10 w-full" variant="primary">Add</flux:button>
                                        </div>
                                    </div>

                                    <!-- Selected chips -->
                                    <div id="selected-tags" class="mt-2 flex flex-wrap gap-2"></div>
                                    <p class="text-xs text-gray-500 mt-1">Type to search tags. Press Enter to add new tag.</p>
                                </div>
                        </div>

                        <div class="bg-white dark:bg-zinc-800 p-4 rounded border dark:border-zinc-700">
                            <h3 class="font-medium text-sm text-gray-700 dark:text-zinc-200 mb-2">Featured Image</h3>
                            <div class="space-y-2">
                                <flux:input name="featured_image" type="text" value="{{ old('featured_image') }}" id="featured-image-url" />
                                <div class="flex space-x-2">
                                    <label class="cursor-pointer inline-flex items-center px-3 py-2 rounded bg-gray-100 dark:bg-zinc-700 text-sm">
                                        <input type="file" name="featured_image_file" id="featured-image-file" class="hidden" accept="image/*" />
                                        Browse local
                                    </label>
                                    <button type="button" id="open-media-library" class="px-3 py-2 rounded bg-white dark:bg-zinc-700 border">Browse media</button>
                                </div>
                                <div id="featured-preview" class="mt-2">
                                    @if(old('featured_image'))
                                        <img src="{{ old('featured_image') }}" alt="Featured" class="w-full object-cover rounded" />
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-zinc-800 p-4 rounded border dark:border-zinc-700">
                            <h3 class="font-medium text-sm text-gray-700 dark:text-zinc-200 mb-2">Excerpt</h3>
                            <flux:textarea name="excerpt">{{ old('excerpt') }}</flux:textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>

<script>
    // Auto-generate slug from title but allow manual edit
    (function(){
        const title = document.querySelector('input[name="title"]');
        const slug = document.querySelector('input[name="slug"]');
        if(!title || !slug) return;
        let manuallyEdited = false;

        function slugify(val){
            return val.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^a-z0-9\-]/g, '')    // Remove all non-alphanumeric chars except -
                .replace(/-+/g,'-')              // Replace multiple - with single -
                .replace(/^-+|-+$/g,'');         // Trim - from start/end
        }

        slug.addEventListener('input', ()=>{ manuallyEdited = true; });
        title.addEventListener('input', ()=>{
            if(manuallyEdited) return;
            slug.value = slugify(title.value);
        });

        // reset button
        const resetBtn = document.getElementById('reset-slug');
        if(resetBtn){
            resetBtn.addEventListener('click', ()=>{
                slug.value = slugify(title.value || '');
                manuallyEdited = false;
                slug.focus();
            });
        }

        // initialize slug on load if empty
        if(!slug.value){ slug.value = slugify(title.value || ''); }
    })();
</script>

<script>
    // Lightweight tag input + category search for Create Post
    (function(){
        const select = document.getElementById('tags-select');
        const container = document.getElementById('selected-tags');
        const search = document.getElementById('tag-search');
        const suggestions = document.getElementById('tag-suggestions');

        function renderSelected(){
            container.innerHTML = '';
            const selected = Array.from(select.selectedOptions).map(opt => ({id: opt.value, name: opt.text}));
            selected.forEach(item => {
                const span = document.createElement('span');
                span.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 dark:bg-zinc-700 text-gray-800 dark:text-gray-100';
                span.textContent = item.name;
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'ml-2 text-xs text-gray-600 dark:text-gray-300';
                btn.textContent = '×';
                btn.addEventListener('click', function(e){
                    e.preventDefault();
                    const opt = Array.from(select.options).find(o => o.value === item.id);
                    if(opt) opt.selected = false;
                    renderSelected();
                });
                span.appendChild(btn);
                container.appendChild(span);
            });
        }

        async function fetchTags(q){
            const url = new URL('{{ route('tags.search') }}', window.location.origin);
            if(q) url.searchParams.set('q', q);
            const res = await fetch(url.toString());
            if(!res.ok) return [];
            return res.json();
        }

        function showSuggestions(items){
            suggestions.innerHTML = '';
            if(!items.length){ suggestions.classList.add('hidden'); return; }
            suggestions.classList.remove('hidden');
            items.forEach(it=>{
                const el = document.createElement('div');
                el.className = 'px-3 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 cursor-pointer flex justify-between items-center';
                el.textContent = it.name;
                el.dataset.id = it.id;
                el.addEventListener('click', ()=>{
                    let opt = Array.from(select.options).find(o=>o.value===String(it.id));
                    if(!opt){ opt = document.createElement('option'); opt.value = it.id; opt.text = it.name; opt.selected = true; select.appendChild(opt); }
                    opt.selected = true;
                    renderSelected();
                    suggestions.classList.add('hidden');
                    search.value = '';
                });
                suggestions.appendChild(el);
            });
        }

        // reusable add-from-input function
        function addTagFromInput(){
            const name = search.value.trim();
            if(!name) return;
            const newId = 'new:'+name;
            let opt = Array.from(select.options).find(o=>o.value===newId);
            if(!opt){ opt = document.createElement('option'); opt.value = newId; opt.text = name; opt.selected = true; select.appendChild(opt); }
            opt.selected = true;
            renderSelected();
            search.value = '';
            suggestions.classList.add('hidden');
        }

        // allow enter to create new tag locally as placeholder (new:NAME)
        search.addEventListener('keydown', (e)=>{
            if(e.key === 'Enter'){
                e.preventDefault();
                addTagFromInput();
            }
        });

        // bind add button
        const addBtn = document.getElementById('tag-add-btn');
        if(addBtn){ addBtn.addEventListener('click', (e)=>{ e.preventDefault(); addTagFromInput(); }); }

        let debounceTimer = null;
        search.addEventListener('input', function(e){
            const q = e.target.value.trim();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(async ()=>{
                if(!q){ suggestions.innerHTML=''; suggestions.classList.add('hidden'); return; }
                const items = await fetchTags(q);
                // filter out already selected
                const selectedIds = Array.from(select.selectedOptions).map(o=>String(o.value));
                const filtered = (items || []).filter(i=>!selectedIds.includes(String(i.id)));
                showSuggestions(filtered || []);
            }, 200);
        });

        // Before submit: populate tags and new_tags hidden fields
        const form = document.querySelector('form');
        if(form){
            form.addEventListener('submit', ()=>{
                const vals = Array.from(select.selectedOptions).map(o=>o.value);
                // set tags hidden as comma separated for server compatibility (existing code accepts tags raw)
                let elTags = document.getElementById('tags-hidden');
                if(!elTags){ elTags = document.createElement('input'); elTags.type='hidden'; elTags.name='tags'; elTags.id='tags-hidden'; form.appendChild(elTags); }
                elTags.value = vals.join(',');

                // collect new: names into new_tags hidden field
                const newNames = vals.filter(v=>String(v).startsWith('new:')).map(v=>String(v).replace(/^new:/,''));
                if(newNames.length){ let elNew = document.getElementById('new-tags-hidden'); if(!elNew){ elNew = document.createElement('input'); elNew.type='hidden'; elNew.name='new_tags'; elNew.id='new-tags-hidden'; form.appendChild(elNew); } elNew.value = newNames.join(','); }
            });
        }

        // initialize display
        document.addEventListener('DOMContentLoaded', renderSelected);
        renderSelected();
    })();
</script>

<script>
    // Featured image local preview and media library modal (create)
    (function(){
        const fileInput = document.getElementById('featured-image-file');
        const urlInput = document.getElementById('featured-image-url');
        const preview = document.getElementById('featured-preview');
        const openMedia = document.getElementById('open-media-library');

        if(fileInput){
            fileInput.addEventListener('change', (e)=>{
                const f = e.target.files && e.target.files[0];
                if(!f) return;
                const url = URL.createObjectURL(f);
                urlInput.value = url;
                preview.innerHTML = '';
                const img = document.createElement('img');
                img.src = url;
                img.className = 'w-full object-cover rounded';
                preview.appendChild(img);
            });
        }

        // Simple modal placeholder — will fetch media from /manage/media when route exists
        if(openMedia){
            openMedia.addEventListener('click', ()=>{
                // create modal
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black/40 flex items-center justify-center z-50';
                const box = document.createElement('div');
                box.className = 'w-full max-w-3xl bg-white dark:bg-zinc-900 rounded p-4';
                box.innerHTML = `<div class="flex justify-between items-center mb-3"><h3 class="font-semibold">Media Library</h3><button id="close-media" class="px-2 py-1">Close</button></div><div id="media-list" class="grid grid-cols-3 gap-2 max-h-96 overflow-auto"></div>`;
                modal.appendChild(box);
                document.body.appendChild(modal);

                document.getElementById('close-media').addEventListener('click', ()=>{ modal.remove(); });

                // fetch media list (if endpoint exists)
                fetch('/manage/media').then(r=>r.ok ? r.json() : Promise.reject()).then(json=>{
                    const list = document.getElementById('media-list');
                    list.innerHTML='';
                    json.forEach(m=>{
                        const it = document.createElement('div');
                        it.className = 'cursor-pointer p-1 border rounded';
                        it.innerHTML = `<img src="${m.url}" class="w-full h-24 object-cover rounded" />`;
                        it.addEventListener('click', ()=>{
                            urlInput.value = m.url;
                            preview.innerHTML = `<img src="${m.url}" class="w-full object-cover rounded" />`;
                            modal.remove();
                        });
                        list.appendChild(it);
                    });
                }).catch(()=>{
                    const list = document.getElementById('media-list');
                    list.innerHTML = '<div class="col-span-3 text-sm text-gray-500">Media endpoint not found. Implement /manage/media to enable browsing.</div>';
                });
            });
        }
    })();
</script>
