<div class="p-4">
    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-4">Analisis Performa Siswa</h2>
        
        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tahun Akademik</label>
                <select wire:model="selectedYear" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach($academicYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Semester</label>
                <select wire:model="selectedSemester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                </select>
            </div>
            
            <div>
                <button wire:click="analyze" class="mt-6 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Analisis
                </button>
            </div>
        </div>

        <!-- Results -->
        @if($results)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Cluster Statistics -->
            @foreach($clusterStats as $cluster => $stats)
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-2">Cluster {{ $cluster + 1 }}</h3>
                <p class="text-sm text-gray-600">Jumlah Siswa: {{ $stats['count'] }}</p>
                <div class="mt-2">
                    <p class="text-sm">Rata-rata:</p>
                    <ul class="list-disc list-inside text-sm text-gray-600">
                        <li>Kehadiran: {{ number_format($stats['avg_attendance'], 1) }}%</li>
                        <li>Partisipasi: {{ number_format($stats['avg_participation'], 1) }}%</li>
                        <li>Prestasi: {{ number_format($stats['avg_achievement'], 1) }}%</li>
                    </ul>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Student List -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partisipasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prestasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cluster</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($studentMetrics as $metric)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $metric->student_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($metric->attendance_score, 1) }}%</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($metric->participation_score, 1) }}%</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($metric->achievement_score, 1) }}%</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $metric->cluster + 1 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
