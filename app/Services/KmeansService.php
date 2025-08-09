<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Achievement;
use App\Models\EskulEvent;
use App\Models\EskulEventParticipant;
use App\Models\EskulMember;
use Carbon\Carbon;

class KmeansService
{
    private $k = 3; // Jumlah cluster (misalnya: tinggi, sedang, rendah)
    private $maxIterations = 100;
    private $startDate;
    private $endDate;
    private $year;
    private $semester;
    private $month; // Tambahan: simpan bulan yang difilter (opsional)
    
    public function calculateMetrics($studentId, $eskulId, $year, $semester, $month = null)
    {

        // dd($year,$semester);

        // dd($studentId);
        // Set periode berdasarkan tahun, semester, dan opsional bulan
        $this->setPeriod($year, $semester, $month);

        // dd( $this->setPeriod($year, $semester));
        
        // Hitung skor kehadiran
        $attendanceScore = $this->calculateAttendanceScore($studentId, $eskulId);
        // dd($attendanceScore);
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
    
    private function setPeriod($year, $semester, $month = null)
    {
        // Jika bulan dipilih, gunakan rentang satu bulan tersebut dan abaikan rentang semester
        if ($month !== null && $month !== '') {
            $this->startDate = Carbon::create($year, (int) $month, 1)->startOfDay();
            $this->endDate = Carbon::create($year, (int) $month, 1)->endOfMonth()->endOfDay();
        } else {
            // Semester 1: Juli - Desember
            // Semester 2: Januari - Juni
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
    
    private function calculateAttendanceScore($studentId, $eskulId)
    {
        // Hitung total pertemuan dalam periode
        $totalSessions = Attendance::where('eskul_id', $eskulId)
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->count();
        
        // Hitung kehadiran siswa (hanya status 'hadir')
        $presentCount = Attendance::where('eskul_id', $eskulId)
            ->where('student_id', $studentId)
            ->where('status', 'hadir')
            ->where('is_verified', 1)
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->count();
        
        return ($totalSessions > 0) ? ($presentCount / $totalSessions) * 100 : 0;
    }
    
    private function calculateParticipationScore($studentId, $eskulId)
    {
        // Hitung total event dalam periode
        $totalEvents = EskulEvent::where('eskul_id', $eskulId)
            ->whereBetween('start_datetime', [$this->startDate, $this->endDate])
            ->count();
        
        // Hitung partisipasi event dalam periode
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
            // Sesuaikan bobot level dengan format di database
            $levelScore = match(strtolower($achievement->level)) {
                'internasional' => 100,
                'nasional' => 80,
                'provinsi' => 60,
                'kabupaten/kota' => 40,  // Sesuaikan dengan format di DB
                'sekolah' => 20,
                default => 0
            };
            
            // Sesuaikan bobot posisi dengan format di database
            $positionScore = match(strtolower($achievement->position)) {
                'juara 1' => 100,
                'juara 2' => 80,
                'juara 3' => 60,
                'harapan' => 40,
                default => 20
            };
            
            $totalScore += ($levelScore + $positionScore) / 2;
        }
        
        // Tambahkan log untuk debugging
        \Log::info("Achievement score calculation for student $studentId:", [
            'achievements' => $achievements->toArray(),
            'total_score' => $totalScore,
            'final_score' => $achievements->count() > 0 ? min(100, $totalScore / $achievements->count()) : 0
        ]);
        
        return $achievements->count() > 0 ? min(100, $totalScore / $achievements->count()) : 0;
    }
    
    public function performClustering($eskulId)
    {
        // Ambil semua data metrik siswa untuk tahun dan semester yang aktif
        $query = \DB::table('student_performance_metrics')
            ->where('eskul_id', $eskulId)
            ->where('year', $this->year)       // Tambahkan filter tahun
            ->where('semester', $this->semester); // Tambahkan filter semester
        
        // Jika ada filter bulan, batasi pada bulan tersebut
        if ($this->month !== null && $this->month !== '') {
            $query->where('month', $this->month);
        }
        
        $students = $query->get();
        
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
        
        // Hitung centroid final berdasarkan assignment terakhir
        $finalCentroids = $this->updateCentroids($dataPoints, $clusters);
        $centroidAverages = [];
        for ($i = 0; $i < $this->k; $i++) {
            $avg = array_sum($finalCentroids[$i]) / (count($finalCentroids[$i]) ?: 1);
            $centroidAverages[$i] = is_nan($avg) ? 0 : $avg;
        }
        
        // Buat peta indeks: tertinggi -> 0, sedang -> 1, terendah -> 2
        $sorted = $centroidAverages;
        arsort($sorted); // descending
        $labelMap = [];
        $label = 0;
        foreach (array_keys($sorted) as $originalIndex) {
            $labelMap[$originalIndex] = $label;
            $label++;
        }
        
        // Edge case: bila semua rata-rata nyaris 0, paksa semua ke cluster terendah
        $maxAvg = !empty($centroidAverages) ? max($centroidAverages) : 0;
        $mappedClusters = [];
        if ($maxAvg <= 0.000001) {
            $mappedClusters = array_fill(0, count($clusters), 2);
        } else {
            foreach ($clusters as $idx => $clusterIndex) {
                $mappedClusters[$idx] = $labelMap[$clusterIndex] ?? 2;
            }
        }
        
        // Override: jika semua metrik siswa nyaris 0, paksa cluster 2
        $nearZero = 0.000001; // threshold sangat kecil untuk persentase
        foreach ($dataPoints as $i => $point) {
            if (max($point) <= $nearZero) {
                $mappedClusters[$i] = 2;
            }
        }
        
        // Update hasil clustering ke database dengan indeks yang sudah dipetakan
        $this->updateClusterResults($students, $mappedClusters);
    }
    
    private function initializeCentroids($dataPoints)
    {
        $centroids = [];
        $n = count($dataPoints);
        
        // Pilih K data point secara random sebagai centroid awal
        $randomKeys = array_rand($dataPoints, $this->k);
        foreach ($randomKeys as $key) {
            $centroids[] = $dataPoints[$key];
        }
        
        return $centroids;
    }
    
    private function assignClusters($dataPoints, $centroids)
    {
        $clusters = [];
        
        foreach ($dataPoints as $i => $point) {
            $minDistance = PHP_FLOAT_MAX;
            $clusterIndex = 0;
            
            // Cari centroid terdekat
            foreach ($centroids as $j => $centroid) {
                $distance = $this->calculateDistance($point, $centroid);
                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $clusterIndex = $j;
                }
            }
            
            $clusters[$i] = $clusterIndex;
        }
        
        return $clusters;
    }
    
    private function calculateDistance($point1, $point2)
    {
        // Euclidean distance
        $sum = 0;
        for ($i = 0; $i < count($point1); $i++) {
            $sum += pow($point1[$i] - $point2[$i], 2);
        }
        return sqrt($sum);
    }
    
    private function updateCentroids($dataPoints, $clusters)
    {
        $newCentroids = array_fill(0, $this->k, array_fill(0, 3, 0));
        $counts = array_fill(0, $this->k, 0);
        
        // Hitung jumlah dan total untuk setiap cluster
        foreach ($clusters as $i => $cluster) {
            for ($j = 0; $j < 3; $j++) {
                $newCentroids[$cluster][$j] += $dataPoints[$i][$j];
            }
            $counts[$cluster]++;
        }
        
        // Hitung rata-rata untuk mendapatkan centroid baru
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
            if ($this->calculateDistance($oldCentroids[$i], $newCentroids[$i]) > $tolerance) {
                return false;
            }
        }
        return true;
    }
    
    private function updateClusterResults($students, $clusters)
    {
        foreach ($students as $index => $student) {
            $query = \DB::table('student_performance_metrics')
                ->where('student_id', $student->student_id)
                ->where('eskul_id', $student->eskul_id)
                ->where('year', $this->year)
                ->where('semester', $this->semester);

            if ($this->month !== null && $this->month !== '') {
                $query->where('month', $this->month);
            }

            $query->update(['cluster' => $clusters[$index]]);
        }
    }
} 