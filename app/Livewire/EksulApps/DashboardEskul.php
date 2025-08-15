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
use App\Imports\AttendanceImport;

class DashboardEskul extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {	

        return $table
            ->query(function () {
                if (auth()->user()->hasRole('admin')) {
                    // dd('admin');
                    return Eskul::query();
                } else if (auth()->user()->hasRole('pembina')) {
                    return Eskul::query()
                        ->where('pembina_id', auth()->id())
                        ->where('is_active', true);
                } else if (auth()->user()->hasRole('pelatih')) {
                    return Eskul::query()
                        ->where('pelatih_id', auth()->id())
                        ->where('is_active', true);
                } else {
                    // dd('siswa');
                    // Get user's eskul IDs through the new relationship
                    return Eskul::query()
                        ->whereHas('members', function($query) {
                            $query->where('student_id', auth()->id())
                                ->where('is_active', true);
                        })
                        ->where('is_active', true);
                }
            })
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Eskul')
                    ->icon('heroicon-o-plus')
                    ->form(formEskul())
                    ->closeModalByClickingAway(false)
                    ->visible(fn (): bool => auth()->user()->hasPermissionTo('create eskul'))
                    ->using(function (array $data) {
                        $data['is_active'] = true; // Set default value for is_active
                        $eskul = Eskul::create($data);
                        return $eskul;
                    }),
            ])
            ->columns([
                TextColumn::make('name')
                ->wrap()
                ->searchable()
                ->description(fn (Eskul $record): string => $record->description)
                ->label('Nama Eskul'),
                TextColumn::make('pelatih_id')
                ->label('Pelatih Dan Pembina')
                ->description(fn (Eskul $record): string => 'Pembina :' .' '. $record->pembina->name)
                ->formatStateUsing(fn ($record) =>'Pelatih :' .' '. $record->pelatih->name),
                TextColumn::make('category')
                ->label('Kategori(Kouta)')
                ->formatStateUsing(fn ($record) => $record->category .'('. $record->quota.')'),
                TextColumn::make('meeting_location')
                ->label('Lokasi Pertemuan'),
                TextColumn::make('requirements')
                ->wrap()
                ->label('Persyaratan'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                ActionGroup::make([
                        Action::make('detail')
                        ->label('Detail')
                        ->visible(fn (): bool => auth()->user()->hasPermissionTo('view eskul'))
                        ->icon('heroicon-o-eye')
                        ->url(fn ($record) => route('eskul.detail', ['hash' => HashHelper::encrypt($record->id)])),
                ActionGroup::make([
                        Action::make('edit')
                            ->label('Edit')
                            ->form(formEskul())
                            ->visible(fn (): bool => auth()->user()->hasPermissionTo('edit eskul'))
                            ->fillForm(fn (Eskul $record): array => [
                                'name' => $record->name,
                                'description' => $record->description,
                                'image' => $record->image,
                                'banner_image' => $record->banner_image,
                                'quota' => $record->quota,
                                'meeting_location' => $record->meeting_location,
                                'requirements' => $record->requirements,
                                'category' => $record->category,
                                'pelatih_id' => $record->pelatih_id,
                                'pembina_id' => $record->pembina_id,
                            ])
                            ->action(fn (Eskul $record, array $data) => $record->update($data))
                            ->icon('heroicon-o-pencil'),
                        Action::make('schedule')
                            ->label('Tambah Jadwal')
                            ->icon('heroicon-o-calendar')
                            ->form(formSchedule())
                            ->visible(fn (): bool => auth()->user()->hasPermissionTo('create schedule'))
                            ->modalHeading('Tambah Jadwal Eskul')
                            ->action(function (Eskul $record, array $data) {
                            
                                // Handle each schedule in the repeater
                                foreach ($data['schedules'] as $schedule) {
                                    EskulSchedule::create([
                                        'eskul_id' => $record->id,
                                        'day' => $schedule['day'],
                                        'start_time' => $schedule['start_time'],
                                        'end_time' => $schedule['end_time'],
                                        'location' => $schedule['location'],
                                        'is_active' => true
                                    ]);
                                }
            
                                Notification::make()
                                    ->success()
                                    ->title('Jadwal berhasil ditambahkan')
                                    ->body('Jadwal eskul telah berhasil disimpan.')
                                    ->send();
                            }),
                        Action::make('attendance')
                            ->label('Attendance')
                            ->icon('heroicon-o-calendar')
                            ->form(formAttendance())
                            ->visible(fn (): bool => auth()->user()->hasPermissionTo('create attendance'))
                            ->modalHeading('Tambah Absensi Eskul')
                            ->action(function (Eskul $record, array $data) {
                               try {
                                   // Create the import instance
                                   $import = new AttendanceImport();
                                   
                                   // Import the file
                                   Excel::import($import, $data['attendance']);
                                   
                                   // Get import statistics
                                   $stats = $import->getStats();
                                   
                                   Notification::make()
                                       ->title('Data imported successfully!')
                                       ->body("Attendance: {$stats['attendanceCreated']}, New Members: {$stats['membersCreated']}, Skipped: {$stats['skipped']}")
                                       ->success()
                                       ->send();
                               } catch (\Throwable $th) {
                                   Notification::make()
                                       ->title('Error importing data!')
                                       ->body("Error: {$th->getMessage()}")
                                       ->danger()
                                       ->send();
                               }
                               
                            }),
                        DeleteAction::make()
                            ->label('Hapus')
                            ->icon('heroicon-o-trash')
                            ->visible(fn (): bool => auth()->user()->hasPermissionTo('delete eskul'))
                            ->color('danger')
                            ->action(fn ($record) => $record->delete())
                        ])
                    ->button()
                    ->label('Eskul Management'),
                ActionGroup::make([
                        Action::make('material_management')
                            ->label('Material Management')
                            ->icon('heroicon-o-calendar')
                            ->form(formMaterial())
                            ->visible(fn (): bool => auth()->user()->hasPermissionTo('manage gallery'))
                            ->action(function (Eskul $record, array $data) {
                                EskulMaterial::create([
                                    'eskul_id' => $record->id,
                                    'uploaded_by' => auth()->id(),
                                    'title' => $data['title'],
                                    'description' => $data['description'],
                                    'file_path' => $data['material'],
                                    'file_type' => pathinfo($data['material'], PATHINFO_EXTENSION),
                                ]);

                                Notification::make()
                                    ->success()
                                    ->title('Material berhasil ditambahkan')
                                    ->body('Material eskul telah berhasil disimpan.')
                                    ->send();
                                
                            }),
                        Action::make('gallery_management')
                            ->label('Gallery Management')
                            ->icon('heroicon-o-photo')
                            ->form(formGallery())
                            ->visible(fn (): bool => auth()->user()->hasPermissionTo('manage gallery'))
                            ->action(function (Eskul $record, array $data) {
                                $link_video = null;
                                $description_video = null;
                                
                                // dd($data);
                                
                                if (!empty($data['media_link'])) {
                                    $link_video = implode(';', array_column($data['media_link'], 'link_video'));
                                    $description_video = implode(';', array_column($data['media_link'], 'description_video'));
                                }
                                
                                // dd([
                                //     'data' => $data,
                                //     'link_video' => $link_video,
                                //     'description_video' => $description_video,
                                // ]);
                                
                                EskulGallery::create([
                                    'eskul_id' => $record->id,
                                    'uploaded_by' => auth()->id(),
                                    'title' => $data['title'],
                                    'description' => $data['description'],
                                    'file_path' => $data['media'],
                                    'event_date' => $data['event_date'],
                                    'title_video' => $data['title_video'],
                                    'description_video' => $description_video,
                                    'link_video' => $link_video,
                                ]);

                                Notification::make()
                                    ->success()
                                    ->title('Gallery item added successfully')
                                    ->send();
                            }),
                        ])
                    ->button()
                    ->label('File Management'),
                ActionGroup::make([
                        Action::make('create_event')
                            ->label('Tambah Event')
                            ->icon('heroicon-o-calendar')
                            ->form(formEvent())
                            ->visible(fn (): bool => auth()->user()->hasAnyPermission(['manage event', 'create event']))
                            ->action(function (Eskul $record, array $data) {
                                EskulEvent::create([
                                    'eskul_id' => $record->id,
                                    'created_by' => auth()->id(),
                                    'title' => $data['title'],
                                    'description' => $data['description'],
                                    'start_datetime' => $data['start_datetime'],
                                    'end_datetime' => $data['end_datetime'],
                                    'location' => $data['location'],
                                    'quota' => $data['quota'],
                                    'requires_registration' => $data['requires_registration'],
                                ]);

                                Notification::make()
                                    ->success()
                                    ->title('Event created successfully')
                                    ->send();
                            }),
                        Action::make('list_event')
                            ->label('List Event')
                            ->icon('heroicon-o-calendar')
                            ->openUrlInNewTab()
                            ->url(fn ($record) => route('eskul.list-event', ['hash' => HashHelper::encrypt($record->id)])),
                    ])
                    ->button()
                    ->visible(fn (): bool => auth()->user()->hasPermissionTo('manage event'))
                    ->label('Event Management'),
                Action::make('analisis_eskul')
                    ->label('Eskul Analisis Report')
                    ->icon('heroicon-o-calendar')
                    ->openUrlInNewTab()
                    ->visible(fn (): bool => auth()->user()->hasPermissionTo('manage event'))
                    ->url(fn ($record) => route('eskul.analisis', ['hash' => HashHelper::encrypt($record->id)])),
                ])
            ])
            ->bulkActions([
                // ...
            ]);
    }


    public function render()
    {
        return view('livewire.eksul-apps.dashboard-eskul');
    }
}
    