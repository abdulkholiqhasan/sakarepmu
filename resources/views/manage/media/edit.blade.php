<x-layouts.app :title="__('Edit Media')">
    <div class="p-4">
        <div class="max-w-xl">
            <h1 class="text-2xl font-semibold">Edit Media</h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-300 mb-4">Update file metadata.</p>

            <form method="POST" action="{{ route('media.update', $media) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Filename</label>
                    <input type="text" name="filename" value="{{ old('filename', $media->filename) }}" class="mt-1 w-full border rounded-md px-3 py-2 bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200" required />
                    @error('filename') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <div>
                    <button class="btn-primary">Save</button>
                    <a href="{{ route('media.index') }}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
