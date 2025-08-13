<!-- Admin Dashboard - Overview Sistem -->
<div class="space-y-8">
    <!-- Global Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" x-data="{ showStats: false }" x-init="setTimeout(() => showStats = true, 100)">
        <x-partials.adminstats 
            :userCount="$userCount" 
            :eskulCount="$eskulCount" 
            :achievementCount="$achievementCount" 
            :eventParticipantCount="$eventParticipantCount" 
            :pelatihCount="$pelatihCount" 
        />
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
    
    <!-- System Overview -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Overview Sistem</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- User Management -->
            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/50 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Manajemen User</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">Kelola semua user, role, dan permission</p>
            </div>
            
            <!-- Eskul Management -->
            <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <div class="p-3 bg-green-100 dark:bg-green-900/50 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Manajemen Eskul</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">Kelola ekstrakurikuler dan jadwal</p>
            </div>
            
            <!-- System Monitoring -->
            <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                <div class="p-3 bg-purple-100 dark:bg-purple-900/50 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Monitoring Sistem</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">Pantau aktivitas dan performa sistem</p>
            </div>
        </div>
    </div>
</div>
