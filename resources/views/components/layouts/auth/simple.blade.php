<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @php
            $sectionTitle = trim($__env->yieldContent('title'));
            $pageTitle = $sectionTitle !== '' ? $sectionTitle : ($title ?? null);

            if (! $pageTitle) {
                if (request()->routeIs('login') || request()->is('login')) {
                    $pageTitle = __('Log in');
                } elseif (request()->routeIs('register') || request()->is('register')) {
                    $pageTitle = __('Create an account');
                } elseif (request()->routeIs('password.request')) {
                    $pageTitle = __('Forgot password');
                } elseif (request()->routeIs('password.reset')) {
                    $pageTitle = __('Reset password');
                } elseif (request()->routeIs('password.confirm')) {
                    $pageTitle = __('Confirm password');
                } elseif (request()->routeIs('verification.notice')) {
                    $pageTitle = __('Verify email');
                }
            }

        @endphp
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
