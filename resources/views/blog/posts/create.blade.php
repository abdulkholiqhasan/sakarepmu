<x-layouts.app>
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
                            <flux:textarea name="content" :label="__('Content')">{{ old('content') }}</flux:textarea>
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
                                    <input type="text" id="category-search" placeholder="Search categories..." class="w-full px-3 py-2 mb-2 rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm dark:text-gray-100">
                                    <div id="categories-list" class="max-h-48 overflow-auto rounded-md border border-gray-100 dark:border-gray-800 p-2 bg-white dark:bg-gray-900">
                                        @foreach($categories as $category)
                                            <label class="flex items-center space-x-2 py-1 px-2 rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="category-checkbox" />
                                                <span class="text-sm text-gray-700 dark:text-gray-200">{{ $category->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                        </div>

                        <div class="bg-white dark:bg-zinc-800 p-4 rounded border dark:border-zinc-700">
                            <h3 class="font-medium text-sm text-gray-700 dark:text-zinc-200 mb-2">Tags</h3>
                                <div class="mt-1">
                                    <div id="tag-input" class="w-full rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-2 flex flex-wrap items-center"></div>
                                    <input type="hidden" name="tags[]" id="tags-hidden" />
                                    <input type="text" id="tag-typeahead" placeholder="Add a tag and press Enter" class="mt-2 w-full px-3 py-2 rounded-md border border-transparent focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-white dark:bg-gray-800 dark:text-gray-100" />
                                    <div id="tag-suggestions" class="relative"></div>
                                    <p class="text-xs text-gray-500 mt-1">Type to add tags. Press Enter to add. Suggestions appear as you type.</p>
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
        const allTags = @json(App\Models\Blog\Tag::orderBy('name')->get()->map(fn($t)=>['id'=>$t->id,'name'=>$t->name]));
        const tagInput = document.getElementById('tag-input');
        const tagType = document.getElementById('tag-typeahead');
        const tagHidden = document.getElementById('tags-hidden');
        const suggestionsBox = document.getElementById('tag-suggestions');
        let selectedTags = [];

        function renderTags(){
            tagInput.innerHTML = '';
            selectedTags.forEach(t=>{
                const el = document.createElement('span');
                el.className = 'm-1 inline-flex items-center px-2 py-0.5 rounded-full text-sm font-medium bg-gray-100 dark:bg-zinc-700 text-gray-800 dark:text-gray-100';
                el.textContent = t.name;
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'ml-2 text-xs opacity-70';
                btn.textContent = '×';
                btn.onclick = ()=>{ selectedTags = selectedTags.filter(x=>x.id!==t.id); renderTags(); };
                el.appendChild(btn);
                tagInput.appendChild(el);
            });
            tagHidden.value = selectedTags.map(t=>t.id).join(',');
        }

        function showSuggestions(query){
            const q = query.trim().toLowerCase();
            if(!q){ suggestionsBox.innerHTML = ''; return; }
            const matches = allTags.filter(t=>t.name.toLowerCase().includes(q) && !selectedTags.find(s=>s.id===t.id)).slice(0,8);
            suggestionsBox.innerHTML = '';
            const list = document.createElement('div');
            list.className = 'absolute mt-1 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded shadow z-50 w-full';
            matches.forEach(m=>{
                const item = document.createElement('div');
                item.className = 'px-3 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 cursor-pointer';
                item.textContent = m.name;
                item.onclick = ()=>{ selectedTags.push(m); renderTags(); tagType.value=''; suggestionsBox.innerHTML=''; };
                list.appendChild(item);
            });
            suggestionsBox.appendChild(list);
        }

        tagType.addEventListener('keydown', (e)=>{
            if(e.key==='Enter'){
                e.preventDefault();
                const name = tagType.value.trim();
                if(!name) return;
                let found = allTags.find(t=>t.name.toLowerCase()===name.toLowerCase());
                if(!found){ found = { id: 'new:'+name, name }; }
                if(!selectedTags.find(t=>t.name.toLowerCase()===found.name.toLowerCase())){ selectedTags.push(found); renderTags(); }
                tagType.value=''; suggestionsBox.innerHTML='';
            }
        });

        tagType.addEventListener('input', (e)=>{ showSuggestions(e.target.value); });

        // Category search
        const catSearch = document.getElementById('category-search');
        const catList = document.getElementById('categories-list');
        catSearch.addEventListener('input', (e)=>{
            const q = e.target.value.trim().toLowerCase();
            Array.from(catList.querySelectorAll('label')).forEach(lbl=>{
                const txt = lbl.textContent.trim().toLowerCase();
                lbl.style.display = txt.includes(q) ? 'flex' : 'none';
            });
        });

        // Before submit: ensure tags hidden input contains IDs; convert 'new:' prefixed ids to names for server to parse
        const form = document.querySelector('form');
        if(form){
            form.addEventListener('submit', ()=>{
                const newTags = selectedTags.filter(t=>String(t.id).startsWith('new:')).map(t=>t.name);
                if(newTags.length){ let el = document.getElementById('new-tags-hidden'); if(!el){ el = document.createElement('input'); el.type='hidden'; el.name='new_tags'; el.id='new-tags-hidden'; form.appendChild(el); } el.value = newTags.join(','); }
                tagHidden.value = selectedTags.map(t=>t.id).join(',');
            });
        }

        renderTags();
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
