<?php $title = 'Edit User'; ?>
<x-layouts.app :title="$title ?? null">
    <div class="bg-white dark:bg-zinc-900 min-h-screen">
        <!-- Admin-style header -->
        <div class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-700 px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <h1 class="text-xl sm:text-2xl font-normal text-gray-900 dark:text-white">Edit User</h1>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <flux:button :href="route('users.index')" variant="ghost" class="text-xs sm:text-sm px-2 sm:px-3 py-1 sm:py-2">
                        <span class="hidden sm:inline">← Back to Users</span>
                        <span class="sm:hidden">← Back</span>
                    </flux:button>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 mx-4 sm:mx-6 mt-4">
                <div class="flex">
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">There were errors with your submission:</h3>
                        <div class="mt-2">
                            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('users.update', $user) }}" method="POST" class="flex flex-col lg:flex-row">
            @csrf
            @method('PUT')
            <!-- Main content area -->
            <div class="w-full lg:w-3/4 px-4 sm:px-6 py-6">
                
                <!-- User ID (read-only) -->
                <div class="mb-6 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">User ID</label>
                    <div class="text-sm text-gray-600 dark:text-zinc-400 font-mono">{{ $user->getKey() }}</div>
                </div>

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Full Name</label>
                    <input 
                        id="name" 
                        name="name" 
                        type="text" 
                        value="{{ old('name', $user->name) }}" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500" 
                        placeholder="Enter full name"
                        required 
                    />
                    @error('name') 
                        <p class="text-red-600 dark:text-red-400 mt-2 text-sm">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Email Address</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        value="{{ old('email', $user->email) }}" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500" 
                        placeholder="Enter email address"
                        required 
                    />
                    @error('email') 
                        <p class="text-red-600 dark:text-red-400 mt-2 text-sm">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Password</label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500" 
                        placeholder="Leave blank to keep current password"
                        autocomplete="new-password"
                    />
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-2">Leave blank to keep the existing password. Minimum 8 characters when provided.</p>
                    @error('password') 
                        <p class="text-red-600 dark:text-red-400 mt-2 text-sm">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-1/4 bg-gray-50 dark:bg-zinc-800 border-t lg:border-t-0 lg:border-l border-gray-200 dark:border-zinc-700 px-4 py-6 lg:overflow-y-auto">
                
                <!-- Save Box -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded mb-6">
                    <div class="bg-gray-50 dark:bg-zinc-800 px-4 py-3 border-b border-gray-200 dark:border-zinc-700">
                        <h3 class="font-medium text-sm text-gray-900 dark:text-white">Update User</h3>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-col gap-3">
                            <button 
                                type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-3 px-4 rounded text-sm transition-colors"
                            >
                                Update User
                            </button>
                            <a 
                                href="{{ route('users.index') }}" 
                                class="w-full bg-gray-100 hover:bg-gray-200 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-gray-700 dark:text-zinc-300 font-medium py-3 px-4 rounded text-sm transition-colors text-center"
                            >
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Roles -->
                @if($roles->isNotEmpty())
                <div class="bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded mb-6">
                    <div class="bg-gray-50 dark:bg-zinc-800 px-4 py-3 border-b border-gray-200 dark:border-zinc-700">
                        <h3 class="font-medium text-sm text-gray-900 dark:text-white">User Roles</h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            @foreach($roles as $role)
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="roles[]" 
                                        value="{{ $role->getKey() }}" 
                                        class="h-4 w-4 text-blue-600 border-gray-300 dark:border-zinc-600 rounded focus:ring-blue-500 bg-white dark:bg-zinc-900"
                                        {{ $user->hasRole($role) ? 'checked' : '' }}
                                    />
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $role->name }}</span>
                                        @if($role->guard_name)
                                            <span class="text-xs text-gray-500 dark:text-zinc-400 block">{{ $role->guard_name }}</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-3">Select one or more roles for this user.</p>
                        @error('roles') 
                            <p class="text-red-600 dark:text-red-400 mt-2 text-sm">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>
                @endif
            </div>
        </form>
    </div>
</x-layouts.app>
