<div class="space-y-6">
    <!-- Header Section -->
    <div class="text-center">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Dashboard Pimpinan</h3>
        <p class="text-gray-600 dark:text-gray-300">Laporan Evaluasi dan Motivasi Siswa</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Reports Card -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-6 rounded-xl border border-blue-200 dark:border-blue-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 dark:text-blue-400 text-sm font-medium">Total Laporan</p>
                    <p class="text-2xl font-bold text-blue-800 dark:text-blue-200">{{ $totalReports ?? 0 }}</p>
                </div>
                <div class="p-3 bg-blue-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Reviews Card -->
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 p-6 rounded-xl border border-yellow-200 dark:border-yellow-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 dark:text-yellow-400 text-sm font-medium">Menunggu Review</p>
                    <p class="text-2xl font-bold text-yellow-800 dark:text-yellow-200">{{ $pendingReports ?? 0 }}</p>
                </div>
                <div class="p-3 bg-yellow-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Students Need Motivation Card -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 p-6 rounded-xl border border-red-200 dark:border-red-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 dark:text-red-400 text-sm font-medium">Perlu Motivasi</p>
                    <p class="text-2xl font-bold text-red-800 dark:text-red-200">{{ $studentsNeedMotivation ?? 0 }}</p>
                </div>
                <div class="p-3 bg-red-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Aksi Cepat</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('analisis-apps.detail-siswa', 'global') }}" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                <div class="p-2 bg-blue-500 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h5 class="font-medium text-gray-800 dark:text-white">Lihat Analisis Siswa</h5>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Akses laporan detail evaluasi siswa</p>
                </div>
            </a>
            
            <button wire:click="viewMotivationReports" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                <div class="p-2 bg-green-500 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h5 class="font-medium text-gray-800 dark:text-white">Laporan Motivasi</h5>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Lihat laporan siswa yang perlu motivasi</p>
                </div>
            </button>
        </div>
    </div>

    <!-- Recent Motivation Reports -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Laporan Motivasi Terbaru</h4>
            <button wire:click="refreshReports" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </div>
        
        @if(isset($recentReports) && $recentReports->count() > 0)
            <div class="space-y-3">
                @foreach($recentReports as $report)
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors cursor-pointer"
                         wire:click="showReportDetail({{ $report->id }})">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h5 class="font-medium text-gray-800 dark:text-white">{{ $report->student->name }}</h5>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if($report->cluster == 2) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @elseif($report->cluster == 1) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                        {{ $report->cluster_label }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">{{ $report->eskul->name }}</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">{{ Str::limit($report->motivation_reason, 100) }}</p>
                            </div>
                            <div class="ml-4 text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($report->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($report->status == 'reviewed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                    {{ $report->status_label }}
                                </span>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $report->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Klik untuk detail</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Belum ada laporan motivasi</p>
            </div>
        @endif
    </div>

</div>

