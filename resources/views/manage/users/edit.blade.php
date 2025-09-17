<x-layouts.app :title="__('Edit User')">
    <div class="p-4">
        <h1 class="text-2xl font-semibold mb-4">Edit User</h1>

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
            <div class="text-sm text-zinc-600">{{ $user->getKey() }}</div>
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block">Name</label>
                <input name="name" value="{{ old('name', $user->name) }}" class="w-full" required />
            </div>
            <div>
                <label class="block">Email</label>
                <input name="email" value="{{ old('email', $user->email) }}" class="w-full" required />
            </div>
            <div>
                <label class="block">Password (leave blank to keep current)</label>
                <input name="password" type="password" class="w-full" />
            </div>

            <div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('users.index') }}" class="btn">Cancel</a>
            </div>
        </form>
    </div>

</x-layouts.app>
