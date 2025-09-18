<x-layouts.app>
    <div class="p-6 flex justify-center">
        <div class="w-full max-w-xl bg-white dark:bg-zinc-800 shadow rounded p-6">
            <h1 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Create Blog Category</h1>

            @if($errors->any())
                <div class="mb-4 bg-red-50 dark:bg-zinc-900 border border-red-100 dark:border-zinc-700 text-red-700 dark:text-red-300 p-3 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <flux:input name="name" :label="__('Name')" type="text" value="{{ old('name') }}" required />
                    @error('name') <p class="text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <flux:button :href="route('categories.index')" variant="ghost">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Create</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
