<x-layouts.app :title="__('Permissions')">
    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-semibold">Permissions</h1>
            <a href="{{ route('permissions.create') }}" class="btn-primary">Create Permission</a>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <div class="mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-zinc-500">Showing</div>
                    <div class="font-medium text-zinc-900 dark:text-zinc-50">{{ $permissions->total() }} permissions</div>
                </div>
                <div class="text-sm text-zinc-500">Page {{ $permissions->currentPage() }} of {{ $permissions->lastPage() }}</div>
            </div>
        </div>
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow">
            <table class="w-full table-auto border-collapse text-sm">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="text-left p-3 text-xs text-zinc-500 dark:text-zinc-300 uppercase">ID</th>
                    <th class="text-left p-3 text-xs text-zinc-500 dark:text-zinc-300 uppercase">Name</th>
                    <th class="text-left p-3 text-xs text-zinc-500 dark:text-zinc-300 uppercase">Guard</th>
                    <th class="text-left p-3 text-xs text-zinc-500 dark:text-zinc-300 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permissions as $permission)
                <tr class="border-t">
                    <td class="p-3 text-xs text-zinc-600 dark:text-zinc-400">{{ $permission->getKey() }}</td>
                    <td class="p-3 text-zinc-800 dark:text-zinc-50">{{ $permission->name }}</td>
                    <td class="p-3 text-zinc-600 dark:text-zinc-400">{{ $permission->guard_name }}</td>
                    <td class="p-3">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-primary">Edit</a>
                            <form method="POST" action="{{ route('permissions.destroy', $permission) }}" onsubmit="return confirm('Are you sure you want to delete this permission?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-zinc-500">No permissions to display.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        </div>

        <div class="mt-4">
            {{ $permissions->links() }}
        </div>
    </div>
</x-layouts.app>
