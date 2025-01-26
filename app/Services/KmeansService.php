<?php

namespace App\Services;

class KmeansService
{
    private $k = 3; // Jumlah cluster (misalnya: tinggi, sedang, rendah)
    private $maxIterations = 100;
    
    public function calculateMetrics($studentId, $eskulId)
    {
        // Hitung skor kehadiran
        $attendanceScore = $this->calculateAttendanceScore($studentId, $eskulId);
        
        // Hitung skor partisipasi
        $participationScore = $this->calculateParticipationScore($studentId, $eskulId);
        
        // Hitung skor prestasi
        $achievementScore = $this->calculateAchievementScore($studentId, $eskulId);
        
        return [
            'attendance_score' => $attendanceScore,
            'participation_score' => $participationScore,
            'achievement_score' => $achievementScore,
        ];
    }
    
    private function calculateAttendanceScore($studentId, $eskulId)
    {
        // Hitung persentase kehadiran
        $totalSessions = \DB::table('attendances')
            ->where('eskul_id', $eskulId)
            ->count();
            
        $studentAttendance = \DB::table('attendance_details')
            ->join('attendances', 'attendances.id', '=', 'attendance_details.attendance_id')
            ->where('student_id', $studentId)
            ->where('eskul_id', $eskulId)
            ->where('status', 'hadir')
            ->count();
            
        return ($totalSessions > 0) ? ($studentAttendance / $totalSessions) * 100 : 0;
    }
    
    public function performClustering($eskulId)
    {
        // Ambil semua data metrik siswa
        $students = \DB::table('student_performance_metrics')
            ->where('eskul_id', $eskulId)
            ->get();
            
        // Persiapkan data points
        $dataPoints = $students->map(function($student) {
            return [
                $student->attendance_score,
                $student->participation_score,
                $student->achievement_score
            ];
        })->toArray();
        
        // Inisialisasi centroids secara random
        $centroids = $this->initializeCentroids($dataPoints);
        
        // Iterasi K-means
        for ($i = 0; $i < $this->maxIterations; $i++) {
            // Assign points ke cluster terdekat
            $clusters = $this->assignClusters($dataPoints, $centroids);
            
            // Update posisi centroids
            $newCentroids = $this->updateCentroids($dataPoints, $clusters);
            
            // Cek konvergensi
            if ($this->hasConverged($centroids, $newCentroids)) {
                break;
            }
            
            $centroids = $newCentroids;
        }
        
        // Update hasil clustering ke database
        $this->updateClusterResults($students, $clusters);
    }
    
    // ... implementasi method helper lainnya ...
} 