<?php

namespace App\Livewire\AnalisisApps;

use Livewire\Component;
use App\Services\KmeansServiceWithBobot;
use App\Models\EskulMember;
use App\Models\Eskul;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Facades\DB;

class DetailSiswa extends Component
{
    public $selectedYear;
    public $selectedSemester;
    public $selectedMonth = '';
    public $selectedClass = '';
    public $selectedEskul = '';
    public $selectedCluster = '';
    public $results = false;
    public $studentMetrics = [];
    public $clusterStats = [];
    public $academicYears = [];
    public $classes = [];
    public $eskuls = [];
    public $months = [];
    
    // Properties untuk awards/penghargaan
    public $topPerformers = [];
    public $showAwards = false;

    public function mount($hash = null)
    {
        // Handle global analysis or specific student analysis
        if ($hash && $hash !== 'global') {
            // For specific student analysis in the future
            // $this->studentId = HashHelper::decrypt($hash);
        }
        
        $this->selectedYear = date('Y');
        $this->selectedSemester = (date('n') > 6) ? 1 : 2;
        $this->selectedMonth = date('n'); // Current month
        
        // Get list of academic years
        $this->academicYears = range(date('Y')-2, date('Y'));
        
        // Get available months
        $this->months = [
            1 => 'Januari',
            2 => 'Februari', 
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        // Get available classes
        $this->loadClasses();
        
        // Get available eskuls
        $this->loadEskuls();
    }

    public function loadClasses()
    {
        $this->classes = UserDetail::whereNotNull('class')
            ->distinct()
            ->pluck('class')
            ->sort()
            ->values()
            ->toArray();
    }

    public function loadEskuls()
    {
        $this->eskuls = Eskul::where('is_active', true)
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }

    public function analyze()
    {
        $kmeansService = new KmeansServiceWithBobot();
        
        // Base query untuk mendapatkan siswa
        $query = EskulMember::with(['student', 'student.detail', 'eskul'])
            ->where('is_active', true);
            
        // Apply filters
        if ($this->selectedEskul) {
            $query->where('eskul_id', $this->selectedEskul);
        }
        
        if ($this->selectedClass) {
            $query->whereHas('student.detail', function($q) {
                $q->where('class', $this->selectedClass);
            });
        }
        
        $students = $query->get();
        
        if ($students->isEmpty()) {
            session()->flash('message', 'Tidak ada data siswa yang sesuai dengan filter yang dipilih.');
            return;
        }
        
        // Calculate metrics for each student
        foreach ($students as $member) {
            $metrics = $kmeansService->calculateMetrics(
                $member->student_id, 
                $member->eskul_id,
                $this->selectedYear,
                $this->selectedSemester,
                $this->selectedMonth
            );
            
            // Store or update metrics
            DB::table('student_performance_metrics')
                ->updateOrInsert(
                    [
                        'student_id' => $member->student_id,
                        'eskul_id' => $member->eskul_id,
                        'year' => $this->selectedYear,
                        'semester' => $this->selectedSemester,
                        'month' => $this->selectedMonth
                    ],
                    [
                        'attendance_score' => $metrics['raw_attendance_score'],
                        'participation_score' => $metrics['raw_participation_score'],
                        'achievement_score' => $metrics['raw_achievement_score'],
                    ]
                );
        }
        
        // Perform clustering untuk setiap eskul yang terlibat
        $eskulIds = $students->pluck('eskul_id')->unique();
        foreach ($eskulIds as $eskulId) {
            $kmeansService->performClustering($eskulId);
        }
        
        // Get results with student details
        $this->loadResults();
        
        // Calculate cluster statistics
        $this->calculateClusterStats();
        
        // Generate awards/penghargaan
        $this->generateAwards();
        
        $this->results = true;
    }

    // Auto-reload results when filters change
    public function updatedSelectedClass()
    {
        if ($this->results) {
            $this->loadResults();
            $this->calculateClusterStats();
            $this->generateAwards();
        }
    }

    public function updatedSelectedEskul()
    {
        if ($this->results) {
            $this->loadResults();
            $this->calculateClusterStats();
            $this->generateAwards();
        }
    }

    public function updatedSelectedCluster()
    {
        if ($this->results) {
            $this->loadResults();
            $this->calculateClusterStats();
            $this->generateAwards();
        }
    }

    public function updatedSelectedMonth()
    {
        if ($this->results) {
            $this->loadResults();
            $this->calculateClusterStats();
            $this->generateAwards();
        }
    }

    private function loadResults()
    {
        $query = DB::table('student_performance_metrics')
            ->join('users', 'users.id', '=', 'student_performance_metrics.student_id')
            ->join('user_details', 'user_details.user_id', '=', 'users.id')
            ->join('eskuls', 'eskuls.id', '=', 'student_performance_metrics.eskul_id')
            ->where('student_performance_metrics.year', $this->selectedYear)
            ->where('student_performance_metrics.semester', $this->selectedSemester);
            
        // Add month filter if selected
        if ($this->selectedMonth) {
            $query->where('student_performance_metrics.month', $this->selectedMonth);
        }
        
        $query->select(
            'users.name as student_name',
            'user_details.class',
            'user_details.nis',
            'eskuls.name as eskul_name',
            'student_performance_metrics.*'
        );
            
        // Apply filters
        if ($this->selectedEskul) {
            $query->where('student_performance_metrics.eskul_id', $this->selectedEskul);
        }
        
        if ($this->selectedClass) {
            $query->where('user_details.class', $this->selectedClass);
        }
        
        if ($this->selectedCluster !== '') {
            $query->where('student_performance_metrics.cluster', $this->selectedCluster);
        }
        
        $this->studentMetrics = $query->orderBy('user_details.class')
            ->orderBy('users.name')
            ->get();
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
                    'label' => $this->getClusterLabel($i)
                ];
            }
        }
    }
    
    private function getClusterLabel($cluster)
    {
        return match($cluster) {
            0 => 'Sangat Aktif',
            1 => 'Cukup Aktif', 
            2 => 'Perlu Motivasi',
            default => 'Unknown'
        };
    }

    private function generateAwards()
    {
        $this->topPerformers = [];
        
        // Group by class if class filter is not applied
        if (!$this->selectedClass) {
            $groupedByClass = $this->studentMetrics->groupBy('class');
            
            foreach ($groupedByClass as $class => $classStudents) {
                // Ambil top performer per kelas (cluster 0 = sangat aktif)
                $topStudent = $classStudents->where('cluster', 0)
                    ->sortByDesc(function($student) {
                        return ($student->attendance_score + $student->participation_score + $student->achievement_score) / 3;
                    })
                    ->first();
                    
                if ($topStudent) {
                    $this->topPerformers[] = [
                        'student_name' => $topStudent->student_name,
                        'class' => $topStudent->class,
                        'eskul_name' => $topStudent->eskul_name,
                        'avg_score' => round(($topStudent->attendance_score + $topStudent->participation_score + $topStudent->achievement_score) / 3, 1),
                        'award_type' => 'Siswa Teraktif Kelas ' . $topStudent->class
                    ];
                }
            }
        } else {
            // Jika ada filter kelas, ambil top 3 dari kelas tersebut
            $topStudents = $this->studentMetrics->where('cluster', 0)
                ->sortByDesc(function($student) {
                    return ($student->attendance_score + $student->participation_score + $student->achievement_score) / 3;
                })
                ->take(3);
                
            foreach ($topStudents as $index => $student) {
                $awards = ['Juara 1', 'Juara 2', 'Juara 3'];
                $this->topPerformers[] = [
                    'student_name' => $student->student_name,
                    'class' => $student->class,
                    'eskul_name' => $student->eskul_name,
                    'avg_score' => round(($student->attendance_score + $student->participation_score + $student->achievement_score) / 3, 1),
                    'award_type' => $awards[$index] . ' Siswa Teraktif Kelas ' . $student->class
                ];
            }
        }
        
        // Group by eskul jika ada multiple eskul
        if (!$this->selectedEskul) {
            $groupedByEskul = $this->studentMetrics->groupBy('eskul_name');
            
            foreach ($groupedByEskul as $eskulName => $eskulStudents) {
                $topStudent = $eskulStudents->where('cluster', 0)
                    ->sortByDesc(function($student) {
                        return ($student->attendance_score + $student->participation_score + $student->achievement_score) / 3;
                    })
                    ->first();
                    
                if ($topStudent) {
                    $this->topPerformers[] = [
                        'student_name' => $topStudent->student_name,
                        'class' => $topStudent->class,
                        'eskul_name' => $topStudent->eskul_name,
                        'avg_score' => round(($topStudent->attendance_score + $topStudent->participation_score + $topStudent->achievement_score) / 3, 1),
                        'award_type' => 'Siswa Teraktif ' . $eskulName
                    ];
                }
            }
        }
    }

    public function toggleAwards()
    {
        $this->showAwards = !$this->showAwards;
    }

    public function exportResults()
    {
        // TODO: Implement export functionality
        session()->flash('message', 'Fitur export sedang dalam pengembangan.');
    }

    public function render()
    {
        return view('livewire.analisis-apps.detail-siswa');
    }
}
