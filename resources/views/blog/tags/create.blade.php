<?php $title = __('Add New Tag'); ?>
<x-layouts.app :title="$title ?? null">
    <div class="bg-white dark:bg-zinc-900 min-h-screen">
        <!-- Admin-style header -->
        <div class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-700 px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <h1 class="text-xl sm:text-2xl font-normal text-gray-900 dark:text-white">Add New Tag</h1>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <button 
                        onclick="window.location.href='{{ route('tags.index') }}'"
                        class="text-gray-600 hover:text-gray-800 dark:text-zinc-400 dark:hover:text-zinc-200 text-sm font-medium transition-colors"
                    >
                        <span class="hidden sm:inline">← Back to Tags</span>
                        <span class="sm:hidden">← Back</span>
                    </button>
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

        <form action="{{ route('tags.store') }}" method="POST" class="flex flex-col lg:flex-row">
            @csrf
            <!-- Main content area (Admin-style 75% width on desktop, full width on mobile) -->
            <div class="flex-1 lg:w-3/4 bg-gray-50 dark:bg-zinc-900 px-4 sm:px-6 py-6">
                <!-- Tag Name -->
                <div class="mb-6">
                    <div class="bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded p-4">
                        <flux:input 
                            name="name" 
                            :label="__('Name')" 
                            type="text" 
                            value="{{ old('name') }}" 
                            required 
                            class="text-lg font-medium"
                            placeholder="Enter tag name"
                        />
                        @error('name') 
                            <p class="text-red-600 dark:text-red-400 mt-1 text-sm">{{ $message }}</p> 
                        @enderror
                        
                        <!-- Slug field -->
                        <div class="mt-4">
                            <flux:input 
                                name="slug" 
                                :label="__('Slug')" 
                                type="text" 
                                value="{{ old('slug') }}" 
                                placeholder="Auto-generated from name"
                                class="text-sm"
                            />
                            @error('slug') 
                                <p class="text-red-600 dark:text-red-400 mt-1 text-sm">{{ $message }}</p> 
                            @enderror
                        </div>
                    </div>
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
                        <div class="flex flex-col gap-2">
                            <button 
                                type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-3 px-4 rounded text-sm transition-colors touch-manipulation"
                            >
                                Create Tag
                            </button>
                            <a 
                                href="{{ route('tags.index') }}" 
                                class="w-full bg-gray-100 hover:bg-gray-200 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-gray-700 dark:text-zinc-300 font-medium py-3 px-4 rounded text-sm transition-colors touch-manipulation text-center inline-block"
                            >
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>

<!-- Admin-style JavaScript -->
<script>
    // Auto-generate slug from name (Admin style)
    (function(){
        const name = document.querySelector('input[name="name"]');
        const slug = document.querySelector('input[name="slug"]');
        if(!name || !slug) return;
        let manuallyEdited = false;

        function slugify(val){
            return val.toString().toLowerCase()
                .replace(/\s+/g, '-')           
                .replace(/[^a-z0-9\-]/g, '')    
                .replace(/-+/g,'-')              
                .replace(/^-+|-+$/g,'');         
        }

        slug.addEventListener('input', ()=>{ manuallyEdited = true; });
        name.addEventListener('input', ()=>{
            if(manuallyEdited) return;
            slug.value = slugify(name.value);
        });
    })();
</script>
