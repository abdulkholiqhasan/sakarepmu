<x-layouts.app>
    <div class="p-4">
        <div class="mb-3">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-900 dark:text-white">Categories <span class="text-sm text-gray-500 dark:text-zinc-400">({{ $categories->total() }})</span></h1>
                    <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Manage blog categories</p>
                </div>
                <flux:button :href="route('categories.create')" variant="primary">Add Category</flux:button>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-700 dark:text-green-400">{{ session('success') }}</div>
        @endif

        <div class="mb-4">
            <form method="GET" class="w-full" action="{{ route('categories.index') }}">
                <flux:input name="q" placeholder="Search categories and press Enter" type="text" value="{{ request('q') }}" class="w-full" autocomplete="off" enterkeyhint="search" />
            </form>
            @if(request('q'))
                <div class="mt-2"><a href="{{ route('categories.index') }}" class="text-sm text-gray-500">Reset search</a></div>
            @endif
        </div>

        <div class="bg-white dark:bg-zinc-800 shadow rounded overflow-hidden">
                @if($categories->isEmpty())
                <div class="p-8 text-center">
                    <p class="mb-4 text-gray-600 dark:text-zinc-300">No categories yet.</p>
                    <flux:button :href="route('categories.create')" variant="primary">Create your first category</flux:button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y text-sm">
                        <thead class="bg-gray-50 dark:bg-zinc-700">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-gray-600 dark:text-zinc-300 uppercase">Name</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-600 dark:text-zinc-300 uppercase">Slug</th>
                                <th class="px-6 py-3 text-right font-medium text-gray-600 dark:text-zinc-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-transparent divide-y divide-gray-100 dark:divide-zinc-700">
                            @foreach($categories as $category)
                                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700">
                                    <td class="px-6 py-3 whitespace-nowrap max-w-xs truncate font-medium text-gray-900 dark:text-white">{{ $category->name }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-gray-600 dark:text-zinc-300 max-w-sm truncate">{{ $category->slug }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-right">
                                        <flux:button :href="route('categories.edit', $category)" variant="ghost" class="mr-2 px-3 py-1 text-sm">Edit</flux:button>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this category?')">
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
                    <div class="text-sm text-gray-500 dark:text-zinc-400">Showing {{ $categories->firstItem() ?? 0 }} - {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }}</div>
                    <div>
                        {{ $categories->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
