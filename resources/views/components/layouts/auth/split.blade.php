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
    <div class="relative grid grid-cols-1 h-dvh items-center justify-center px-8 sm:px-0 lg:max-w-none lg:flex lg:px-0">
        <div class="bg-muted relative hidden h-full flex-col p-10 text-white lg:flex lg:w-2/3 dark:border-e dark:border-neutral-800">
                <div class="absolute inset-0 bg-neutral-900"></div>
                <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium" wire:navigate>
                    <span class="flex h-10 w-10 items-center justify-center rounded-md">
                        <x-app-logo-icon class="me-2 h-7 fill-current text-white" />
                    </span>
                    {{ config('app.name', 'Laravel') }}
                </a>

                @php
                    [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                @endphp

                <div class="relative z-20 mt-auto">
                    <blockquote class="space-y-2 text-white">
                        <flux:heading size="lg" class="text-white">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                        <footer><flux:heading class="text-white">{{ trim($author) }}</flux:heading></footer>
                    </blockquote>
                </div>
            </div>
            <div class="w-full lg:p-8 lg:w-1/3">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>
                        <span class="flex h-9 w-9 items-center justify-center rounded-md">
                            <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                        </span>

                        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
