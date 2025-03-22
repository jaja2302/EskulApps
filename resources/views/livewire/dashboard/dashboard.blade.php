<div class="relative">
    <!-- Background elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-gradient-to-br from-blue-400/10 to-indigo-400/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-1/4 h-1/4 bg-gradient-to-tr from-purple-400/10 to-pink-400/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Main Dashboard Container -->
    <div class="relative z-10">
        <!-- Welcome Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-lg rounded-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700">
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <span class="welcome-message">Selamat datang, {{ auth()->user()->name }}!</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}
                            </span>
                        </h2>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">{{ now()->format('l, d F Y') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" wire:click="refreshStats" wire:loading.class="animate-spin" class="p-2 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/50 dark:text-blue-400 dark:hover:bg-blue-900/80 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" x-data="{ showStats: false }" x-init="setTimeout(() => showStats = true, 100)">
                    <!-- Admin Only Stats -->
                    
                    @role('admin')
                    <x-partials.adminstats 
                    :userCount="$userCount" 
                    :eskulCount="$eskulCount" 
                    :achievementCount="$achievementCount" 
                    :eventParticipantCount="$eventParticipantCount" 
                    :pelatihCount="$pelatihCount" 
                    />
                    @endrole
                    
                    <!-- User Only Stats -->
                    @role('user')
                    <x-partials.userstats 
                    :userCount="$userCount" 
                    :eskulCount="$eskulCount"
                     :achievementCount="$achievementCount" 
                     :eventParticipantCount="$eventParticipantCount" 
                     :pelatihCount="$pelatihCount"
                      />
                    @endrole
                    
                    <!-- Mentor Only Stats -->
                    @role('pelatih')
                    <x-partials.mentorstats />
                    @endrole
                </div>
                
                <!-- Quick Actions & Recent Activities -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
                    <x-partials.quick-actions 
                    />
                </div>
                
                <!-- Additional Sections schgedule dan announcement -->
                <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                   <x-partials.schedule-annoucement
                    :upcomingSchedules="$upcomingSchedules" 
                    :announcements="$announcements" />
                </div>
                
                <!-- My Eskul & Progress Section -->
                @role('user')
                <div class="mt-8">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Ekstrakurikuler Saya</h3>
                            <a href="{{ route('user.ekskul') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua</a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($myEskuls ?? [] as $eskul)
                            <div class="bg-gradient-to-br from-white to-blue-50 dark:from-gray-700 dark:to-gray-800 rounded-xl shadow overflow-hidden group hover:shadow-md transition-all duration-300">
                                <div class="h-40 overflow-hidden relative">
                                    <img src="{{ Storage::url($eskul->image) }}" alt="{{ $eskul->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                                        <div class="p-4 w-full">
                                            <h4 class="text-white font-bold text-lg">{{ $eskul->name }}</h4>
                                            <p class="text-blue-100 text-sm">{{ $eskul->schedule_day }} {{ $eskul->schedule_time }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">Progress</span>
                                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ $eskul->progress ?? '65' }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                        <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full" style="width: {{ $eskul->progress ?? '65' }}%"></div>
                                    </div>
                                    <div class="mt-4 flex justify-between">
                                        <span class="px-2.5 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 text-xs rounded-full">
                                            {{ $eskul->attendance_rate ?? '85' }}% kehadiran
                                        </span>
                                        <a href="{{ route('user.ekskul.detail', $eskul->id) }}" class="text-blue-600 dark:text-blue-400 text-sm hover:underline">
                                            Detail →
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <!-- If no data -->
                            <div class="col-span-full bg-blue-50 dark:bg-gray-700 rounded-lg p-6 text-center">
                                <svg class="h-12 w-12 text-blue-400 dark:text-blue-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-gray-700 dark:text-gray-300 mb-3">
                                    Anda belum bergabung dengan ekstrakurikuler apapun.
                                </p>
                                <a href="{{ route('user.pendaftaran') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Daftar Sekarang
                                </a>
                            </div>
                            
                            <!-- Sample data if none available -->
                            @if(empty($myEskuls ?? []))
                            <div class="bg-gradient-to-br from-white to-blue-50 dark:from-gray-700 dark:to-gray-800 rounded-xl shadow overflow-hidden group hover:shadow-md transition-all duration-300">
                                <div class="h-40 overflow-hidden relative">
                                    <img src="{{ asset('images/basketball.jpg') }}" alt="Basket" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                                        <div class="p-4 w-full">
                                            <h4 class="text-white font-bold text-lg">Basket</h4>
                                            <p class="text-blue-100 text-sm">Selasa & Kamis 15:00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">Progress</span>
                                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">65%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                        <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full" style="width: 65%"></div>
                                    </div>
                                    <div class="mt-4 flex justify-between">
                                        <span class="px-2.5 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 text-xs rounded-full">
                                            85% kehadiran
                                        </span>
                                        <a href="#" class="text-blue-600 dark:text-blue-400 text-sm hover:underline">
                                            Detail →
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-br from-white to-purple-50 dark:from-gray-700 dark:to-gray-800 rounded-xl shadow overflow-hidden group hover:shadow-md transition-all duration-300">
                                <div class="h-40 overflow-hidden relative">
                                    <img src="{{ asset('images/music.jpg') }}" alt="Musik" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                                        <div class="p-4 w-full">
                                            <h4 class="text-white font-bold text-lg">Paduan Suara</h4>
                                            <p class="text-blue-100 text-sm">Senin & Rabu 14:00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">Progress</span>
                                        <span class="text-sm font-medium text-purple-600 dark:text-purple-400">78%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                        <div class="bg-purple-600 dark:bg-purple-500 h-2.5 rounded-full" style="width: 78%"></div>
                                    </div>
                                    <div class="mt-4 flex justify-between">
                                        <span class="px-2.5 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 text-xs rounded-full">
                                            92% kehadiran
                                        </span>
                                        <a href="#" class="text-purple-600 dark:text-purple-400 text-sm hover:underline">
                                            Detail →
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforelse
                        </div>
                    </div>
                </div>
                @endrole
                
                <!-- Mentor's Students Section -->
                @role('mentor')
                <div class="mt-8">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Siswa yang Dibimbing</h3>
                            <a href="{{ route('mentor.students') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua</a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ekstrakurikuler</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kehadiran</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Progress</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($students ?? [] as $student)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $student->profile_photo ?? asset('images/default-avatar.png') }}" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $student->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $student->eskul }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $student->attendance_rate > 80 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                {{ $student->attendance_rate ?? '85' }}%
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                                <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full" style="width: {{ $student->progress ?? '65' }}%"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('mentor.student.detail', $student->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Detail</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400">
                                            Belum ada siswa yang dibimbing
                                        </td>
                                    </tr>
                                    @endforelse
                                    
                                    <!-- Sample data if none available -->
                                    @if(empty($students ?? []))
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('images/default-avatar.png') }}" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Budi Santoso</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">budi@example.com</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">Basket</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                85%
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                                <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full" style="width: 65%"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Detail</a>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('images/default-avatar.png') }}" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Siti Rahayu</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">siti@example.com</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">Paduan Suara</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                92%
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                                <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full" style="width: 78%"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Detail</a>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endrole
            </div>
        </div>
    </div>
</div>
