<?php $title = __('Add New Post'); ?>
<x-layouts.app :title="$title ?? null">
    <div class="bg-white dark:bg-zinc-900 min-h-screen">
        <!-- Admin-style header -->
        <div class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-700 px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <h1 class="text-xl sm:text-2xl font-normal text-gray-900 dark:text-white">Add New Post</h1>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <flux:button :href="route('posts.index')" variant="ghost" class="text-xs sm:text-sm px-2 sm:px-3 py-1 sm:py-2">
                        <span class="hidden sm:inline">← Back to Posts</span>
                        <span class="sm:hidden">← Back</span>
                    </flux:button>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mx-4 sm:mx-6 mt-4">
                <div class="flex">
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                        <div class="mt-2">
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('posts.store') }}" method="POST" class="flex flex-col lg:flex-row">
            @csrf
            <!-- Main content area (Admin-style 75% width on desktop, full width on mobile) -->
            <div class="w-full lg:w-3/4 px-4 sm:px-6 py-6">
                <!-- Title -->
                <div class="mb-6">
                    <input 
                        name="title" 
                        type="text" 
                        value="{{ old('title') }}" 
                        placeholder="Enter title here" 
                        class="w-full text-2xl sm:text-3xl font-normal px-0 py-3 border-0 border-b border-gray-200 dark:border-zinc-700 bg-transparent dark:bg-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:ring-0 placeholder-gray-400 dark:placeholder-zinc-500 text-gray-900 dark:text-white" 
                        style="box-shadow: none;"
                    />
                    @error('title') 
                        <p class="text-red-600 dark:text-red-400 mt-2 text-sm">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Permalink -->
                <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 px-3 py-2 rounded">
                    <div class="flex flex-col sm:flex-row sm:items-center text-sm gap-1 sm:gap-0">
                        <span class="text-gray-600 dark:text-zinc-400">Permalink:</span>
                        <div class="flex items-center flex-1">
                            <span class="text-gray-600 dark:text-zinc-400 hidden sm:inline">{{ url('/') }}/</span>
                            <input 
                                name="slug" 
                                id="slug-input" 
                                type="text" 
                                value="{{ old('slug') }}" 
                                class="bg-transparent border-0 p-0 text-blue-600 dark:text-blue-400 focus:ring-0 min-w-0 flex-1 text-sm" 
                                style="box-shadow: none;"
                            />
                            <button type="button" id="reset-slug" class="ml-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm touch-manipulation px-2 py-1">Edit</button>
                        </div>
                    </div>
                    @error('slug') 
                        <p class="text-red-600 dark:text-red-400 mt-1 text-sm">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Content Editor -->
                <div class="mb-6">
                    <div class="bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded">
                        @include('components.wysiwyg', ['name' => 'content', 'value' => old('content')])
                        @error('content') 
                            <p class="text-red-600 dark:text-red-400 mt-2 text-sm">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <!-- Excerpt -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Excerpt</h3>
                    <div class="bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded">
                        <flux:textarea 
                            name="excerpt" 
                            rows="4"
                            placeholder="Write an excerpt (optional)"
                            class="w-full border-0 focus:ring-0 resize-none"
                        >{{ old('excerpt') }}</flux:textarea>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-zinc-400 mt-2">Excerpts are optional hand-crafted summaries of your content that can be used in your theme.</p>
                </div>
            </div>

            <!-- Admin-style sidebar (stacked on mobile, 25% width on desktop) -->
            <div class="w-full lg:w-1/4 bg-gray-50 dark:bg-zinc-800 border-t lg:border-t-0 lg:border-l border-gray-200 dark:border-zinc-700 px-4 py-6 lg:overflow-y-auto">
                
                <!-- Publish Box -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded mb-6">
                    <div class="bg-gray-50 dark:bg-zinc-800 px-4 py-3 border-b border-gray-200 dark:border-zinc-700">
                        <h3 class="font-medium text-sm text-gray-900 dark:text-white">Publish</h3>
                    </div>
                    <div class="p-4">
                        <div class="mb-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 dark:text-zinc-400">Status:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ old('status', 'Draft') }}</span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="block text-sm text-gray-600 dark:text-zinc-400 mb-1">Publish immediately</label>
                            <input 
                                name="published_at" 
                                type="datetime-local" 
                                value="{{ old('published_at') }}" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 touch-manipulation bg-white dark:bg-zinc-900 text-gray-900 dark:text-white"
                            />
                            @error('published_at') 
                                <p class="text-red-600 dark:text-red-400 mt-1 text-sm">{{ $message }}</p> 
                            @enderror
                        </div>

                        <div class="pt-3 border-t border-gray-200 dark:border-zinc-700">
                            <div class="flex flex-col gap-2">
                                <button 
                                    type="submit" 
                                    name="action" 
                                    value="publish" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-3 px-4 rounded text-sm transition-colors touch-manipulation"
                                >
                                    Publish
                                </button>
                                <button 
                                    type="submit" 
                                    name="action" 
                                    value="draft" 
                                    class="w-full bg-gray-100 hover:bg-gray-200 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-gray-700 dark:text-zinc-300 font-medium py-3 px-4 rounded text-sm transition-colors touch-manipulation"
                                >
                                    Save Draft
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded mb-6">
                    <div class="bg-gray-50 dark:bg-zinc-800 px-4 py-3 border-b border-gray-200 dark:border-zinc-700">
                        <h3 class="font-medium text-sm text-gray-900 dark:text-white">Categories</h3>
                    </div>
                    <div class="p-4">
                        <!-- Hidden native select -->
                        <select id="categories-select" name="categories[]" multiple class="hidden"></select>
                        
                        <!-- Search input -->
                        <div class="relative mb-3">
                            <input 
                                type="text" 
                                id="category-search" 
                                placeholder="Search categories..." 
                                class="w-full px-3 py-3 border border-gray-300 dark:border-zinc-700 rounded text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 touch-manipulation bg-white dark:bg-zinc-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500" 
                                autocomplete="off" 
                            />
                            <div id="category-suggestions" class="absolute left-0 right-0 mt-1 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded shadow-lg max-h-48 overflow-auto hidden z-50"></div>
                        </div>
                        
                        <!-- Selected categories -->
                        <div id="selected-categories" class="flex flex-wrap gap-2"></div>
                    </div>
                </div>

                <!-- Tags -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded mb-6">
                    <div class="bg-gray-50 dark:bg-zinc-800 px-4 py-3 border-b border-gray-200 dark:border-zinc-700">
                        <h3 class="font-medium text-sm text-gray-900 dark:text-white">Tags</h3>
                    </div>
                    <div class="p-4">
                        <!-- Hidden native select -->
                        <select id="tags-select" name="tags[]" multiple class="hidden"></select>
                        
                        <!-- Tag search -->
                        <div class="relative mb-3">
                            <input 
                                type="text" 
                                id="tag-search" 
                                placeholder="Add new tag" 
                                class="w-full px-3 py-3 border border-gray-300 dark:border-zinc-700 rounded text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 touch-manipulation bg-white dark:bg-zinc-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500" 
                                autocomplete="off" 
                            />
                            <div id="tag-suggestions" class="absolute left-0 right-0 mt-1 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded shadow-lg max-h-48 overflow-auto hidden z-50"></div>
                        </div>
                        
                        <button 
                            type="button" 
                            id="tag-add-btn" 
                            class="w-full bg-gray-100 hover:bg-gray-200 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-gray-700 dark:text-zinc-300 font-medium py-3 px-3 rounded text-sm mb-3 transition-colors touch-manipulation"
                        >
                            Add
                        </button>
                        
                        <!-- Selected tags -->
                        <div id="selected-tags" class="flex flex-wrap gap-2"></div>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-2">Separate tags with commas</p>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded mb-6">
                    <div class="bg-gray-50 dark:bg-zinc-800 px-4 py-3 border-b border-gray-200 dark:border-zinc-700">
                        <h3 class="font-medium text-sm text-gray-900 dark:text-white">Featured Image</h3>
                    </div>
                    <div class="p-4">
                        <input 
                            name="featured_image" 
                            type="text" 
                            value="{{ old('featured_image') }}" 
                            id="featured-image-url" 
                            placeholder="Image URL" 
                            class="w-full px-3 py-3 border border-gray-300 dark:border-zinc-700 rounded text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 mb-3 touch-manipulation bg-white dark:bg-zinc-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500" 
                        />
                        
                        <div class="flex flex-col gap-2 mb-3">
                            <label class="cursor-pointer text-center py-3 px-3 border border-gray-300 dark:border-zinc-700 rounded text-sm text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors touch-manipulation">
                                <input type="file" name="featured_image_file" id="featured-image-file" class="hidden" accept="image/*" />
                                Upload Image
                            </label>
                            <button 
                                type="button" 
                                id="open-media-library" 
                                class="py-3 px-3 border border-gray-300 dark:border-zinc-700 rounded text-sm text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors touch-manipulation"
                            >
                                Media Library
                            </button>
                        </div>
                        
                        <div id="featured-preview">
                            @if(old('featured_image'))
                                <img src="{{ old('featured_image') }}" alt="Featured" class="w-full object-cover rounded border border-gray-300 dark:border-zinc-700" style="max-height: 150px;" />
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</x-layouts.app>

