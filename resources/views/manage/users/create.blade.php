<x-layouts.app :title="__('Create User')">
    <div class="p-4">
            <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold">Create User</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">Add a new user account to the application.</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-outline">Back to list</a>
        </div>

        <div class="bg-white dark:bg-zinc-900 shadow-sm rounded p-6 border border-zinc-200 dark:border-zinc-700">
            <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                @csrf

                <!-- Dummy hidden credentials to reduce browser password manager interference -->
                <div aria-hidden="true" tabindex="-1" class="sr-only">
                    <input type="text" name="_dummy_username" autocomplete="username" />
                    <input type="password" name="_dummy_password" autocomplete="new-password" />
                </div>

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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-zinc-700">Name</label>
                        <input id="name" name="name" value="{{ old('name') }}" class="w-full border rounded-md px-3 py-2 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border-zinc-200 dark:border-zinc-700" required />
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium text-zinc-700">Username</label>
                        <div class="relative">
                            <!-- Visible display input renamed to avoid password-manager heuristics -->
                            <input id="username-display" name="username_display" value="{{ old('username') }}" class="w-full border rounded-md px-3 py-2 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border-zinc-200 dark:border-zinc-700" required aria-describedby="username-help" autocomplete="off" autocorrect="off" spellcheck="false" data-lpignore="true" />
                            <!-- Hidden real username field sent to server -->
                            <input type="hidden" id="username-hidden" name="username" value="{{ old('username') }}" />
                            <div id="username-status" class="absolute top-1/2 end-3 -translate-y-1/2 text-sm"></div>
                        </div>
                        <p id="username-help" class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Username must be alpha-numeric, dashes or underscores, max 50 characters.</p>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-zinc-700">Email</label>
                        <input id="email" name="email" value="{{ old('email') }}" class="w-full border rounded-md px-3 py-2 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border-zinc-200 dark:border-zinc-700" required />
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-zinc-700">Password</label>
                        <input id="password" name="password" type="password" class="w-full border rounded-md px-3 py-2 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border-zinc-200 dark:border-zinc-700" required autocomplete="new-password" />
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Password must be at least 8 characters.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700">Roles</label>
                        <div class="mt-2 grid grid-cols-1 gap-2">
                            @foreach($roles as $role)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="roles[]" value="{{ $role->getKey() }}" class="h-4 w-4 text-indigo-600 border-gray-300 rounded bg-white dark:bg-zinc-900" />
                                    <span class="text-sm text-zinc-800 dark:text-zinc-200">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2">Select one or more roles for this user.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="btn-primary">Create user</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>

<script>
// Username availability check (debounced)
;(function(){
    const input = document.getElementById('username-display');
    const statusEl = document.getElementById('username-status');
    const submitBtn = document.querySelector('button[type="submit"].btn-primary');
    const hidden = document.getElementById('username-hidden');
    let timeout = null;

    function setStatus(state, suggestions = []){
        statusEl.textContent = '';
        statusEl.className = '';
        if(state === 'invalid'){
            statusEl.textContent = 'invalid';
            statusEl.classList.add('text-red-600');
            if(submitBtn) submitBtn.setAttribute('disabled','disabled');
        } else if(state === 'taken'){
            statusEl.textContent = 'taken';
            statusEl.classList.add('text-yellow-600');
            if(submitBtn) submitBtn.setAttribute('disabled','disabled');
        } else if(state === 'available'){
            statusEl.textContent = 'available';
            statusEl.classList.add('text-green-600');
            if(submitBtn) submitBtn.removeAttribute('disabled');
        } else {
            if(submitBtn) submitBtn.removeAttribute('disabled');
        }
    }

    if(!input) return;

    input.addEventListener('input', function(e){
        clearTimeout(timeout);
        const value = input.value.trim();
        if(value === ''){
            setStatus('');
            if(hidden) hidden.value = '';
            return;
        }

        timeout = setTimeout(async function(){
            try{
                const res = await fetch('/username/check?username=' + encodeURIComponent(value));
                if(!res.ok) return;
                const data = await res.json();
                setStatus(data.status, data.suggestions || []);
                if(hidden) hidden.value = value;
            }catch(err){
                // network failed: don't block submit
                setStatus('');
            }
        }, 350);
    });

    // Run initial check if there's a prefilled value (e.g., after validation error)
    if (input.value && input.value.trim() !== '') {
        input.dispatchEvent(new Event('input'));
    }

    // Sync hidden input on form submit in case availability check hasn't run
    const form = input.closest('form');
    if (form && hidden) {
        form.addEventListener('submit', function(){
            hidden.value = input.value.trim();
        });
    }
})();
</script>
