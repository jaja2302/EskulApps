<?php

namespace App\Livewire\EksulApps;


use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use App\Models\User;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Spatie\Permission\Models\Role;
use Livewire\WithFileUploads;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Models\Eskul;
use App\Helpers\HashHelper;
use Filament\Forms\Components\Repeater;
use App\Models\EskulSchedule;
use Filament\Forms\Components\Radio;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\ActionGroup;
use App\Models\EskulMaterial;
use App\Models\EskulGallery;
use Filament\Forms\Components\DatePicker;
use App\Models\EskulEvent;
use Filament\Forms\Components\Toggle;
use App\Models\EskulMember;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Textarea;
use App\Models\EskulEventParticipant;
use App\Models\Achievement;

class DetailEvent extends Component implements HasForms, HasTable
{
    public $event;
    use InteractsWithTable;
    use InteractsWithForms;
    public $id;
    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return EskulEvent::query()->where('eskul_id', $this->id)->with(['participants.student','eskul.members.student']);
            })
            ->columns([
               TextColumn::make('title')
               ->sortable()
               ->searchable(),
               TextColumn::make('description')
               ->sortable()
               ->searchable(),
               TextColumn::make('start_datetime')
               ->label('Tanggal Mulai'),
               TextColumn::make('end_datetime')
               ->label('Tanggal Selesai'),
               TextColumn::make('quota')
               ->label('Kuota'),
               TextColumn::make('location')
               ->sortable()
               ->label('Lokasi'),
               TextColumn::make('requires_registration')
               ->state(fn ($record) => $record->requires_registration == 1 ? 'Pendaftaran' : 'Umum')
               ->label('Jenis Pendaftaran'),
               TextColumn::make('participants')
               ->sortable()
               ->label('Peserta')
               ->state(fn ($record) => $record->participants->count() === 0 ? 'Tidak ada peserta' : $record->participants->count()),
               TextColumn::make('status_registration')
               ->sortable()
               ->label('Status Pendaftaran')
               ->color(fn ($state) => $state == 'Buka' ? 'success' : 'danger')
               ->badge()
               ->state(function ($record) {
                   if ($record->isRegistrationOpen()) {
                       return 'Buka'; // Registration is open
                   } else {
                       return 'Tutup'; // Registration is closed
                   }
               }),
               TextColumn::make('status')
                   ->label('Status Event')
                   ->badge()
                   ->color(fn ($state) => match ($state) {
                       'pending' => 'warning',
                       'completed' => 'success',
                       'cancelled' => 'danger',
                   })
                   ->state(fn ($record) => $record->status ?? 'pending'),
            ])
            ->filters([
                SelectFilter::make('requires_registration')
                    ->options([
                        1 => 'Pendaftaran',
                        0 => 'Umum',
                    ]),
            ])
            ->actions([
                Action::make('view_participants')
                ->form([
                    Select::make('participants_id')
                        ->label('List Peserta')
                        ->options(fn ($record) => $record->eskul->members->pluck('student.name', 'student.id'))
                        ->multiple()
                        ->disabled(fn ($record) => !$record->isRegistrationOpen())
                        ->searchable()
                        ->maxItems(fn ($record) => $record->quota)
                        ->preload(),
                ])
                ->fillForm(function (EskulEvent $record) {
                    return [
                        'participants_id' => $record->participants->pluck('student_id'),
                    ];
                })
                ->action(function (EskulEvent $record, array $data) {
                    // Get the current participants for the event
                    $currentParticipants = $record->participants->pluck('student_id')->toArray();
            
                    // Get the selected participants from the form
                    $selectedParticipants = $data['participants_id'];
            
                    // Participants to add (selected but not currently registered)
                    $participantsToAdd = array_diff($selectedParticipants, $currentParticipants);
            
                    // Participants to remove (currently registered but not selected)
                    $participantsToRemove = array_diff($currentParticipants, $selectedParticipants);
            
                    // Add new participants
                    foreach ($participantsToAdd as $participant) {
                        EskulEventParticipant::create([
                            'event_id' => $record->id,
                            'student_id' => $participant,
                            'status' => 'registered',
                            'notes' => 'Pendaftaran Peserta',
                        ]);
                    }
            
                    // Remove deselected participants
                    if (!empty($participantsToRemove)) {
                        EskulEventParticipant::where('event_id', $record->id)
                            ->whereIn('student_id', $participantsToRemove)
                            ->delete();
                    }
            
                    Notification::make()
                        ->title('Pendaftaran Peserta Berhasil Diperbarui')
                        ->success()
                        ->send();
                })
                ->visible(fn (): bool => auth()->user()->hasPermissionTo('create event')),
                Action::make('edit')
                    ->visible(fn (): bool => auth()->user()->hasPermissionTo('edit event'))
                    ->form(formEvent())
                    ->fillform(fn (EskulEvent $record) => $record->toArray())
                    ->action(function (EskulEvent $record, array $data) {
                        $record->update($data);
                        Notification::make()
                            ->title('Event Berhasil Diperbarui')
                            ->success()
                            ->send();
                    })
                ->visible(fn (): bool => auth()->user()->hasPermissionTo('edit event')),
                Action::make('delete')
                    ->label('Hapus Event')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (): bool => auth()->user()->hasPermissionTo('delete event'))
                    ->action(function (EskulEvent $record) {
                        $record->delete();
                        Notification::make()
                            ->title('Event Berhasil Dihapus')
                            ->success()
                            ->send();
                    }),
                Action::make('verify_event')
                    ->label('Verifikasi Event')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->form([
                        Select::make('status')
                            ->label('Status Event')
                            ->options([
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                'pending' => 'Belum Selesai'
                            ])
                            ->live()
                            ->required(),
                        Textarea::make('result_notes')
                            ->label('Catatan Hasil')
                            ->placeholder('Masukkan detail hasil event (prestasi/penghargaan/dll)')
                            ->rows(3),
                        Select::make('achievement_type')
                            ->label('Jenis Prestasi')
                            ->options([
                                'juara_1' => 'Juara 1',
                                'juara_2' => 'Juara 2',
                                'juara_3' => 'Juara 3',
                                'harapan' => 'Juara Harapan',
                                'partisipasi' => 'Partisipasi',
                                'lainnya' => 'Lainnya'
                            ])
                            ->visible(fn (Get $get) => $get('status') === 'completed'),
                    ])
                    ->fillForm(function (EskulEvent $record) {
                        return [
                            'status' => $record->status ?? 'pending',
                            'result_notes' => $record->result_notes,
                            'achievement_type' => $record->achievement_type,
                        ];
                    })
                    ->action(function (EskulEvent $record, array $data) {

                        // dd($data);
                        $record->update([
                            'status' => $data['status'],
                            'result_notes' => $data['result_notes'],
                            'achievement_type' => $data['status'] === 'completed' ? $data['achievement_type'] : null,
                            'is_finished' => 1
                        ]);

                        // Jika status completed dan ada achievement_type, buat achievement baru
                        if ($data['status'] === 'completed' && isset($data['achievement_type'])) {
                            // Ambil semua siswa yang terdaftar di event ini
                            $participants = $record->participants;
                            
                            foreach ($participants as $participant) {
                                Achievement::create([
                                    'eskul_id' => $record->eskul_id,
                                    'student_id' => $participant->id,
                                    'title' => "Prestasi: {$record->title}",
                                    'description' => $data['result_notes'],
                                    'achievement_date' => now(),
                                    'level' => 'event', // atau bisa ditambahkan field level di form
                                    'position' => match ($data['achievement_type']) {
                                        'juara_1' => '1',
                                        'juara_2' => '2',
                                        'juara_3' => '3',
                                        'harapan' => '4',
                                        'partisipasi' => '5',
                                        'lainnya' => null,
                                    },
                                    // Certificate file bisa ditambahkan nanti jika diperlukan
                                ]);
                            }
                        }
                        
                        Notification::make()
                            ->title('Status Event Berhasil Diperbarui')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (): bool => auth()->user()->hasPermissionTo('edit event')),
            ]);
    }

    public function mount($hash)
    {
        $id = HashHelper::decrypt($hash);
        $this->id = $id;
    }

    public function render()
    {

        return view('livewire.eksul-apps.detail-event');
    }
}
