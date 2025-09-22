<?php $title = 'Posts'; ?>
<x-layouts.app :title="$title ?? null">
    <div class="bg-white dark:bg-zinc-900 min-h-screen">
        <!-- Admin-style header -->
        <div class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-700 px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Posts</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Manage your blog posts</p>
                </div>
                <div>
                    <button 
                        onclick="window.location.href='{{ route('posts.create') }}'"
                        class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors"
                    >
                        Add New
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 rounded-r-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Status filters and search -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Status filters -->
                <div class="flex items-center space-x-1 text-sm">
                    <a href="{{ route('posts.index') }}" 
                       class="px-3 py-1.5 rounded-md transition-colors {{ !request('status') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium' : 'text-gray-600 dark:text-zinc-400 hover:text-gray-900 dark:hover:text-zinc-200 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                        All
                    </a>
                    <a href="{{ route('posts.index', ['status' => 'published']) }}" 
                       class="px-3 py-1.5 rounded-md transition-colors {{ request('status') === 'published' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium' : 'text-gray-600 dark:text-zinc-400 hover:text-gray-900 dark:hover:text-zinc-200 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                        Published
                    </a>
                    <a href="{{ route('posts.index', ['status' => 'draft']) }}" 
                       class="px-3 py-1.5 rounded-md transition-colors {{ request('status') === 'draft' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium' : 'text-gray-600 dark:text-zinc-400 hover:text-gray-900 dark:hover:text-zinc-200 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                        Draft
                    </a>
                </div>

                <!-- Search -->
                <div class="w-full sm:w-80">
                    <form method="GET" action="{{ route('posts.index') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <flux:input 
                                name="q" 
                                placeholder="Search posts..." 
                                type="text" 
                                value="{{ request('q') }}" 
                                class="pl-10 w-full border-gray-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                autocomplete="off" 
                                enterkeyhint="search" 
                            />
                        </div>
                    </form>
                </div>
            </div>

            <!-- Posts table -->
            <div class="bg-white dark:bg-zinc-800 shadow-sm rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden">
                @if($posts->isEmpty())
                    <div class="text-center py-12">
                        <div class="mx-auto h-12 w-12 text-gray-400 dark:text-zinc-500 mb-4">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No posts found</h3>
                        <p class="text-gray-500 dark:text-zinc-400 mb-6 max-w-sm mx-auto">
                            @if(request('q') || request('status'))
                                No posts match your current filters. Try adjusting your search or filter criteria.
                            @else
                                Get started by creating your first blog post.
                            @endif
                        </p>
                        @if(!request('q') && !request('status'))
                            <button 
                                onclick="window.location.href='{{ route('posts.create') }}'"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Create your first post
                            </button>
                        @endif
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                            <thead class="bg-gray-50 dark:bg-zinc-700/50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-zinc-300 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-zinc-300 uppercase tracking-wider">
                                        Author
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-zinc-300 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-zinc-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                                @foreach($posts as $post)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="max-w-sm">
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                            {{ $post->title }}
                                                        </p>
                                                        @if($post->excerpt)
                                                            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1 line-clamp-2">
                                                                {{ Str::limit($post->excerpt, 100) }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    @if($post->status === 'draft' || !$post->published)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-zinc-700 text-gray-800 dark:text-zinc-300">
                                                            Draft
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                            Published
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-zinc-600 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-gray-600 dark:text-zinc-300">
                                                        {{ substr($post->author?->name ?? 'U', 0, 1) }}
                                                    </span>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $post->author?->name ?? 'Unknown' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-zinc-400">
                                                <p class="font-medium">{{ $post->created_at->format('M d, Y') }}</p>
                                                <p class="text-xs">{{ $post->created_at->format('g:i A') }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button 
                                                    onclick="window.location.href='{{ route('posts.edit', $post) }}'"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 rounded-md transition-colors"
                                                >
                                                    Edit
                                                </button>
                                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this post?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 rounded-md transition-colors"
                                                    >
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced pagination -->
                    <div class="bg-gray-50 dark:bg-zinc-700/30 px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center text-sm text-gray-500 dark:text-zinc-400">
                                <span class="mr-2">Showing</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $posts->firstItem() ?? 0 }}</span>
                                <span class="mx-1">to</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $posts->lastItem() ?? 0 }}</span>
                                <span class="mx-1">of</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $posts->total() }}</span>
                                <span class="ml-1">posts</span>
                            </div>
                            <div class="flex items-center">
                                {{ $posts->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
