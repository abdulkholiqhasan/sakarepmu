<x-layouts.app :title="__('Edit Permission')">
    <div class="p-4">
        <h1 class="text-2xl font-semibold mb-4">Edit Permission</h1>

        <form method="POST" action="{{ route('permissions.update', $permission) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <div class="text-sm text-zinc-600">{{ $permission->getKey() }}</div>
                <label class="block text-sm">Name</label>
                <input name="name" value="{{ old('name', $permission->name) }}" class="w-full" required />
            </div>

            <div>
                <label class="block text-sm">Guard Name</label>
                <input name="guard_name" value="{{ old('guard_name', $permission->guard_name) }}" class="w-full" />
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('permissions.index') }}" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>
