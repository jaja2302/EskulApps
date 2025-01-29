<div class="max-w-7xl mx-auto px-4 py-8 bg-gray-50 dark:bg-gray-900">
    <!-- Header dengan gradient background -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-600 dark:to-indigo-700 rounded-2xl p-8 mb-8 shadow-xl">
        <div class="relative z-10">
            <h1 class="text-4xl font-bold text-white mb-4">{{ $eskul->name }}</h1>
            <p class="text-blue-100 text-lg">Ekstrakurikuler Unggulan</p>
        </div>
        <div class="absolute bottom-0 right-0 transform translate-x-1/4 translate-y-1/4">
            <svg class="w-64 h-64 text-white/10" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Info Card - Spans 8 columns -->
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Informasi Eskul</h2>
                        <span class="px-4 py-2 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-100 rounded-full text-sm font-semibold">Aktif</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Deskripsi Section -->
                        <div class="space-y-4 p-6 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-300">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-500 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-800">Deskripsi</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $eskul->description }}</p>
                        </div>

                        <!-- Pelatih & Pembina Section -->
                        <div class="space-y-6">
                            <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-300">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="bg-green-500 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-800">Pelatih</h3>
                                        <p class="text-gray-600 dark:text-gray-300">{{ $eskul->pelatih->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-300">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="bg-purple-500 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-800">Pembina</h3>
                                        <p class="text-gray-600 dark:text-gray-300">{{ $eskul->pembina->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($eskul->image)
                    <div class="mt-6 p-6 bg-gray-50 dark:bg-gray-700">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Banner Eskul</h3>
                        <div class="relative group">
                            <img src="{{ Storage::url($eskul->image) }}" alt="Banner Eskul" 
                                 class="w-full rounded-xl shadow-lg transition-transform duration-300 group-hover:scale-[1.02]">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl flex items-end">
                                <div class="p-4 text-white">
                                    <p class="font-semibold">{{ $eskul->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Stats Card - Spans 4 columns -->
        <div class="col-span-12 lg:col-span-4 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-all duration-300">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Statistik Cepat</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-4 rounded-xl">
                        <div class="text-blue-500 text-2xl font-bold">{{ $members_total }}</div>
                        <div class="text-sm text-gray-600">Total Anggota</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-xl">
                        <div class="text-green-500 text-2xl font-bold">{{ $attendance_percentage }}%</div>
                        <div class="text-sm text-gray-600">Kehadiran Hari Ini</div>
                    </div>
                    <div class="col-span-2 bg-orange-50 p-4 rounded-xl">
                        <div class="text-orange-500 text-2xl font-bold">0</div>
                        <div class="text-sm text-gray-600">Prestasi</div>
                    </div>
                </div>
            </div>
        </div>

        @if($eskulMaterial)
            <div class="col-span-12">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Materi Eskul</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach($eskulMaterial as $key => $material)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-300">
                                    <div class="p-6">
                                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ $key }}</h3>
                                        @foreach($material as $item)
                                            <p class="text-gray-600 dark:text-gray-300">{{ $item['title'] }}</p>
                                            <p class="text-gray-600 dark:text-gray-300">{{ $item['description'] }}</p>
                                            <a href="{{ Storage::url($item['file_path']) }}" class="text-blue-500 hover:text-blue-600">Unduh</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
        

        <!-- Members Section - Full width -->
        <div class="col-span-12">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="bg-indigo-500 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800">Anggota Eskul</h2>
                        </div>
 
                      
                    </div>
                    
                    {{ $this->table }}
                </div>
            </div>
        </div>

        <!-- Schedule and History Section - Side by side -->
        <div class="col-span-12 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Schedule Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 p-6">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="bg-yellow-500 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Jadwal Latihan</h2>
                    </div>
                    @if(auth()->user()->hasRole('siswa'))
                        <button 
                            wire:click="clockIn" 
                            @if(!$canClockIn) disabled @endif
                            class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-2 
                                @if(!$canClockIn) opacity-50 cursor-not-allowed @endif"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Clock In</span>
                        </button>
                    @else
                        <a href="{{ route('dashboard.eskul') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>Tambah Jadwal</span>
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @forelse($eskul->schedules as $schedule)
                        <div class="group bg-gray-50 dark:bg-gray-700 rounded-xl p-6 hover:bg-yellow-50 dark:hover:bg-yellow-900/50 transition-all duration-300">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ $schedule->day }}</h3>
                                    <div class="space-y-2">
                                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                                            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                                        </div>
                                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                                            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <p>{{ $schedule->location }}</p>
                                        </div>
                                    </div>
                                </div>
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $schedule->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>Belum ada jadwal yang ditambahkan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Attendance History Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 p-6">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="bg-indigo-500 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">History Absensi</h2>
                    </div>
                </div>

                <div>
                    @livewire('EksulApps.AttedanceWidgetTable', ['eskul' => $eskul])
                </div>
            </div>
        </div>
    </div>
</div>

