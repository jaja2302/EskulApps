<div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
                         x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Ekskul Saya</h3>
                            <div class="p-2 bg-white dark:bg-gray-900 rounded-lg shadow">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white" wire:loading.class="opacity-50">
                            {{ $myEskulCount ?? '2' }}
                        </p>
                        <div class="mt-2 text-sm text-blue-600 dark:text-blue-400 flex items-center gap-1">
                            <span>Ekskul yang diikuti</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
                         x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:enter-delay="100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Jadwal Terdekat</h3>
                            <div class="p-2 bg-white dark:bg-gray-900 rounded-lg shadow">
                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white" wire:loading.class="opacity-50">
                            {{ $nextSchedule ?? 'Besok' }}
                        </p>
                        <div class="mt-2 text-sm text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                            <span>Kegiatan ekstrakurikuler selanjutnya</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
                         x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:enter-delay="200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Presensi</h3>
                            <div class="p-2 bg-white dark:bg-gray-900 rounded-lg shadow">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white" wire:loading.class="opacity-50">
                            {{ $presensiPersen ?? '85%' }}
                        </p>
                        <div class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                            <span>Tingkat kehadiran bulan ini</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
                         x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:enter-delay="300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Pengumuman</h3>
                            <div class="p-2 bg-white dark:bg-gray-900 rounded-lg shadow">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-xl font-bold text-gray-800 dark:text-white line-clamp-1" wire:loading.class="opacity-50">
                            {{ $announcement ?? 'Libur kegiatan 17 Agustus' }}
                        </p>
                        <div class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 flex items-center gap-1">
                            <span>Pengumuman terbaru</span>
                        </div>
                    </div>