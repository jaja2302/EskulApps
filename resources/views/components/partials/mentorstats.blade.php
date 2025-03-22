<div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
                         x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Ekskul Dibimbing</h3>
                            <div class="p-2 bg-white dark:bg-gray-900 rounded-lg shadow">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white" wire:loading.class="opacity-50">
                            {{ $mentorEskulCount ?? '2' }}
                        </p>
                        <div class="mt-2 text-sm text-blue-600 dark:text-blue-400 flex items-center gap-1">
                            <span>Ekskul yang dibimbing</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-fuchsia-50 to-fuchsia-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
                         x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:enter-delay="100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Total Siswa</h3>
                            <div class="p-2 bg-white dark:bg-gray-900 rounded-lg shadow">
                                <svg class="w-6 h-6 text-fuchsia-600 dark:text-fuchsia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white" wire:loading.class="opacity-50">
                            {{ $totalStudents ?? '45' }}
                        </p>
                        <div class="mt-2 text-sm text-fuchsia-600 dark:text-fuchsia-400 flex items-center gap-1">
                            <span>Siswa yang dibimbing</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
                         x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:enter-delay="200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Jadwal Hari Ini</h3>
                            <div class="p-2 bg-white dark:bg-gray-900 rounded-lg shadow">
                                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white" wire:loading.class="opacity-50">
                            {{ $todayScheduleCount ?? '3' }}
                        </p>
                        <div class="mt-2 text-sm text-amber-600 dark:text-amber-400 flex items-center gap-1">
                            <span>Sesi kegiatan hari ini</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
                         x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:enter-delay="300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Kehadiran</h3>
                            <div class="p-2 bg-white dark:bg-gray-900 rounded-lg shadow">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white" wire:loading.class="opacity-50">
                            {{ $attendanceRate ?? '92%' }}
                        </p>
                        <div class="mt-2 text-sm text-green-600 dark:text-green-400 flex items-center gap-1">
                            <span>Rata-rata kehadiran siswa</span>
                        </div>
                    </div>