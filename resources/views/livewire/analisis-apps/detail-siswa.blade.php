<div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Analisis Performa Siswa</h1>
            <p class="text-gray-600">Analisis menggunakan metode K-Means untuk mengelompokkan siswa berdasarkan performa</p>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900">Filter Analisis</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- Tahun Akademik -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Akademik</label>
                    <select wire:model="selectedYear" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach($academicYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Semester -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <select wire:model="selectedSemester" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="1">Semester 1</option>
                        <option value="2">Semester 2</option>
                    </select>
                </div>

                <!-- Bulan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select wire:model="selectedMonth" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Bulan</option>
                        @foreach($months as $value => $name)
                            <option value="{{ $value }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kelas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select wire:model="selectedClass" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $class)
                        <option value="{{ $class }}">{{ $class }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Ekstrakurikuler -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ekstrakurikuler</label>
                    <select wire:model="selectedEskul" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Eskul</option>
                        @foreach($eskuls as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cluster -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cluster</label>
                    <select wire:model="selectedCluster" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Cluster</option>
                        <option value="0">Sangat Aktif</option>
                        <option value="1">Cukup Aktif</option>
                        <option value="2">Perlu Motivasi</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4 mt-6">
                <button wire:click="analyze"
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Analisis
                </button>

                @if($results)
                <button wire:click="toggleAwards"
                    class="px-6 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition duration-200">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    {{ $showAwards ? 'Sembunyikan' : 'Tampilkan' }} Penghargaan
                </button>

                <button wire:click="exportResults"
                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Data
                </button>
                @endif
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6" role="alert">
            {{ session('message') }}
        </div>
        @endif

        <!-- Awards Section -->
        @if($results && $showAwards && !empty($topPerformers))
        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-lg shadow-sm p-6 mb-6 border border-yellow-200">
            <h2 class="text-xl font-bold text-amber-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                🏆 Penghargaan Siswa Teraktif
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($topPerformers as $performer)
                <div class="bg-white rounded-lg p-4 shadow-sm border border-yellow-300">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-amber-600">{{ $performer['award_type'] }}</span>
                        <span class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded-full">{{ $performer['avg_score'] }}%</span>
                    </div>
                    <h3 class="font-semibold text-gray-900">{{ $performer['student_name'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $performer['class'] }} - {{ $performer['eskul_name'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Results Section -->
        @if($results)
        <div class="space-y-6">
            <!-- Cluster Statistics -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Statistik Cluster</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($clusterStats as $cluster => $stats)
                    <div class="bg-gradient-to-br from-{{ $cluster == 0 ? 'green' : ($cluster == 1 ? 'yellow' : 'red') }}-50 to-{{ $cluster == 0 ? 'green' : ($cluster == 1 ? 'yellow' : 'red') }}-100 rounded-lg p-4 border border-{{ $cluster == 0 ? 'green' : ($cluster == 1 ? 'yellow' : 'red') }}-200">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-{{ $cluster == 0 ? 'green' : ($cluster == 1 ? 'yellow' : 'red') }}-800">
                                {{ $stats['label'] }}
                            </h3>
                            <span class="bg-{{ $cluster == 0 ? 'green' : ($cluster == 1 ? 'yellow' : 'red') }}-200 text-{{ $cluster == 0 ? 'green' : ($cluster == 1 ? 'yellow' : 'red') }}-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $stats['count'] }} siswa
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Kehadiran:</span>
                                <span class="text-sm font-medium">{{ number_format($stats['avg_attendance'], 1) }}%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Partisipasi:</span>
                                <span class="text-sm font-medium">{{ number_format($stats['avg_participation'], 1) }}%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Prestasi:</span>
                                <span class="text-sm font-medium">{{ number_format($stats['avg_achievement'], 1) }}%</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Student Data Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Data Siswa</h2>
                    <p class="text-sm text-gray-600 mt-1">Total: {{ count($studentMetrics) }} siswa</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eskul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partisipasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prestasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cluster</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Score</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($studentMetrics as $index => $metric)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                        {{ $metric->student_name }}
                                        <span class="relative group">
                                            <button type="button" class="text-blue-600 text-xs underline">Detail</button>
                                            <div class="hidden group-hover:block absolute z-10 left-0 mt-1 w-72 p-3 bg-white border border-gray-200 rounded shadow text-xs text-gray-700">
                                                <div class="font-semibold mb-1">Rincian Perhitungan</div>
                                                <div>
                                                    Kehadiran = (Hadir / Total Pertemuan) × 100%<br>
                                                    = ({{ $metric->att_present ?? 0 }} / {{ $metric->att_total ?? 0 }}) × 100%
                                                </div>
                                                <div class="mt-2">
                                                    Partisipasi = (Ikut Event / Total Event) × 100%<br>
                                                    = ({{ $metric->ev_participated ?? 0 }} / {{ $metric->ev_total ?? 0 }}) × 100%
                                                </div>
                                                <div class="mt-2">
                                                    Prestasi dihitung dari rata-rata skor tiap prestasi (level & posisi). Jumlah prestasi periode ini: {{ $metric->ach_count ?? 0 }}
                                                </div>
                                                <div class="mt-3 pt-2 border-t">
                                                    <div class="font-semibold mb-1">Detail K-Means</div>
                                                    @php $dbg = $kmeansDebug[$metric->eskul_id] ?? null; @endphp
                                                    @if($dbg)
                                                        <div class="mb-1">Centroid awal:</div>
                                                        <ul class="list-disc ml-4 space-y-0.5">
                                                            @foreach(($dbg['initial_centroids'] ?? []) as $cent)
                                                                @php $vals = array_map(function($v){ return is_numeric($v) ? number_format($v,1) : $v; }, $cent); @endphp
                                                                <li class="text-xs">[{{ implode(', ', $vals) }}]</li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="mt-1 text-xs">Iterasi: {{ count($dbg['iterations'] ?? []) }}</div>
                                                        <div class="mt-1">Centroid akhir:</div>
                                                        <ul class="list-disc ml-4 space-y-0.5">
                                                            @foreach(($dbg['final']['final_centroids'] ?? []) as $cent)
                                                                @php $vals = array_map(function($v){ return is_numeric($v) ? number_format($v,1) : $v; }, $cent); @endphp
                                                                <li class="text-xs">[{{ implode(', ', $vals) }}]</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <div class="text-gray-500 text-xs">Detail K-Means belum tersedia. Klik Analisis untuk menghasilkan.</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $metric->nis }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">{{ $metric->class }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $metric->eskul_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm text-gray-900">{{ number_format($metric->attendance_score, 1) }}%</div>
                                        <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $metric->attendance_score }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm text-gray-900">{{ number_format($metric->participation_score, 1) }}%</div>
                                        <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $metric->participation_score }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm text-gray-900">{{ number_format($metric->achievement_score, 1) }}%</div>
                                        <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                            <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $metric->achievement_score }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $clusterColors = [
                                    0 => 'bg-green-100 text-green-800',
                                    1 => 'bg-yellow-100 text-yellow-800',
                                    2 => 'bg-red-100 text-red-800'
                                    ];
                                    $clusterLabels = [
                                    0 => 'Sangat Aktif',
                                    1 => 'Cukup Aktif',
                                    2 => 'Perlu Motivasi'
                                    ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $clusterColors[$metric->cluster] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $clusterLabels[$metric->cluster] ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ number_format(($metric->attendance_score + $metric->participation_score + $metric->achievement_score) / 3, 1) }}%
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data untuk ditampilkan. Silakan lakukan analisis terlebih dahulu.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>