<!-- Admin-style JavaScript -->
<script>
    // Auto-generate slug from title (Admin style)
    (function(){
        const title = document.querySelector('input[name="title"]');
        const slug = document.querySelector('input[name="slug"]');
        if(!title || !slug) return;
        let manuallyEdited = false;

        function slugify(val){
            return val.toString().toLowerCase()
                .replace(/\s+/g, '-')           
                .replace(/[^a-z0-9\-]/g, '')    
                .replace(/-+/g,'-')              
                .replace(/^-+|-+$/g,'');         
        }

        slug.addEventListener('input', ()=>{ manuallyEdited = true; });
        title.addEventListener('input', ()=>{
            if(manuallyEdited) return;
            slug.value = slugify(title.value);
        });

        const resetBtn = document.getElementById('reset-slug');
        if(resetBtn){
            resetBtn.addEventListener('click', ()=>{
                slug.value = slugify(title.value || '');
                manuallyEdited = false;
                slug.focus();
            });
        }

        if(!slug.value){ slug.value = slugify(title.value || ''); }
    })();
</script>

<script>
    // Categories functionality (Admin style)
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
                span.className = 'inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800 border';
                span.textContent = item.name;
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'ml-1 text-xs text-blue-600 hover:text-blue-800';
                btn.innerHTML = '×';
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

        async function fetchCategories(q){
            const url = new URL('{{ route('categories.search') }}', window.location.origin);
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
                el.className = 'px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm border-b border-gray-100 last:border-b-0';
                el.textContent = it.name;
                el.addEventListener('click', ()=>{
                    let opt = Array.from(select.options).find(o=>o.value===String(it.id));
                    if(!opt){ 
                        opt = document.createElement('option'); 
                        opt.value = it.id; 
                        opt.text = it.name; 
                        opt.selected = true; 
                        select.appendChild(opt); 
                    }
                    opt.selected = true;
                    renderSelected();
                    suggestions.classList.add('hidden');
                    search.value = '';
                });
                suggestions.appendChild(el);
            });
        }

        let debounceTimer = null;
        search.addEventListener('input', function(e){
            const q = e.target.value.trim();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(async ()=>{
                if(!q){ suggestions.innerHTML=''; suggestions.classList.add('hidden'); return; }
                const items = await fetchCategories(q);
                showSuggestions(items || []);
            }, 200);
        });

        document.addEventListener('click', function(e){
            if(!suggestions.classList.contains('hidden') && !suggestions.contains(e.target) && e.target !== search){
                suggestions.classList.add('hidden');
            }
        });

        renderSelected();
    })();
