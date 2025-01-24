<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Filament\Notifications\Notification;
class UsersImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation,
    SkipsOnError,
    SkipsOnFailure,
    WithBatchInserts,
    WithChunkReading
{
    use SkipsErrors, SkipsFailures;

    private $rows = 0;
    private $importErrors = [];  // Renamed from $errors to avoid conflict

    public function model(array $row)
    {
        $this->rows++;
        
        try {
            DB::beginTransaction();

            // Check if email already exists
            if (User::where('email', $row['email'])->exists()) {
                $this->importErrors[] = "Row {$this->rows}: Email {$row['email']} already exists";
                DB::rollBack();
                return null;
            }

            $user = User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => Hash::make($row['password']),
            ]);

            $user->assignRole('siswa');

            DB::commit();

            Notification::make()
                ->title('Success')
                ->body('Users imported successfully')
                ->success()
                ->send();   
            
            return $user;

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->importErrors[] = "Row {$this->rows}: " . $e->getMessage();
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Kolom nama wajib diisi',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter',
            'email.required' => 'Kolom email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter',
            'password.required' => 'Kolom password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ];
    }

    public function getErrors(): array
    {
        // Combine both validation errors and our custom import errors
        $allErrors = $this->importErrors;
        
        foreach ($this->failures() as $failure) {
            $allErrors[] = "Row {$failure->row()}: {$failure->errors()[0]}";
        }

        return $allErrors;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
} 