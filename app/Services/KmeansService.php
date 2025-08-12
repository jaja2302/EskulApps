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
    private $maxIterations = 50; // Mengurangi iterasi untuk performa dan konsistensi
    private $startDate;
    private $endDate;
    private $year;
    private $semester;
    private $month; // Tambahan: simpan bulan yang difilter (opsional)
    
    // Kumpulan informasi debug untuk menampilkan detail proses K-Means
    private $debug = [];
    
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
        // Kembalikan JUMLAH kehadiran (bukan persentase) agar sesuai dengan tabel skripsi (mis. 4 0 0)
        return $presentCount;
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
        // Kembalikan JUMLAH partisipasi event (bukan persentase)
        return $participatedEvents;
    }
    
    private function calculateAchievementScore($studentId, $eskulId)
    {
        // Kembalikan JUMLAH prestasi agar konsisten dengan tabel skripsi (mis. 0)
        return Achievement::where('student_id', $studentId)
            ->where('eskul_id', $eskulId)
            ->whereBetween('achievement_date', [$this->startDate, $this->endDate])
            ->count();
    }
    
    public function performClustering($eskulId, $filteredStudentIds = null)
    {
        // Ambil semua data metrik siswa untuk tahun dan semester yang aktif beserta nama siswa
        $query = \DB::table('student_performance_metrics')
            ->join('users', 'users.id', '=', 'student_performance_metrics.student_id')
            ->where('student_performance_metrics.eskul_id', $eskulId)
            ->where('student_performance_metrics.year', $this->year)
            ->where('student_performance_metrics.semester', $this->semester)
            ->select(
                'student_performance_metrics.*',
                'users.name as student_name'
            );
        
        // Jika ada filter bulan, batasi pada bulan tersebut; jika tidak, gunakan baris agregat semester (month NULL)
        if ($this->month !== null && $this->month !== '') {
            $query->where('month', $this->month);
        } else {
            $query->whereNull('month');
        }
        
        // Jika diberikan filter daftar siswa, batasi pada siswa tersebut
        if (is_array($filteredStudentIds) && count($filteredStudentIds) > 0) {
            $query->whereIn('student_performance_metrics.student_id', $filteredStudentIds);
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
        
        // Inisialisasi struktur debug
        $this->debug = [
            'eskul_id' => $eskulId,
            'period' => [
                'year' => $this->year,
                'semester' => $this->semester,
                'month' => $this->month,
            ],
            'data_points' => $dataPoints,
            'students' => $students->map(function($s) {
                return [
                    'student_id' => $s->student_id,
                    'student_name' => $s->student_name,
                    'vector' => [
                        $s->attendance_score,
                        $s->participation_score,
                        $s->achievement_score,
                    ],
                ];
            })->toArray(),
            'initial_centroids' => [],
            'initial_centroid_indices' => [],
            'initial_centroid_students' => [],
            'iterations' => [],
            'final' => [],
        ];
        
        // Inisialisasi centroids secara random
        $initialInfo = $this->initializeCentroids($dataPoints, true);
        $centroids = $initialInfo['centroids'];
        $this->debug['initial_centroids'] = $centroids;
        $this->debug['initial_centroid_indices'] = $initialInfo['indices'];
        // Simpan juga nama siswa untuk centroid awal jika tersedia
        $this->debug['initial_centroid_students'] = array_map(function($idx) use ($students) {
            $s = $students[$idx] ?? null;
            if (!$s) { return null; }
            return [
                'student_id' => $s->student_id,
                'student_name' => $s->student_name,
                'vector' => [
                    $s->attendance_score,
                    $s->participation_score,
                    $s->achievement_score,
                ],
            ];
        }, $this->debug['initial_centroid_indices']);
        
        // Iterasi K-means
        for ($i = 0; $i < $this->maxIterations; $i++) {
            // Hitung jarak setiap data point ke setiap centroid dan tentukan cluster terdekat
            $clusters = [];
            $distanceMatrix = [];
            foreach ($dataPoints as $pi => $point) {
                $distances = [];
                foreach ($centroids as $cj => $centroid) {
                    $distances[$cj] = $this->calculateDistance($point, $centroid);
                }
                $distanceMatrix[$pi] = $distances;
                // pilih cluster dengan jarak minimum
                $minCluster = array_keys($distances, min($distances))[0];
                $clusters[$pi] = $minCluster;
            }
            
            // Update posisi centroids
            $newCentroids = $this->updateCentroids($dataPoints, $clusters);
            $this->debug['iterations'][] = [
                'iteration' => $i + 1,
                'assigned_clusters' => $clusters,
                'centroids_before' => $centroids,
                'centroids_after' => $newCentroids,
                'distances' => $distanceMatrix,
            ];
            
            // Cek konvergensi
            if ($this->hasConverged($centroids, $newCentroids)) {
                break;
            }
            
            $centroids = $newCentroids;
        }
        
        // Hitung centroid final berdasarkan assignment terakhir
        $finalCentroids = $this->updateCentroids($dataPoints, $clusters);
        
        // Hitung rata-rata total score untuk setiap cluster
        $centroidAverages = [];
        for ($i = 0; $i < $this->k; $i++) {
            if (isset($finalCentroids[$i]) && is_array($finalCentroids[$i])) {
                $avg = array_sum($finalCentroids[$i]) / (count($finalCentroids[$i]) ?: 1);
                $centroidAverages[$i] = is_nan($avg) ? 0 : $avg;
            } else {
                $centroidAverages[$i] = 0;
            }
        }
        
        // Buat peta indeks yang lebih deterministik: tertinggi -> 0, sedang -> 1, terendah -> 2
        $clusterInfo = [];
        for ($i = 0; $i < $this->k; $i++) {
            $clusterInfo[$i] = [
                'index' => $i,
                'average' => $centroidAverages[$i]
            ];
        }
        
        // Urutkan berdasarkan rata-rata (descending)
        usort($clusterInfo, function($a, $b) {
            if (abs($a['average'] - $b['average']) < 0.001) {
                // Jika rata-rata hampir sama, urutkan berdasarkan index untuk konsistensi
                return $a['index'] <=> $b['index'];
            }
            return $b['average'] <=> $a['average'];
        });
        
        // Buat mapping: cluster dengan rata-rata tertinggi = 0, dst
        $labelMap = [];
        for ($i = 0; $i < count($clusterInfo); $i++) {
            $labelMap[$clusterInfo[$i]['index']] = $i;
        }
        
        // Edge case: bila semua rata-rata nyaris 0, paksa semua ke cluster terendah
        $maxAvg = !empty($centroidAverages) ? max($centroidAverages) : 0;
        $mappedClusters = [];
        if ($maxAvg <= 0.001) {
            $mappedClusters = array_fill(0, count($clusters), 2);
        } else {
            foreach ($clusters as $idx => $clusterIndex) {
                $mappedClusters[$idx] = $labelMap[$clusterIndex] ?? 2;
            }
        }
        
        // Override: jika semua metrik siswa nyaris 0, paksa cluster 2 (terendah)
        $nearZero = 0.01; // threshold untuk nilai yang sangat kecil
        foreach ($dataPoints as $i => $point) {
            if (max($point) <= $nearZero) {
                $mappedClusters[$i] = 2; // cluster terendah
            }
        }
        
        // Simpan informasi final untuk debug & logging
        $this->debug['final'] = [
            'final_centroids' => $finalCentroids,
            'centroid_averages' => $centroidAverages,
            'label_map' => $labelMap,
            'final_assigned_clusters' => $clusters,
            'mapped_clusters' => $mappedClusters,
        ];
        \Log::info('[KMeans Debug] Eskul ' . $eskulId, $this->debug);
        
        // Update hasil clustering ke database dengan indeks yang sudah dipetakan
        $this->updateClusterResults($students, $mappedClusters);
    }
    
    private function initializeCentroids($dataPoints, $withIndices = false)
    {
        $centroids = [];
        $n = count($dataPoints);
        
        if ($n < $this->k) {
            // Jika data points kurang dari k, duplikasi beberapa points
            for ($i = 0; $i < $this->k; $i++) {
                $centroids[] = $dataPoints[$i % $n];
            }
            if ($withIndices) {
                $indices = [];
                for ($i = 0; $i < $this->k; $i++) {
                    $indices[] = $i % $n;
                }
                return ['centroids' => $centroids, 'indices' => $indices];
            }
            return $centroids;
        }
        
        // Menggunakan metode deterministik untuk inisialisasi centroid
        // Metode: Pilih titik dengan nilai tertinggi, sedang, dan terendah berdasarkan total score
        $pointsWithScore = [];
        foreach ($dataPoints as $idx => $point) {
            $totalScore = array_sum($point);
            $pointsWithScore[] = [
                'index' => $idx,
                'point' => $point,
                'total_score' => $totalScore
            ];
        }
        
        // Urutkan berdasarkan total score
        usort($pointsWithScore, function($a, $b) {
            return $b['total_score'] <=> $a['total_score']; // descending
        });
        
        $selectedIndices = [];
        
        if ($this->k == 3) {
            // Untuk 3 cluster: ambil tertinggi, tengah, dan terendah
            $selectedIndices[] = 0; // tertinggi
            $selectedIndices[] = floor($n / 2); // tengah
            $selectedIndices[] = $n - 1; // terendah
        } else {
            // Untuk k lainnya, bagi rata
            for ($i = 0; $i < $this->k; $i++) {
                $idx = floor($i * ($n - 1) / ($this->k - 1));
                $selectedIndices[] = $idx;
            }
        }
        
        $actualIndices = [];
        foreach ($selectedIndices as $idx) {
            $centroids[] = $pointsWithScore[$idx]['point'];
            $actualIndices[] = $pointsWithScore[$idx]['index'];
        }
        
        if ($withIndices) {
            return ['centroids' => $centroids, 'indices' => $actualIndices];
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
    
    private function hasConverged($oldCentroids, $newCentroids, $tolerance = 0.01)
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

    // Publikasi informasi debug untuk ditampilkan di UI jika diperlukan
    public function getDebugInfo()
    {
        return $this->debug;
    }
    
    // Method untuk mendapatkan data detail untuk verifikasi manual
    public function getDetailedClusteringInfo($eskulId, $filteredStudentIds = null)
    {
        // Ambil data yang sama seperti performClustering tapi dengan detail lengkap
        $query = \DB::table('student_performance_metrics')
            ->join('users', 'users.id', '=', 'student_performance_metrics.student_id')
            ->where('student_performance_metrics.eskul_id', $eskulId)
            ->where('student_performance_metrics.year', $this->year)
            ->where('student_performance_metrics.semester', $this->semester)
            ->select(
                'student_performance_metrics.*',
                'users.name as student_name'
            );
        
        if ($this->month !== null && $this->month !== '') {
            $query->where('month', $this->month);
        } else {
            $query->whereNull('month');
        }
        
        if (is_array($filteredStudentIds) && count($filteredStudentIds) > 0) {
            $query->whereIn('student_performance_metrics.student_id', $filteredStudentIds);
        }

        $students = $query->get();
        
        $detailedInfo = [];
        foreach ($students as $student) {
            $detailedInfo[] = [
                'student_id' => $student->student_id,
                'student_name' => $student->student_name,
                'attendance_score' => $student->attendance_score,
                'participation_score' => $student->participation_score,
                'achievement_score' => $student->achievement_score,
                'total_score' => $student->attendance_score + $student->participation_score + $student->achievement_score,
                'cluster' => $student->cluster ?? null,
            ];
        }
        
        // Urutkan berdasarkan total score untuk memudahkan verifikasi
        usort($detailedInfo, function($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });
        
        return [
            'period' => [
                'year' => $this->year,
                'semester' => $this->semester,
                'month' => $this->month,
                'start_date' => $this->startDate->format('Y-m-d'),
                'end_date' => $this->endDate->format('Y-m-d'),
            ],
            'students' => $detailedInfo,
            'summary' => [
                'total_students' => count($detailedInfo),
                'cluster_0_count' => count(array_filter($detailedInfo, fn($s) => $s['cluster'] == 0)),
                'cluster_1_count' => count(array_filter($detailedInfo, fn($s) => $s['cluster'] == 1)),
                'cluster_2_count' => count(array_filter($detailedInfo, fn($s) => $s['cluster'] == 2)),
            ]
        ];
    }
} 