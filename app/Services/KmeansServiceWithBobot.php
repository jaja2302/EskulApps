<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Achievement;
use App\Models\EskulEvent;
use App\Models\EskulEventParticipant;
use App\Models\EskulMember;
use Carbon\Carbon;

class KmeansServiceWithBobot
{
    private $k = 3; // Jumlah cluster (tinggi, sedang, rendah)
    private $maxIterations = 100;
    private $startDate;
    private $endDate;
    private $year;
    private $semester;
    private $month;
    
    // Definisi bobot untuk setiap metrik
    private $weights = [
        'attendance' => 0.4,    // Kehadiran 40%
        'participation' => 0.3, // Partisipasi 30%
        'achievement' => 0.3    // Prestasi 30%
    ];

    public function calculateMetrics($studentId, $eskulId, $year, $semester, $month = null)
    {
        $this->setPeriod($year, $semester, $month);
        
        $attendanceScore = $this->calculateAttendanceScore($studentId, $eskulId);
        $participationScore = $this->calculateParticipationScore($studentId, $eskulId);
        $achievementScore = $this->calculateAchievementScore($studentId, $eskulId);
        
        // Terapkan bobot pada setiap skor
        $weightedScores = [
            'attendance_score' => $attendanceScore * $this->weights['attendance'],
            'participation_score' => $participationScore * $this->weights['participation'],
            'achievement_score' => $achievementScore * $this->weights['achievement'],
            'raw_attendance_score' => $attendanceScore,
            'raw_participation_score' => $participationScore,
            'raw_achievement_score' => $achievementScore,
        ];
        
        return $weightedScores;
    }

    private function setPeriod($year, $semester, $month = null)
    {
        if ($month) {
            // If month is specified, use that specific month
            $this->startDate = Carbon::create($year, $month, 1)->startOfDay();
            $this->endDate = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();
        } else {
            // Use semester-based filtering as before
            if ($semester == 1) {
                $this->startDate = Carbon::create($year, 7, 1)->startOfDay();
                $this->endDate = Carbon::create($year, 12, 31)->endOfDay();
            } else {
                $this->startDate = Carbon::create($year, 1, 1)->startOfDay();
                $this->endDate = Carbon::create($year, 6, 30)->endOfDay();
            }
        }
        $this->year = $year;
        $this->semester = $semester;
        $this->month = $month;
    }

    // Fungsi perhitungan skor sama seperti sebelumnya
    private function calculateAttendanceScore($studentId, $eskulId)
    {
        $totalSessions = Attendance::where('eskul_id', $eskulId)
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->count();
        
        $studentAttendance = Attendance::where('eskul_id', $eskulId)
            ->where('student_id', $studentId)
            ->where('is_verified', 1)
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->count();
            
        return ($totalSessions > 0) ? ($studentAttendance / $totalSessions) * 100 : 0;
    }

    private function calculateParticipationScore($studentId, $eskulId)
    {
        $totalEvents = EskulEvent::where('eskul_id', $eskulId)
            ->whereBetween('start_datetime', [$this->startDate, $this->endDate])
            ->count();
        
        $participatedEvents = EskulEventParticipant::whereHas('event', function($query) use ($eskulId) {
            $query->where('eskul_id', $eskulId)
                ->whereBetween('start_datetime', [$this->startDate, $this->endDate]);
        })->where('student_id', $studentId)->count();
        
        return ($totalEvents > 0) ? ($participatedEvents / $totalEvents) * 100 : 0;
    }

    private function calculateAchievementScore($studentId, $eskulId)
    {
        $achievements = Achievement::where('student_id', $studentId)
            ->where('eskul_id', $eskulId)
            ->whereBetween('achievement_date', [$this->startDate, $this->endDate])
            ->get();
        
        $totalScore = 0;
        foreach ($achievements as $achievement) {
            $levelScore = match(strtolower($achievement->level)) {
                'internasional' => 100,
                'nasional' => 80,
                'provinsi' => 60,
                'kabupaten/kota' => 40,
                'sekolah' => 20,
                default => 0
            };
            
            $positionScore = match(strtolower($achievement->position)) {
                'juara 1' => 100,
                'juara 2' => 80,
                'juara 3' => 60,
                'harapan' => 40,
                default => 20
            };
            
            $totalScore += ($levelScore + $positionScore) / 2;
        }
        
        return $achievements->count() > 0 ? min(100, $totalScore / $achievements->count()) : 0;
    }

