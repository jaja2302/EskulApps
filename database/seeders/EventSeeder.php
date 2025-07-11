<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Eskul;
use App\Models\EskulEvent;
use App\Models\EskulEventParticipant;
use App\Models\Achievement;
use Illuminate\Support\Facades\Log;

class EventSeeder extends Seeder
{
    /**
     * Helper method to parse dates in format "DD Month YYYY"
     */
    private function parseCustomDate($dateString)
    {
        // First, try direct parsing with Carbon
        try {
            return Carbon::parse($dateString);
        } catch (\Exception $e) {
            // If that fails, try manual parsing
            $parts = explode(' ', $dateString);

            if (count($parts) == 3) {
                $day = (int)$parts[0];
                $month = $this->getMonthNumber($parts[1]);
                $year = (int)$parts[2];

                return Carbon::createFromDate($year, $month, $day);
            }

            // If all else fails, throw exception
            throw new \Exception("Could not parse date: $dateString");
        }
    }

    /**
     * Convert Indonesian month name to number
     */
    private function getMonthNumber($monthName)
    {
        $months = [
            'januari' => 1,
            'january' => 1,
            'jan' => 1,
            'februari' => 2,
            'february' => 2,
            'feb' => 2,
            'maret' => 3,
            'march' => 3,
            'mar' => 3,
            'april' => 4,
            'apr' => 4,
            'mei' => 5,
            'may' => 5,
            'juni' => 6,
            'june' => 6,
            'jun' => 6,
            'juli' => 7,
            'july' => 7,
            'jul' => 7,
            'agustus' => 8,
            'august' => 8,
            'aug' => 8,
            'september' => 9,
            'sept' => 9,
            'sep' => 9,
            'oktober' => 10,
            'october' => 10,
            'oct' => 10,
            'okt' => 10,
            'november' => 11,
            'nov' => 11,
            'desember' => 12,
            'december' => 12,
            'dec' => 12,
            'des' => 12
        ];

        $key = strtolower($monthName);

        if (isset($months[$key])) {
            return $months[$key];
        }

        // If month name not found, try to parse as number
        if (is_numeric($monthName)) {
            return (int)$monthName;
        }

        throw new \Exception("Unknown month: $monthName");
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Starting event, participant, and achievement import...');

        // Get admin user for created_by field
        $admin = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->first();

        if (!$admin) {
            $this->command->warn("No admin user found. Using the first user available as 'created_by'");
            $admin = User::first();

            if (!$admin) {
                $this->command->error("No users found in the database. Cannot proceed.");
                return;
            }
        }

        // Step 1: Import events
        $this->importEvents($admin);

        // Step 2: Import participants
        $this->importParticipants();

        // Step 3: Import achievements
        // $this->importAchievements();

        $this->command->info("Import completed successfully!");
    }

    /**
     * Import events from CSV
     */
    private function importEvents($admin)
    {
        $this->command->info('Importing events...');

        $csvPath = storage_path('app/events.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan: $csvPath");
            return;
        }

        // Create CSV reader
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setDelimiter(',');
        $csv->setHeaderOffset(0);

        $eventsCreated = 0;
        $skipped = 0;

        $this->command->getOutput()->progressStart(count($csv));

        // Track processed events to avoid duplicates
        $processedEvents = [];

        foreach ($csv as $record) {
            try {
                $eventTitle = $record['nama_event'];
                $eskulName = $record['nama_eskul'];
                $dateRange = $record['tanggal_event'];

                // Find the eskul
                $eskul = Eskul::where('name', $eskulName)->first();
                if (!$eskul) {
                    $this->command->warn("Eskul tidak ditemukan: $eskulName");
                    $skipped++;
                    $this->command->getOutput()->progressAdvance();
                    continue;
                }

                // Check if this event has been processed already
                $eventKey = $eventTitle . '-' . $eskulName;
                if (isset($processedEvents[$eventKey])) {
                    $this->command->getOutput()->progressAdvance();
                    continue;
                }

                // Parse date range (format: DD MM YYYY - DD MM YYYY)
                try {
                    $dates = explode(' - ', $dateRange);

                    // For start date - parse in English format with numeric month
                    $startDate = $this->parseCustomDate(trim($dates[0]));

                    // For end date
                    $endDate = isset($dates[1])
                        ? $this->parseCustomDate(trim($dates[1]))
                        : $startDate->copy();

                    // Set times (default start at 8 AM, end at 4 PM)
                    $startDatetime = $startDate->setTime(8, 0, 0);
                    $endDatetime = $endDate->setTime(16, 0, 0);
                } catch (\Exception $e) {
                    $this->command->warn("Format tanggal tidak valid: $dateRange - " . $e->getMessage());
                    $skipped++;
                    $this->command->getOutput()->progressAdvance();
                    continue;
                }

                // Create event
                EskulEvent::create([
                    'eskul_id' => $eskul->id,
                    'created_by' => $admin->id,
                    'title' => $eventTitle,
                    'description' => "Event $eventTitle untuk eskul $eskulName",
                    'start_datetime' => $startDatetime,
                    'end_datetime' => $endDatetime,
                    'location' => 'Tempat pelaksanaan event',
                    'is_finished' => false,
                    'is_winner_announced' => false,
                    'quota' => 100,
                    'requires_registration' => true,
                    'status' => 'pending',
                    'achievement_type' => 'juara_1' // Default, will be updated based on achievements
                ]);

                $eventsCreated++;
                $processedEvents[$eventKey] = true;
            } catch (\Exception $e) {
                $this->command->error("Error processing event: " . $e->getMessage());
                $skipped++;
            }

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info("Events created: $eventsCreated, Skipped: $skipped");
    }

    /**
     * Import participants from CSV
     */
    private function importParticipants()
    {
        $this->command->info('Importing participants...');

        $csvPath = storage_path('app/participants.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan: $csvPath");
            return;
        }

        // Create CSV reader
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        $participantsCreated = 0;
        $skipped = 0;

        $this->command->getOutput()->progressStart(count($csv));


        foreach ($csv as $record) {
            $tes_record = json_encode($record);
            Log::info("ATTENDANCE_SEEDER: Processing row {$tes_record}");
            try {
                $eskulName = $record['eskul_nama'];
                $studentName = $record['nama_siswa'];
                $eventTitle = $record['deskripsi_event'];
                $dateRange = $record['tanggal_event'];
                Log::info("ATTENDANCE_SEEDER: Processing row {$eskulName}");


                // Find the student
                $student = User::where('name', $studentName)
                    ->whereHas('roles', function ($q) {
                        $q->where('name', 'siswa');
                    })
                    ->first();

                if (!$student) {
                    $this->command->warn("Siswa tidak ditemukan: $studentName");
                    $skipped++;
                    $this->command->getOutput()->progressAdvance();
                    continue;
                }

                // Find the eskul
                $eskul = Eskul::where('name', $eskulName)->first();
                if (!$eskul) {
                    $this->command->warn("Eskul tidak ditemukan: $eskulName");
                    $skipped++;
                    $this->command->getOutput()->progressAdvance();
                    continue;
                }

                // Find the event
                $event = EskulEvent::where('title', $eventTitle)
                    ->where('eskul_id', $eskul->id)
                    ->first();

                if (!$event) {
                    $this->command->warn("Event tidak ditemukan: $eventTitle for $eskulName");
                    $skipped++;
                    $this->command->getOutput()->progressAdvance();
                    continue;
                }

                // Create participant entry
                $participant = EskulEventParticipant::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'student_id' => $student->id
                    ],
                    [
                        'status' => 'registered',
                        'notes' => "Participated in $eventTitle"
                    ]
                );

                $participantsCreated++;
            } catch (\Exception $e) {
                $this->command->error("Error processing participant: " . $e->getMessage());
                $skipped++;
            }

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info("Participants created: $participantsCreated, Skipped: $skipped");
    }

