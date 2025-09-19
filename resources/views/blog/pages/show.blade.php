<?php $title = $page->title ?? __('Page'); ?>
<x-layouts.app :title="$title ?? null">
    <div class="p-6">
        <div class="mx-auto w-full max-w-4xl bg-white dark:bg-zinc-800 shadow rounded p-8">
            <div class="mb-6 flex items-start justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $page->title }}</h1>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Published: {{ $page->published ? $page->published_at?->toDayDateTimeString() : 'Draft' }}</p>
                </div>
                <div class="space-x-2">
                    <flux:button :href="route('pages.edit', $page)" variant="ghost">Edit</flux:button>
                    <flux:button :href="route('pages.index')" variant="ghost">Back</flux:button>
                </div>
            </div>

            <div class="prose max-w-none dark:prose-invert text-gray-700 dark:text-zinc-200">{!! $page->content !!}</div>
        </div>
    </div>
</x-layouts.app>
