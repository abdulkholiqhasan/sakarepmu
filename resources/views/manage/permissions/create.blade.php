<x-layouts.app :title="__('Create Permission')">
    <div class="p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-semibold">Create Permission</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">Define a new permission used by roles.</p>
            </div>
            <a href="{{ route('permissions.index') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800">Back to list</a>
        </div>

        <div class="bg-white dark:bg-zinc-900 shadow-sm rounded p-6 border border-zinc-200 dark:border-zinc-700">
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-100 text-red-700 rounded">
                    <strong class="block">There were some problems with your input:</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('permissions.store') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm">Name</label>
                        <input name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border-zinc-200 dark:border-zinc-700" required />
                    </div>
                    <div>
                        <label class="block text-sm">Guard Name</label>
                        <input name="guard_name" value="{{ old('guard_name') }}" class="w-full border rounded px-3 py-2 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border-zinc-200 dark:border-zinc-700" />
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700">Create</button>
                    <a href="{{ route('permissions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium bg-white text-zinc-800 border border-zinc-200 hover:bg-zinc-50">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
