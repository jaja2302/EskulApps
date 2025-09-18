<div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Analisis Performa Siswa</h1>
            <p class="text-gray-600">Analisis menggunakan metode K-Means untuk mengelompokkan siswa berdasarkan performa</p>
        </div>

        <!-- Filters Section -->
        <style>
            .modal-overlay { display: none; }
            .modal-overlay:target { display: block; }
        </style>
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900">Filter Analisis</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-4">
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
                    <select wire:model.live="selectedSemester" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="1">Semester 1</option>
                        <option value="2">Semester 2</option>
                    </select>
                </div>

                <!-- Bulan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select wire:model.live="selectedMonth" wire:key="month-select-{{ $selectedSemester }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Bulan</option>
                        @foreach($months as $value => $name)
                            <option value="{{ $value }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kelas Umum -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas Umum</label>
                    <select wire:model="selectedClass" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Tingkat</option>
                        @foreach($classes as $class)
                        <option value="{{ $class }}">{{ $class }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kelas Spesifik -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas Spesifik</label>
                    <select wire:model="selectedSpecificClass" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Kelas</option>
                        @foreach($specificClasses as $class)
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
                    wire:loading.attr="disabled"
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                    
                    <!-- Normal state -->
                    <span wire:loading.remove wire:target="analyze">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Analisis
                    </span>
                    
                    <!-- Loading state -->
                    <span wire:loading wire:target="analyze">
                        <svg class="w-4 h-4 inline-block mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menganalisis...
                    </span>
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

        @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            {{ session('error') }}
        </div>
        @endif

        <!-- Chart Section -->
        @if($results)
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-900">Visualisasi Data</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow p-4 h-[400px] md:h-96">
                    <div class="flex justify-between mb-2">
                        <h4 class="text-md font-medium text-gray-700">Distribusi Cluster</h4>
                    </div>
                    <div wire:ignore id="clusterDistribution" class="h-full"></div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 h-[400px] md:h-96">
                    <div class="flex justify-between mb-2">
                        <h4 class="text-md font-medium text-gray-700">Performa Per Cluster</h4>
                    </div>
                    <div wire:ignore id="clusterPerformance" class="h-full"></div>
                </div>
            </div>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($studentMetrics as $index => $metric)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                        {{ $metric->student_name }}
                                        
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($metric->cluster == 1)
                                        <button wire:click="openMotivationForm({{ $metric->student_id }}, {{ $metric->eskul_id }})"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            Tambah Motivasi
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
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
            
            <!-- Detail Perhitungan K-Means (format mirip Excel) -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Detail Perhitungan K-Means</h2>
                @php $eskulsOnPage = $studentMetrics->pluck('eskul_name','eskul_id')->unique(); @endphp
                @foreach($eskulsOnPage as $eskulId => $eskulName)
                    @php $dbg = $kmeansDebug[$eskulId] ?? null; @endphp
                    <div class="mb-8">
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $eskulName }}</h3>
                        @if($dbg)
                            <!-- Tabel: Centroid Awal (Random) / Iterasi 1 -->
                            <div class="mb-4">
                                <div class="text-sm font-semibold mb-1">Centroid Awal (Random)</div>
                                <div class="overflow-auto border rounded">
                                    <table class="min-w-full text-xs">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left">C</th>
                                                <th class="px-3 py-2 text-left">Nama Siswa</th>
                                                <th class="px-3 py-2 text-right">Absensi</th>
                                                <th class="px-3 py-2 text-right">Partisipasi</th>
                                                <th class="px-3 py-2 text-right">Prestasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(($dbg['initial_centroids'] ?? []) as $i => $cent)
                                                @php $st = $dbg['initial_centroid_students'][$i] ?? null; @endphp
                                                <tr class="border-t">
                                                    <td class="px-3 py-1">C{{ $i+1 }}</td>
                                                    <td class="px-3 py-1">{{ $st['student_name'] ?? '-' }}</td>
                                                    <td class="px-3 py-1 text-right">{{ number_format($cent[0] ?? 0, 2) }}</td>
                                                    <td class="px-3 py-1 text-right">{{ number_format($cent[1] ?? 0, 2) }}</td>
                                                    <td class="px-3 py-1 text-right">{{ number_format($cent[2] ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Iterasi -->
                            @foreach(($dbg['iterations'] ?? []) as $iter)
                                <div class="mb-6">
                                    <div class="text-sm font-semibold mb-1">Iterasi {{ $iter['iteration'] }} — Jarak ke Centroid & Clustering</div>
                                    <div class="overflow-auto border rounded">
                                        <table class="min-w-full text-xs">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-3 py-2 text-left">Data ke i</th>
                                                    <th class="px-3 py-2 text-left">Nama Siswa</th>
                                                    <th class="px-3 py-2 text-right">Absensi</th>
                                                    <th class="px-3 py-2 text-right">Partisipasi</th>
                                                    <th class="px-3 py-2 text-right">Prestasi</th>
                                                    <th class="px-3 py-2 text-right">C1</th>
                                                    <th class="px-3 py-2 text-right">C2</th>
                                                    <th class="px-3 py-2 text-right">C3</th>
                                                    <th class="px-3 py-2 text-left">Jarak Terdekat</th>
                                                    <th class="px-3 py-2 text-left">Clustering</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $studentsDbg = $dbg['students'] ?? []; @endphp
                                                @foreach(($iter['distances'] ?? []) as $rowIdx => $dists)
                                                    @php
                                                        $stud = $studentsDbg[$rowIdx] ?? null;
                                                        $vec = $stud['vector'] ?? [0,0,0];
                                                        $c1 = number_format($dists[0] ?? 0, 2);
                                                        $c2 = number_format($dists[1] ?? 0, 2);
                                                        $c3 = number_format($dists[2] ?? 0, 2);
                                                        $closest = $iter['assigned_clusters'][$rowIdx] ?? 0;
                                                    @endphp
                                                    <tr class="border-t">
                                                        <td class="px-3 py-1">{{ $rowIdx + 1 }}</td>
                                                        <td class="px-3 py-1">{{ $stud['student_name'] ?? '-' }}</td>
                                                        <td class="px-3 py-1 text-right">{{ number_format($vec[0] ?? 0, 2) }}</td>
                                                        <td class="px-3 py-1 text-right">{{ number_format($vec[1] ?? 0, 2) }}</td>
                                                        <td class="px-3 py-1 text-right">{{ number_format($vec[2] ?? 0, 2) }}</td>
                                                        <td class="px-3 py-1 text-right">{{ $c1 }}</td>
                                                        <td class="px-3 py-1 text-right">{{ $c2 }}</td>
                                                        <td class="px-3 py-1 text-right">{{ $c3 }}</td>
                                                        <td class="px-3 py-1">C{{ ($closest + 1) }}</td>
                                                        <td class="px-3 py-1">C{{ ($closest + 1) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Jumlah anggota tiap cluster -->
                                    @php $counts = array_count_values($iter['assigned_clusters'] ?? []); @endphp
                                    <div class="text-xs text-gray-700 mt-2">Jumlah: C1 = {{ $counts[0] ?? 0 }}, C2 = {{ $counts[1] ?? 0 }}, C3 = {{ $counts[2] ?? 0 }}</div>
                                </div>

                                <!-- Centroid Baru untuk iterasi berikutnya (centroids_after) -->
                                <div class="mb-8">
                                    <div class="text-sm font-semibold mb-1">Centroid Baru (Iterasi {{ ($iter['iteration'] + 1) }})</div>
                                    <div class="overflow-auto border rounded">
                                        <table class="min-w-full text-xs">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-3 py-2 text-left">C</th>
                                                    <th class="px-3 py-2 text-right">Absensi</th>
                                                    <th class="px-3 py-2 text-right">Partisipasi</th>
                                                    <th class="px-3 py-2 text-right">Prestasi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(($iter['centroids_after'] ?? []) as $i => $cent)
                                                    <tr class="border-t">
                                                        <td class="px-3 py-1">C{{ $i+1 }}</td>
                                                        <td class="px-3 py-1 text-right">{{ number_format($cent[0] ?? 0, 2) }}</td>
                                                        <td class="px-3 py-1 text-right">{{ number_format($cent[1] ?? 0, 2) }}</td>
                                                        <td class="px-3 py-1 text-right">{{ number_format($cent[2] ?? 0, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Centroid Akhir -->
                            <div>
                                <div class="text-sm font-semibold mb-1">Centroid Akhir</div>
                                <div class="overflow-auto border rounded">
                                    <table class="min-w-full text-xs">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left">C</th>
                                                <th class="px-3 py-2 text-right">Absensi</th>
                                                <th class="px-3 py-2 text-right">Partisipasi</th>
                                                <th class="px-3 py-2 text-right">Prestasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(($dbg['final']['final_centroids'] ?? []) as $i => $cent)
                                                <tr class="border-t">
                                                    <td class="px-3 py-1">C{{ $i+1 }}</td>
                                                    <td class="px-3 py-1 text-right">{{ number_format($cent[0] ?? 0, 2) }}</td>
                                                    <td class="px-3 py-1 text-right">{{ number_format($cent[1] ?? 0, 2) }}</td>
                                                    <td class="px-3 py-1 text-right">{{ number_format($cent[2] ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="text-gray-500 text-xs">Detail belum tersedia untuk eskul ini. Klik Analisis untuk menghasilkan.</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="analyze" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md mx-4 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Menganalisis Data</h3>
            <p class="text-gray-600">Mohon tunggu, sistem sedang memproses analisis K-Means...</p>
        </div>
    </div>

    <!-- Motivation Form Modal -->
    @if($showMotivationForm)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Laporan Motivasi</h3>
                <button wire:click="closeMotivationForm" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="saveMotivationReport">
                <div class="space-y-4">
                    <!-- Student Info -->
                    @if($selectedStudentForMotivation)
                        @php
                            $studentInfo = $studentMetrics->where('student_id', $selectedStudentForMotivation['student_id'])
                                ->where('eskul_id', $selectedStudentForMotivation['eskul_id'])
                                ->first();
                        @endphp
                        @if($studentInfo)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-2">Informasi Siswa</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Nama:</span>
                                    <span class="font-medium">{{ $studentInfo->student_name }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Kelas:</span>
                                    <span class="font-medium">{{ $studentInfo->class }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Eskul:</span>
                                    <span class="font-medium">{{ $studentInfo->eskul_name }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Cluster:</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Perlu Motivasi</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif

                    <!-- Motivation Reason -->
                    <div>
                        <label for="motivationReason" class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Perlu Motivasi <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="motivationReason" 
                            id="motivationReason"
                            rows="4"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('motivationReason') border-red-300 @enderror"
                            placeholder="Jelaskan alasan mengapa siswa ini perlu motivasi..."></textarea>
                        @error('motivationReason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Recommendation -->
                    <div>
                        <label for="recommendation" class="block text-sm font-medium text-gray-700 mb-2">
                            Rekomendasi Perbaikan <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="recommendation" 
                            id="recommendation"
                            rows="4"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('recommendation') border-red-300 @enderror"
                            placeholder="Berikan rekomendasi untuk membantu siswa meningkatkan performa..."></textarea>
                        @error('recommendation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" wire:click="closeMotivationForm"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </button>
                    <button type="submit"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="saveMotivationReport">
                            Simpan Laporan
                        </span>
                        <span wire:loading wire:target="saveMotivationReport">
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

@if($results)
    @php $eskulsOnPage = $studentMetrics->pluck('eskul_name','eskul_id')->unique(); @endphp
    @foreach($eskulsOnPage as $eskulId => $eskulName)
        @php $dbg = $kmeansDebug[$eskulId] ?? null; @endphp
        <div id="kmeans-{{ $eskulId }}" class="modal-overlay fixed inset-0 z-50 bg-black/50">
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="bg-white w-full max-w-6xl max-h-[85vh] overflow-auto rounded-lg shadow-xl">
                    <div class="px-6 py-4 border-b flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Detail K-Means — {{ $eskulName }}</h3>
                            <p class="text-xs text-gray-500">k = 3, jarak: Euclidean, pemilihan centroid awal: acak dari data.</p>
                        </div>
                        <a href="#" class="text-gray-500 hover:text-gray-700">Tutup ✕</a>
                    </div>
                    <div class="p-6 space-y-6 text-sm">
                        @if($dbg)
                            <div>
                                <h4 class="font-semibold mb-2">Tabel III.2 — Penentuan Titik Pusat (Centroid) Iterasi 1</h4>
                                <div class="overflow-auto border rounded">
                                    <table class="min-w-full text-xs">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left">Centroid</th>
                                                <th class="px-3 py-2 text-left">Nama Siswa</th>
                                                <th class="px-3 py-2 text-right">Absensi</th>
                                                <th class="px-3 py-2 text-right">Partisipasi</th>
                                                <th class="px-3 py-2 text-right">Prestasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(($dbg['initial_centroids'] ?? []) as $i => $cent)
                                                @php $st = $dbg['initial_centroid_students'][$i] ?? null; @endphp
                                                <tr class="border-t">
                                                    <td class="px-3 py-1">C{{ $i+1 }}</td>
                                                    <td class="px-3 py-1">{{ $st['student_name'] ?? '-' }}</td>
                                                    <td class="px-3 py-1 text-right">{{ number_format($cent[0] ?? 0, 2) }}</td>
                                                    <td class="px-3 py-1 text-right">{{ number_format($cent[1] ?? 0, 2) }}</td>
                                                    <td class="px-3 py-1 text-right">{{ number_format($cent[2] ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @foreach(($dbg['iterations'] ?? []) as $iter)
                                <div>
                                    <h4 class="font-semibold mb-2">Tabel III.3/III.5 — Hasil Perhitungan Iterasi {{ $iter['iteration'] }}</h4>
                                    <div class="overflow-auto border rounded">
                                        <table class="min-w-full text-xs">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-3 py-2 text-left">Data ke i</th>
                                                    <th class="px-3 py-2 text-right">C1</th>
                                                    <th class="px-3 py-2 text-right">C2</th>
                                                    <th class="px-3 py-2 text-right">C3</th>
                                                    <th class="px-3 py-2 text-left">Terdekat</th>
                                                    <th class="px-3 py-2 text-left">Clustering</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(($iter['distances'] ?? []) as $rowIdx => $dists)
                                                    @php
                                                        $c1 = number_format($dists[0] ?? 0, 2);
                                                        $c2 = number_format($dists[1] ?? 0, 2);
                                                        $c3 = number_format($dists[2] ?? 0, 2);
                                                        $minC = array_keys($dists, min($dists))[0] ?? 0;
                                                    @endphp
                                                    <tr class="border-t">
                                                        <td class="px-3 py-1">{{ $rowIdx + 1 }}</td>
                                                        <td class="px-3 py-1 text-right">{{ $c1 }}</td>
                                                        <td class="px-3 py-1 text-right">{{ $c2 }}</td>
                                                        <td class="px-3 py-1 text-right">{{ $c3 }}</td>
                                                        <td class="px-3 py-1">{{ $minC === 0 ? '0,00' : ($minC === 1 ? '0,00' : '0,00') }}</td>
                                                        <td class="px-3 py-1">C{{ $minC + 1 }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach

                            <div>
                                <h4 class="font-semibold mb-2">Tabel III.4 — Centroid Baru / Akhir</h4>
                                <div class="overflow-auto border rounded">
                                    <table class="min-w-full text-xs">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left">C</th>
                                                <th class="px-3 py-2 text-right">Absensi</th>
                                                <th class="px-3 py-2 text-right">Partisipasi</th>
                                                <th class="px-3 py-2 text-right">Prestasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(($dbg['final']['final_centroids'] ?? []) as $i => $cent)
                                                <tr class="border-t">
                                                    <td class="px-3 py-1">C{{ $i+1 }}</td>
                                                    <td class="px-3 py-1 text-right">{{ number_format($cent[0] ?? 0, 2) }}</td>
                                                    <td class="px-3 py-1 text-right">{{ number_format($cent[1] ?? 0, 2) }}</td>
                                                    <td class="px-3 py-1 text-right">{{ number_format($cent[2] ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="text-gray-500 text-xs">Detail K-Means belum tersedia. Klik Analisis untuk menghasilkan.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif



<script type="module">
    document.addEventListener('livewire:init', function() {
        // Check if ApexCharts is loaded
        if (typeof ApexCharts === 'undefined') {
            console.error('ApexCharts library not loaded!');
            return;
        }
        
        console.log('ApexCharts loaded successfully');
        
        let pieChart, barChart;
        
        // Create Pie Chart for Cluster Distribution
        const pieOptions = {
            series: [1, 1, 1], // Start with some data to ensure chart renders
            chart: {
                type: 'donut',
                height: 320,
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                }
            },
            labels: ['Sangat Aktif', 'Cukup Aktif', 'Perlu Motivasi'],
            colors: ['#10B981', '#FBBF24', '#EF4444'],
            legend: {
                position: 'bottom',
                formatter: function(seriesName, opts) {
                    return seriesName + ": " + opts.w.globals.series[opts.seriesIndex] + " siswa";
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val, opts) {
                    const total = opts.w.globals.series.reduce((a, b) => a + b, 0);
                    const percentage = ((opts.w.globals.series[opts.seriesIndex] / total) * 100).toFixed(1);
                    return opts.w.globals.series[opts.seriesIndex] + ' (' + percentage + '%)';
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " siswa";
                    }
                }
            }
        };
        
        const barOptions = {
            series: [
                {
                    name: 'Kehadiran',
                    data: [1, 1, 1] // Start with some data to ensure chart renders
                },
                {
                    name: 'Partisipasi',
                    data: [1, 1, 1]
                },
                {
                    name: 'Prestasi',
                    data: [1, 1, 1]
                }
            ],
            chart: {
                type: 'bar',
                height: 320,
                stacked: false,
                toolbar: {
                    show: true,
                    tools: {
                        download: true
                    }
                }
            },
            colors: ['#3B82F6', '#9333EA', '#F59E0B'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 2,
                    dataLabels: {
                        position: 'top'
                    }
                },
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return Number(val).toFixed(1) + '%';
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: ['Sangat Aktif', 'Cukup Aktif', 'Perlu Motivasi'],
            },
            yaxis: {
                title: {
                    text: 'Persentase (%)'
                },
                max: 100,
                min: 0,
                tickAmount: 5,
                labels: {
                    formatter: function(val) {
                        return Number(val).toFixed(1);
                    }
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function(val) {
                        return Number(val).toFixed(1) + "%";
                    }
                }
            },
            legend: {
                position: 'bottom'
            }
        };
        
        // Initialize charts
        function initializeCharts() {
            const pieElement = document.querySelector('#clusterDistribution');
            const barElement = document.querySelector('#clusterPerformance');
            
            console.log('Looking for chart elements:', {
                pieElement: pieElement,
                barElement: barElement,
                pieElementExists: !!pieElement,
                barElementExists: !!barElement
            });
            
            if (!pieElement || !barElement) {
                console.error('Chart elements not found!', {
                    pieElement: !!pieElement,
                    barElement: !!barElement
                });
                return;
            }
            
            // Clear existing content
            pieElement.innerHTML = '';
            barElement.innerHTML = '';
            
            // Only destroy charts if they exist and are not already destroyed
            if (pieChart && !pieChart.w.globals.dom.baseEl.classList.contains('apexcharts-destroyed')) {
                pieChart.destroy();
                pieChart = null;
            }
            if (barChart && !barChart.w.globals.dom.baseEl.classList.contains('apexcharts-destroyed')) {
                barChart.destroy();
                barChart = null;
            }
            
            // Wait a bit for DOM to be ready
            setTimeout(() => {
                try {
                    pieChart = new ApexCharts(pieElement, pieOptions);
                    barChart = new ApexCharts(barElement, barOptions);
                    
                    pieChart.render().then(() => {
                        console.log('Pie chart rendered successfully');
                        // Update with real data if available
                        if (window.chartData && window.chartData.clusterCounts) {
                            pieChart.updateSeries(window.chartData.clusterCounts);
                        }
                    }).catch(error => {
                        console.error('Error rendering pie chart:', error);
                    });
                    
                    barChart.render().then(() => {
                        console.log('Bar chart rendered successfully');
                        // Update with real data if available
                        if (window.chartData && window.chartData.performanceData) {
                            barChart.updateSeries(window.chartData.performanceData);
                        }
                    }).catch(error => {
                        console.error('Error rendering bar chart:', error);
                    });
                } catch (error) {
                    console.error('Error creating charts:', error);
                }
            }, 100);
        }
        
        // Initialize charts immediately
        initializeCharts();
        
        // Fallback: re-initialize charts if they fail to render
        setTimeout(() => {
            if (!pieChart || !barChart) {
                console.log('Charts not initialized, retrying...');
                initializeCharts();
            }
        }, 1000);
        
        // Event listener for chart updates
        Livewire.on('chartRender', (data) => {
            console.log('Chart render event received:', data);
            
            try {
                // Data berupa array dengan satu elemen, ambil elemen pertama
                const eventData = Array.isArray(data) ? data[0] : data;
                
                if (!eventData || !eventData.clusterStats) {
                    console.error('Invalid data received:', eventData);
                    return;
                }
                
                // Update pie chart data
                const clusterCounts = [0, 0, 0];
                
                // clusterStats adalah array, bukan object
                if (Array.isArray(eventData.clusterStats)) {
                    eventData.clusterStats.forEach((stats, index) => {
                        if (index >= 0 && index < 3) {
                            clusterCounts[index] = stats.count || 0;
                        }
                    });
                } else {
                    // Fallback untuk object format
                    Object.entries(eventData.clusterStats).forEach(([cluster, stats]) => {
                        const clusterIndex = parseInt(cluster);
                        if (!isNaN(clusterIndex) && clusterIndex >= 0 && clusterIndex < 3) {
                            clusterCounts[clusterIndex] = stats.count || 0;
                        }
                    });
                }
                
                console.log('Cluster counts:', clusterCounts);
                
                // Update bar chart data
                const attendance = [0, 0, 0];
                const participation = [0, 0, 0];
                const achievement = [0, 0, 0];
                
                if (Array.isArray(eventData.clusterStats)) {
                    eventData.clusterStats.forEach((stats, index) => {
                        if (index >= 0 && index < 3) {
                            attendance[index] = parseFloat(stats.avg_attendance) || 0;
                            participation[index] = parseFloat(stats.avg_participation) || 0;
                            achievement[index] = parseFloat(stats.avg_achievement) || 0;
                        }
                    });
                } else {
                    // Fallback untuk object format
                    Object.entries(eventData.clusterStats).forEach(([cluster, stats]) => {
                        const clusterIndex = parseInt(cluster);
                        if (!isNaN(clusterIndex) && clusterIndex >= 0 && clusterIndex < 3) {
                            attendance[clusterIndex] = parseFloat(stats.avg_attendance) || 0;
                            participation[clusterIndex] = parseFloat(stats.avg_participation) || 0;
                            achievement[clusterIndex] = parseFloat(stats.avg_achievement) || 0;
                        }
                    });
                }
                
                console.log('Performance data:', {
                    attendance,
                    participation,
                    achievement
                });
                
                // Store chart data in window object for persistence
                window.chartData = {
                    clusterCounts: clusterCounts,
                    performanceData: [
                        { name: 'Kehadiran', data: attendance },
                        { name: 'Partisipasi', data: participation },
                        { name: 'Prestasi', data: achievement }
                    ]
                };
                
                // Update charts with a small delay to ensure they're ready
                setTimeout(() => {
                    if (pieChart && !pieChart.w.globals.dom.baseEl.classList.contains('apexcharts-destroyed')) {
                        pieChart.updateSeries(clusterCounts).then(() => {
                            console.log('Pie chart updated successfully');
                        }).catch(error => {
                            console.error('Error updating pie chart:', error);
                        });
                    } else {
                        console.log('Pie chart not available for update, re-initializing...');
                        initializeCharts();
                        // Update with data after re-initialization
                        setTimeout(() => {
                            if (pieChart) {
                                pieChart.updateSeries(clusterCounts);
                            }
                        }, 500);
                    }
                    
                    if (barChart && !barChart.w.globals.dom.baseEl.classList.contains('apexcharts-destroyed')) {
                        barChart.updateSeries([
                            { name: 'Kehadiran', data: attendance },
                            { name: 'Partisipasi', data: participation },
                            { name: 'Prestasi', data: achievement }
                        ]).then(() => {
                            console.log('Bar chart updated successfully');
                        }).catch(error => {
                            console.error('Error updating bar chart:', error);
                        });
                    } else {
                        console.log('Bar chart not available for update, re-initializing...');
                        initializeCharts();
                        // Update with data after re-initialization
                        setTimeout(() => {
                            if (barChart) {
                                barChart.updateSeries([
                                    { name: 'Kehadiran', data: attendance },
                                    { name: 'Partisipasi', data: participation },
                                    { name: 'Prestasi', data: achievement }
                                ]);
                            }
                        }, 500);
                    }
                }, 200);
                
            } catch (error) {
                console.error('Error updating charts:', error);
            }
        });
    });
</script>