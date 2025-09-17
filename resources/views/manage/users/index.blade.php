<x-layouts.app :title="__('Users')">
    <div class="p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-semibold">Users</h1>
                <p class="text-sm text-zinc-600">Manage application users — view, create, and edit accounts.</p>
            </div>

                <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-2">
                    <label for="users-search" class="sr-only">Search users</label>
                    <input id="users-search" name="q" type="search" placeholder="Search users (press Enter)" value="{{ request('q') }}" class="border rounded-md px-3 py-2 text-sm w-48" aria-label="Search users" />
                </form>
                <a href="{{ route('users.create') }}" class="btn-primary">Create User</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

                @if($users->isEmpty())
            <div class="bg-white border rounded-lg p-6 text-center text-zinc-600">
                <p class="mb-2">No users found.</p>
                <a href="{{ route('users.create') }}" class="btn-primary">Create first user</a>
            </div>
        @endif

        <div class="overflow-x-auto bg-white border rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 rounded-lg text-sm">
                <thead class="bg-white">
                    <tr>
                        <th colspan="4" class="px-6 py-4 border-b text-left">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm text-zinc-500">Showing</div>
                                    <div class="font-medium text-zinc-900">{{ $users->total() }} users</div>
                                </div>
                                <div class="text-sm text-zinc-500">Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</div>
                            </div>
                        </th>
                    </tr>
                </thead>
                <thead class="bg-zinc-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-zinc-700">User</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-zinc-700">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-zinc-700">Roles</th>
                        <th class="px-6 py-3 text-right text-sm font-medium text-zinc-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($users as $user)
                        <tr class="hover:bg-zinc-50">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-400 to-indigo-500 text-white flex items-center justify-center text-sm font-semibold">{{ $user->initials() }}</div>
                                    <div>
                                        <div class="font-medium text-zinc-900">{{ $user->name }}</div>
                                        <div class="text-xs text-zinc-500">{{ $user->getKey() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-zinc-700">{{ $user->email }}</td>
                            <td class="px-6 py-3">
                                <div class="flex flex-wrap gap-2">
                                    @forelse($user->roles as $role)
                                        <span class="text-xs bg-indigo-50 text-indigo-700 px-2 py-1 rounded">{{ $role->name }}</span>
                                    @empty
                                        <span class="text-xs text-zinc-500">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit</a>
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-zinc-500">No users to display.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-layouts.app>
