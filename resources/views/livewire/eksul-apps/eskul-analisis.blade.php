<div class="p-6 bg-gray-50">
    <div class="mb-8 bg-white rounded-xl shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 border-b pb-3">Analisis Performa Siswa Eskul {{ $selectedEskul->name }}</h2>
        
        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-6">
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700">Tahun Akademik</label>
                <select wire:model="selectedYear" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach($academicYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700">Semester</label>
                <select wire:model="selectedSemester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                </select>
            </div>
            
            <div class="md:col-span-3">
                <button wire:click="analyze" wire:loading.attr="disabled" class="w-full mt-6 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center">
                    <div wire:loading.remove wire:target="analyze" class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Analisis
                    </div>
                    <div wire:loading wire:target="analyze" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </div>
                </button>
            </div>
            
            @if($results)
            <div class="md:col-span-3">
                <div class="grid grid-cols-2 gap-2">
                    <button wire:click="downloadReport" wire:loading.attr="disabled" class="w-full mt-6 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200 flex items-center justify-center">
                        <div wire:loading.remove wire:target="downloadReport" class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            PDF
                        </div>
                        <div wire:loading wire:target="downloadReport" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyiapkan...
                        </div>
                    </button>
                    
                    <button wire:click="downloadExcel" wire:loading.attr="disabled" class="w-full mt-6 px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition-colors duration-200 flex items-center justify-center">
                        <div wire:loading.remove wire:target="downloadExcel" class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Excel
                        </div>
                        <div wire:loading wire:target="downloadExcel" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyiapkan...
                        </div>
                    </button>
                </div>
            </div>
            @endif
        </div>
         <!-- Chart Section -->
        <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Visualisasi Data</h3>
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

        <!-- Results -->
        @if($results)
      
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8">
            <!-- Cluster Statistics -->
            @foreach($clusterStats as $cluster => $stats)
            <div class="bg-white p-5 rounded-lg shadow-md border-l-4 {{ $cluster == 0 ? 'border-green-500' : ($cluster == 1 ? 'border-yellow-500' : 'border-red-500') }} transition-transform duration-200 hover:scale-105">
                <h3 class="text-lg font-semibold mb-2 {{ $cluster == 0 ? 'text-green-600' : ($cluster == 1 ? 'text-yellow-600' : 'text-red-600') }}">
                    Cluster {{ $cluster + 1 }}
                    <span class="text-sm font-normal text-gray-500">
                        ({{ $cluster == 0 ? 'Tinggi' : ($cluster == 1 ? 'Sedang' : 'Rendah') }})
                    </span>
                </h3>
                <p class="text-sm text-gray-600 mb-2">Jumlah Siswa: <span class="font-semibold">{{ $stats['count'] }}</span></p>
                <div class="mt-3">
                    <p class="text-sm font-medium">Rata-rata:</p>
                    <div class="space-y-2 mt-2">
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span>Kehadiran</span>
                                <span class="font-medium">{{ number_format($stats['avg_attendance'], 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['avg_attendance'] }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span>Partisipasi</span>
                                <span class="font-medium">{{ number_format($stats['avg_participation'], 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $stats['avg_participation'] }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span>Prestasi</span>
                                <span class="font-medium">{{ number_format($stats['avg_achievement'], 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $stats['avg_achievement'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Student List -->
        <div class="bg-white rounded-xl shadow-md p-2 md:p-4">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 px-2 md:px-0">Daftar Siswa</h3>
            <div class="relative overflow-x-auto max-h-[400px] md:max-h-[500px] rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">Kehadiran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">Partisipasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">Prestasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">Cluster</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($studentMetrics as $metric)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $metric->student_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="mr-2 w-12 text-right">{{ number_format($metric->attendance_score, 1) }}%</span>
                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $metric->attendance_score }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="mr-2 w-12 text-right">{{ number_format($metric->participation_score, 1) }}%</span>
                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $metric->participation_score }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="mr-2 w-12 text-right">{{ number_format($metric->achievement_score, 1) }}%</span>
                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                            <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $metric->achievement_score }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $metric->cluster == 0 ? 'bg-green-100 text-green-800' : 
                                          ($metric->cluster == 1 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        Cluster {{ $metric->cluster + 1 }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
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
                labels: ['Cluster 1 (Tinggi)', 'Cluster 2 (Sedang)', 'Cluster 3 (Rendah)'],
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
                    categories: ['Cluster 1 (Tinggi)', 'Cluster 2 (Sedang)', 'Cluster 3 (Rendah)'],
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
            
            // Inisialisasi charts
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
            
            // Charts are protected by wire:ignore, no need to re-initialize
            
            // Perbaikan event listener
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

                    // Setelah chart diupdate, ambil base64 image
                    setTimeout(() => {
                        try {
                            // Get pie chart base64
                            if (pieChart) {
                                pieChart.dataURI().then(({ imgURI }) => {
                                    Livewire.dispatch('chartImageUpdated', ['pieChartBase64', imgURI]);
                                }).catch(error => {
                                    console.error('Error getting pie chart image:', error);
                                });
                            }
                            
                            // Get bar chart base64
                            if (barChart) {
                                barChart.dataURI().then(({ imgURI }) => {
                                    Livewire.dispatch('chartImageUpdated', ['barChartBase64', imgURI]);
                                }).catch(error => {
                                    console.error('Error getting bar chart image:', error);
                                });
                            }
                        } catch (error) {
                            console.error('Error getting chart images:', error);
                        }
                    }, 1000); // Delay 1 detik untuk memastikan chart sudah ter-render
                    
                } catch (error) {
                    console.error('Error updating charts:', error);
                }
            });
        });
    </script>
</div>

