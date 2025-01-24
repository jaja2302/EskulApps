<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Filament\Notifications\Notification;

class UsersImport implements 
    ToArray, 
    WithHeadingRow,
    WithBatchInserts,
    WithChunkReading
{
    private $rows = 0;
    private $importErrors = [];

    public function array(array $rows)
    {
        foreach ($rows as $row) {
            if (!isset($row['name']) || !isset($row['email']) || !isset($row['password'])) {
                continue;
            }

            // Skip if email already exists
            if (User::where('email', $row['email'])->exists()) {
                continue;
            }

            $userData = [
                'name' => trim($row['name']),
                'email' => trim($row['email']),
                'password' => Hash::make($row['password'])
            ];

            $user = User::create($userData);
            $user->assignRole('siswa');
        }
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getErrors(): array
    {
        $allErrors = $this->importErrors;
        
        return $allErrors;
    }
} 