    public function performClustering($eskulId)
    {
        $query = \DB::table('student_performance_metrics')
            ->where('eskul_id', $eskulId)
            ->where('year', $this->year)
            ->where('semester', $this->semester);
            
        // Add month filter if we're filtering by month
        if (isset($this->month) && $this->month) {
            $query->where('month', $this->month);
        }
        
        $students = $query->get();
            
        // Inisialisasi centroid dengan strategi yang lebih baik
        $centroids = [
            [100, 100, 100], // Cluster performa tinggi
            [50, 50, 50],    // Cluster performa sedang
            [0, 0, 0]        // Cluster performa rendah
        ];
        
        $dataPoints = $students->map(function($student) {
            return [
                $student->attendance_score,
                $student->participation_score,
                $student->achievement_score
            ];
        })->toArray();
        
        $clusters = $this->runKmeans($dataPoints, $centroids);
        $this->updateClusterResults($students, $clusters);
    }

    private function runKmeans($dataPoints, $centroids)
    {
        $clusters = [];
        
        for ($i = 0; $i < $this->maxIterations; $i++) {
            $clusters = $this->assignClusters($dataPoints, $centroids);
            $newCentroids = $this->updateCentroids($dataPoints, $clusters);
            
            if ($this->hasConverged($centroids, $newCentroids)) {
                break;
            }
            
            $centroids = $newCentroids;
        }
        
        return $clusters;
    }

    private function assignClusters($dataPoints, $centroids)
    {
        $clusters = [];
        
        foreach ($dataPoints as $i => $point) {
            $minDistance = PHP_FLOAT_MAX;
            $clusterIndex = 0;
            
            foreach ($centroids as $j => $centroid) {
                $distance = $this->calculateWeightedDistance($point, $centroid);
                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $clusterIndex = $j;
                }
            }
            
            $clusters[$i] = $clusterIndex;
        }
        
        return $clusters;
    }

    private function calculateWeightedDistance($point1, $point2)
    {
        $sum = 0;
        $weights = array_values($this->weights);
        
        for ($i = 0; $i < count($point1); $i++) {
            $sum += $weights[$i] * pow($point1[$i] - $point2[$i], 2);
        }
        
        return sqrt($sum);
    }

    private function updateCentroids($dataPoints, $clusters)
    {
        $newCentroids = array_fill(0, $this->k, array_fill(0, 3, 0));
        $counts = array_fill(0, $this->k, 0);
        
        foreach ($clusters as $i => $cluster) {
            for ($j = 0; $j < 3; $j++) {
                $newCentroids[$cluster][$j] += $dataPoints[$i][$j];
            }
            $counts[$cluster]++;
        }
        
        for ($i = 0; $i < $this->k; $i++) {
            if ($counts[$i] > 0) {
                for ($j = 0; $j < 3; $j++) {
                    $newCentroids[$i][$j] /= $counts[$i];
                }
            }
        }
        
        return $newCentroids;
    }

    private function hasConverged($oldCentroids, $newCentroids, $tolerance = 0.0001)
    {
        for ($i = 0; $i < $this->k; $i++) {
            if ($this->calculateWeightedDistance($oldCentroids[$i], $newCentroids[$i]) > $tolerance) {
                return false;
            }
        }
        return true;
    }

    private function updateClusterResults($students, $clusters)
    {
        foreach ($students as $index => $student) {
            $totalScore = 
                ($student->attendance_score * $this->weights['attendance']) +
                ($student->participation_score * $this->weights['participation']) +
                ($student->achievement_score * $this->weights['achievement']);
            
            // Interpretasi cluster berdasarkan total skor
            $finalCluster = match(true) {
                $totalScore >= 70 => 0,  // Tinggi
                $totalScore >= 40 => 1,  // Sedang
                default => 2             // Rendah
            };
            
            \DB::table('student_performance_metrics')
                ->where('student_id', $student->student_id)
                ->where('eskul_id', $student->eskul_id)
                ->update(['cluster' => $finalCluster]);
        }
    }
} 