<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AnalysisReport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithMapping, ShouldAutoSize
{
    protected $studentMetrics;
    protected $clusterStats;
    protected $eskulName;
    protected $year;
    protected $semester;
    
    public function __construct($studentMetrics, $clusterStats, $eskulName, $year, $semester)
    {
        $this->studentMetrics = $studentMetrics;
        $this->clusterStats = $clusterStats;
        $this->eskulName = $eskulName;
        $this->year = $year;
        $this->semester = $semester;
    }

    public function collection()
    {
        return collect($this->studentMetrics);
    }

    public function map($metric): array
    {
        return [
            $metric->student_name,
            number_format($metric->attendance_score, 1),
            number_format($metric->participation_score, 1),
            number_format($metric->achievement_score, 1),
            'Cluster ' . ($metric->cluster + 1) . ' (' . 
            ($metric->cluster == 0 ? 'Tinggi' : 
            ($metric->cluster == 1 ? 'Sedang' : 'Rendah')) . ')'
        ];
    }

    public function headings(): array
    {
        return [
            ['Analisis Performa Siswa Ekstrakurikuler'],
            [''],
            ["Ekstrakurikuler: {$this->eskulName}"],
            ["Tahun Akademik: {$this->year}"],
            ["Semester: {$this->semester}"],
            [''],
            [
                'Nama Siswa',
                'Kehadiran (%)',
                'Partisipasi (%)',
                'Prestasi (%)',
                'Cluster'
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells untuk judul
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A3:E3');
        $sheet->mergeCells('A4:E4');
        $sheet->mergeCells('A5:E5');

        // Style untuk judul
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Style untuk info ekstrakurikuler
        $sheet->getStyle('A3:A5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);

        // Style untuk header tabel
        $sheet->getStyle('A7:E7')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '4F7DFF',
                ],
            ],
            'font' => [
                'color' => [
                    'rgb' => 'FFFFFF',
                ],
            ],
        ]);

        // Auto-size columns
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Alignment untuk semua cells
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:E' . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B8:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [
            7 => ['font' => ['bold' => true]], // Header row
        ];
    }

    public function title(): string
    {
        return 'Analisis Performa';
    }
} 