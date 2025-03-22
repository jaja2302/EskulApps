 <!-- Upcoming Schedule -->
 <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Jadwal Mendatang</h3>
                        <div class="space-y-4">
                            @forelse($upcomingSchedules as $schedule)
                            <div class="bg-blue-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-800 dark:text-gray-100">{{ $schedule->eskul->name }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $schedule->location }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-blue-600 dark:text-blue-300">{{ $schedule->day }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $schedule->start_time }} - {{ $schedule->end_time }}</p>
                                    </div>
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
                                            Tidak ada jadwal kegiatan mendatang.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Announcements -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Pengumuman Terbaru</h3>
                        <div class="space-y-4">
                            @forelse($announcements as $announcement)
                            <div class="bg-indigo-50 dark:bg-gray-700 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-800 dark:text-gray-100">{{ $announcement->title }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">{{ Str::limit($announcement->content, 150) }}</p>
                                <div class="flex justify-between items-center mt-3">
                                    <div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $announcement->publish_at ? \Carbon\Carbon::parse($announcement->publish_at)->format('d M Y') : $announcement->created_at->format('d M Y') }}</span>
                                        @if($announcement->eskul)
                                        <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-xs rounded-full">{{ $announcement->eskul->name }}</span>
                                        @endif
                                    </div>
                                    <a href="#" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Baca Selengkapnya</a>
                                </div>
                            </div>
                            @empty
                            <div class="bg-indigo-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            Belum ada pengumuman terbaru.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>