<x-layouts.app :title="__('Media Library')">
    <div class="p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-semibold">Media</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">Manage uploaded files — upload, view, and delete media.</p>
            </div>

            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('media.index') }}" class="flex items-center gap-2">
                    <label for="media-search" class="sr-only">Search media</label>
                    <input id="media-search" name="q" type="search" placeholder="Search media (press Enter)" value="{{ request('q') }}" class="border rounded-md px-3 py-2 text-sm w-48 bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200 border-zinc-200 dark:border-zinc-700" />
                </form>
                <a href="{{ route('media.create') }}" class="btn-primary">Upload</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        @if($media->isEmpty())
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg p-6 text-center text-zinc-600 dark:text-zinc-300">
                <p class="mb-2">No media found.</p>
                <a href="{{ route('media.create') }}" class="btn-primary">Upload first file</a>
            </div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-4">
            @foreach($media as $item)
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                    <a href="{{ route('media.show', $item) }}" class="block p-3">
                        @if(str_starts_with($item->mime_type, 'image/'))
                            <img src="{{ Storage::url($item->path) }}" alt="{{ $item->filename }}" class="w-full h-40 object-cover rounded" />
                        @else
                            <div class="w-full h-40 flex items-center justify-center text-zinc-500">{{ strtoupper(pathinfo($item->filename, PATHINFO_EXTENSION)) }}</div>
                        @endif
                        <div class="mt-2 text-sm text-zinc-700 dark:text-zinc-200">{{ $item->filename }}</div>
                    </a>
                    <div class="p-3 border-t border-zinc-100 dark:border-zinc-800 text-right">
                        <a href="{{ route('media.edit', $item) }}" class="btn">Edit</a>
                        <form method="POST" action="{{ route('media.destroy', $item) }}" class="inline-block" onsubmit="return confirm('Delete this media?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $media->links() }}
        </div>
    </div>
</x-layouts.app>
