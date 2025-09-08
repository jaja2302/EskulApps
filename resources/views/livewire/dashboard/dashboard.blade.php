<div class="relative">
    <!-- Background elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-gradient-to-br from-blue-400/10 to-indigo-400/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-1/4 h-1/4 bg-gradient-to-tr from-purple-400/10 to-pink-400/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Main Dashboard Container -->
    <div class="relative z-10">
        <!-- Welcome Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-lg rounded-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700">
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <span class="welcome-message">Selamat datang, {{ auth()->user()->name }}!</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}
                            </span>
                        </h2>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">{{ now()->format('l, d F Y') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" wire:click="refreshStats" wire:loading.class="animate-spin" class="p-2 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/50 dark:text-blue-400 dark:hover:bg-blue-900/80 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Role-based Dashboard Content -->
                @role('admin')
                    @include('livewire.dashboard.partials.admin-dashboard')
                @endrole
                
                @role('pembina')
                    @include('livewire.dashboard.partials.pembina-dashboard')
                @endrole
                
                @role('pelatih')
                    @include('livewire.dashboard.partials.pelatih-dashboard')
                @endrole
                
                @role('siswa')
                    @include('livewire.dashboard.partials.siswa-dashboard')
                @endrole
                
                @role('pimpinan')
                    @livewire('dashboard.pimpinan-dashboard')
                @endrole
            </div>
        </div>
    </div>
</div>
