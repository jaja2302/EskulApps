<?php

namespace App\Livewire\AnalisisApps;

use Livewire\Component;
use App\Services\KmeansService;
use App\Models\EskulMember;
use App\Models\Eskul;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DetailSiswa extends Component
{
    public $selectedYear;
    public $selectedSemester;
    public $selectedMonth = '';
    public $selectedClass = '';
    public $selectedSpecificClass = '';
    public $selectedEskul = '';
    public $selectedCluster = '';
    public $results = false;
    public $studentMetrics = [];
    public $clusterStats = [];
    public $academicYears = [];
    public $classes = [];
    public $specificClasses = [];
    public $eskuls = [];
    public $months = [];
    
    // Reactive property untuk memaksa re-render
    public $monthsUpdated = 0;
    
    // Properties untuk awards/penghargaan
    public $topPerformers = [];
    public $showAwards = false;
    
    // Debug info untuk menampilkan detail proses K-Means di UI
    public $kmeansDebug = [];

    public function mount($hash = null)
    {
        // Handle global analysis or specific student analysis
        if ($hash && $hash !== 'global') {
            // For specific student analysis in the future
            // $this->studentId = HashHelper::decrypt($hash);
        }
        
        $this->selectedYear = date('Y');
        $this->selectedSemester = (date('n') > 6) ? 2 : 1; // Jika bulan > 6 (Juli-Des) = Semester 2, sebaliknya Semester 1
        $this->selectedMonth = ''; // Default to all months
        
        // Get list of academic years
        $this->academicYears = range(date('Y')-2, date('Y'));
        
        // Load months based on semester
        $this->loadAvailableMonths();
        
        // Get available classes
        $this->loadClasses();
        $this->loadSpecificClasses();
        
        // Get available eskuls
        $this->loadEskuls();
    }

    public function loadClasses()
    {
        // Hardcoded general class values (tingkat kelas)
        $this->classes = ['X', 'XI', 'XII'];
    }

    public function loadSpecificClasses()
    {
        // Load specific classes from database
        $this->specificClasses = UserDetail::whereNotNull('class')
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

    private function filterByGeneralClass($collection, $classField = 'class')
    {
        if (!$this->selectedClass) {
            return $collection;
        }

        return $collection->filter(function($item) use ($classField) {
            $className = $item->$classField ?? '';
            
            // Exact matching for general class filter
            if ($this->selectedClass === 'X') {
                return strpos($className, 'X ') === 0 || $className === 'X';
            } elseif ($this->selectedClass === 'XI') {
                return strpos($className, 'XI ') === 0 || $className === 'XI';
            } elseif ($this->selectedClass === 'XII') {
                return strpos($className, 'XII ') === 0 || $className === 'XII';
            }
            
            return false;
        });
    }

    public function loadAvailableMonths()
    {
        // Filter bulan berdasarkan semester yang dipilih
        if ($this->selectedSemester == 1) {
            // Semester 1: Januari - Juni
            $this->months = [
                1 => 'Januari',
                2 => 'Februari', 
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni'
            ];
        } else {
            // Semester 2: Juli - Desember
            $this->months = [
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];
        }
        
        // Reset pilihan bulan jika bulan yang dipilih tidak valid untuk semester ini
        if ($this->selectedMonth && !array_key_exists((int)$this->selectedMonth, $this->months)) {
            $this->selectedMonth = '';
        }
        
        // Increment reactive property untuk memaksa re-render
        $this->monthsUpdated++;
    }

    public function analyze()
    {
        $kmeansService = new KmeansService();
        
        // Base query untuk mendapatkan siswa
        $query = EskulMember::with(['student', 'student.detail', 'eskul'])
            ->where('is_active', true);
            
        // Apply filters
        if ($this->selectedEskul) {
            $query->where('eskul_id', $this->selectedEskul);
        }
        
        if ($this->selectedSpecificClass) {
            $query->whereHas('student.detail', function($q) {
                $q->where('class', $this->selectedSpecificClass);
            });
        }
        
        $students = $query->get();
        
        // Apply general class filter after query (post-query filtering)
        if ($this->selectedClass) {
            $students = $students->filter(function($member) {
                $className = $member->student->detail->class ?? '';
                
                if ($this->selectedClass === 'X') {
                    return strpos($className, 'X ') === 0 || $className === 'X';
                } elseif ($this->selectedClass === 'XI') {
                    return strpos($className, 'XI ') === 0 || $className === 'XI';
                } elseif ($this->selectedClass === 'XII') {
                    return strpos($className, 'XII ') === 0 || $className === 'XII';
                }
                
                return false;
            });
        }
        
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
                            'month' => $this->selectedMonth !== '' ? (int)$this->selectedMonth : null
                    ],
                    [
                        'attendance_score' => $metrics['attendance_score'],
                        'participation_score' => $metrics['participation_score'],
                        'achievement_score' => $metrics['achievement_score'],
                    ]
                );
        }
        
        // Perform clustering untuk setiap eskul yang terlibat, HANYA untuk siswa sesuai filter
        $eskulIds = $students->pluck('eskul_id')->unique();
        foreach ($eskulIds as $eskulId) {
            $studentIdsForEskul = $students->where('eskul_id', $eskulId)->pluck('student_id')->values()->all();
            
            // Set period pada KmeansService sebelum clustering
            $monthParam = $this->selectedMonth !== '' ? (int)$this->selectedMonth : null;
            $kmeansService->setPeriod($this->selectedYear, $this->selectedSemester, $monthParam);
            
            $kmeansService->performClustering($eskulId, $studentIdsForEskul);
            // Simpan informasi debug per eskul untuk ditampilkan di UI, tetap sesuai filter
            $this->kmeansDebug[$eskulId] = $kmeansService->getDebugInfo();
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
    public function updatedSelectedSemester()
    {
        // Update available months when semester changes
        $this->loadAvailableMonths();
        
        // Force UI to refresh by dispatching an event
        $this->dispatch('semester-changed');
        
        if ($this->results) {
            $this->loadResults();
            $this->calculateClusterStats();
            $this->generateAwards();
        }
    }

    public function updatedSelectedClass()
    {
        if ($this->results) {
            $this->loadResults();
            $this->calculateClusterStats();
            $this->generateAwards();
        }
    }

    public function updatedSelectedSpecificClass()
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

    private function getPeriodRange(): array
    {
        if ($this->selectedMonth && $this->selectedMonth !== '') {
            $start = Carbon::create($this->selectedYear, (int) $this->selectedMonth, 1)->startOfDay();
            $end = Carbon::create($this->selectedYear, (int) $this->selectedMonth, 1)->endOfMonth()->endOfDay();
        } else {
            if ((int) $this->selectedSemester === 1) {
                // Semester 1: Januari - Juni
                $start = Carbon::create($this->selectedYear, 1, 1)->startOfDay();
                $end = Carbon::create($this->selectedYear, 6, 30)->endOfDay();
            } else {
                // Semester 2: Juli - Desember
                $start = Carbon::create($this->selectedYear, 7, 1)->startOfDay();
                $end = Carbon::create($this->selectedYear, 12, 31)->endOfDay();
            }
        }
        return ['start' => $start, 'end' => $end];
    }

    private function loadResults()
    {
        $period = $this->getPeriodRange();
        $start = $period['start'];
        $end = $period['end'];

        $query = DB::table('student_performance_metrics')
            ->join('users', 'users.id', '=', 'student_performance_metrics.student_id')
            ->join('user_details', 'user_details.user_id', '=', 'users.id')
            ->join('eskuls', 'eskuls.id', '=', 'student_performance_metrics.eskul_id')
            ->where('student_performance_metrics.year', $this->selectedYear)
            ->where('student_performance_metrics.semester', $this->selectedSemester);
            
        // Add month filter if selected; otherwise gunakan agregat semester (month NULL)
        if ($this->selectedMonth && $this->selectedMonth !== '') {
            $query->where('student_performance_metrics.month', (int)$this->selectedMonth);
        } else {
            $query->whereNull('student_performance_metrics.month');
        }
        
        $query->select(
            'users.name as student_name',
            'user_details.class',
            'user_details.nis',
            'eskuls.name as eskul_name',
            'student_performance_metrics.*'
        );

        // Breakdown subqueries: attendance
        $query->selectSub(function($q) use ($start, $end) {
            $q->from('attendances as a')
                ->selectRaw('COUNT(*)')
                ->whereColumn('a.eskul_id', 'student_performance_metrics.eskul_id')
                ->whereBetween('a.date', [$start, $end]);
        }, 'att_total');

        $query->selectSub(function($q) use ($start, $end) {
            $q->from('attendances as a')
                ->selectRaw('COUNT(*)')
                ->whereColumn('a.eskul_id', 'student_performance_metrics.eskul_id')
                ->whereColumn('a.student_id', 'student_performance_metrics.student_id')
                ->where('a.status', 'hadir')
                ->where('a.is_verified', 1)
                ->whereBetween('a.date', [$start, $end]);
        }, 'att_present');

        // Events breakdown
        $query->selectSub(function($q) use ($start, $end) {
            $q->from('eskul_events as e')
                ->selectRaw('COUNT(*)')
                ->whereColumn('e.eskul_id', 'student_performance_metrics.eskul_id')
                ->whereBetween('e.start_datetime', [$start, $end]);
        }, 'ev_total');

        $query->selectSub(function($q) use ($start, $end) {
            $q->from('eskul_event_participants as p')
                ->join('eskul_events as e', 'e.id', '=', 'p.event_id')
                ->selectRaw('COUNT(*)')
                ->whereColumn('e.eskul_id', 'student_performance_metrics.eskul_id')
                ->whereColumn('p.student_id', 'student_performance_metrics.student_id')
                ->whereBetween('e.start_datetime', [$start, $end]);
        }, 'ev_participated');

        // Achievements breakdown
        $query->selectSub(function($q) use ($start, $end) {
            $q->from('achievements as ac')
                ->selectRaw('COUNT(*)')
                ->whereColumn('ac.eskul_id', 'student_performance_metrics.eskul_id')
                ->whereColumn('ac.student_id', 'student_performance_metrics.student_id')
                ->whereBetween('ac.achievement_date', [$start, $end]);
        }, 'ach_count');
            
        // Apply filters
        if ($this->selectedEskul) {
            $query->where('student_performance_metrics.eskul_id', $this->selectedEskul);
        }
        
        if ($this->selectedSpecificClass) {
            $query->where('user_details.class', $this->selectedSpecificClass);
        }
        
        if ($this->selectedCluster !== '') {
            $query->where('student_performance_metrics.cluster', $this->selectedCluster);
        }
        
        // Hindari duplikasi baris (mis. kombinasi student_id + eskul_id ganda karena month NULL vs "")
        $this->studentMetrics = $query->select(
                'users.name as student_name',
                'user_details.class',
                'user_details.nis',
                'eskuls.name as eskul_name',
                'student_performance_metrics.student_id',
                'student_performance_metrics.eskul_id',
                DB::raw('MAX(student_performance_metrics.attendance_score) as attendance_score'),
                DB::raw('MAX(student_performance_metrics.participation_score) as participation_score'),
                DB::raw('MAX(student_performance_metrics.achievement_score) as achievement_score'),
                DB::raw('MAX(student_performance_metrics.cluster) as cluster')
            )
            ->groupBy('student_performance_metrics.student_id', 'student_performance_metrics.eskul_id', 'users.name', 'user_details.class', 'user_details.nis', 'eskuls.name')
            ->orderBy('user_details.class')
            ->orderBy('users.name')
            ->get();

        // Apply general class filter after query (post-query filtering)
        if ($this->selectedClass) {
            $this->studentMetrics = $this->studentMetrics->filter(function($metric) {
                $className = $metric->class ?? '';
                
                if ($this->selectedClass === 'X') {
                    return strpos($className, 'X ') === 0 || $className === 'X';
                } elseif ($this->selectedClass === 'XI') {
                    return strpos($className, 'XI ') === 0 || $className === 'XI';
                } elseif ($this->selectedClass === 'XII') {
                    return strpos($className, 'XII ') === 0 || $className === 'XII';
                }
                
                return false;
            });
        }
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
                ->take(3)
                ->values();
                
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
