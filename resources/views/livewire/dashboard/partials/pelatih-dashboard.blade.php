<!-- Pelatih Dashboard - Eskul Management & Student Tracking -->
<div class="space-y-8">
    <!-- Coaching Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" x-data="{ showStats: false }" x-init="setTimeout(() => showStats = true, 100)">
        <!-- Eskul yang Diampu -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg" x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Eskul Diampu</p>
                    <p class="text-3xl font-bold">{{ $eskulCount ?? 0 }}</p>
                </div>
                <div class="p-3 bg-blue-400/20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Prestasi -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg" x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:delay="100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Prestasi</p>
                    <p class="text-3xl font-bold">{{ $achievementCount ?? 0 }}</p>
                </div>
                <div class="p-3 bg-green-400/20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Event Participants -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6 shadow-lg" x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:delay="200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Peserta Event</p>
                    <p class="text-3xl font-bold">{{ $eventParticipantCount ?? 0 }}</p>
                </div>
                <div class="p-3 bg-purple-400/20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Students -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-xl p-6 shadow-lg" x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:delay="300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Siswa Aktif</p>
                    <p class="text-3xl font-bold">25</p>
                </div>
                <div class="p-3 bg-yellow-400/20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <x-partials.quick-actions />
    </div>
    
    <!-- Schedule & Announcements -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-partials.schedule-annoucement
            :upcomingSchedules="$upcomingSchedules" 
            :announcements="$announcements" />
    </div>
    
    <!-- Coaching Tools Section -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Tools Pelatih</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Eskul Management -->
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <h4 class="font-semibold text-gray-800 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Kelola Eskul
                </h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Kelola ekstrakurikuler yang diampu</p>
                <a href="#" class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:underline">
                    Akses
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            
            <!-- Student Tracking -->
            <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <h4 class="font-semibold text-gray-800 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Tracking Siswa
                </h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Pantau perkembangan siswa</p>
                <a href="#" class="inline-flex items-center text-sm text-green-600 dark:text-green-400 hover:underline">
                    Akses
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            
            <!-- Performance Analysis -->
            <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                <h4 class="font-semibold text-gray-800 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Analisis Performa
                </h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Analisis performa siswa dengan K-means</p>
                <a href="#" class="inline-flex items-center text-sm text-purple-600 dark:text-purple-400 hover:underline">
                    Akses
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
