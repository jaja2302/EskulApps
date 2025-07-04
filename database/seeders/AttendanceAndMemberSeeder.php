<?php

namespace Database\Seeders;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Eskul;
use App\Models\EskulSchedule;
use App\Models\Attendance;
use App\Models\EskulMember;
use Carbon\Carbon;

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
        
        $totalAttendanceCreated = 0;
        $totalMembersCreated = 0;
        $totalSkipped = 0;

        $bookNumberTotal = 7;    

        // Process buku1.xlsx to buku7.xlsx
        for ($bookNumber = 1; $bookNumber <= $bookNumberTotal; $bookNumber++) {
            $path = storage_path("app/buku{$bookNumber}.xlsx");
            
            if (!file_exists($path)) {
                $this->command->warn("File buku{$bookNumber}.xlsx tidak ditemukan, melanjutkan ke file berikutnya...");
                continue;
            }

            $this->command->info("Memproses buku{$bookNumber}.xlsx...");
            
            $spreadsheet = IOFactory::load($path);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Skip header row
            array_shift($rows);

            $attendanceCreated = 0;
            $membersCreated = 0;
            $skipped = 0;

            foreach ($rows as $row) {
                try {
                    // Get data from columns
                    $studentName = $row[0];
                    $eskulName = $row[1];
                    $date = Carbon::createFromFormat('d/m/Y', $row[2])->format('Y-m-d');
                    $status = strtolower($row[3]);
                    
                    // Convert 'tidak hadir' to 'alpha'
                    if ($status === 'tidak hadir') {
                        $status = 'alpha';
                    }

                    // Find student and eskul
                    $student = User::where('name', $studentName)
                        ->whereHas('roles', function($q) {
                            $q->where('name', 'siswa');
                        })->first();
                    
                    $eskul = Eskul::where('name', $eskulName)->first();

                    if (!$student || !$eskul) {
                        $skipped++;
                        continue;
                    }

                    // Get or create schedule for this date
                    $dayName = Carbon::parse($date)->format('l'); // Gets day name (Monday, Tuesday, etc.)
                    $schedule = EskulSchedule::firstOrCreate(
                        [
                            'eskul_id' => $eskul->id,
                            'day' => $dayName,
                        ],
                        [
                            'start_time' => '14:00:00',  // Default time
                            'end_time' => '16:00:00',    // Default time
                            'location' => 'Default Location',
                            'is_active' => true
                        ]
                    );

                    // Register student to eskul if not already registered
                    $member = EskulMember::firstOrCreate(
                        [
                            'student_id' => $student->id,
                            'eskul_id' => $eskul->id,
                        ],
                        [
                            'is_active' => true,
                            'join_date' => $date,
                            'notes' => 'Auto-generated from attendance import',
                            'added_by' => 1
                        ]
                    );

                    if ($member->wasRecentlyCreated) {
                        $membersCreated++;
                    }

                    // Create attendance record with schedule_id and check_in_time
                    Attendance::updateOrCreate(
                        [
                            'eskul_id' => $eskul->id,
                            'student_id' => $student->id,
                            'date' => $date,
                        ],
                        [
                            'status' => $status,
                            'is_verified' => true,
                            'schedule_id' => $schedule->id,
                            'check_in_time' => Carbon::parse($date)->setTime(14, 0, 0), // Set to 14:00:00 of the attendance date
                            'verified_by' => 1,  // Set admin as verifier
                            'verified_at' => now()  // Set verification time
                        ]
                    );

                    $attendanceCreated++;

                } catch (\Exception $e) {
                    $this->command->error("Error on row: " . $e->getMessage());
                    $skipped++;
                }
            }

            $totalAttendanceCreated += $attendanceCreated;
            $totalMembersCreated += $membersCreated;
            $totalSkipped += $skipped;

            $this->command->info("Selesai memproses buku{$bookNumber}.xlsx");
            $this->command->info("Attendance: $attendanceCreated, New Members: $membersCreated, Skipped: $skipped");
        }

        $this->command->info("Import selesai untuk semua buku.");
        $this->command->info("Total Attendance: $totalAttendanceCreated, Total New Members: $totalMembersCreated, Total Skipped: $totalSkipped");
    }
}