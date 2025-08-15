<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\User;
use App\Models\Eskul;
use App\Models\EskulSchedule;
use App\Models\Attendance;
use App\Models\EskulMember;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceImport implements ToCollection
{
    private $attendanceCreated = 0;
    private $membersCreated = 0;
    private $skipped = 0;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        // Skip header row
        $rows = $collection->skip(1);
        
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because we skipped header and arrays are 0-indexed
            
            try {
                // Check if row has enough columns
                if ($row->count() < 4) {
                    Log::error("ATTENDANCE_IMPORT: Row {$rowNumber} - Not enough columns. Expected 4, got " . $row->count(), [
                        'row' => $rowNumber,
                        'columns_count' => $row->count(),
                        'raw_data' => $row->toArray()
                    ]);
                    $this->skipped++;
                    continue;
                }

                // Get data from columns with null checks
                $studentName = $row[0] ?? null;
                $eskulName = $row[1] ?? null;
                $dateString = $row[2] ?? null;
                $status = $row[3] ?? null;

                // Log raw data
                Log::info("ATTENDANCE_IMPORT: Row {$rowNumber} data", [
                    'row' => $rowNumber,
                    'student' => $studentName,
                    'eskul' => $eskulName,
                    'date' => $dateString,
                    'status' => $status
                ]);

                // Validate required fields
                if (empty($studentName)) {
                    Log::error("ATTENDANCE_IMPORT: Row {$rowNumber} - Student name is empty", [
                        'row' => $rowNumber,
                        'raw_data' => $row->toArray()
                    ]);
                    $this->skipped++;
                    continue;
                }

                if (empty($eskulName)) {
                    Log::error("ATTENDANCE_IMPORT: Row {$rowNumber} - Eskul name is empty", [
                        'row' => $rowNumber,
                        'raw_data' => $row->toArray()
                    ]);
                    $this->skipped++;
                    continue;
                }

                if (empty($dateString)) {
                    Log::error("ATTENDANCE_IMPORT: Row {$rowNumber} - Date is empty", [
                        'row' => $rowNumber,
                        'raw_data' => $row->toArray()
                    ]);
                    $this->skipped++;
                    continue;
                }

                if (empty($status)) {
                    Log::error("ATTENDANCE_IMPORT: Row {$rowNumber} - Status is empty", [
                        'row' => $rowNumber,
                        'raw_data' => $row->toArray()
                    ]);
                    $this->skipped++;
                    continue;
                }

                // Try to parse date with better error handling
                try {
                    [$date, $formatUsed] = $this->parseDateStrict($dateString);
                    Log::info("ATTENDANCE_IMPORT: Row {$rowNumber} date parsed strictly", [
                        'row' => $rowNumber,
                        'format_used' => $formatUsed,
                        'original_date' => $dateString,
                        'parsed_date' => $date
                    ]);
                } catch (\Throwable $dateError) {
                    Log::error("ATTENDANCE_IMPORT: Row {$rowNumber} - Failed strict date parse", [
                        'row' => $rowNumber,
                        'date_string' => $dateString,
                        'error' => $dateError->getMessage(),
                        'raw_data' => $row->toArray()
                    ]);
                    $this->skipped++;
                    continue;
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
                    Log::error("ATTENDANCE_IMPORT: Row {$rowNumber} - Student not found", [
                        'row' => $rowNumber,
                        'student_name' => $studentName,
                        'raw_data' => $row->toArray()
                    ]);
                    $this->skipped++;
                    continue;
                }

                if (!$eskul) {
                    Log::error("ATTENDANCE_IMPORT: Row {$rowNumber} - Eskul not found", [
                        'row' => $rowNumber,
                        'eskul_name' => $eskulName,
                        'raw_data' => $row->toArray()
                    ]);
                    $this->skipped++;
                    continue;
                }

                Log::info("ATTENDANCE_IMPORT: Row {$rowNumber} found records", [
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
                        'added_by' => auth()->id() ?? 1
                    ]
                );

                if ($member->wasRecentlyCreated) {
                    $this->membersCreated++;
                    Log::info("ATTENDANCE_IMPORT: Row {$rowNumber} created new member", [
                        'row' => $rowNumber,
                        'student_id' => $student->id,
                        'eskul_id' => $eskul->id
                    ]);
                }

                // Create attendance record with schedule_id and check_in_time
                // Warn if the parsed date year is not 2025 before inserting/updating
                $parsedYear = Carbon::parse($date)->year;
                // if ($parsedYear !== 2025) {
                //     Log::warning("ATTENDANCE_IMPORT: Row {$rowNumber} Non-2025 date detected before insert/update", [
                //         'row' => $rowNumber,
                //         'student' => $studentName,
                //         'eskul' => $eskulName,
                //         'original_date' => $dateString,
                //         'parsed_date' => $date,
                //         'parsed_year' => $parsedYear
                //     ]);
                // }

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
                        'verified_by' => auth()->id() ?? 1,  // Set admin as verifier
                        'verified_at' => now()  // Set verification time
                    ]
                );

                $this->attendanceCreated++;
                Log::info("ATTENDANCE_IMPORT: Row {$rowNumber} attendance record created/updated successfully", [
                    'row' => $rowNumber,
                    'student_id' => $student->id,
                    'eskul_id' => $eskul->id,
                    'date' => $date,
                    'status' => $status
                ]);
            } catch (\Exception $e) {
                Log::error("ATTENDANCE_IMPORT: Row {$rowNumber} - Unexpected error", [
                    'row' => $rowNumber,
                    'error_message' => $e->getMessage(),
                    'error_line' => $e->getLine(),
                    'error_file' => $e->getFile(),
                    'stack_trace' => $e->getTraceAsString(),
                    'raw_data' => $row->toArray()
                ]);
                $this->skipped++;
            }
        }
    }

    /**
     * Strictly parse a date string against known formats and prevent rollover
     * Returns array [Y-m-d, formatUsed].
     */
    private function parseDateStrict($input): array
    {
        // Handle Excel serial numbers (like 45672)
        if (is_numeric($input)) {
            try {
                // Excel serial date number (days since 1900-01-01, with 1900 incorrectly treated as leap year)
                $excelEpoch = Carbon::create(1900, 1, 1)->subDays(2); // Adjust for Excel's leap year bug
                $date = $excelEpoch->addDays(intval($input));
                
                Log::info("ATTENDANCE_IMPORT: Parsed Excel serial number", [
                    'input' => $input,
                    'parsed_date' => $date->format('Y-m-d'),
                    'format_used' => 'Excel Serial'
                ]);
                
                return [$date->format('Y-m-d'), 'Excel Serial'];
            } catch (\Throwable $e) {
                Log::error("ATTENDANCE_IMPORT: Failed to parse Excel serial number", [
                    'input' => $input,
                    'error' => $e->getMessage()
                ]);
                // Continue to string parsing below
            }
        }

        // Convert to string for string-based parsing
        $input = strval($input);

        $candidates = [
            'd/m/Y', 'm/d/Y', 'd-m-Y', 'Y-m-d', 'Y/m/d'
        ];

        foreach ($candidates as $format) {
            // Use '!' to reset non-specified fields and avoid bleed-over
            $dt = \DateTime::createFromFormat('!' . $format, $input);
            $errors = \DateTime::getLastErrors();
            if (!$dt || ($errors['warning_count'] ?? 0) > 0 || ($errors['error_count'] ?? 0) > 0) {
                continue;
            }

            // Round-trip check to ensure the same components (tolerate missing leading zeros)
            $reformatted = $dt->format($format);
            $normalize = static function (string $s): string {
                // remove leading zeros in day/month segments for comparison
                $s = preg_replace('/(^|[\\/\-])0+(\d)/', '$1$2', $s);
                return $s;
            };
            if ($normalize($reformatted) !== $normalize($input)) {
                // mismatch -> this format is not correct (prevents 10/25/2024 -> 2026-01-10 under d/m/Y)
                continue;
            }

            return [$dt->format('Y-m-d'), $format];
        }

        throw new \InvalidArgumentException('Unrecognized or invalid date: ' . $input);
    }

    /**
     * Get import statistics
     */
    public function getStats(): array
    {
        return [
            'attendanceCreated' => $this->attendanceCreated,
            'membersCreated' => $this->membersCreated,
            'skipped' => $this->skipped,
        ];
    }
}
