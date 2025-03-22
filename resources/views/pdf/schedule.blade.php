<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jadwal Kegiatan Ekstrakurikuler</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
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
        .date {
            text-align: right;
            margin-bottom: 20px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #4f7dff;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 10px;
        }
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .eskul-title {
            background-color: #e6eeff;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Jadwal Kegiatan Ekstrakurikuler</h1>
    </div>
    
    <div class="date">
        Tanggal Cetak: {{ now()->format('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Ekstrakurikuler</th>
                <th>Hari</th>
                <th>Waktu</th>
                <th>Lokasi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $eskulName => $eskulSchedules)
                @foreach($eskulSchedules as $index => $schedule)
                    <tr>
                        @if($index === 0)
                            <td class="eskul-title" rowspan="{{ count($eskulSchedules) }}">{{ $eskulName }}</td>
                        @endif
                        <td>{{ $schedule->day }}</td>
                        <td>{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</td>
                        <td>{{ $schedule->location }}</td>
                        <td>{{ $schedule->notes ?? '-' }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada jadwal kegiatan yang tersedia</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>© {{ date('Y') }} Sistem Informasi Ekstrakurikuler</p>
    </div>
</body>
</html> 