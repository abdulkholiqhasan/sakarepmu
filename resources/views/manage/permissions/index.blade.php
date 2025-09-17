<x-layouts.app :title="__('Permissions')">
    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-semibold">Permissions</h1>
            <a href="{{ route('permissions.create') }}" class="btn btn-primary">Create Permission</a>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <table class="w-full table-auto border-collapse">
            <thead>
                <tr>
                    <th class="text-left p-2">ID</th>
                    <th class="text-left p-2">Name</th>
                    <th class="text-left p-2">Guard</th>
                    <th class="text-left p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                <tr class="border-t">
                    <td class="p-2">{{ $permission->getKey() }}</td>
                    <td class="p-2">{{ $permission->name }}</td>
                    <td class="p-2">{{ $permission->guard_name }}</td>
                    <td class="p-2">
                        <a href="{{ route('permissions.edit', $permission) }}" class="text-blue-600">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $permissions->links() }}
        </div>
    </div>
</x-layouts.app>
