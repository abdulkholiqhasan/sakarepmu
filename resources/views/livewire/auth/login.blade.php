<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    // Accept either email or username in this field. Validation performed as string;
    // we detect email format at runtime when attempting authentication.
    #[Validate('required|string')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    // Page title forwarded to the layout so head can render "Site Title - Page Title".
    public string $title = '';

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();


        $this->ensureIsNotRateLimited();

        $login = trim($this->email);

        $attempted = false;

        // If input looks like an email, try email first
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $attempted = Auth::attempt(['email' => $login, 'password' => $this->password], $this->remember);
        } else {
            // Normalize username to lowercase for lookup
            $username = Str::lower($login);
            $attempted = Auth::attempt(['username' => $username, 'password' => $this->password], $this->remember);

            // If username attempt failed, fall back to email (in case user entered email without @)
            if (! $attempted) {
                $attempted = Auth::attempt(['email' => $login, 'password' => $this->password], $this->remember);
            }
        }

        if (! $attempted) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }


        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
    return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
    public function mount(): void
    {
        $this->title = __('Log in');
    }
}; ?>

<?php $title = __('Log in'); ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email or username and password below')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="login" class="flex flex-col gap-6">
        <!-- Email or Username -->
        <flux:input
            wire:model="email"
            :label="__('Email or username')"
            type="text"
            required
            autofocus
            autocomplete="username"
            placeholder="Email or username"
        />

        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            @if (Route::has('password.request'))
                <flux:link class="absolute end-0 top-0 text-sm" :href="route('password.request')" wire:navigate>
                    {{ __('Forgot your password?') }}
                </flux:link>
            @endif
        </div>

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" :label="__('Remember me')" />

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                {{ __('Log in') }}
            </flux:button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Don\'t have an account?') }}</span>
            <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
        </div>
    @endif
</div>
