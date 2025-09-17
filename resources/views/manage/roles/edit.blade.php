<x-layouts.app :title="__('Edit Role')">
    <div class="p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-semibold">Edit Role</h1>
                <p class="text-sm text-zinc-600">Modify role details and assigned permissions.</p>
            </div>
            <a href="{{ route('roles.index') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium bg-white text-zinc-800 border border-zinc-200 hover:bg-zinc-50">Back to list</a>
        </div>

        <div class="bg-white shadow-sm rounded p-6 border">
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

            <div class="mb-4">
                <label class="block text-sm font-medium text-zinc-700">ID</label>
                <div class="text-sm text-zinc-600">{{ $role->getKey() }}</div>
            </div>

            <form method="POST" action="{{ route('roles.update', $role) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700">Name</label>
                        <input name="name" value="{{ old('name', $role->name) }}" class="w-full border rounded px-3 py-2" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700">Guard Name</label>
                        <input name="guard_name" value="{{ old('guard_name', $role->guard_name) }}" class="w-full border rounded px-3 py-2" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700">Permissions</label>
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($permissions as $permission)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->getKey() }}" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ $role->permissions->contains($permission) ? 'checked' : '' }} />
                                <span class="text-sm">{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-zinc-500 mt-2">Select permissions granted by this role.</p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700">Save</button>
                    <a href="{{ route('roles.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium bg-white text-zinc-800 border border-zinc-200 hover:bg-zinc-50">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
