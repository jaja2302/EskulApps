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
use Illuminate\Support\Facades\Log;

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

        $bookNumberTotal = 4;

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
            $rowNumber = 2; // Start from row 2 (after header)

            foreach ($rows as $row) {
                try {
                    // Log current row being processed

                    // Check if row has enough columns
                    if (count($row) < 4) {
                        Log::error("ATTENDANCE_SEEDER: Row {$rowNumber} in buku{$bookNumber}.xlsx - Not enough columns. Expected 4, got " . count($row), [
                            'file' => "buku{$bookNumber}.xlsx",
                            'row' => $rowNumber,
                            'columns_count' => count($row),
                            'raw_data' => $row
                        ]);
                        $skipped++;
                        $rowNumber++;
                        continue;
                    }

                    // Get data from columns with null checks
                    $studentName = $row[0] ?? null;
                    $eskulName = $row[1] ?? null;
                    $dateString = $row[2] ?? null;
                    $status = $row[3] ?? null;

                    // Log raw data
                    Log::info("ATTENDANCE_SEEDER: Row {$rowNumber} data", [
                        'file' => "buku{$bookNumber}.xlsx",
                        'row' => $rowNumber,
                        'student' => $studentName,
                        'eskul' => $eskulName,
                        'date' => $dateString,
                        'status' => $status
                    ]);

                    // Validate required fields
                    if (empty($studentName)) {
                        Log::error("ATTENDANCE_SEEDER: Row {$rowNumber} in buku{$bookNumber}.xlsx - Student name is empty", [
                            'file' => "buku{$bookNumber}.xlsx",
                            'row' => $rowNumber,
                            'raw_data' => $row
                        ]);
                        $skipped++;
                        $rowNumber++;
                        continue;
                    }

                    if (empty($eskulName)) {
                        Log::error("ATTENDANCE_SEEDER: Row {$rowNumber} in buku{$bookNumber}.xlsx - Eskul name is empty", [
                            'file' => "buku{$bookNumber}.xlsx",
                            'row' => $rowNumber,
                            'raw_data' => $row
                        ]);
                        $skipped++;
                        $rowNumber++;
                        continue;
                    }

                    if (empty($dateString)) {
                        Log::error("ATTENDANCE_SEEDER: Row {$rowNumber} in buku{$bookNumber}.xlsx - Date is empty", [
                            'file' => "buku{$bookNumber}.xlsx",
                            'row' => $rowNumber,
                            'raw_data' => $row
                        ]);
                        $skipped++;
                        $rowNumber++;
                        continue;
                    }

                    if (empty($status)) {
                        Log::error("ATTENDANCE_SEEDER: Row {$rowNumber} in buku{$bookNumber}.xlsx - Status is empty", [
                            'file' => "buku{$bookNumber}.xlsx",
                            'row' => $rowNumber,
                            'raw_data' => $row
                        ]);
                        $skipped++;
                        $rowNumber++;
                        continue;
                    }

                    // Try to parse date with better error handling
                    try {
                        $date = Carbon::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
                        Log::info("ATTENDANCE_SEEDER: Row {$rowNumber} date parsed successfully", [
                            'file' => "buku{$bookNumber}.xlsx",
                            'row' => $rowNumber,
                            'original_date' => $dateString,
                            'parsed_date' => $date
                        ]);
                    } catch (\Exception $dateError) {
                        Log::error("ATTENDANCE_SEEDER: Row {$rowNumber} in buku{$bookNumber}.xlsx - Failed to parse date", [
                            'file' => "buku{$bookNumber}.xlsx",
                            'row' => $rowNumber,
                            'date_string' => $dateString,
                            'error' => $dateError->getMessage(),
                            'raw_data' => $row
                        ]);

                        // Try alternative date formats
                        $alternativeFormats = ['Y-m-d', 'm/d/Y', 'd-m-Y', 'Y/m/d'];
                        $dateParsed = false;

                        foreach ($alternativeFormats as $format) {
                            try {
                                $date = Carbon::createFromFormat($format, $dateString)->format('Y-m-d');
                                Log::info("ATTENDANCE_SEEDER: Row {$rowNumber} date parsed with alternative format", [
                                    'file' => "buku{$bookNumber}.xlsx",
                                    'row' => $rowNumber,
                                    'format_used' => $format,
                                    'original_date' => $dateString,
                                    'parsed_date' => $date
                                ]);
                                $dateParsed = true;
                                break;
                            } catch (\Exception $e) {
                                // Continue to next format
                            }
                        }

                        if (!$dateParsed) {
                            Log::error("ATTENDANCE_SEEDER: Row {$rowNumber} in buku{$bookNumber}.xlsx - Could not parse date with any format", [
                                'file' => "buku{$bookNumber}.xlsx",
                                'row' => $rowNumber,
                                'date_string' => $dateString,
                                'tried_formats' => $alternativeFormats,
                                'raw_data' => $row
                            ]);
                            $skipped++;
                            $rowNumber++;
                            continue;
                        }
                    }

                    $status = strtolower($status);

                    // Convert 'tidak hadir' to 'alpha'
                    if ($status === 'tidak hadir') {
                        $status = 'alpha';
                    }

                    // Find student and eskul
                    $student = User::where('name', $studentName)
                        ->whereHas('roles', function ($q) {
                            $q->where('name', 'siswa');
                        })->first();

                    $eskul = Eskul::where('name', $eskulName)->first();

                    if (!$student) {
                        Log::error("ATTENDANCE_SEEDER: Row {$rowNumber} in buku{$bookNumber}.xlsx - Student not found", [
                            'file' => "buku{$bookNumber}.xlsx",
                            'row' => $rowNumber,
                            'student_name' => $studentName,
                            'raw_data' => $row
                        ]);
                        $skipped++;
                        $rowNumber++;
                        continue;
                    }

                    if (!$eskul) {
                        Log::error("ATTENDANCE_SEEDER: Row {$rowNumber} in buku{$bookNumber}.xlsx - Eskul not found", [
                            'file' => "buku{$bookNumber}.xlsx",
                            'row' => $rowNumber,
                            'eskul_name' => $eskulName,
                            'raw_data' => $row
                        ]);
                        $skipped++;
                        $rowNumber++;
                        continue;
                    }

                    Log::info("ATTENDANCE_SEEDER: Row {$rowNumber} found records", [
                        'file' => "buku{$bookNumber}.xlsx",
                        'row' => $rowNumber,
                        'student_id' => $student->id,
                        'student_name' => $student->name,
                        'eskul_id' => $eskul->id,
                        'eskul_name' => $eskul->name
                    ]);

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
                        Log::info("ATTENDANCE_SEEDER: Row {$rowNumber} created new member", [
                            'file' => "buku{$bookNumber}.xlsx",
                            'row' => $rowNumber,
                            'student_id' => $student->id,
                            'eskul_id' => $eskul->id
                        ]);
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
                    Log::info("ATTENDANCE_SEEDER: Row {$rowNumber} attendance record created/updated successfully", [
                        'file' => "buku{$bookNumber}.xlsx",
                        'row' => $rowNumber,
                        'student_id' => $student->id,
                        'eskul_id' => $eskul->id,
                        'date' => $date,
                        'status' => $status
                    ]);
                } catch (\Exception $e) {
                    Log::error("ATTENDANCE_SEEDER: Row {$rowNumber} in buku{$bookNumber}.xlsx - Unexpected error", [
                        'file' => "buku{$bookNumber}.xlsx",
                        'row' => $rowNumber,
                        'error_message' => $e->getMessage(),
                        'error_line' => $e->getLine(),
                        'error_file' => $e->getFile(),
                        'stack_trace' => $e->getTraceAsString(),
                        'raw_data' => $row
                    ]);
                    $skipped++;
                }

                $rowNumber++;
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
