<div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm overflow-hidden shadow-sm rounded-xl transition-all duration-300">
        <div class="p-8">
            <h2 class="text-xl font-semibold mb-6 flex items-center">
                <span class="welcome-message">Welcome, {{ auth()->user()->name }}!</span>
                <span class="ml-auto">
                    <button type="button" wire:click="refreshStats" wire:loading.class="animate-spin" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </span>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-data="{ showStats: false }" x-init="setTimeout(() => showStats = true, 100)">
                @role('admin')
                <div class="card card-gradient-primary p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="card-title">Total Users</h3>
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <p class="card-value text-3xl" wire:loading.class="opacity-50">
                        {{ $userCount }}
                    </p>
                </div>
                @endrole
            </div>
        </div>
    </div>
</div>