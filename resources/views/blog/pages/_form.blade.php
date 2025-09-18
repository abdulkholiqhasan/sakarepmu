<div class="grid grid-cols-1 gap-6">
    <div>
        <input name="title" type="text" value="{{ old('title', $page->title ?? '') }}" placeholder="Add title" class="w-full text-2xl font-semibold px-4 py-3 rounded border bg-white dark:bg-zinc-900 dark:border-zinc-700" />
        @error('title') <p class="text-red-600 dark:text-red-300 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <div class="flex items-center space-x-2">
            <input name="slug" id="page-slug-input" type="text" value="{{ old('slug', $page->slug ?? '') }}" placeholder="slug (optional)" class="flex-1 px-3 py-2 rounded border bg-white dark:bg-zinc-900 dark:border-zinc-700" />
            <button type="button" id="page-reset-slug" aria-label="Reset slug to title" class="px-2 py-1 rounded bg-gray-100 dark:bg-zinc-700 text-sm">Reset</button>
        </div>
        @error('slug') <p class="text-red-600 dark:text-red-300 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <flux:textarea name="content" :label="__('Content')">{{ old('content', $page->content ?? '') }}</flux:textarea>
        @error('content') <p class="text-red-600 dark:text-red-300 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center space-x-3">
        <flux:input name="published_at" type="datetime-local" value="{{ old('published_at', isset($page) && $page->published_at ? $page->published_at->format('Y-m-d\TH:i') : '') }}" />
        <p class="text-sm text-gray-500 dark:text-zinc-400">Use Publish / Save as Draft buttons to set page status.</p>
    </div>

    <div class="flex space-x-2">
            @if(isset($page) && $page->exists)
            <flux:button type="submit" name="action" value="update" variant="primary">Update</flux:button>
            <flux:button type="submit" name="action" value="revert" onclick="return confirm('Are you sure you want to revert this page to draft?')" variant="ghost">Revert to Draft</flux:button>
        @else
            <flux:button type="submit" name="action" value="publish" variant="primary">Publish</flux:button>
            <flux:button type="submit" name="action" value="draft" variant="ghost">Save as Draft</flux:button>
    @endif
    </div>
</div>
