<div>
    <x-filament::modal id="report-detail-modal" width="4xl">
        <x-slot name="heading">
            Detail Laporan Motivasi
        </x-slot>

        @if($selectedReport)
        <div class="space-y-6">
            <!-- Student Information -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Informasi Siswa</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Nama:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-2">{{ $selectedReport->student->name }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Eskul:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-2">{{ $selectedReport->eskul->name }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Cluster:</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            @if($selectedReport->cluster == 2) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @elseif($selectedReport->cluster == 1) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif ml-2">
                            {{ $selectedReport->cluster_label }}
                        </span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Status:</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($selectedReport->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($selectedReport->status == 'reviewed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif ml-2">
                            {{ $selectedReport->status_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Nilai Performa Siswa</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($selectedReport->attendance_score, 1) }}%</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Kehadiran</div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $selectedReport->attendance_score }}%"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($selectedReport->participation_score, 1) }}%</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Partisipasi</div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mt-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $selectedReport->participation_score }}%"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($selectedReport->achievement_score, 1) }}%</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Prestasi</div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mt-2">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $selectedReport->achievement_score }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <div class="text-lg font-semibold text-gray-900 dark:text-white">
                        Rata-rata: {{ number_format(($selectedReport->attendance_score + $selectedReport->participation_score + $selectedReport->achievement_score) / 3, 1) }}%
                    </div>
                </div>
            </div>

            <!-- Motivation Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Alasan Perlu Motivasi</h4>
                    <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">{{ $selectedReport->motivation_reason }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Rekomendasi Perbaikan</h4>
                    <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">{{ $selectedReport->recommendation }}</p>
                </div>
            </div>

            <!-- Report Info -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Informasi Laporan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600 dark:text-gray-300">Dibuat oleh:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-2">{{ $selectedReport->createdBy->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-300">Tanggal dibuat:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-2">{{ $selectedReport->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-300">Periode:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-2">
                            {{ $selectedReport->year }} - Semester {{ $selectedReport->semester }}
                            @if($selectedReport->month)
                                - Bulan {{ $selectedReport->month }}
                            @endif
                        </span>
                    </div>
                    @if($selectedReport->reviewed_at)
                    <div>
                        <span class="text-gray-600 dark:text-gray-300">Direview pada:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-2">{{ $selectedReport->reviewed_at->format('d M Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </x-filament::modal>
</div>
