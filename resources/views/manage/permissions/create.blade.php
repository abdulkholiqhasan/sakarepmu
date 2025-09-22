<?php $title = 'Create Permission'; ?>
<x-layouts.app :title="$title ?? null">
    <div class="bg-white dark:bg-zinc-900 min-h-screen">
        <!-- Admin-style header -->
        <div class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-700 px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <h1 class="text-xl sm:text-2xl font-normal text-gray-900 dark:text-white">Create Permission</h1>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <flux:button :href="route('permissions.index')" variant="ghost" class="text-xs sm:text-sm px-2 sm:px-3 py-1 sm:py-2">
                        <span class="hidden sm:inline">← Back to Permissions</span>
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

        <form action="{{ route('permissions.store') }}" method="POST" class="flex flex-col lg:flex-row">
            @csrf
            <!-- Main content area -->
            <div class="w-full lg:w-3/4 px-4 sm:px-6 py-6">
                
                <!-- Permission Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Permission Name</label>
                    <input 
                        id="name" 
                        name="name" 
                        type="text" 
                        value="{{ old('name') }}" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500" 
                        placeholder="Enter permission name (e.g., edit users, view reports)"
                        required 
                    />
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-2">Use descriptive names like 'edit users', 'view reports', 'manage settings'.</p>
                    @error('name') 
                        <p class="text-red-600 dark:text-red-400 mt-2 text-sm">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Guard Name -->
                <div class="mb-6">
                    <label for="guard_name" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Guard Name</label>
                    <input 
                        id="guard_name" 
                        name="guard_name" 
                        type="text" 
                        value="{{ old('guard_name', 'web') }}" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-zinc-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500" 
                        placeholder="Enter guard name (e.g., web)"
                    />
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-2">The guard defines which authentication system this permission applies to. Leave as 'web' for most cases.</p>
                    @error('guard_name') 
                        <p class="text-red-600 dark:text-red-400 mt-2 text-sm">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-1/4 bg-gray-50 dark:bg-zinc-800 border-t lg:border-t-0 lg:border-l border-gray-200 dark:border-zinc-700 px-4 py-6 lg:overflow-y-auto">
                
                <!-- Save Box -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded mb-6">
                    <div class="bg-gray-50 dark:bg-zinc-800 px-4 py-3 border-b border-gray-200 dark:border-zinc-700">
                        <h3 class="font-medium text-sm text-gray-900 dark:text-white">Save Permission</h3>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-col gap-3">
                            <button 
                                type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-3 px-4 rounded text-sm transition-colors"
                            >
                                Create Permission
                            </button>
                            <a 
                                href="{{ route('permissions.index') }}" 
                                class="w-full bg-gray-100 hover:bg-gray-200 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-gray-700 dark:text-zinc-300 font-medium py-3 px-4 rounded text-sm transition-colors text-center"
                            >
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Permission Info -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded mb-6">
                    <div class="bg-gray-50 dark:bg-zinc-800 px-4 py-3 border-b border-gray-200 dark:border-zinc-700">
                        <h3 class="font-medium text-sm text-gray-900 dark:text-white">About Permissions</h3>
                    </div>
                    <div class="p-4">
                        <div class="text-sm text-gray-600 dark:text-zinc-400 space-y-2">
                            <p>Permissions define specific actions that can be granted to roles.</p>
                            <p>Once created, permissions can be assigned to roles to control user access.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
