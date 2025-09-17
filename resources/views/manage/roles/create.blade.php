<x-layouts.app :title="__('Create Role')">
    <div class="p-4">
        <h1 class="text-2xl font-semibold mb-4">Create Role</h1>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('roles.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block">Name</label>
                <input name="name" value="{{ old('name') }}" class="w-full" required />
            </div>
            <div>
                <label class="block">Guard Name</label>
                <input name="guard_name" value="{{ old('guard_name') }}" class="w-full" />
            </div>

            <div>
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('roles.index') }}" class="btn">Cancel</a>
            </div>
        </form>
    </div>

</x-layouts.app>
