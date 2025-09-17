<x-layouts.app :title="__('Roles')">
    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-semibold">Roles</h1>
            <a href="{{ route('roles.create') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700">Create Role</a>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <div class="mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-zinc-500">Showing</div>
                    <div class="font-medium text-zinc-900 dark:text-zinc-50">{{ $roles->total() }} roles</div>
                </div>
                <div class="text-sm text-zinc-500">Page {{ $roles->currentPage() }} of {{ $roles->lastPage() }}</div>
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
                @forelse($roles as $role)
                <tr class="border-t">
                    <td class="p-2 text-zinc-700 dark:text-zinc-200">{{ $role->getKey() }}</td>
                    <td class="p-2 text-zinc-800 dark:text-zinc-50">{{ $role->name }}</td>
                    <td class="p-2 text-zinc-700 dark:text-zinc-200">{{ $role->guard_name }}</td>
                    <td class="p-2">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary">Edit</a>
                            <form method="POST" action="{{ route('roles.destroy', $role) }}" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-zinc-500">No roles to display.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        </div>

        <div class="mt-4">
            {{ $roles->links() }}
        </div>
    </div>
</x-layouts.app>
