<?php $title = __('Page List'); ?>
<x-layouts.app :title="$title ?? null">
    <div class="p-6">
        <div class="mb-4 flex items-start justify-between">
            <div>
                <h1 class="text-lg font-semibold text-gray-900 dark:text-white">Pages <span class="text-sm text-gray-500 dark:text-zinc-400">({{ $pages->total() }})</span></h1>
                <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Manage static pages</p>
            </div>
            <flux:button :href="route('pages.create')" variant="primary">Add Page</flux:button>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-700 dark:text-green-400">{{ session('success') }}</div>
        @endif

        <div class="mb-4">
            <form method="GET" class="w-full" action="{{ route('pages.index') }}">
                <flux:input name="q" placeholder="Search pages and press Enter" type="text" value="{{ request('q') }}" class="w-full" autocomplete="off" enterkeyhint="search" />
            </form>
        </div>

        <div class="bg-white dark:bg-zinc-800 shadow rounded overflow-hidden">
            @if($pages->isEmpty())
                <div class="p-8 text-center">
                    <p class="mb-4 text-gray-600 dark:text-zinc-300">No pages yet.</p>
                    <flux:button :href="route('pages.create')" variant="primary">Create your first page</flux:button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y text-sm">
                        <thead class="bg-gray-50 dark:bg-zinc-700">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-600 dark:text-zinc-300 uppercase">Title</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600 dark:text-zinc-300 uppercase">Status</th>
                            <th class="px-6 py-3 text-right font-medium text-gray-600 dark:text-zinc-300 uppercase">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-transparent divide-y divide-gray-100 dark:divide-zinc-700">
                        @foreach($pages as $page)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700">
                                <td class="px-6 py-3 whitespace-nowrap max-w-xs truncate font-medium text-gray-900 dark:text-white">{{ $page->title }}<div class="text-sm text-gray-500 dark:text-zinc-400 mt-1">{{ Str::limit(strip_tags($page->content ?? ''), 120) }}</div></td>
                                <td class="px-6 py-3 whitespace-nowrap text-gray-600 dark:text-zinc-300">{{ $page->published ? 'published' : 'draft' }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-right">
                                    <flux:button :href="route('pages.edit', $page)" variant="ghost" class="mr-2 px-3 py-1 text-sm">Edit</flux:button>
                                    <form action="{{ route('pages.destroy', $page) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this page?')">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button variant="danger" type="submit" class="px-3 py-1 text-sm">Delete</flux:button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <div class="text-sm text-gray-500 dark:text-zinc-400">Showing {{ $pages->firstItem() ?? 0 }} - {{ $pages->lastItem() ?? 0 }} of {{ $pages->total() }}</div>
                    <div>
                        {{ $pages->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
