<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $username = '';
    /**
     * Array of suggested available usernames when the requested username is taken.
     * Example: ['anjing-hub', 'anjing-jpg', 'anjing702']
     */
    public array $usernameSuggestions = [];
    // checking | available | taken | invalid | ''
    public string $usernameStatus = '';
    // email status: '' | 'invalid'
    public string $emailStatus = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Page title forwarded to the layout so head can render "Site Title - Page Title".
    public string $title = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        // normalize username to lowercase before validating/saving
        $this->username = \Illuminate\Support\Str::lower($this->username);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Extra: ensure the email domain appears to accept mail (has MX record).
        // Skip this check in the testing environment to avoid CI/network dependency.
        if (! app()->environment('testing')) {
            $email = $validated['email'] ?? '';
            $domain = str_contains($email, '@') ? explode('@', $email, 2)[1] : null;
            if (! $domain || ! checkdnsrr($domain, 'MX')) {
                // Return a validation error for the email field
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => __('The email address does not appear to be a deliverable address.'),
                ]);
            }
        }

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Realtime username availability check using direct DB query.
     */
    public function checkUsername(): void
    {
        // log entry for debugging
        \Illuminate\Support\Facades\Log::debug('checkUsername called (before normalize)', ['username' => $this->username]);

        // normalize before validation so unique check uses lowercase
        $this->username = \Illuminate\Support\Str::lower($this->username);

        // if username is empty after trimming, clear status and stop
        if (trim($this->username) === '') {
            $this->usernameStatus = '';
            return;
        }

        // Validate format first
        $validator = \Illuminate\Support\Facades\Validator::make([
            'username' => $this->username,
        ], [
            'username' => ['required', 'string', 'max:50', 'alpha_dash'],
        ]);

        if ($validator->fails()) {
            $this->usernameStatus = 'invalid';
            \Illuminate\Support\Facades\Log::debug('checkUsername result: invalid', ['username' => $this->username]);
            return;
        }

        // Check DB
        $exists = User::where('username', $this->username)->exists();
        $this->usernameStatus = $exists ? 'taken' : 'available';

        // Build suggestions when the username is taken. Prefer hyphenated suffixes, then numeric suffixes.
        $this->usernameSuggestions = [];
        if ($exists) {
            $suffixes = ['hub', 'jpg', '702', 'app', 'xyz'];
            foreach ($suffixes as $s) {
                $candidate = preg_match('/^\d+$/', $s) ? $this->username . $s : ($this->username . '-' . $s);
                if (!User::where('username', $candidate)->exists()) {
                    $this->usernameSuggestions[] = $candidate;
                }
                if (count($this->usernameSuggestions) >= 3) break;
            }

            // Fallback: append incremental numbers until we have 3 suggestions or reach reasonable limit
            $i = 1;
            while (count($this->usernameSuggestions) < 3 && $i <= 50) {
                $candidate = $this->username . $i;
                if (!User::where('username', $candidate)->exists()) {
                    $this->usernameSuggestions[] = $candidate;
                }
                $i++;
            }
        }

        if (! $exists) {
            $this->usernameSuggestions = [];
        }

        \Illuminate\Support\Facades\Log::debug('checkUsername result', ['username' => $this->username, 'exists' => $exists, 'status' => $this->usernameStatus, 'suggestions' => $this->usernameSuggestions]);

        // We rely on Livewire re-rendering the component and updating the server-rendered
        // availability block (which carries a data attribute). Client-side Alpine watches
        // that attribute via MutationObserver to pick up the new status. This avoids
        // depending on emit/dispatchBrowserEvent methods that aren't available here.
    }

    /**
     * Realtime email deliverability check (non-networking in testing).
     */
    public function checkEmail(): void
    {
        $this->email = trim($this->email ?? '');
        if ($this->email === '') {
            $this->emailStatus = '';
            return;
        }

        // Basic format check
        $validator = \Illuminate\Support\Facades\Validator::make([
            'email' => $this->email,
        ], [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            $this->emailStatus = 'invalid';
            return;
        }

        // If not in testing environment, perform domain MX check.
        if (! app()->environment('testing')) {
            $domain = str_contains($this->email, '@') ? explode('@', $this->email, 2)[1] : null;
            if (! $domain || ! checkdnsrr($domain, 'MX')) {
                $this->emailStatus = 'invalid';
                return;
            }
        }

        // Valid/deliverable (we do not expose 'available'/'taken' for email here)
        $this->emailStatus = '';
    }

    public function mount(): void
    {
        $this->title = __('Create an account');
    }
}; ?>

<?php $title = __('Create an account'); $usernameStatus = $usernameStatus ?? ''; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="register" class="flex flex-col gap-6">
        <!-- Dummy hidden credentials to appease browser password managers and avoid their tooltip overlapping the real inputs -->
        <div aria-hidden="true" tabindex="-1" class="sr-only">
            <input type="text" name="dummy-username" autocomplete="username" />
            <input type="password" name="dummy-password" autocomplete="new-password" />
        </div>
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Full name')"
        />

        <!-- Username (placed after name as requested) -->
        <div id="username-wrapper" class="relative">
            <flux:input
                wire:model="username"
                :label="__('Username')"
                type="text"
                required
                autocomplete="off"
                autocorrect="off"
                spellcheck="false"
                data-lpignore="true"
                wire:input.debounce.250ms="checkUsername"
                :placeholder="__('Username')"
            />

            <!-- Inline availability container: JS in head partial will populate this when available -->
            <div class="lw-inline-availability mt-2 text-sm" aria-live="polite" wire:ignore></div>
        </div>

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirm password')"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button
                type="submit"
                variant="primary"
                class="w-full"
                data-test="register-user-button"
                <?php echo ($usernameStatus !== 'available' || $emailStatus === 'invalid') ? 'disabled="disabled"' : ''; ?>
            >
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('Already have an account?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
