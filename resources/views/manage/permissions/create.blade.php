<x-layouts.app :title="__('Create Permission')">
    <div class="p-4">
        <h1 class="text-2xl font-semibold mb-4">Create Permission</h1>

        <form method="POST" action="{{ route('permissions.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm">Name</label>
                <input name="name" value="{{ old('name') }}" class="w-full" required />
            </div>

            <div>
                <label class="block text-sm">Guard Name</label>
                <input name="guard_name" value="{{ old('guard_name') }}" class="w-full" />
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('permissions.index') }}" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>
