<?php

namespace App\Livewire\EksulApps;

use Livewire\Component;
use App\Services\KmeansService;
use App\Models\EskulMember;
use App\Models\Eskul;
use App\Models\User;
use App\Helpers\HashHelper;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Exports\AnalysisReport;
use Maatwebsite\Excel\Facades\Excel;

class EskulAnalisis extends Component
{
    public $selectedYear;
    public $selectedSemester;
    public $eskulId;
    public $results = false;
    public $studentMetrics = [];
    public $clusterStats = [];
    public $academicYears = [];
    public $pieChartBase64;
    public $barChartBase64;
    public $selectedEskul;
    protected $listeners = ['chartImageUpdated' => 'saveChartImage'];
    
    // public function mount($hash)
    // {
    //     $id = HashHelper::decrypt($hash);
    //     $this->id = $id;
    // }
    public function mount($hash)
    {
        $this->eskulId = HashHelper::decrypt($hash);
        $this->selectedYear = date('Y');
        $this->selectedSemester = (date('n') > 6) ? 1 : 2;
        $this->selectedEskul = Eskul::find($this->eskulId);
        // Get list of academic years
        $this->academicYears = range(date('Y')-2, date('Y'));
    }

    public function analyze()
    {
        $kmeansService = new KmeansService();
        
        // Get all active students in the eskul using relationship
        $students = EskulMember::with('student')
            ->where('eskul_id', $this->eskulId)
            ->where('is_active', true)
            ->get();
            
        // Calculate metrics for each student
        foreach ($students as $member) {
            $metrics = $kmeansService->calculateMetrics(
                $member->student_id, 
                $this->eskulId,
                $this->selectedYear,
                $this->selectedSemester
            );
            
            // Store or update metrics
            DB::table('student_performance_metrics')
                ->updateOrInsert(
                    [
                        'student_id' => $member->student_id,
                        'eskul_id' => $this->eskulId,
                        'year' => $this->selectedYear,
                        'semester' => $this->selectedSemester
                    ],
                    [
                        'attendance_score' => $metrics['attendance_score'],
                        'participation_score' => $metrics['participation_score'],
                        'achievement_score' => $metrics['achievement_score'],
                    ]
                );
        }
        
        // Perform clustering
        $kmeansService->performClustering($this->eskulId);
        
        // Get results with student names
        $this->studentMetrics = DB::table('student_performance_metrics')
            ->join('users', 'users.id', '=', 'student_performance_metrics.student_id')
            ->where('eskul_id', $this->eskulId)
            ->select('users.name as student_name', 'student_performance_metrics.*')
            ->get();
            
        $this->calculateClusterStats();

        // Dispatch event dengan data untuk chart
        $this->dispatch('chartRender', [
            'clusterStats' => array_values($this->clusterStats), // Convert to indexed array
            'studentMetrics' => $this->studentMetrics
        ]);

        $this->results = true;
    }

    private function calculateClusterStats()
    {
        $this->clusterStats = [];
        
        for ($i = 0; $i < 3; $i++) {
            $clusterMetrics = $this->studentMetrics->where('cluster', $i);
            
            if ($clusterMetrics->count() > 0) {
                $this->clusterStats[$i] = [
                    'count' => $clusterMetrics->count(),
                    'avg_attendance' => $clusterMetrics->avg('attendance_score'),
                    'avg_participation' => $clusterMetrics->avg('participation_score'),
                    'avg_achievement' => $clusterMetrics->avg('achievement_score'),
                ];
            }
        }
    }

    public function saveChartImage($inputId, $base64Data)
    {
        if ($inputId === 'pieChartBase64') {
            $this->pieChartBase64 = $base64Data;
        } elseif ($inputId === 'barChartBase64') {
            $this->barChartBase64 = $base64Data;
        }
    }

    public function downloadReport()
    {
        if (!$this->results) {
            return;
        }
        
        // Get eskul name
        $eskul = Eskul::find($this->eskulId);
        
        // Define data for PDF
        $data = [
            'eskulName' => $eskul->name,
            'year' => $this->selectedYear,
            'semester' => $this->selectedSemester,
            'clusterStats' => $this->clusterStats,
            'studentMetrics' => $this->studentMetrics,
            'generatedAt' => Carbon::now()->format('d M Y H:i'),
            'pieChartBase64' => $this->pieChartBase64,
            'barChartBase64' => $this->barChartBase64
        ];
        
        // Generate PDF
        $pdf = PDF::loadView('pdf.analysis-report', $data);
        
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, "analisis-performa-{$eskul->name}-{$this->selectedYear}-semester-{$this->selectedSemester}.pdf");
    }

    public function downloadExcel()
    {
        if (!$this->results) {
            return;
        }
        
        $eskul = Eskul::find($this->eskulId);
        $filename = "analisis-performa-{$eskul->name}-{$this->selectedYear}-semester-{$this->selectedSemester}.xlsx";
        
        return Excel::download(new AnalysisReport(
            $this->studentMetrics, 
            $this->clusterStats, 
            $eskul->name,
            $this->selectedYear,
            $this->selectedSemester
        ), $filename);
    }

    public function render()
    {
        return view('livewire.eksul-apps.eskul-analisis');
    }
}