    /**
     * Import achievements from CSV
     */
    private function importAchievements()
    {
        $this->command->info('Importing achievements...');

        $csvPath = storage_path('app/achievements.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan: $csvPath");
            return;
        }

        // Create CSV reader
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setDelimiter(',');
        $csv->setHeaderOffset(0);

        $achievementsCreated = 0;
        $skipped = 0;

        $this->command->getOutput()->progressStart(count($csv));

        foreach ($csv as $record) {
            try {
                $eskulName = $record['eskul_nama'];
                $studentName = $record['nama_siswa'];
                $eventTitle = $record['deskripsi_event'];
                $achievement = $record['status_event'];
                $dateRange = $record['tanggal_event'];

                // Find the student
                $student = User::where('name', $studentName)
                    ->whereHas('roles', function ($q) {
                        $q->where('name', 'siswa');
                    })
                    ->first();

                if (!$student) {
                    $this->command->warn("Siswa tidak ditemukan: $studentName");
                    $skipped++;
                    $this->command->getOutput()->progressAdvance();
                    continue;
                }

                // Find the eskul
                $eskul = Eskul::where('name', $eskulName)->first();
                if (!$eskul) {
                    $this->command->warn("Eskul tidak ditemukan: $eskulName");
                    $skipped++;
                    $this->command->getOutput()->progressAdvance();
                    continue;
                }

                // Parse date range for achievement date
                try {
                    $dates = explode(' - ', $dateRange);
                    $achievementDate = $this->parseCustomDate(trim(isset($dates[1]) ? $dates[1] : $dates[0]));
                } catch (\Exception $e) {
                    $this->command->warn("Format tanggal tidak valid: $dateRange");
                    $achievementDate = Carbon::now();
                }

                // Map achievement to level and position
                $level = 'Kabupaten/Kota'; // Default level
                $position = '';

                if (stripos($achievement, 'Juara 1') !== false) {
                    $position = 'Juara 1';
                } elseif (stripos($achievement, 'Juara 2') !== false) {
                    $position = 'Juara 2';
                } elseif (stripos($achievement, 'Juara 3') !== false) {
                    $position = 'Juara 3';
                } elseif (stripos($achievement, 'Harapan') !== false) {
                    $position = 'Harapan';
                } else {
                    $position = $achievement;
                }

                // Create achievement
                Achievement::create([
                    'eskul_id' => $eskul->id,
                    'student_id' => $student->id,
                    'title' => $eventTitle,
                    'description' => "Meraih $position dalam $eventTitle",
                    'achievement_date' => $achievementDate,
                    'level' => $level,
                    'position' => $position,
                ]);

                // Also update the event to mark that it's finished and winners announced
                $event = EskulEvent::where('title', $eventTitle)
                    ->where('eskul_id', $eskul->id)
                    ->first();

                if ($event) {
                    $event->update([
                        'is_finished' => true,
                        'is_winner_announced' => true,
                        'status' => 'completed',
                        'result_notes' => "Pengumuman pemenang telah dilakukan."
                    ]);
                }

                $achievementsCreated++;
            } catch (\Exception $e) {
                $this->command->error("Error processing achievement: " . $e->getMessage());
                $skipped++;
            }

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info("Achievements created: $achievementsCreated, Skipped: $skipped");
    }
}
