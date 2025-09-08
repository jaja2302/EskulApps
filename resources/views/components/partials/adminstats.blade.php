@props(['userCount', 'eskulCount', 'achievementCount', 'eventParticipantCount', 'pelatihCount'])
<div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
                         x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Akun Users</h3>
                            <div class="p-2 bg-white dark:bg-gray-900 rounded-lg shadow">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white" wire:loading.class="opacity-50">
                            {{ $userCount }}
                        </p>
                        <div class="mt-2 text-sm text-green-600 dark:text-green-400 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            <span>+5% dari bulan lalu</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
                         x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:enter-delay="100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Total Ekskul</h3>
                            <div class="p-2 bg-white dark:bg-gray-900 rounded-lg shadow">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white" wire:loading.class="opacity-50">
                            {{ $eskulCount ?? '15' }}
                        </p>
                        <div class="mt-2 text-sm text-purple-600 dark:text-purple-400 flex items-center gap-1">
                            <span>Total Jumlah Ekstrakurikuler Aktif</span>
                        </div>
                    </div>
                        
                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
                         x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:enter-delay="200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Achievement</h3>
                            <div class="p-2 bg-white dark:bg-gray-900 rounded-lg shadow">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white" wire:loading.class="opacity-50">
                            {{ $achievementCount ?? '87' }}
                        </p>
                        <div class="mt-2 text-sm text-green-600 dark:text-green-400 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            <span>+12% dari periode lalu</span>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
                         x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:enter-delay="300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Pelatih</h3>
                            <div class="p-2 bg-white dark:bg-gray-900 rounded-lg shadow">
                                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white" wire:loading.class="opacity-50">
                            {{ $pelatihCount ?? '24' }}
                        </p>
                        <div class="mt-2 text-sm text-amber-600 dark:text-amber-400 flex items-center gap-1">
                            <span>Pelatih aktif bulan ini</span>
                        </div>
                    </div>

                    <!-- eventParticipantCount -->

                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
                         x-show="showStats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:enter-delay="300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Event Participant</h3>
                            <div class="p-2 bg-white dark:bg-gray-900 rounded-lg shadow">
                                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white" wire:loading.class="opacity-50">
                            {{ $eventParticipantCount ?? '24' }}
                        </p>
                        <div class="mt-2 text-sm text-amber-600 dark:text-amber-400 flex items-center gap-1">
                            <span>Event Participant aktif bulan ini</span>
                        </div>
                    </div>
