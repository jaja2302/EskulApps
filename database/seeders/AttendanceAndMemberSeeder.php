<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Eskul;
use App\Models\EskulSchedule;
use App\Models\Attendance;
use App\Models\EskulMember;
use Illuminate\Support\Facades\Auth;

class AttendanceAndMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Starting attendance and member import...');
        
        $csvPath = storage_path('app/DaftarAbsensi.csv');
        
        // Check if file exists
        if (!file_exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan: $csvPath");
            return;
        }

        // Create CSV reader
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setDelimiter(','); // Delimiter koma
        $csv->setHeaderOffset(0); // Assuming first row contains headers

        // Count for statistics
        $processed = 0;
        $attendanceCreated = 0;
        $membersCreated = 0;
        $skipped = 0;

        // We need an admin user to set as the "added_by" for members
        $admin = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->first();

        if (!$admin) {
            $this->command->warn("No admin user found. Using the first user available as 'added_by'");
            $admin = User::first();
            
            if (!$admin) {
                $this->command->error("No users found in the database. Cannot proceed.");
                return;
            }
        }

        $this->command->getOutput()->progressStart(count($csv));

        // Track unique student-eskul pairs to avoid duplicate processing
        $processedPairs = [];

        foreach ($csv as $record) {
            $processed++;
            
            try {
                // Find student by name
                $student = User::where('name', $record['nama_siswa'])
                            ->whereHas('roles', function($q) {
                                $q->where('name', 'siswa');
                            })
                            ->first();
                
                if (!$student) {
                    $this->command->warn("Siswa tidak ditemukan: {$record['nama_siswa']}");
                    $skipped++;
                    $this->command->getOutput()->progressAdvance();
                    continue;
                }

                // Find eskul by name
                $eskul = Eskul::where('name', $record['nama_eskul'])->first();
                if (!$eskul) {
                    $this->command->warn("Eskul tidak ditemukan: {$record['nama_eskul']}");
                    $skipped++;
                    $this->command->getOutput()->progressAdvance();
                    continue;
                }
                
                // Parse date
                try {
                    // Assuming date format is DD/MM/YYYY in CSV
                    $carbonDate = Carbon::createFromFormat('d/m/Y', $record['tanggal']);
                    $date = $carbonDate->format('Y-m-d');
                    $dayOfWeek = $carbonDate->format('l'); // Get day name (Monday, Tuesday, etc.)
                } catch (\Exception $e) {
                    $this->command->warn("Format tanggal tidak valid: {$record['tanggal']}");
                    $skipped++;
                    $this->command->getOutput()->progressAdvance();
                    continue;
                }
                
                // PART 1: ENSURE STUDENT IS A MEMBER OF THE ESKUL
                // Check if this student-eskul pair has been processed
                $pairKey = $student->id . '-' . $eskul->id;
                if (!isset($processedPairs[$pairKey])) {
                    // Check if the student is already a member of this eskul
                    $existingMembership = EskulMember::where('student_id', $student->id)
                                            ->where('eskul_id', $eskul->id)
                                            ->first();
                    
                    if (!$existingMembership) {
                        // Create a new membership
                        EskulMember::create([
                            'student_id' => $student->id,
                            'eskul_id' => $eskul->id,
                            'added_by' => $admin->id,
                            'is_active' => true,
                            'join_date' => $date, // Use the attendance date as join date
                            'notes' => 'Auto-generated from attendance import'
                        ]);
                        
                        $membersCreated++;
                    }
                    
                    // Mark this pair as processed
                    $processedPairs[$pairKey] = true;
                }
                
                // PART 2: FIND OR CREATE SCHEDULE
                // Find schedule for this eskul and day of week
                $schedule = EskulSchedule::where('eskul_id', $eskul->id)
                                ->where('day', $dayOfWeek)
                                ->where('is_active', true)
                                ->first();
                                
                if (!$schedule) {
                    // Create a new schedule if one doesn't exist
                    $schedule = EskulSchedule::create([
                        'eskul_id' => $eskul->id,
                        'day' => $dayOfWeek,
                        'start_time' => '14:00:00', // Default start time
                        'end_time' => '16:00:00',   // Default end time
                        'location' => $eskul->meeting_location ?? 'Default Location',
                        'notes' => 'Auto-generated schedule from attendance import',
                        'is_active' => true
                    ]);
                }
                
                // PART 3: CREATE ATTENDANCE RECORD
                // Validate status
                $validStatuses = ['hadir', 'izin', 'sakit', 'alpha'];
                $status = strtolower(trim($record['keterangan']));
                if (!in_array($status, $validStatuses)) {
                    $status = 'hadir'; // Default to hadir if invalid
                }
                
                // Create or update attendance record
                $attendance = Attendance::updateOrCreate(
                    [
                        'eskul_id' => $eskul->id,
                        'schedule_id' => $schedule->id,
                        'student_id' => $student->id,
                        'date' => $date,
                    ],
                    [
                        'check_in_time' => $carbonDate->setTimeFromTimeString($schedule->start_time),
                        'status' => $status,
                        'notes' => $record['notes'] ?? null,
                        'is_verified' => false
                    ]
                );
                
                $attendanceCreated++;
                
            } catch (\Exception $e) {
                $this->command->error("Error processing row for {$record['nama_siswa']}: " . $e->getMessage());
                $skipped++;
            }
            
            $this->command->getOutput()->progressAdvance();
        }
        
        $this->command->getOutput()->progressFinish();
        $this->command->info("Import selesai. Processed: $processed, Attendance created: $attendanceCreated, Members created: $membersCreated, Skipped: $skipped");
    }
}