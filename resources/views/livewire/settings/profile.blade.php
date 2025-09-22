<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $username = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->username = Auth::user()->username;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        // Base rules always applied
        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        // Only validate email if the user is changing it (case-insensitive)
        if (strtolower($this->email) !== strtolower($user->email ?? '')) {
            $rules['email'] = [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ];
        }

        $validated = $this->validate($rules);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // If the email was changed, ensure the domain accepts mail (MX record).
        if (! empty($validated['email'] ?? '') && ! app()->environment('testing')) {
            $email = $validated['email'];
            $domain = str_contains($email, '@') ? explode('@', $email, 2)[1] : null;
            if (! $domain || ! checkdnsrr($domain, 'MX')) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => __('The email address does not appear to be a deliverable address.'),
                ]);
            }
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

@section('title', __('Settings') . ' / ' . __('Profile'))

<div class="bg-white dark:bg-zinc-900 min-h-screen">
    <!-- Admin-style header -->
    <div class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-700 px-4 sm:px-6 py-3 sm:py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Settings</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Manage your profile and account settings</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-start gap-8 max-lg:flex-col">
            <!-- Sidebar Navigation -->
            <div class="w-full lg:w-64 flex-shrink-0">
                <nav class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-lg p-1">
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('profile.edit') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' }}">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ __('Profile') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('password.edit') }}" 
                               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('password.edit') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' }}">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                {{ __('Password') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('general.edit') }}" 
                               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('general.edit') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' }}">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ __('General') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('appearance.edit') }}" 
                               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('appearance.edit') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800' }}">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                                </svg>
                                {{ __('Appearance') }}
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1 min-w-0">
                <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-lg shadow-sm overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Profile') }}</h2>
                                <p class="text-sm text-gray-500 dark:text-zinc-400">{{ __('Update your name and email address') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Content -->
                    <div class="p-6">
                        <form wire:submit="updateProfileInformation" class="space-y-6">
                            <!-- Username Section -->
                            <div class="bg-gray-50 dark:bg-zinc-800/30 rounded-lg p-4 border border-gray-200 dark:border-zinc-700">
                                <div class="flex items-center mb-3">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-zinc-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <label class="text-sm font-medium text-gray-700 dark:text-zinc-300">{{ __('Username') }}</label>
                                    <span class="ml-2 text-xs bg-gray-200 dark:bg-zinc-700 text-gray-600 dark:text-zinc-400 px-2 py-0.5 rounded-full">Read Only</span>
                                </div>
                                <input 
                                    value="{{ auth()->user()->username }}" 
                                    type="text" 
                                    disabled 
                                    class="w-full px-3 py-2.5 border border-gray-200 dark:border-zinc-600 rounded-lg bg-gray-100 dark:bg-zinc-700 text-gray-500 dark:text-zinc-400 cursor-not-allowed text-sm"
                                />
                                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-2">Your username cannot be changed and is used for login purposes.</p>
                            </div>

                            <!-- Personal Information -->
                            <div class="space-y-4">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-zinc-700 pb-2">Personal Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Name Field -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                                            {{ __('Name') }}
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input 
                                                wire:model="name" 
                                                type="text" 
                                                required 
                                                autofocus 
                                                autocomplete="name"
                                                class="w-full px-3 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 transition-colors text-sm"
                                                placeholder="Enter your full name"
                                            />
                                        </div>
                                        @error('name')
                                            <p class="text-red-600 dark:text-red-400 mt-1 text-xs flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Email Field -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                                            {{ __('Email') }}
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input 
                                                wire:model="email" 
                                                type="email" 
                                                required 
                                                autocomplete="email"
                                                class="w-full px-3 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 transition-colors text-sm"
                                                placeholder="Enter your email address"
                                            />
                                        </div>
                                        @error('email')
                                            <p class="text-red-600 dark:text-red-400 mt-1 text-xs flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror

                                        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                                            <div class="mt-3 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                                                <div class="flex items-start">
                                                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <div class="flex-1">
                                                        <p class="text-sm text-amber-800 dark:text-amber-200 font-medium">
                                                            {{ __('Your email address is unverified.') }}
                                                        </p>
                                                        <button type="button" wire:click.prevent="resendVerificationNotification" class="mt-1 text-sm text-amber-800 dark:text-amber-200 underline hover:no-underline font-medium">
                                                            {{ __('Click here to re-send the verification email.') }}
                                                        </button>

                                                        @if (session('status') === 'verification-link-sent')
                                                            <p class="mt-2 text-sm font-medium text-green-600 dark:text-green-400 flex items-center">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                {{ __('A new verification link has been sent to your email address.') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-zinc-700">
                                <x-action-message on="profile-updated" class="text-sm text-green-600 dark:text-green-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('Saved.') }}
                                </x-action-message>
                                
                                <button 
                                    type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg text-sm transition-all duration-200 hover:shadow-md focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 flex items-center"
                                    data-test="update-profile-button"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="mt-8">
                    <livewire:settings.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</div>
