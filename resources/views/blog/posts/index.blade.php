<x-layouts.app>
    <div class="p-6">
        <div class="mb-4 flex items-start justify-between">
            <div>
                <h1 class="text-lg font-semibold text-gray-900 dark:text-white">Posts <span class="text-sm text-gray-500 dark:text-zinc-400">({{ $posts->total() }})</span></h1>
                <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Manage blog posts</p>
            </div>
            <flux:button :href="route('posts.create')" variant="primary">Add Post</flux:button>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-700 dark:text-green-400">{{ session('success') }}</div>
        @endif

        <div class="mb-4">
            <form method="GET" class="w-full" action="{{ route('posts.index') }}">
                <flux:input name="q" placeholder="Search posts and press Enter" type="text" value="{{ request('q') }}" class="w-full" autocomplete="off" enterkeyhint="search" />
            </form>
        </div>

        <div class="bg-white dark:bg-zinc-800 shadow rounded overflow-hidden">
            @if($posts->isEmpty())
                <div class="p-8 text-center">
                    <p class="mb-4 text-gray-600 dark:text-zinc-300">No posts yet.</p>
                    <flux:button :href="route('posts.create')" variant="primary">Create your first post</flux:button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y text-sm">
                        <thead class="bg-gray-50 dark:bg-zinc-700">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-600 dark:text-zinc-300 uppercase">Title</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600 dark:text-zinc-300 uppercase">Author</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600 dark:text-zinc-300 uppercase">Category</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600 dark:text-zinc-300 uppercase">Status</th>
                            <th class="px-6 py-3 text-right font-medium text-gray-600 dark:text-zinc-300 uppercase">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-transparent divide-y divide-gray-100 dark:divide-zinc-700">
                        @foreach($posts as $post)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700">
                                <td class="px-6 py-3 whitespace-nowrap max-w-xs truncate font-medium text-gray-900 dark:text-white">{{ $post->title }}<div class="text-sm text-gray-500 dark:text-zinc-400 mt-1">{{ $post->excerpt ? Str::limit($post->excerpt, 100) : '' }}</div></td>
                                <td class="px-6 py-3 whitespace-nowrap text-gray-600 dark:text-zinc-300">{{ $post->author?->name ?? '-' }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-gray-600 dark:text-zinc-300 max-w-sm truncate">{{ $post->category?->name ?? '-' }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-gray-600 dark:text-zinc-300">{{ $post->status ?? ($post->published ? 'published' : 'draft') }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-right">
                                    <flux:button :href="route('posts.edit', $post)" variant="ghost" class="mr-2 px-3 py-1 text-sm">Edit</flux:button>
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this post?')">
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
                    <div class="text-sm text-gray-500 dark:text-zinc-400">Showing {{ $posts->firstItem() ?? 0 }} - {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }}</div>
                    <div>
                        {{ $posts->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
