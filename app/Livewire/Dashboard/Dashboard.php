<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\User;
use App\Models\eskul;
use App\Models\Achievement;
use App\Models\EskulEventParticipant;
use App\Models\Announcements;
use App\Models\EskulSchedule;
use App\Models\Attendance;
use App\Models\EskulMember;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class Dashboard extends Component
{

    public $userCount;
    public $eskulCount;
    public $achievementCount;
    public $eventParticipantCount;
    public $pelatihCount;
    public $announcements;
    public $upcomingSchedules;
    
    // New properties for student dashboard
    public $myEskuls;
    public $eskulStats;
    public $monthlyAttendance;
    public $recentAchievements;
    public $overallProgress;
    public $attendanceRate;
    public $totalSessions;
    public $attendedSessions;
    
    public function mount()
    {
        $this->generateData();
     
    }

    public function refreshStats()
    {
        $this->generateData();
        $this->dispatch('stats-refreshed');
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard');
    }
        
    private function generateData(){
        // Data yang selalu tersedia untuk semua role
        $this->userCount = User::count();
        $this->pelatihCount = Role::where('name', 'Pelatih')->first()->users()->count();
        
        // Pengumuman yang sama untuk semua role
        $this->announcements = Announcements::with('eskul', 'createdBy')
            ->where('expire_at', '>=', now())
            ->latest('publish_at')
            ->take(5)
            ->get();
        
        // Logika khusus berdasarkan role
        if(auth()->user()->hasRole('admin')){
            // Admin melihat semua data
            $this->eskulCount = Eskul::count();
            $this->achievementCount = Achievement::count();
            $this->eventParticipantCount = EskulEventParticipant::count();
            
            // Admin melihat semua jadwal
            $this->upcomingSchedules = EskulSchedule::with('eskul')
                ->where('is_active', true)
                ->take(5)
                ->get();
        }
        elseif(auth()->user()->hasRole('pelatih')){
            $userId = auth()->user()->id;
            
            // Pelatih hanya melihat data yang terkait dengannya
            $this->eskulCount = Eskul::where('pelatih_id', $userId)->count();
            
            // Changed to use eskul relationship to find achievements
            $this->achievementCount = Achievement::whereHas('eskul', function($query) use ($userId) {
                $query->where('pelatih_id', $userId);
            })->count();
            
            $this->eventParticipantCount = EskulEventParticipant::whereHas('event.eskul', function($query) use ($userId) {
                $query->where('pelatih_id', $userId);
            })->count();
            
            // Pelatih hanya melihat jadwal eskul yang diasuhnya
            $this->upcomingSchedules = EskulSchedule::with('eskul')
                ->where('is_active', true)
                ->whereHas('eskul', function($query) use ($userId) {
                    $query->where('pelatih_id', $userId);
                })
                ->take(5)
                ->get();
        }
        elseif(auth()->user()->hasRole('pembina')){
            $userId = auth()->user()->id;
            
            // Pembina hanya melihat data yang terkait dengannya
            $this->eskulCount = Eskul::where('pembina_id', $userId)->count();
            
            // Changed to use eskul relationship to find achievements
            $this->achievementCount = Achievement::whereHas('eskul', function($query) use ($userId) {
                $query->where('pembina_id', $userId);
            })->count();
            
            // Changed to use event.eskul relationship
            $this->eventParticipantCount = EskulEventParticipant::whereHas('event.eskul', function($query) use ($userId) {
                $query->where('pembina_id', $userId);
            })->count();
            
            // Pembina hanya melihat jadwal eskul yang dibinanya
            $this->upcomingSchedules = EskulSchedule::with('eskul')
                ->where('is_active', true)
                ->whereHas('eskul', function($query) use ($userId) {
                    $query->where('pembina_id', $userId);
                })
                ->take(5)
                ->get();
        }
        elseif(auth()->user()->hasRole('siswa')){
            // Siswa melihat data umum
            $this->eskulCount = Eskul::count();
            $this->achievementCount = Achievement::count();
            $this->eventParticipantCount = EskulEventParticipant::count();
            
            $userId = auth()->user()->id;
            
            // Siswa hanya melihat jadwal eskul yang diikutinya
            $this->upcomingSchedules = EskulSchedule::with('eskul')
                ->where('is_active', true)
                ->whereHas('eskul.participants', function($query) use ($userId) {
                    $query->where('student_id', $userId);
                })
                ->take(5)
                ->get();
                
            // Generate detailed student statistics
            $this->generateStudentStats($userId);
        }
    }
    
    private function generateStudentStats($userId)
    {
        // Get student's active eskul memberships
        $this->myEskuls = EskulMember::with(['eskul', 'student'])
            ->where('student_id', $userId)
            ->where('is_active', true)
            ->get();
            
        // Get detailed statistics for each eskul
        $this->eskulStats = [];
        $totalSessions = 0;
        $totalAttended = 0;
        $totalAchievements = 0;
        
        foreach ($this->myEskuls as $membership) {
            $eskulId = $membership->eskul_id;
            
            // Get attendance data for this eskul
            $attendanceData = Attendance::where('eskul_id', $eskulId)
                ->where('student_id', $userId)
                ->get();
                
            $totalSessionsForEskul = $attendanceData->count();
            $attendedSessionsForEskul = $attendanceData->where('status', 'hadir')->count();
            $attendanceRate = $totalSessionsForEskul > 0 ? 
                round(($attendedSessionsForEskul / $totalSessionsForEskul) * 100, 1) : 0;
                
            // Get achievements for this eskul
            $achievements = Achievement::where('eskul_id', $eskulId)
                ->where('student_id', $userId)
                ->get();
                
            // Calculate progress based on attendance and achievements
            $progress = $this->calculateProgress($attendanceRate, $achievements->count());
            
            $this->eskulStats[$eskulId] = [
                'eskul_name' => $membership->eskul->name,
                'total_sessions' => $totalSessionsForEskul,
                'attended_sessions' => $attendedSessionsForEskul,
                'attendance_rate' => $attendanceRate,
                'achievements_count' => $achievements->count(),
                'progress' => $progress,
                'join_date' => $membership->join_date,
                'last_attendance' => $attendanceData->sortByDesc('date')->first()?->date,
                'recent_achievements' => $achievements->sortByDesc('achievement_date')->take(3)
            ];
            
            $totalSessions += $totalSessionsForEskul;
            $totalAttended += $attendedSessionsForEskul;
            $totalAchievements += $achievements->count();
        }
        
        // Overall statistics
        $this->totalSessions = $totalSessions;
        $this->attendedSessions = $totalAttended;
        $this->attendanceRate = $totalSessions > 0 ? round(($totalAttended / $totalSessions) * 100, 1) : 0;
        $this->overallProgress = $this->calculateOverallProgress();
        
        // Monthly attendance data for charts
        $this->monthlyAttendance = $this->getMonthlyAttendance($userId);
        
        // Recent achievements across all eskuls
        $this->recentAchievements = Achievement::where('student_id', $userId)
            ->with('eskul')
            ->latest('achievement_date')
            ->take(5)
            ->get();
    }
    
    private function calculateProgress($attendanceRate, $achievementsCount)
    {
        // Progress calculation: 70% attendance + 30% achievements
        $attendanceScore = min($attendanceRate, 100) * 0.7;
        $achievementScore = min($achievementsCount * 10, 30); // Max 30 points for achievements
        
        return min(round($attendanceScore + $achievementScore), 100);
    }
    
    private function calculateOverallProgress()
    {
        if (empty($this->eskulStats)) {
            return 0;
        }
        
        $totalProgress = 0;
        foreach ($this->eskulStats as $stat) {
            $totalProgress += $stat['progress'];
        }
        
        return round($totalProgress / count($this->eskulStats), 1);
    }
    
    private function getMonthlyAttendance($userId)
    {
        $months = [];
        $attendanceData = [];
        
        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->format('M Y');
            
            $months[] = $monthLabel;
            
            // Count attendance for this month
            $count = Attendance::where('student_id', $userId)
                ->whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->where('status', 'hadir')
                ->count();
                
            $attendanceData[] = $count;
        }
        
        return [
            'months' => $months,
            'data' => $attendanceData
        ];
    }
}
