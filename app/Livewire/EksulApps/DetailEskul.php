<?php

namespace App\Livewire\EksulApps;
use Livewire\Component;
use App\Models\Eskul;
use App\Helpers\HashHelper;
use App\Models\EskulMember;
use App\Models\Attendance;
use App\Models\EskulSchedule;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Carbon\Carbon;
use App\Models\EskulMaterial;
use Filament\Tables\Actions\ActionGroup;
use App\Models\EskulGallery;
use App\Models\EskulEvent;
use App\Models\EskulEventParticipant;

class DetailEskul extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $eskul;
    public $members_total;
    public $members_active;
    public $members_inactive;
    public $todaySchedule;
    public $userAttendanceStatus;
    public $canClockIn = false;
    public $attendanceHistory = [];
    public $attendance_percentage;
    public $eskulMaterial;
    public $eskulGallery;
    public $eskulEvents;

    public function mount($hash)
    {
        try {
            $id = HashHelper::decrypt($hash);
            
            if (!$id) {
                // Redirect jika decrypt gagal
                return redirect()->route('dashboard.eskul')->with('error', 'Invalid eskul ID');
            }

            $this->eskul = Eskul::with(['schedules.attendances.student', 'members.student', 'events.participants'])->find($id);

            // dd($this->eskuls);
            
            if (!$this->eskul) {
                // Redirect jika eskul tidak ditemukan
                return redirect()->route('dashboard.eskul')->with('error', 'Eskul not found');
            }

            // Menggunakan relasi members langsung
            $this->members_total = $this->eskul->members->count();
            $this->members_active = $this->eskul->members->where('status', 'active')->count();
            $this->members_inactive = $this->eskul->members->where('status', 'inactive')->count();

            // Get today's schedule
            $today = now()->format('l'); // Ini akan menghasilkan 'Monday'
            
            // Konversi hari ke bahasa Indonesia
            $dayMapping = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];
            
            $todayInIndonesian = $dayMapping[$today];
            $this->todaySchedule = $this->eskul->schedules->where('day', $todayInIndonesian)->first();

            // Check user's attendance status if they're a student
            if (Auth::user()->hasRole('siswa') && $this->todaySchedule) {
                $eskul_schedule = EskulSchedule::where('eskul_id', $this->eskul->id)
                    ->where('day', $todayInIndonesian)
                    ->where('is_active', true)
                    ->first();

                if ($eskul_schedule) {
                    // Set timezone ke Asia/Jakarta
                    date_default_timezone_set('Asia/Jakarta');
                    
                    $start_time = Carbon::parse($eskul_schedule->start_time)->format('H:i');
                    $end_time = Carbon::parse($eskul_schedule->end_time)->format('H:i');
                    $now = Carbon::now('Asia/Jakarta')->format('H:i');
                    
                    // Menggunakan strtotime dengan waktu relatif
                    $start_timestamp = strtotime("today {$start_time}");
                    $end_timestamp = strtotime("today {$end_time}");
                    $now_timestamp = strtotime("today {$now}");
                    
                    $this->canClockIn = $now_timestamp >= $start_timestamp && $now_timestamp <= $end_timestamp;
                }
            }

            // Calculate attendance percentage for today
            $attendance_percentage = 0;
            if ($this->members_total > 0) {
                $present_today = Attendance::where('eskul_id', $this->eskul->id)
                    ->where('date', today())
                    ->where('status', 'hadir')
                    ->count();
                $attendance_percentage = round(($present_today / $this->members_total) * 100);
            }
            
            $this->attendance_percentage = $attendance_percentage;

            // Menggunakan relasi materials langsung
            $this->loadEskulMaterial();
            $this->loadEskulGallery();
            $this->loadEskulEvents();

            $this->form->fill();

        } catch (\Exception $e) {
            // Log error jika perlu
            \Log::error('Error in DetailEskul mount: ' . $e->getMessage());
            return redirect()->route('dashboard.eskul')->with('error', 'An error occurred');
        }
    }

    public function loadEskulMaterial()
    {
        // Menggunakan relasi materials langsung
        $this->eskulMaterial = $this->eskul->materials->groupBy('title')->toArray();
    }

    public function loadEskulGallery()
    {
        // Menggunakan relasi galleries langsung
        $this->eskulGallery = $this->eskul->galleries()
            ->orderBy('event_date', 'desc')
            ->get();
    }

    public function loadEskulEvents()
    {
        // Load upcoming events with participants
        $this->eskulEvents = $this->eskul->events()
            ->with(['participants.student'])
            ->get();
    }

    public function getAttendanceStats()
    {
        if (!$this->todaySchedule) {
            return null;
        }

        return [
            'hadir' => $this->todaySchedule->attendances
                ->where('date', today())
                ->where('status', 'hadir')
                ->count(),
            'izin' => $this->todaySchedule->attendances
                ->where('date', today())
                ->where('status', 'izin')
                ->count(),
            'sakit' => $this->todaySchedule->attendances
                ->where('date', today())
                ->where('status', 'sakit')
                ->count(),
            'alpha' => $this->todaySchedule->attendances
                ->where('date', today())
                ->where('status', 'alpha')
                ->count(),
        ];
    }

    public function render()
    {
        $attendanceStats = $this->getAttendanceStats();
        
        $members = EskulMember::where('eskul_id', $this->eskul->id)
            ->with(['student'])
            ->get();

        return view('livewire.eksul-apps.detail-eskul', [
            'attendanceStats' => $attendanceStats,
            'members' => $members,
            'attendance_percentage' => $this->attendance_percentage
        ]);
    }

    public function createAttendanceFromSchedule()
    {
        // Cek jadwal hari ini
        $today = now()->format('l'); // Misalnya 'Monday'
        $currentTime = now();
        
        $schedule = EskulSchedule::where('eskul_id', $this->eskul->id)
            ->where('day', $today)
            ->where('is_active', true)
            ->first();
        
        if ($schedule) {
            // Buat attendance otomatis
            $attendance = Attendance::firstOrCreate(
                [
                    'eskul_id' => $this->eskul->id,
                    'schedule_id' => $schedule->id,
                    'date' => now()->toDateString()
                ],
                [
                    'created_by' => auth()->id(),
                    'actual_start_time' => null,
                    'actual_end_time' => null,
                    'location' => $schedule->location,
                    'status' => 'active'
                ]
            );
            
            return $attendance;
        }
    }

    public function clockIn()
    {
        try {
            // Validasi apakah bisa clock in
            if (!$this->canClockIn) {
                Notification::make()
                    ->title('Tidak dapat melakukan absensi')
                    ->warning()
                    ->send();
                return;
            }

            // Cek apakah sudah absen
            $existingAttendance = Attendance::where([
                'schedule_id' => $this->todaySchedule->id,
                'student_id' => auth()->id(),
                'date' => today()
            ])->first();

            if ($existingAttendance) {
                Notification::make()
                    ->title('Anda sudah melakukan absensi')
                    ->warning()
                    ->send();
                return;
            }

            // Catat kehadiran
            $attendance = Attendance::create([
                'eskul_id' => $this->eskul->id,
                'schedule_id' => $this->todaySchedule->id,
                'student_id' => auth()->id(),
                'date' => today(),
                'check_in_time' => now(),
                'status' => 'hadir',
                'is_verified' => false,
                'notes' => 'Absensi melalui sistem'
            ]);

            // Update status kehadiran di komponen
            $this->userAttendanceStatus = $attendance;
            $this->canClockIn = false;

            // Tampilkan notifikasi sukses
            Notification::make()
                ->title('Absensi Berhasil')
                ->success()
                ->send();

        } catch (\Exception $e) {
            \Log::error('Error in clockIn: ' . $e->getMessage());
            Notification::make()
                ->title('Terjadi kesalahan saat melakukan absensi')
                ->danger()
                ->send();
        }
    }

    public function registerForEvent($eventId)
    {
        try {
            $event = EskulEvent::findOrFail($eventId);
            
            // Check if registration is open
            if (!$event->isRegistrationOpen()) {
                Notification::make()
                    ->warning()
                    ->title('Pendaftaran sudah ditutup')
                    ->send();
                return;
            }

            // Check if user already registered
            if ($event->hasUserRegistered(auth()->user())) {
                Notification::make()
                    ->warning()
                    ->title('Anda sudah terdaftar di event ini')
                    ->send();
                return;
            }

            // Check if event requires registration approval
            if ($event->requires_registration) {
                Notification::make()
                    ->warning()
                    ->title('Event ini memerlukan persetujuan pelatih')
                    ->body('Silahkan hubungi pelatih untuk mendaftar')
                    ->send();
                return;
            }

            // Create participant record with auto-approved status
            EskulEventParticipant::create([
                'event_id' => $eventId,
                'student_id' => auth()->id(),
                'status' => 'approved', // Langsung approved karena tidak memerlukan persetujuan
                'notes' => 'Pendaftaran langsung melalui sistem'
            ]);

            // Refresh events data
            $this->loadEskulEvents();

            Notification::make()
                ->success()
                ->title('Berhasil mendaftar ke event')
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Gagal mendaftar ke event')
                ->send();
        }
    }

    public function table(Table $table): Table
    {
        $existingMemberIds = EskulMember::where('eskul_id', $this->eskul->id)
            ->pluck('student_id')
            ->toArray();
        return $table
            ->query(EskulMember::query()->where('eskul_id', $this->eskul->id))
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Anggota')
                    ->form([
                        Select::make('student_id')
                            ->label('Pilih Siswa')
                            ->options(
                                User::role('siswa')
                                    ->whereNotIn('id', $existingMemberIds)
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->multiple()
                            ->required()
                            ->helperText('Hanya menampilkan siswa yang belum menjadi anggota'),
                    ])
                    ->using(function (array $data) {
                        // Handle multiple student IDs
                        $studentIds = $data['student_id'];
                        $firstRecord = null;
                        
                        foreach ($studentIds as $studentId) {
                            $record = EskulMember::create([
                                'student_id' => $studentId,
                                'eskul_id' => $this->eskul->id,
                                'added_by' => auth()->id(),
                                'is_active' => true,
                                'join_date' => now(),
                                'notes' => 'Ditambahkan melalui form anggota'
                            ]);
                            
                            if (!$firstRecord) {
                                $firstRecord = $record;
                            }
                        }

                        // Notification::make()
                        //     ->title('Anggota berhasil ditambahkan!')
                        //     ->success()
                        //     ->send();
                            
                        return $firstRecord; // Return only the first record for table refresh
                    })
                    ->visible(fn (): bool => auth()->user()->hasPermissionTo('create eskul'))
                    ->successNotificationTitle('Anggota berhasil ditambahkan!')
                    ->closeModalByClickingAway(false)
                    ->createAnother(false)
            ])
            ->columns([
                TextColumn::make('student.name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Siswa'),
                TextColumn::make('student.detail.class')
                    ->searchable()
                    ->sortable()
                    ->label('Kelas'),

                TextColumn::make('is_active')
                    ->badge()
                    ->state(fn ($record) => $record->is_active == '1' ? 'Aktif' : 'Nonaktif')
                    ->color(fn ($state) => $state == 'Aktif' ? 'success' : 'danger'),
                TextColumn::make('join_date')
                    ->date('d M Y'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                
                ActionGroup::make([
                    Action::make('edit')
                        ->label('Edit')
                        ->icon('heroicon-o-pencil')
                    ->visible(fn (): bool => auth()->user()->hasPermissionTo('edit eskul'))
                    ->form([
                        Select::make('is_active')
                            ->options([
                                '1' => 'Aktif',
                                '0' => 'Nonaktif'
                            ])
                            ->required()
                    ])  
                    ->fillForm(fn (EskulMember $record): array => [
                        'is_active' => $record->is_active
                    ])
                    ->action(function (array $data, EskulMember $record) {
                        $record->update([
                            'is_active' => $data['is_active']
                        ]);
                    }),
                    Action::make('Detail')
                        ->label('Detail Akademik')
                        ->color('info')
                        ->icon('heroicon-o-eye')
                        ->url(fn ($record) => route('analisis-apps.detail-siswa', ['hash' => HashHelper::encrypt($record->student_id)])),
                    Action::make('delete')
                        ->label('Hapus')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (): bool => auth()->user()->hasPermissionTo('delete eskul'))
                        ->action(function (EskulMember $record) {
                            $record->delete();
                        }), 
                ])      
            ])
            ->bulkActions([
                // ...
            ]);
    }

    
    
}

