<!-- Student Dashboard - Statistik Lengkap -->
<div class="space-y-8">
    <!-- Overall Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" x-data="{ showStats: false }" x-init="setTimeout(() => showStats = true, 100)">
        <!-- Total Eskul -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg" x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Eskul</p>
                    <p class="text-3xl font-bold">{{ count($myEskuls ?? []) }}</p>
                </div>
                <div class="p-3 bg-blue-400/20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Overall Progress -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg" x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:delay="100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Progress Overall</p>
                    <p class="text-3xl font-bold">{{ $overallProgress ?? 0 }}%</p>
                </div>
                <div class="p-3 bg-green-400/20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Attendance Rate -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6 shadow-lg" x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:delay="200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Kehadiran</p>
                    <p class="text-3xl font-bold">{{ $attendanceRate ?? 0 }}%</p>
                    <p class="text-purple-100 text-xs">{{ $attendedSessions ?? 0 }}/{{ $totalSessions ?? 0 }} sesi</p>
                </div>
                <div class="p-3 bg-purple-400/20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Achievements -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-xl p-6 shadow-lg" x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:delay="300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Prestasi</p>
                    <p class="text-3xl font-bold">{{ count($recentAchievements ?? []) }}</p>
                    <p class="text-yellow-100 text-xs">Total prestasi</p>
                </div>
                <div class="p-3 bg-yellow-400/20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Attendance Chart -->
    @if(isset($monthlyAttendance) && !empty($monthlyAttendance['data']))
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Grafik Kehadiran Bulanan</h3>
        <div class="h-64 flex items-end justify-between space-x-2">
            @foreach($monthlyAttendance['months'] as $index => $month)
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-blue-200 dark:bg-blue-700 rounded-t-lg relative group cursor-pointer" 
                     style="height: {{ $monthlyAttendance['data'][$index] > 0 ? ($monthlyAttendance['data'][$index] / max($monthlyAttendance['data'])) * 200 : 20 }}px">
                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                        {{ $monthlyAttendance['data'][$index] }} sesi
                    </div>
                </div>
                <span class="text-xs text-gray-600 dark:text-gray-400 mt-2 text-center">{{ $month }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Detailed Eskul Statistics -->
    @if(isset($eskulStats) && !empty($eskulStats))
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Statistik Detail Per Eskul</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($eskulStats as $eskulId => $stat)
            <div class="bg-gradient-to-br from-white to-blue-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $stat['eskul_name'] }}</h4>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-sm rounded-full">
                        {{ $stat['progress'] }}% Progress
                    </span>
                </div>
                
                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <span>Progress</span>
                        <span>{{ $stat['progress'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $stat['progress'] }}%"></div>
                    </div>
                </div>
                
                <!-- Statistics Grid -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stat['attendance_rate'] }}%</p>
                        <p class="text-xs text-green-600 dark:text-green-400">Kehadiran</p>
                    </div>
                    <div class="text-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stat['achievements_count'] }}</p>
                        <p class="text-xs text-purple-600 dark:text-purple-400">Prestasi</p>
                    </div>
                </div>
                
                <!-- Additional Info -->
                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex justify-between">
                        <span>Total Sesi:</span>
                        <span class="font-medium">{{ $stat['total_sessions'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Hadir:</span>
                        <span class="font-medium">{{ $stat['attended_sessions'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Bergabung:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($stat['join_date'])->format('d M Y') }}</span>
                    </div>
                    @if($stat['last_attendance'])
                    <div class="flex justify-between">
                        <span>Terakhir Hadir:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($stat['last_attendance'])->format('d M Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recent Achievements -->
    @if(isset($recentAchievements) && !empty($recentAchievements))
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Prestasi Terbaru</h3>
            <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua</a>
        </div>
        <div class="space-y-4">
            @foreach($recentAchievements as $achievement)
            <div class="flex items-center p-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900/50 rounded-full mr-4">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ $achievement->title }}</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $achievement->description }}</p>
                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            {{ $achievement->eskul->name }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($achievement->achievement_date)->format('d M Y') }}
                        </span>
                        @if($achievement->level)
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                            {{ ucfirst($achievement->level) }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

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
</div>
