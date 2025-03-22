<div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Jadwal Kegiatan
            </h1>
            <a href="{{ route('eskul.schedule.pdf') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 transition-colors rounded-lg text-white font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download PDF
            </a>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($schedules as $eskulName => $eskulSchedules)
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-4 py-3">
                        <h2 class="text-lg font-bold text-white">{{ $eskulName }}</h2>
                    </div>
                    <div class="p-4">
                        @forelse($eskulSchedules as $schedule)
                            <div class="mb-4 border-b border-gray-100 dark:border-gray-600 pb-3 last:border-b-0 last:pb-0">
                                <div class="flex items-center text-gray-700 dark:text-gray-200 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-medium">{{ $schedule->day }}</span>
                                </div>
                                
                                <div class="flex items-center text-gray-700 dark:text-gray-200 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</span>
                                </div>
                                
                                <div class="flex items-center text-gray-700 dark:text-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $schedule->location }}</span>
                                </div>
                                
                                @if($schedule->notes)
                                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 p-2 rounded">
                                        <span class="font-medium">Catatan:</span> {{ $schedule->notes }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400">Tidak ada jadwal tersedia</p>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-4 text-gray-600 dark:text-gray-300">Belum ada jadwal kegiatan yang tersedia</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
