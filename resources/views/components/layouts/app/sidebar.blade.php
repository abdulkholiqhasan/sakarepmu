<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @php
            $sectionTitle = trim($__env->yieldContent('title'));
            $pageTitle = $sectionTitle !== '' ? $sectionTitle : ($title ?? null);
        @endphp
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        {{-- Accessibility: quick skip link for keyboard users --}}
        <a href="#main-content" class="sr-only focus:not-sr-only px-4 py-2">Skip to content</a>
        <div class="flex min-h-screen">
            <div id="sidebar-wrapper" class="transition-all duration-200 ease-in-out">
                <flux:sidebar id="sidebar" role="navigation" aria-label="Primary sidebar" sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 h-full w-64 transition-all duration-200 ease-in-out">
            <flux:sidebar.toggle class="lg:hidden text-zinc-800 dark:text-white/80" icon="x-mark" />

            <a href="/" aria-label="Home" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            {{-- Hidden heading to label navigation for assistive tech --}}
            <h2 id="sidebar-navigation" class="sr-only">Main navigation</h2>

        <flux:navlist variant="outline">
            @php
            // Shared class for sidebar nav items to match Posts/Pages admin style
            $navItemClass = 'px-3 py-2 rounded-md text-sm transition-colors hover:bg-gray-100 dark:hover:bg-zinc-800';

            // Helper to resolve route or null and mark enabled/disabled
            $resolve = function($name) {
                if (!$name) return ['href' => '#', 'enabled' => false, 'route' => null];
                if (Route::has($name)) return ['href' => route($name), 'enabled' => true, 'route' => $name];
                return ['href' => '#', 'enabled' => false, 'route' => $name];
            };

            // Build menu items with enabled flag
            $mainGroups = [
                ['label' => 'Posts', 'icon' => 'book-open-text', 'items' => [
                    array_merge(['label' => 'Post List', 'icon' => 'bars-3'], $resolve('posts.index')),
                    array_merge(['label' => 'Add Post', 'icon' => 'plus'], $resolve('posts.create')),
                    array_merge(['label' => 'Categories', 'icon' => 'folder'], $resolve('categories.index')),
                    array_merge(['label' => 'Tags', 'icon' => 'tag'], $resolve('tags.index')),
                ]],
                ['label' => 'Pages', 'icon' => 'document-text', 'items' => [
                    array_merge(['label' => 'Page List', 'icon' => 'bars-3'], $resolve('pages.index')),
                    array_merge(['label' => 'Add Page', 'icon' => 'plus'], $resolve('pages.create')),
                    array_merge(['label' => 'Templates', 'icon' => 'layout-grid'], $resolve('pages.templates')),
                ]],
                ['label' => 'Media', 'icon' => 'photo', 'items' => [
                    array_merge(['label' => 'Media Library', 'icon' => 'photo'], $resolve('media.index')),
                    array_merge(['label' => 'Upload Media', 'icon' => 'cloud-arrow-up'], $resolve('media.create')),
                ]],
            ];

            $footerGroups = [
                ['label' => 'Appearance', 'icon' => 'paint-brush', 'items' => [
                    array_merge(['label' => 'Themes', 'icon' => 'paint-brush'], $resolve('appearance.themes')),
                    array_merge(['label' => 'Menus', 'icon' => 'bars-3'], $resolve('appearance.menus')),
                    array_merge(['label' => 'Widgets', 'icon' => 'puzzle-piece'], $resolve('appearance.widgets')),
                ]],
                ['label' => 'Manages', 'icon' => 'user-group', 'items' => [
                    array_merge(['label' => 'Users', 'icon' => 'user'], $resolve('users.index')),
                    array_merge(['label' => 'Roles', 'icon' => 'users'], $resolve('roles.index')),
                    array_merge(['label' => 'Permissions', 'icon' => 'key'], $resolve('permissions.index')),
                ]],
                ['label' => 'Settings', 'icon' => 'cog', 'items' => [
                    array_merge(['label' => 'Profile', 'icon' => 'user'], $resolve('profile.edit')),
                    array_merge(['label' => 'Password', 'icon' => 'key'], $resolve('password.edit')),
                    array_merge(['label' => 'General', 'icon' => 'globe-alt'], $resolve('general.edit')),
                    array_merge(['label' => 'Display', 'icon' => 'paint-brush'], $resolve('appearance.edit')),
                ]],
            ];
            @endphp
                    <flux:navlist.item class="sidebar-item {{ $navItemClass }}" icon="squares-2x2" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate data-label="Dashboard" aria-label="Dashboard">
                        <span class="sidebar-label">Dashboard</span>
                    </flux:navlist.item>

                    {{-- Render main groups (expanded + compact) from shared data --}}
                    @foreach($mainGroups as $group)
                        <div class="expanded-only">
                            <flux:sidebar.group heading="{{ $group['label'] }}" icon="{{ $group['icon'] }}" expandable :expanded="false" class="sidebar-item" data-label="{{ $group['label'] }}" aria-label="{{ $group['label'] }}">
                                @foreach($group['items'] as $item)
                                    @php
                                        $isEnabled = isset($item['enabled']) ? $item['enabled'] : (isset($item['href']) && $item['href'] !== '#');
                                        $itemClass = $navItemClass . ($isEnabled ? '' : ' opacity-50 cursor-not-allowed');
                                        $ariaAttrs = $isEnabled ? '' : 'aria-disabled="true" tabindex="-1"';
                                    @endphp
                                    @if($isEnabled)
                                        @php $isCurrent = isset($item['route']) && $item['route'] ? request()->routeIs($item['route']) : false; @endphp
                                        <flux:navlist.item class="{{ $itemClass }}" href="{{ $item['href'] }}" icon="{{ $item['icon'] }}" wire:navigate :current="$isCurrent">{{ $item['label'] }}</flux:navlist.item>
                                    @else
                                        {{-- Render non-interactive fallback for unavailable routes --}} 
                                        <flux:navlist.item class="{{ $itemClass }}" href="#" icon="{{ $item['icon'] }}" aria-disabled="true" tabindex="-1">{{ $item['label'] }}</flux:navlist.item>
                                    @endif
                                    @endforeach
                            </flux:sidebar.group>
                        </div>

                        <div class="compact-only compact-sidebar-group sidebar-item" data-label="{{ $group['label'] }}">
                            <flux:dropdown position="right" align="start">
                                <button slot="trigger" type="button" title="Open {{ strtolower($group['label']) }}" aria-haspopup="true" class="compact-trigger inline-flex items-center justify-center p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-400 hover:text-zinc-800 dark:text-white/60 dark:hover:text-white" aria-label="Open {{ $group['label'] }}" data-compact-target="{{ $group['label'] }}">
                                    <!-- SVG will be copied from the matching expanded group by syncCompactIcon() -->
                                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"></svg>
                                </button>

                                <flux:menu>
                                    <flux:menu.radio.group>
                                        @foreach($group['items'] as $item)
                                            @php
                                                $isEnabled = isset($item['enabled']) ? $item['enabled'] : (isset($item['href']) && $item['href'] !== '#');
                                                $isCurrent = isset($item['route']) && $item['route'] ? request()->routeIs($item['route']) : false;
                                            @endphp
                                            @if($isEnabled)
                                                <flux:menu.item href="{{ $item['href'] }}" wire:navigate icon="{{ $item['icon'] }}">{{ $item['label'] }}</flux:menu.item>
                                            @else
                                                <flux:menu.item href="#" icon="{{ $item['icon'] }}" class="opacity-50 cursor-not-allowed" aria-disabled="true" tabindex="-1">{{ $item['label'] }}</flux:menu.item>
                                            @endif
                                        @endforeach
                                    </flux:menu.radio.group>
                                </flux:menu>
                            </flux:dropdown>
                        </div>
                    @endforeach

            </flux:navlist>

            <flux:spacer />

            <!-- Footer menus grouped to reduce vertical spacing -->
                <div class="space-y-1">
                @foreach($footerGroups as $group)
                    <div class="expanded-only">
                        <flux:sidebar.group heading="{{ $group['label'] }}" icon="{{ $group['icon'] }}" expandable :expanded="false" class="sidebar-item" data-label="{{ $group['label'] }}" aria-label="{{ $group['label'] }}">
                            @foreach($group['items'] as $item)
                                @php
                                    $isEnabled = isset($item['enabled']) ? $item['enabled'] : (isset($item['href']) && $item['href'] !== '#');
                                    $itemClass = $navItemClass . ($isEnabled ? '' : ' opacity-50 cursor-not-allowed');
                                    $ariaAttrs = $isEnabled ? '' : 'aria-disabled="true" tabindex="-1"';
                                @endphp
                                @if($isEnabled)
                                    <flux:navlist.item class="{{ $itemClass }}" href="{{ $item['href'] }}" icon="{{ $item['icon'] }}" wire:navigate>{{ $item['label'] }}</flux:navlist.item>
                                @else
                                    <flux:navlist.item class="{{ $itemClass }}" href="#" icon="{{ $item['icon'] }}" aria-disabled="true" tabindex="-1">{{ $item['label'] }}</flux:navlist.item>
                                @endif
                            @endforeach
                        </flux:sidebar.group>
                    </div>
                @endforeach

                <!-- Compact-only footer grouped -->
                <div class="compact-only flex flex-col space-y-1">
                    @foreach($footerGroups as $group)
                        <div class="compact-sidebar-group sidebar-item" data-label="{{ $group['label'] }}">
                            <flux:dropdown position="right" align="start">
                                <button slot="trigger" type="button" title="Open {{ strtolower($group['label']) }}" aria-haspopup="true" class="compact-trigger inline-flex items-center justify-center p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-400 hover:text-zinc-800 dark:text-white/60 dark:hover:text-white" aria-label="Open {{ $group['label'] }}" data-compact-target="{{ $group['label'] }}">
                                    <!-- SVG will be copied from the matching expanded group by syncCompactIcon() -->
                                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"></svg>
                                </button>

                                <flux:menu>
                                    <flux:menu.radio.group>
                                        @foreach($group['items'] as $item)
                                            @php
                                                $isEnabled = isset($item['enabled']) ? $item['enabled'] : (isset($item['href']) && $item['href'] !== '#');
                                            @endphp
                                            @if($isEnabled)
                                                <flux:menu.item href="{{ $item['href'] }}" wire:navigate icon="{{ $item['icon'] }}">{{ $item['label'] }}</flux:menu.item>
                                            @else
                                                <flux:menu.item href="#" icon="{{ $item['icon'] }}" class="opacity-50 cursor-not-allowed" aria-disabled="true" tabindex="-1">{{ $item['label'] }}</flux:menu.item>
                                            @endif
                                        @endforeach
                                    </flux:menu.radio.group>
                                </flux:menu>
                            </flux:dropdown>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sidebar copyright/footer (moved to horizontal footer for consistency) -->

                </flux:sidebar>
            </div>
            <div class="flex-1 flex flex-col">
                <div class="w-full">
                    <!-- Horizontal Navbar (Desktop): minimize di kiri, profile di kanan -->
                    <nav class="hidden lg:flex items-center justify-between px-6 h-16 border-b border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 relative z-20">
                        <div>
                            <button id="sidebar-minimize-btn" type="button" title="Minimize sidebar" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-800 dark:text-white/80" aria-pressed="false">
                                <svg id="sidebar-minimize-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-all duration-200" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <!-- Modern hamburger (default) toggles to X when compact -->
                                    <!-- We keep the same path id so existing JS continues to work -->
                                    <path id="sidebar-minimize-path" fill-rule="evenodd" clip-rule="evenodd" d="M4 6.75C4 6.33579 4.33579 6 4.75 6H19.25C19.6642 6 20 6.33579 20 6.75C20 7.16421 19.6642 7.5 19.25 7.5H4.75C4.33579 7.5 4 7.16421 4 6.75ZM4 12C4 11.5858 4.33579 11.25 4.75 11.25H19.25C19.6642 11.25 20 11.5858 20 12C20 12.4142 19.6642 12.75 19.25 12.75H4.75C4.33579 12.75 4 12.4142 4 12ZM4.75 16.5C4.33579 16.5 4 16.8358 4 17.25C4 17.6642 4.33579 18 4.75 18H19.25C19.6642 18 20 17.6642 20 17.25C20 16.8358 19.6642 16.5 19.25 16.5H4.75Z" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center gap-3">
                            <!-- Appearance toggle (Desktop) -->
                            <div class="hidden lg:flex items-center">
                                <button id="appearance-toggle-btn" type="button" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors" aria-pressed="false" title="Toggle appearance">
                                    <svg id="appearance-toggle-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path id="appearance-toggle-path" stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                                    </svg>
                                    <span class="sr-only">Toggle appearance</span>
                                </button>
                            </div>

                            <!-- Profile Dropdown mirip sidebar bawah -->
                            <flux:dropdown position="bottom" align="end">
                                <flux:profile
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                    icon:trailing="chevrons-up-down"
                                />
                                <flux:menu class="w-[220px]">
                                    <flux:menu.radio.group>
                                        <div class="p-0 text-sm font-normal">
                                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                                        {{ auth()->user()->initials() }}
                                                    </span>
                                                </span>
                                                <div class="grid flex-1 text-start text-sm leading-tight">
                                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </flux:menu.radio.group>
                                    <flux:menu.separator />
                                    <flux:menu.radio.group>
                                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>Settings</flux:menu.item>
                                    </flux:menu.radio.group>
                                    <flux:menu.separator />
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                                            Log Out
                                        </flux:menu.item>
                                    </form>
                                </flux:menu>
                            </flux:dropdown>
                        </div>
                    </nav>
                </div>

                <!-- Mobile User Menu -->
                <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden text-zinc-800 dark:text-white/80" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>


                <div id="main-content" tabindex="-1" class="flex-1">
                    {{ $slot }}
                </div>

                <!-- Horizontal footer (improved for consistency with theme) -->
                <footer class="w-full border-t border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
                    <div class="max-w-7xl mx-auto px-6 py-3 flex flex-col sm:flex-row items-center sm:items-center justify-between gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                        <div class="flex items-center gap-3 text-xs sm:text-sm">
                            <span class="text-zinc-500 dark:text-zinc-500">&copy; {{ date('Y') }}</span>
                            <span class="font-medium truncate">{{ config('app.name') }}</span>
                            <span class="text-zinc-400">·</span>
                            <span class="text-xs text-zinc-500">All rights reserved</span>
                        </div>

                        <div class="flex items-center gap-4">
                            <a href="/privacy" class="text-zinc-600 dark:text-zinc-300 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500 rounded px-1 py-0.5">Privacy</a>
                            <a href="/terms" class="text-zinc-600 dark:text-zinc-300 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500 rounded px-1 py-0.5">Terms</a>
                        </div>
                    </div>
                </footer>
            </div> <!-- end flex-1 -->
        </div> <!-- end flex -->

    @stack('scripts')
    @fluxScripts
        <!-- Sidebar styles moved to `resources/css/app.css` under @layer components { /* Sidebar compact ... */ } -->
        <script>
        // Sidebar minimize/compact logic with accessibility and resilience to DOM updates
        (function() {
            const SIDEBAR_COMPACT_KEY = 'sidebar-compact';
            // guard to avoid double-initialization
            if (window.__sidebarCompactInit) return;
            window.__sidebarCompactInit = true;

            function queryElements() {
                return {
                    sidebarWrapper: document.getElementById('sidebar-wrapper'),
                    btn: document.getElementById('sidebar-minimize-btn'),
                    iconPath: document.getElementById('sidebar-minimize-path'),
                    sidebar: document.getElementById('sidebar')
                };
            }

            function applyCompactVisuals({sidebarWrapper, sidebar, iconPath, btn}, compact) {
                if (!sidebarWrapper || !sidebar || !iconPath) return;
                    if (compact) {
                    sidebarWrapper.classList.add('sidebar-compact');
                    sidebarWrapper.setAttribute('data-compact', '1');
                    // enforce width with important to reduce chance of being overridden
                    sidebar.style.setProperty('width', '4rem', 'important');
                    sidebar.style.setProperty('min-width', '4rem', 'important');
                    sidebar.style.setProperty('max-width', '4rem', 'important');
                    // Keep hamburger icon even when compact
                    iconPath.setAttribute('d', 'M4 6.75C4 6.33579 4.33579 6 4.75 6H19.25C19.6642 6 20 6.33579 20 6.75C20 7.16421 19.6642 7.5 19.25 7.5H4.75C4.33579 7.5 4 7.16421 4 6.75ZM4 12C4 11.5858 4.33579 11.25 4.75 11.25H19.25C19.6642 11.25 20 11.5858 20 12C20 12.4142 19.6642 12.75 19.25 12.75H4.75C4.33579 12.75 4 12.4142 4 12ZM4.75 16.5C4.33579 16.5 4 16.8358 4 17.25C4 17.6642 4.33579 18 4.75 18H19.25C19.6642 18 20 17.6642 20 17.25C20 16.8358 19.6642 16.5 19.25 16.5H4.75Z');
                    if (btn) btn.setAttribute('aria-pressed', 'true');
                } else {
                    sidebarWrapper.classList.remove('sidebar-compact');
                    sidebarWrapper.removeAttribute('data-compact');
                    // restore to default width (use important to override inline forced compact)
                    sidebar.style.setProperty('width', '16rem', 'important');
                    sidebar.style.removeProperty('min-width');
                    sidebar.style.removeProperty('max-width');
                    // Restore hamburger icon when not compact
                    iconPath.setAttribute('d', 'M4 6.75C4 6.33579 4.33579 6 4.75 6H19.25C19.6642 6 20 6.33579 20 6.75C20 7.16421 19.6642 7.5 19.25 7.5H4.75C4.33579 7.5 4 7.16421 4 6.75ZM4 12C4 11.5858 4.33579 11.25 4.75 11.25H19.25C19.6642 11.25 20 11.5858 20 12C20 12.4142 19.6642 12.75 19.25 12.75H4.75C4.33579 12.75 4 12.4142 4 12ZM4.75 16.5C4.33579 16.5 4 16.8358 4 17.25C4 17.6642 4.33579 18 4.75 18H19.25C19.6642 18 20 17.6642 20 17.25C20 16.8358 19.6642 16.5 19.25 16.5H4.75Z');
                    if (btn) btn.setAttribute('aria-pressed', 'false');
                }
            }

            function setCompactState(compact) {
                try { localStorage.setItem(SIDEBAR_COMPACT_KEY, compact ? '1' : '0'); } catch (e) {}
                // Mirror state on <body> so CSS can be applied globally and survive partial re-renders
                if (compact) {
                    document.body.setAttribute('data-sidebar-compact', '1');
                } else {
                    document.body.removeAttribute('data-sidebar-compact');
                }
                applyCompactVisuals(queryElements(), compact);
            }

            function getSavedCompact() {
                try {
                    return localStorage.getItem(SIDEBAR_COMPACT_KEY) === '1';
                } catch (e) {
                    return false;
                }
            }

            function enhanceNavItems() {
                document.querySelectorAll('#sidebar [data-label]').forEach(function(el) {
                    if (!el.hasAttribute('tabindex')) el.setAttribute('tabindex', '0');
                });
            }

            function attachDelegatedHandlers() {
                // Avoid attaching multiple identical listeners
                if (!document.__sidebarClickHandler) {
                    document.__sidebarClickHandler = function(e) {
                        const target = e.target;
                        const btn = target.closest && target.closest('#sidebar-minimize-btn');
                        if (btn) {
                            e.stopPropagation();
                            const current = getSavedCompact();
                            setCompactState(!current);
                        }
                    };
                    document.addEventListener('click', document.__sidebarClickHandler, true);
                }

                if (!document.__sidebarKeydownHandler) {
                    document.__sidebarKeydownHandler = function(e) {
                        const target = e.target;
                        const btnKey = target.closest && target.closest('#sidebar-minimize-btn');
                        if (btnKey && (e.key === 'Enter' || e.key === ' ')) {
                            e.preventDefault();
                            e.stopPropagation();
                            const current = getSavedCompact();
                            setCompactState(!current);
                        }
                    };
                    document.addEventListener('keydown', document.__sidebarKeydownHandler, true);
                }
            }

            function initializeOnce() {
                const els = queryElements();
                const saved = getSavedCompact();

                // Ensure button has correct ARIA/role attributes (may be re-rendered)
                if (els.btn) {
                    els.btn.setAttribute('role', 'button');
                    els.btn.setAttribute('aria-label', 'Minimize sidebar');
                    // keydown on the button itself (in addition to delegated listener) to be safe
                    if (!els.btn.__keydownAttached) {
                        els.btn.addEventListener('keydown', function(e) {
                            if (e.key === 'Enter' || e.key === ' ') {
                                e.preventDefault();
                                const current = getSavedCompact();
                                setCompactState(!current);
                            }
                        });
                        els.btn.__keydownAttached = true;
                    }
                }

                // Attach delegated listeners (idempotent)
                attachDelegatedHandlers();

                // Apply initial visuals
                applyCompactVisuals(els, saved);
                enhanceNavItems();
            }

            // Run immediately if DOM already loaded, otherwise on DOMContentLoaded
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                // small timeout to allow elements to be present if scripts are executed before markup inserted
                setTimeout(initializeOnce, 0);
            } else {
                document.addEventListener('DOMContentLoaded', initializeOnce);
            }

            // The behavior is intentionally simple now: only user toggles change state.
            // Body attribute `data-sidebar-compact` and the CSS rules above ensure
            // the compact styling persists across partial DOM replacements.
        })();

        // If Livewire is present, re-apply visuals after Livewire updates (do not change saved state)
        if (window.Livewire && typeof window.Livewire.hook === 'function') {
            try {
                // Single reapply that reads persisted preference and enforces visuals
                function reapplySavedStateOnce() {
                    var saved = (function() { try { return localStorage.getItem('sidebar-compact') === '1'; } catch (e) { return false; } })();
                    var els = { sidebarWrapper: document.getElementById('sidebar-wrapper'), btn: document.getElementById('sidebar-minimize-btn'), iconPath: document.getElementById('sidebar-minimize-path'), sidebar: document.getElementById('sidebar') };
                    if (saved) {
                        if (els.sidebarWrapper) els.sidebarWrapper.classList.add('sidebar-compact');
                        if (document.body) document.body.setAttribute('data-sidebar-compact', '1');
                        if (els.sidebar) {
                            els.sidebar.style.setProperty('width', '4rem', 'important');
                            els.sidebar.style.setProperty('min-width', '4rem', 'important');
                            els.sidebar.style.setProperty('max-width', '4rem', 'important');
                        }
                        if (els.btn) els.btn.setAttribute('aria-pressed', 'true');
                        if (els.iconPath) els.iconPath.setAttribute('d', 'M4 6.75C4 6.33579 4.33579 6 4.75 6H19.25C19.6642 6 20 6.33579 20 6.75C20 7.16421 19.6642 7.5 19.25 7.5H4.75C4.33579 7.5 4 7.16421 4 6.75ZM4 12C4 11.5858 4.33579 11.25 4.75 11.25H19.25C19.6642 11.25 20 11.5858 20 12C20 12.4142 19.6642 12.75 19.25 12.75H4.75C4.33579 12.75 4 12.4142 4 12ZM4.75 16.5C4.33579 16.5 4 16.8358 4 17.25C4 17.6642 4.33579 18 4.75 18H19.25C19.6642 18 20 17.6642 20 17.25C20 16.8358 19.6642 16.5 19.25 16.5H4.75Z');
                    } else {
                        if (els.sidebarWrapper) els.sidebarWrapper.classList.remove('sidebar-compact');
                        if (document.body) document.body.removeAttribute('data-sidebar-compact');
                        if (els.sidebar) {
                            els.sidebar.style.setProperty('width', '16rem', 'important');
                            els.sidebar.style.removeProperty('min-width');
                            els.sidebar.style.removeProperty('max-width');
                        }
                        if (els.btn) els.btn.setAttribute('aria-pressed', 'false');
                        if (els.iconPath) els.iconPath.setAttribute('d', 'M4 6.75C4 6.33579 4.33579 6 4.75 6H19.25C19.6642 6 20 6.33579 20 6.75C20 7.16421 19.6642 7.5 19.25 7.5H4.75C4.33579 7.5 4 7.16421 4 6.75ZM4 12C4 11.5858 4.33579 11.25 4.75 11.25H19.25C19.6642 11.25 20 11.5858 20 12C20 12.4142 19.6642 12.75 19.25 12.75H4.75C4.33579 12.75 4 12.4142 4 12ZM4.75 16.5C4.33579 16.5 4 16.8358 4 17.25C4 17.6642 4.33579 18 4.75 18H19.25C19.6642 18 20 17.6642 20 17.25C20 16.8358 19.6642 16.5 19.25 16.5H4.75Z');
                    }
                }

                // Reapply repeatedly for a short window to win against any other scripts that may mutate
                // the sidebar during Livewire's navigation sequence.
                function reapplyUntilStable(retries, delay) {
                    var attempts = 0;
                    (function attempt() {
                        reapplySavedStateOnce();
                        attempts++;
                        if (attempts < retries) setTimeout(attempt, delay);
                    }());
                }

                window.Livewire.hook('message.processed', function() {
                    // schedule one immediate reapply then a short series of retries
                    // to ensure our visual state persists after Livewire injections.
                    setTimeout(function() {
                        reapplyUntilStable(6, 100);
                    }, 0);
                });
            } catch (e) {
                // no-op
            }
        }
        </script>
        <script>
        // Ensure compact/expanded-only elements toggle visibility even if CSS isn't applied yet
        (function() {
            function isCompact() {
                try { return localStorage.getItem('sidebar-compact') === '1'; } catch (e) { return document.body.hasAttribute('data-sidebar-compact'); }
            }

            function updateCompactFallback() {
                var compact = isCompact();
                // Elements we control in Blade
                var expanded = document.querySelectorAll('#sidebar .expanded-only');
                var compactOnly = document.querySelectorAll('#sidebar .compact-only');

                expanded.forEach(function(el){
                    try { el.style.display = compact ? 'none' : ''; } catch (e) {}
                });
                compactOnly.forEach(function(el){
                    try { el.style.display = compact ? '' : 'none'; } catch (e) {}
                });
                // Ensure the compact trigger shows the same icon as the expanded Settings group
                try { syncCompactIcon(); } catch (e) {}
            }

            function syncCompactIcon() {
                try {
                    // For each compact trigger that declares a `data-compact-target`, try to copy
                    // the SVG from the expanded group that has the matching `data-label`.
                    var triggers = document.querySelectorAll('#sidebar .compact-only .compact-trigger[data-compact-target]');
                    if (!triggers || !triggers.length) return;

                    // Helper to locate the svg inside an expanded block by label
                    function findExpandedSvg(label) {
                        // data-label is on the flux:sidebar.group element inside the .expanded-only wrapper,
                        // so search for that element anywhere inside the expanded-only container.
                        var selectorBase = '#sidebar .expanded-only [data-label="' + CSS.escape(label) + '"]';
                        var sel = selectorBase + ' [data-slot="icon"] svg, ' + selectorBase + ' [data-flux-icon] svg, ' + selectorBase + ' svg, ' + selectorBase + ' [data-flux-icon]';
                        return document.querySelector(sel);
                    }

                    triggers.forEach(function(trigger) {
                        try {
                            var target = trigger.getAttribute('data-compact-target');
                            if (!target) return;

                            var src = findExpandedSvg(target);

                            // retry a few times if not yet rendered by Flux
                            var attempts = 0;
                            function attemptCopy() {
                                try {
                                    attempts++;
                                    if (!src) src = findExpandedSvg(target);
                                    if (src) {
                                        var srcMarkup = src.outerHTML.trim();
                                        var existingSvg = trigger.querySelector('svg');
                                        var existingMarkup = existingSvg ? existingSvg.outerHTML.trim() : '';
                                        if (existingMarkup !== srcMarkup) {
                                            trigger.innerHTML = srcMarkup;
                                        }
                                        return;
                                    }
                                } catch (e) {
                                    // ignore per-item
                                }
                                if (attempts < 6) setTimeout(attemptCopy, 80);
                            }

                            attemptCopy();
                        } catch (e) {
                            // ignore per trigger
                        }
                    });
                } catch (e) {
                    // ignore
                }
            }

            // Run once immediately and after relevant mutations
            updateCompactFallback();

            // Observe body attribute changes and sidebar wrapper mutations
            try {
                var obs = new MutationObserver(function(mutations){
                    var relevant = false;
                    mutations.forEach(function(m){
                        if (m.type === 'attributes' && m.target === document.body && m.attributeName === 'data-sidebar-compact') relevant = true;
                        if (m.type === 'attributes' && (m.target && (m.target.id === 'sidebar' || m.target.id === 'sidebar-wrapper'))) relevant = true;
                        if (m.type === 'childList') relevant = true;
                    });
                    if (relevant) updateCompactFallback();
                });

                obs.observe(document.body, { attributes: true, attributeFilter: ['data-sidebar-compact'] });
                var wrapper = document.getElementById('sidebar-wrapper') || document.getElementById('sidebar');
                if (wrapper) obs.observe(wrapper, { attributes: true, childList: true, subtree: true });
            } catch (e) {
                // ignore
            }
            // Ensure icon sync on initial load
            try { syncCompactIcon(); } catch (e) {}
        })();
        </script>
        <script>
        // ENFORCE: MutationObserver that immediately reapplies saved compact state when other scripts
        // (e.g. Livewire's injected script) mutate the sidebar. It disconnects while enforcing to
        // avoid recursion.
        (function() {
            function getSavedCompactSafe() {
                try { return localStorage.getItem('sidebar-compact') === '1'; } catch (e) { return false; }
            }

            function enforceSavedStateNow() {
                var saved = getSavedCompactSafe();
                var sidebar = document.getElementById('sidebar');
                var wrapper = document.getElementById('sidebar-wrapper');
                var btn = document.getElementById('sidebar-minimize-btn');
                var iconPath = document.getElementById('sidebar-minimize-path');

                if (saved) {
                    if (wrapper && !wrapper.classList.contains('sidebar-compact')) wrapper.classList.add('sidebar-compact');
                    if (document.body && document.body.getAttribute('data-sidebar-compact') !== '1') document.body.setAttribute('data-sidebar-compact', '1');
                    if (sidebar) {
                        var width = sidebar.style.getPropertyValue('width') || '';
                        var priority = sidebar.style.getPropertyPriority('width');
                        // Only set if it's not already the desired value with important
                        if (width.trim() !== '4rem' || priority !== 'important') {
                            sidebar.style.setProperty('width', '4rem', 'important');
                            sidebar.style.setProperty('min-width', '4rem', 'important');
                            sidebar.style.setProperty('max-width', '4rem', 'important');
                        }
                    }
                    if (btn) btn.setAttribute('aria-pressed', 'true');
                    if (iconPath) iconPath.setAttribute('d', 'M4 6.75C4 6.33579 4.33579 6 4.75 6H19.25C19.6642 6 20 6.33579 20 6.75C20 7.16421 19.6642 7.5 19.25 7.5H4.75C4.33579 7.5 4 7.16421 4 6.75ZM4 12C4 11.5858 4.33579 11.25 4.75 11.25H19.25C19.6642 11.25 20 11.5858 20 12C20 12.4142 19.6642 12.75 19.25 12.75H4.75C4.33579 12.75 4 12.4142 4 12ZM4.75 16.5C4.33579 16.5 4 16.8358 4 17.25C4 17.6642 4.33579 18 4.75 18H19.25C19.6642 18 20 17.6642 20 17.25C20 16.8358 19.6642 16.5 19.25 16.5H4.75Z');
                } else {
                    if (wrapper && wrapper.classList.contains('sidebar-compact')) wrapper.classList.remove('sidebar-compact');
                    if (document.body && document.body.hasAttribute('data-sidebar-compact')) document.body.removeAttribute('data-sidebar-compact');
                    if (sidebar) {
                        var width2 = sidebar.style.getPropertyValue('width') || '';
                        var priority2 = sidebar.style.getPropertyPriority('width');
                        if (width2.trim() !== '16rem' || priority2 !== 'important') {
                            sidebar.style.setProperty('width', '16rem', 'important');
                            sidebar.style.removeProperty('min-width');
                            sidebar.style.removeProperty('max-width');
                        }
                    }
                    if (btn) btn.setAttribute('aria-pressed', 'false');
                    if (iconPath) iconPath.setAttribute('d', 'M4 6.75C4 6.33579 4.33579 6 4.75 6H19.25C19.6642 6 20 6.33579 20 6.75C20 7.16421 19.6642 7.5 19.25 7.5H4.75C4.33579 7.5 4 7.16421 4 6.75ZM4 12C4 11.5858 4.33579 11.25 4.75 11.25H19.25C19.6642 11.25 20 11.5858 20 12C20 12.4142 19.6642 12.75 19.25 12.75H4.75C4.33579 12.75 4 12.4142 4 12ZM4.75 16.5C4.33579 16.5 4 16.8358 4 17.25C4 17.6642 4.33579 18 4.75 18H19.25C19.6642 18 20 17.6642 20 17.25C20 16.8358 19.6642 16.5 19.25 16.5H4.75Z');
                }
            }

            try {
                var enforceObserver = new MutationObserver(function(mutations) {
                    var relevant = false;
                    mutations.forEach(function(m) {
                        if (m.type === 'attributes') {
                            var t = m.target;
                            if (t === document.body && m.attributeName === 'data-sidebar-compact') relevant = true;
                            if (t && (t.id === 'sidebar' || t.id === 'sidebar-wrapper') && (m.attributeName === 'style' || m.attributeName === 'class')) relevant = true;
                        }
                        if (m.type === 'childList') {
                            if (m.addedNodes && m.addedNodes.length) {
                                m.addedNodes.forEach(function(n) {
                                    if (n && n.id && (n.id === 'sidebar' || n.id === 'sidebar-wrapper')) relevant = true;
                                });
                            }
                        }
                    });

                    if (relevant) {
                        // disconnect to avoid reacting to our own changes
                        enforceObserver.disconnect();
                        try { enforceSavedStateNow(); } catch (e) { /* ignore */ }
                        // reattach after a tick
                        setTimeout(function() {
                            try {
                                enforceObserver.observe(document.body, { attributes: true, subtree: true, attributeFilter: ['data-sidebar-compact'], childList: true });
                                enforceObserver.observe(document.getElementById('sidebar-wrapper') || document.body, { attributes: true, subtree: true, attributeFilter: ['class', 'style'], childList: true });
                            } catch (e) {
                                // ignore
                            }
                        }, 0);
                    }
                });

                // Start observing
                enforceObserver.observe(document.body, { attributes: true, subtree: true, attributeFilter: ['data-sidebar-compact'], childList: true });
                enforceObserver.observe(document.getElementById('sidebar-wrapper') || document.body, { attributes: true, subtree: true, attributeFilter: ['class', 'style'], childList: true });
            } catch (e) {
                // ignore
            }
        })();

        // (debug observer removed)
        </script>
        <script>
        // Appearance toggle wiring: robustly sync with Flux and settings UI
        (function() {
            var KEY = 'flux.appearance';

            function readSaved() {
                try { return window.localStorage.getItem(KEY) || 'system'; } catch (e) { return 'system'; }
            }

            function writeSaved(val) {
                try {
                    if (!val || val === 'system') window.localStorage.removeItem(KEY);
                    else window.localStorage.setItem(KEY, val);
                } catch (e) {}
            }

            function applyAppearance(val) {
                try {
                    if (window.Flux && typeof window.Flux.applyAppearance === 'function') {
                        // Use Flux helper (will set DOM classes and localStorage appropriately)
                        window.Flux.applyAppearance(val);
                        // Ensure reactive property is set so Alpine/Settings UI sees it
                        try { if (window.Flux && 'appearance' in window.Flux) window.Flux.appearance = val; } catch (e) {}
                    } else {
                        // Fallback behavior when Flux not available yet
                        if (val === 'dark') document.documentElement.classList.add('dark');
                        else if (val === 'light') document.documentElement.classList.remove('dark');
                        else document.documentElement.classList.remove('dark');
                    }
                } catch (e) { /* ignore */ }
            }

            function updateUI(val) {
                var btn = document.getElementById('appearance-toggle-btn');
                if (!btn) return;
                btn.setAttribute('aria-pressed', val === 'dark' ? 'true' : 'false');
                var path = document.getElementById('appearance-toggle-path');
                if (path) {
                    if (val === 'dark') path.setAttribute('d', 'M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z');
                    else if (val === 'light') path.setAttribute('d', 'M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z');
                    else path.setAttribute('d', 'M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25');
                }
            }

            function setFluxAppearanceSafe(val) {
                try {
                    if (window.Flux) {
                        // prefer setter if available
                        try { window.Flux.appearance = val; } catch (e) {
                            // fallback: if Flux has method to set, call it
                            if (typeof window.Flux.applyAppearance === 'function') window.Flux.applyAppearance(val);
                        }
                    }
                } catch (e) {}
            }

            function toggle() {
                var current = readSaved();
                var next;
                // cycle: system -> dark -> light -> system
                if (current === 'system') next = 'dark';
                else if (current === 'dark') next = 'light';
                else next = 'system';

                writeSaved(next);
                // apply to Flux (if present) and set reactive property
                applyAppearance(next);
                setFluxAppearanceSafe(next);
                // Broadcast change so other listeners (e.g. settings page) can react
                try { document.dispatchEvent(new CustomEvent('flux:appearance.changed', { detail: { appearance: next } })); } catch (e) {}
                updateUI(next);
            }

            function init() {
                var val = readSaved();
                applyAppearance(val);
                setFluxAppearanceSafe(val);
                updateUI(val);

                var btn = document.getElementById('appearance-toggle-btn');
                if (!btn) return;
                if (!btn.__apToggleAttached) {
                    btn.addEventListener('click', function(e) { e.preventDefault(); toggle(); });
                    btn.__apToggleAttached = true;
                }

                // If Livewire updates the DOM, reapply UI state
                if (window.Livewire && typeof window.Livewire.hook === 'function') {
                    try {
                        window.Livewire.hook('message.processed', function() {
                            setTimeout(function() {
                                var v = readSaved();
                                applyAppearance(v);
                                setFluxAppearanceSafe(v);
                                updateUI(v);
                            }, 0);
                        });
                    } catch (e) {}
                }

                // Listen for external appearance change broadcasts
                document.addEventListener('flux:appearance.changed', function(e) {
                    try {
                        var v = e && e.detail && e.detail.appearance ? e.detail.appearance : readSaved();
                        setFluxAppearanceSafe(v);
                        applyAppearance(v);
                        updateUI(v);
                        // also update any radio inputs (settings UI) that match the value
                        try {
                            var radios = document.querySelectorAll('input[type=radio][value="' + v + '"]');
                            radios.forEach(function(r) {
                                try {
                                    r.checked = true;
                                    r.dispatchEvent(new Event('change', { bubbles: true }));
                                } catch (er) {}
                            });
                        } catch (er) {}
                    } catch (err) {}
                });

                // If Flux loads after this script, ensure Flux.appearance is synced once it appears.
                if (!window.Flux) {
                    var attempts = 0;
                    var poll = setInterval(function() {
                        attempts++;
                        if (window.Flux) {
                            try { var v = readSaved(); window.Flux.appearance = v; if (typeof window.Flux.applyAppearance === 'function') window.Flux.applyAppearance(v); } catch (e) {}
                            clearInterval(poll);
                        } else if (attempts > 50) { // ~5s
                            clearInterval(poll);
                        }
                    }, 100);
                }
            }

            if (document.readyState === 'complete' || document.readyState === 'interactive') init();
            else document.addEventListener('DOMContentLoaded', init);
        })();
        </script>
    </body>
</html>
