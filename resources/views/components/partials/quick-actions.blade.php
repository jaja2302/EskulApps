                        <!-- Quick Actions -->
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Aksi Cepat</h3>
                            <div class="space-y-4">
                                @role('admin')
                                <div class="grid grid-cols-2 gap-3">
                                    <a href="#" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg group transition-all duration-200">
                                        <div class="rounded-full p-2 bg-blue-200 dark:bg-blue-800 mr-3">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Kelola Pengguna</p>
                                        </div>
                                    </a>
                                    
                                    <a href="#" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg group transition-all duration-200">
                                        <div class="rounded-full p-2 bg-purple-200 dark:bg-purple-800 mr-3">
                                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Kelola Ekskul</p>
                                        </div>
                                    </a>
                                    
                                    <a href="#" class="flex items-center p-4 bg-green-50 hover:bg-green-100 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg group transition-all duration-200">
                                        <div class="rounded-full p-2 bg-green-200 dark:bg-green-800 mr-3">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Pendaftaran</p>
                                        </div>
                                    </a>
                                    
                                    <a href="#" class="flex items-center p-4 bg-amber-50 hover:bg-amber-100 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg group transition-all duration-200">
                                        <div class="rounded-full p-2 bg-amber-200 dark:bg-amber-800 mr-3">
                                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Jadwal Kegiatan</p>
                                        </div>
                                    </a>
                                </div>
                                @endrole
                                
                                @role('user')
                                <div class="grid grid-cols-2 gap-3">
                                    <a href="#" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg group transition-all duration-200">
                                        <div class="rounded-full p-2 bg-blue-200 dark:bg-blue-800 mr-3">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Profil Saya</p>
                                        </div>
                                    </a>
                                    
                                    <a href="#" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg group transition-all duration-200">
                                        <div class="rounded-full p-2 bg-purple-200 dark:bg-purple-800 mr-3">
                                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Ekskul Saya</p>
                                        </div>
                                    </a>
                                    
                                    <a href="#" class="flex items-center p-4 bg-green-50 hover:bg-green-100 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg group transition-all duration-200">
                                        <div class="rounded-full p-2 bg-green-200 dark:bg-green-800 mr-3">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Jadwal Saya</p>
                                        </div>
                                    </a>
                                    
                                    <a href="#" class="flex items-center p-4 bg-amber-50 hover:bg-amber-100 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg group transition-all duration-200">
                                        <div class="rounded-full p-2 bg-amber-200 dark:bg-amber-800 mr-3">
                                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Daftar Ekskul</p>
                                        </div>
                                    </a>
                                </div>
                                @endrole
                                
                                @role('pelatih')
                                <div class="grid grid-cols-2 gap-3">
                                    <a href="#" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg group transition-all duration-200">
                                        <div class="rounded-full p-2 bg-blue-200 dark:bg-blue-800 mr-3">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Profil Pelatih</p>
                                        </div>
                                    </a>
                                    
                                    <a href="#" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg group transition-all duration-200">
                                        <div class="rounded-full p-2 bg-purple-200 dark:bg-purple-800 mr-3">
                                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Jadwal Mengajar</p>
                                        </div>
                                    </a>
                                    
                                    <a href="#" class="flex items-center p-4 bg-green-50 hover:bg-green-100 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg group transition-all duration-200">
                                        <div class="rounded-full p-2 bg-green-200 dark:bg-green-800 mr-3">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Jadwal Mengajar</p>
                                        </div>
                                    </a>
                                    
                                    <a href="#" class="flex items-center p-4 bg-amber-50 hover:bg-amber-100 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg group transition-all duration-200">
                                        <div class="rounded-full p-2 bg-amber-200 dark:bg-amber-800 mr-3">
                                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Presensi Siswa</p>
                                        </div>
                                    </a>
                                </div>
                                @endrole
                            </div>
                        </div>
                        
                        <!-- Recent Activities -->
                        <div class="lg:col-span-2 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Aktivitas Terbaru</h3>
                                <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua</a>
                            </div>
                            
                            <div class="space-y-5">
                                @forelse($activities ?? [] as $activity)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-800 dark:text-gray-200 font-medium">{{ $activity->description }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @empty
                                <div class="bg-blue-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                                Belum ada aktivitas terbaru untuk ditampilkan.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforelse
                                
                                <!-- Sample activities if none available -->
                                @if(empty($activities ?? []))
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-800 dark:text-gray-200 font-medium">Anda mendaftar di ekstrakurikuler Basket</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">2 jam yang lalu</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-800 dark:text-gray-200 font-medium">Kehadiran dalam latihan terbaru telah dicatat</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kemarin</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-800 dark:text-gray-200 font-medium">Pengumuman: Jadwal latihan Sabtu dipindahkan ke Minggu</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">3 hari yang lalu</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>