</script>

<script>
    // Tags functionality (Admin style)
    (function(){
        const select = document.getElementById('tags-select');
        const container = document.getElementById('selected-tags');
        const search = document.getElementById('tag-search');
        const suggestions = document.getElementById('tag-suggestions');
        const addBtn = document.getElementById('tag-add-btn');

        function renderSelected(){
            container.innerHTML = '';
            const selected = Array.from(select.selectedOptions).map(opt => ({id: opt.value, name: opt.text}));
            selected.forEach(item => {
                const span = document.createElement('span');
                span.className = 'inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-800 border';
                span.textContent = item.name;
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'ml-1 text-xs text-gray-600 hover:text-gray-800';
                btn.innerHTML = '×';
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
                el.className = 'px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm border-b border-gray-100 last:border-b-0';
                el.textContent = it.name;
                el.addEventListener('click', ()=>{
                    let opt = Array.from(select.options).find(o=>o.value===String(it.id));
                    if(!opt){ 
                        opt = document.createElement('option'); 
                        opt.value = it.id; 
                        opt.text = it.name; 
                        opt.selected = true; 
                        select.appendChild(opt); 
                    }
                    opt.selected = true;
                    renderSelected();
                    suggestions.classList.add('hidden');
                    search.value = '';
                });
                suggestions.appendChild(el);
            });
        }

        function addTagFromInput(){
            const name = search.value.trim();
            if(!name) return;
            const newId = 'new:'+name;
            let opt = Array.from(select.options).find(o=>o.value===newId);
            if(!opt){ 
                opt = document.createElement('option'); 
                opt.value = newId; 
                opt.text = name; 
                opt.selected = true; 
                select.appendChild(opt); 
            }
            opt.selected = true;
            renderSelected();
            search.value = '';
            suggestions.classList.add('hidden');
        }

        search.addEventListener('keydown', (e)=>{
            if(e.key === 'Enter'){
                e.preventDefault();
                addTagFromInput();
            }
        });

        if(addBtn){ 
            addBtn.addEventListener('click', (e)=>{ 
                e.preventDefault(); 
                addTagFromInput(); 
            }); 
        }

        let debounceTimer = null;
        search.addEventListener('input', function(e){
            const q = e.target.value.trim();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(async ()=>{
                if(!q){ suggestions.innerHTML=''; suggestions.classList.add('hidden'); return; }
                const items = await fetchTags(q);
                const selectedIds = Array.from(select.selectedOptions).map(o=>String(o.value));
                const filtered = (items || []).filter(i=>!selectedIds.includes(String(i.id)));
                showSuggestions(filtered || []);
            }, 200);
        });

        // Handle form submission
        const form = document.querySelector('form');
        if(form){
            form.addEventListener('submit', ()=>{
                const vals = Array.from(select.selectedOptions).map(o=>o.value);
                let elTags = document.getElementById('tags-hidden');
                if(!elTags){ 
                    elTags = document.createElement('input'); 
                    elTags.type='hidden'; 
                    elTags.name='tags'; 
                    elTags.id='tags-hidden'; 
                    form.appendChild(elTags); 
                }
                elTags.value = vals.join(',');

                const newNames = vals.filter(v=>String(v).startsWith('new:')).map(v=>String(v).replace(/^new:/,''));
                if(newNames.length){ 
                    let elNew = document.getElementById('new-tags-hidden'); 
                    if(!elNew){ 
                        elNew = document.createElement('input'); 
                        elNew.type='hidden'; 
                        elNew.name='new_tags'; 
                        elNew.id='new-tags-hidden'; 
                        form.appendChild(elNew); 
                    } 
                    elNew.value = newNames.join(','); 
                }
            });
        }

        renderSelected();
    })();
