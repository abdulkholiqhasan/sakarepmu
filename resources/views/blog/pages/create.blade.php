<?php $title = __('Add Page'); ?>
<x-layouts.app :title="$title ?? null">
    <div class="p-6">
    <div class="mx-auto w-full max-w-6xl bg-white dark:bg-zinc-800 shadow rounded p-8">
            <h1 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Create Page</h1>

            @if($errors->any())
                <div class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-100 dark:border-red-800 text-red-700 dark:text-red-200 p-3 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pages.store') }}" method="POST">
                @csrf
                <div class="mb-4 flex items-start justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Create a new page</p>
                    </div>
                    <div>
                        <flux:button :href="route('pages.index')" variant="ghost">Back to pages</flux:button>
                    </div>
                </div>

                @include('blog.pages._form')
            </form>
        </div>
    </div>
</x-layouts.app>

<script>
    // Auto-generate slug from title but allow manual edit for Pages
    (function(){
        const title = document.querySelector('input[name="title"]');
        const slug = document.getElementById('page-slug-input');
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

        const resetBtn = document.getElementById('page-reset-slug');
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
