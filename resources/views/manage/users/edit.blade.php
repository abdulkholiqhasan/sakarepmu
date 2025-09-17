<x-layouts.app :title="__('Edit User')">
    <div class="p-4">
            <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold">Edit User</h1>
                <p class="text-sm text-zinc-600">Modify user information and roles.</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-outline">Back to list</a>
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
                <div class="text-sm text-zinc-600">{{ $user->getKey() }}</div>
            </div>

            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-zinc-700">Name</label>
                        <input id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded px-3 py-2" required />
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-zinc-700">Email</label>
                        <input id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2" required />
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-zinc-700">Password (leave blank to keep current)</label>
                        <input id="password" name="password" type="password" class="w-full border rounded px-3 py-2" />
                        <p class="text-xs text-zinc-500 mt-1">Leave blank to keep the existing password. Minimum 8 characters when provided.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700">Roles</label>
                        <div class="mt-2 grid grid-cols-1 gap-2">
                            @foreach($roles as $role)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="roles[]" value="{{ $role->getKey() }}" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ $user->hasRole($role) ? 'checked' : '' }} />
                                    <span class="text-sm">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-zinc-500 mt-2">Select one or more roles for this user.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="btn-primary">Save</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
