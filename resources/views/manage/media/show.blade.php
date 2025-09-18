<x-layouts.app :title="__('Media')">
    <div class="p-4">
        <div class="max-w-3xl">
            <div class="mb-4 flex items-start gap-4">
                @if(str_starts_with($media->mime_type, 'image/'))
                    <img src="{{ Storage::url($media->path) }}" alt="{{ $media->filename }}" class="w-48 h-48 object-cover rounded" />
                @else
                    <div class="w-48 h-48 flex items-center justify-center bg-zinc-100 dark:bg-zinc-800 rounded">{{ strtoupper(pathinfo($media->filename, PATHINFO_EXTENSION)) }}</div>
                @endif

                <div>
                    <h1 class="text-2xl font-semibold">{{ $media->filename }}</h1>
                    <div class="text-sm text-zinc-500">Type: {{ $media->mime_type ?? '—' }}</div>
                    <div class="text-sm text-zinc-500">Size: {{ number_format($media->size ?? 0) }} bytes</div>
                    <div class="mt-3">
                        <a href="{{ Storage::url($media->path) }}" class="btn">Download</a>
                        <a href="{{ route('media.edit', $media) }}" class="btn">Edit</a>
                        <form method="POST" action="{{ route('media.destroy', $media) }}" class="inline-block" onsubmit="return confirm('Delete this media?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
