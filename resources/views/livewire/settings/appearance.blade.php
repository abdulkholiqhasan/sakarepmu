<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

@section('title', __('Settings') . ' / ' . __('Appearance'))

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
                        @if(auth()->check() && method_exists(auth()->user(), 'hasPermission') && auth()->user()->hasPermission('manage settings'))
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
                        @endif
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Appearance') }}</h2>
                                <p class="text-sm text-gray-500 dark:text-zinc-400">{{ __('Update the appearance settings for your account') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Content -->
                    <div class="p-6">
                        <div class="space-y-6">
                            <!-- Theme Selection -->
                            <div class="space-y-4">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-zinc-700 pb-2">Theme Preference</h3>
                                
                                <div class="bg-gray-50 dark:bg-zinc-800/30 rounded-lg p-4 border border-gray-200 dark:border-zinc-700">
                                    <p class="text-sm text-gray-600 dark:text-zinc-400 mb-4">
                                        Choose how the interface looks and feels across all your devices. Your theme preference will be saved automatically.
                                    </p>
                                    
                                    <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" class="w-full max-w-md">
                                        <flux:radio value="light" icon="sun" class="flex-1">{{ __('Light') }}</flux:radio>
                                        <flux:radio value="dark" icon="moon" class="flex-1">{{ __('Dark') }}</flux:radio>
                                        <flux:radio value="system" icon="computer-desktop" class="flex-1">{{ __('System') }}</flux:radio>
                                    </flux:radio.group>
                                    
                                    <div class="mt-4 space-y-2 text-xs text-gray-500 dark:text-zinc-400">
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span><strong>Light:</strong> Use light theme</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-2 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                            </svg>
                                            <span><strong>Dark:</strong> Use dark theme</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <span><strong>System:</strong> Follow your device's theme setting</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