</script>

<script>
    // Featured image functionality (Admin style)
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
                img.className = 'w-full object-cover rounded border';
                img.style.maxHeight = '150px';
                preview.appendChild(img);
            });
        }

        if(openMedia){
            openMedia.addEventListener('click', ()=>{
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4';
                modal.style.zIndex = '10000';
                const box = document.createElement('div');
                box.className = 'w-full max-w-4xl bg-white dark:bg-zinc-900 rounded-lg shadow-xl max-h-[90vh] overflow-auto';
                box.innerHTML = `
                    <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-zinc-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Media Library</h3>
                        <button id="close-media" class="text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-300 text-2xl touch-manipulation px-2 py-1">&times;</button>
                    </div>
                    <div id="media-list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 p-4"></div>
                `;
                modal.appendChild(box);
                document.body.appendChild(modal);

                document.getElementById('close-media').addEventListener('click', ()=>{ modal.remove(); });
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) modal.remove();
                });

                // Fetch media
                fetch('/manage/media', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(r=>r.ok ? r.json() : Promise.reject()).then(json=>{
                    const list = document.getElementById('media-list');
                    list.innerHTML='';
                    json.forEach(m=>{
                        const it = document.createElement('div');
                        it.className = 'cursor-pointer border-2 border-transparent hover:border-blue-500 rounded overflow-hidden touch-manipulation';
                        it.innerHTML = `<img src="${m.url}" class="w-full h-20 sm:h-24 object-cover" />`;
                        it.addEventListener('click', ()=>{
                            urlInput.value = m.url;
                            preview.innerHTML = `<img src="${m.url}" class="w-full object-cover rounded border" style="max-height: 150px;" />`;
                            modal.remove();
                        });
                        list.appendChild(it);
                    });
                }).catch((error)=>{
                    console.error('Media fetch error:', error);
                    const list = document.getElementById('media-list');
                    list.innerHTML = '<div class="col-span-full text-center text-gray-500 py-8">Unable to load media. Please try again.</div>';
                });
            });
        }
    })();
</script>