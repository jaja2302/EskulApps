<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Analisis Performa Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #1a56db;
            margin-bottom: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .meta-info {
            margin-bottom: 20px;
            font-size: 14px;
        }
        .chart-container {
            width: 100%;
            margin: 20px 0;
        }
        .chart-container table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
        }
        .chart-container td {
            width: 50%;
            padding: 15px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            vertical-align: top;
        }
        .chart {
            text-align: center;
        }
        .chart img {
            max-width: 100%;
            height: auto;
        }
        .chart-title {
            font-weight: bold;
            margin-bottom: 15px;
            color: #4a5568;
            text-align: center;
            font-size: 14px;
        }
        .cluster-stats {
            width: 100%;
            margin: 20px 0;
        }
        .cluster-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
        }
        .cluster-cell {
            width: 33.33%;
            padding: 15px;
            background-color: #f8fafc;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            vertical-align: top;
        }
        .cluster-header {
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid;
        }
        .cluster-high {
            border-color: #10B981;
            color: #10B981;
        }
        .cluster-medium {
            border-color: #FBBF24;
            color: #FBBF24;
        }
        .cluster-low {
            border-color: #EF4444;
            color: #EF4444;
        }
        .metric {
            margin: 8px 0;
        }
        .progress-container {
            margin-top: 5px;
        }
        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 4px;
        }
        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
        }
        .student-table th {
            background-color: #4f7dff;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 8px;
        }
        .student-table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
        }
        .student-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Analisis Performa Siswa Ekstrakurikuler</h1>
    </div>
    
    <div class="meta-info">
        <table style="width: 100%">
            <tr>
                <td style="width: 50%">
                    <strong>Ekstrakurikuler:</strong> {{ $eskulName }}<br>
                    <strong>Tahun Akademik:</strong> {{ $year }}
                </td>
                <td style="width: 50%">
                    <strong>Semester:</strong> {{ $semester }}<br>
                    <strong>Tanggal Analisis:</strong> {{ $generatedAt }}
                </td>
            </tr>
        </table>
    </div>

    <div class="chart-container">
        <table>
            <tr>
                <td>
                    <div class="chart">
                        <div class="chart-title">Distribusi Cluster</div>
                        @if($pieChartBase64)
                            <img src="{{ $pieChartBase64 }}" alt="Distribusi Cluster">
                        @endif
                    </div>
                </td>
                <td>
                    <div class="chart">
                        <div class="chart-title">Performa Per Cluster</div>
                        @if($barChartBase64)
                            <img src="{{ $barChartBase64 }}" alt="Performa Per Cluster">
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="cluster-stats">
        <table class="cluster-table">
            <tr>
                @foreach($clusterStats as $cluster => $stats)
                <td class="cluster-cell">
                    <div class="cluster-header {{ $cluster == 0 ? 'cluster-high' : ($cluster == 1 ? 'cluster-medium' : 'cluster-low') }}">
                        Cluster {{ $cluster + 1 }} 
                        ({{ $cluster == 0 ? 'Tinggi' : ($cluster == 1 ? 'Sedang' : 'Rendah') }})
                    </div>
                    <div style="font-size: 14px; margin-bottom: 10px;">
                        Jumlah Siswa: <strong>{{ $stats['count'] }}</strong>
                    </div>
                    <div class="metric">
                        <div style="display: flex; justify-content: space-between; font-size: 12px;">
                            <span>Kehadiran</span>
                            <span>{{ number_format($stats['avg_attendance'], 1) }}%</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $stats['avg_attendance'] }}%; background-color: #3B82F6;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="metric">
                        <div style="display: flex; justify-content: space-between; font-size: 12px;">
                            <span>Partisipasi</span>
                            <span>{{ number_format($stats['avg_participation'], 1) }}%</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $stats['avg_participation'] }}%; background-color: #9333EA;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="metric">
                        <div style="display: flex; justify-content: space-between; font-size: 12px;">
                            <span>Prestasi</span>
                            <span>{{ number_format($stats['avg_achievement'], 1) }}%</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $stats['avg_achievement'] }}%; background-color: #F59E0B;"></div>
                            </div>
                        </div>
                    </div>
                </td>
                @endforeach
            </tr>
        </table>
    </div>
    <div style='page-break-before: always;'></div>
    <h3>Daftar Siswa</h3>
    <table class="student-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kehadiran</th>
                <th>Partisipasi</th>
                <th>Prestasi</th>
                <th>Cluster</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentMetrics as $metric)
                <tr>
                    <td>{{ $metric->student_name }}</td>
                    <td>{{ number_format($metric->attendance_score, 1) }}%</td>
                    <td>{{ number_format($metric->participation_score, 1) }}%</td>
                    <td>{{ number_format($metric->achievement_score, 1) }}%</td>
                    <td>Cluster {{ $metric->cluster + 1 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Sistem Informasi Ekstrakurikuler</p>
    </div>
</body>
</html> 