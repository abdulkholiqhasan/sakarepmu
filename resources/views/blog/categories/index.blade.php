<?php $title = __('Manage / Categories'); ?>
<x-layouts.app :title="$title ?? null">
    <div class="bg-white dark:bg-zinc-900 min-h-screen">
        <!-- Admin-style header -->
        <div class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-700 px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <h1 class="text-xl sm:text-2xl font-normal text-gray-900 dark:text-white">Categories</h1>
                    <span class="text-sm text-gray-500 dark:text-zinc-400">({{ $categories->total() }})</span>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-3">
                    @permission('create categories')
                        <button 
                            onclick="window.location.href='{{ route('categories.create') }}'"
                            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors touch-manipulation"
                        >
                            Add New
                        </button>
                    @endpermission
                </div>
            </div>
        </div>

        <div class="px-4 sm:px-6 py-4">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Search -->
            <div class="mb-4">
                <form method="GET" class="w-full max-w-md" action="{{ route('categories.index') }}">
                    <flux:input name="q" placeholder="Search categories..." type="text" value="{{ request('q') }}" class="w-full" autocomplete="off" enterkeyhint="search" />
                </form>
            </div>

        <div class="bg-white dark:bg-zinc-800 shadow rounded-lg overflow-hidden">
                    @if($categories->isEmpty())
                <div class="p-8 text-center">
                    <div class="text-gray-400 dark:text-zinc-500 mb-4">
                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No categories yet</h3>
                    <p class="text-gray-500 dark:text-zinc-400 mb-4">Get started by creating your first category.</p>
                    @permission('create categories')
                        <button 
                            onclick="window.location.href='{{ route('categories.create') }}'"
                            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors touch-manipulation"
                        >
                            Create Category
                        </button>
                    @endpermission
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-300 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-300 uppercase tracking-wider">Slug</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-zinc-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                            @foreach($categories as $category)
                                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-xs">{{ $category->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 dark:text-zinc-400 truncate max-w-sm">{{ $category->slug }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            @permission('edit categories')
                                                <button 
                                                    onclick="window.location.href='{{ route('categories.edit', $category) }}'"
                                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium transition-colors"
                                                >
                                                    Edit
                                                </button>
                                            @endpermission
                                            @permission('delete categories')
                                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this category?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        type="submit" 
                                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium transition-colors"
                                                    >
                                                        Delete
                                                    </button>
                                                </form>
                                            @endpermission
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Admin-style pagination -->
                <div class="bg-white dark:bg-zinc-800 px-4 sm:px-6 py-3 border-t border-gray-200 dark:border-zinc-700 flex flex-col sm:flex-row items-center sm:justify-between gap-3">
                    <div class="text-sm text-gray-500 dark:text-zinc-400 order-2 sm:order-1">
                        Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} results
                    </div>
                    <div class="order-1 sm:order-2">
                        {{ $categories->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
</x-layouts.app>
