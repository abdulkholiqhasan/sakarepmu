<x-layouts.app :title="__('Create User')">
    <div class="p-4">
        <h1 class="text-2xl font-semibold mb-4">Create User</h1>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block">Name</label>
                <input name="name" value="{{ old('name') }}" class="w-full" required />
            </div>
            <div>
                <label class="block">Email</label>
                <input name="email" value="{{ old('email') }}" class="w-full" required />
            </div>
            <div>
                <label class="block">Password</label>
                <input name="password" type="password" class="w-full" required />
            </div>

            <div>
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('users.index') }}" class="btn">Cancel</a>
            </div>
        </form>
    </div>

</x-layouts.app>
