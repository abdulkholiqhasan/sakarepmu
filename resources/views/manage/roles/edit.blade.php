<x-layouts.app :title="__('Edit Role')">
    <div class="p-4">
        <h1 class="text-2xl font-semibold mb-4">Edit Role</h1>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-4">
            <label class="block text-sm font-medium text-zinc-700">ID</label>
            <div class="text-sm text-zinc-600">{{ $role->getKey() }}</div>
        </div>

        <form method="POST" action="{{ route('roles.update', $role) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block">Name</label>
                <input name="name" value="{{ old('name', $role->name) }}" class="w-full" required />
            </div>
            <div>
                <label class="block">Guard Name</label>
                <input name="guard_name" value="{{ old('guard_name', $role->guard_name) }}" class="w-full" />
            </div>

            <div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('roles.index') }}" class="btn">Cancel</a>
            </div>
        </form>
    </div>

</x-layouts.app>
