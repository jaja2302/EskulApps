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

class DashboardEskul extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {	

        return $table
            ->query(function () {
                if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('pembina') || auth()->user()->hasRole('pelatih')) {
                    // dd('admin');
                    return Eskul::query();
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
                    ->form($this->formEskul())
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
                ->label('Nama Eskul'),
                TextColumn::make('description')
                ->label('Deskripsi'),
                TextColumn::make('pelatih_id')
                ->label('Pelatih')
                ->formatStateUsing(fn ($record) => $record->pelatih->name),
                TextColumn::make('pembina_id')
                ->label('Pembina')
                ->formatStateUsing(fn ($record) => $record->pembina->name),
                TextColumn::make('category')
                ->label('Kategori'),
                TextColumn::make('quota')
                ->label('Kuota'),
                TextColumn::make('meeting_location')
                ->label('Lokasi Pertemuan'),
                TextColumn::make('requirements')
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
                    Action::make('edit')
                        ->label('Edit')
                        ->form($this->formEskul())
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
                        ->form($this->formSchedule())
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
                    Action::make('material_management')
                        ->label('Material Management')
                        ->icon('heroicon-o-calendar')
                        ->form($this->formMaterial())
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
                    DeleteAction::make()
                        ->label('Hapus')
                        ->icon('heroicon-o-trash')
                        ->visible(fn (): bool => auth()->user()->hasPermissionTo('delete eskul'))
                        ->color('danger')
                        ->action(fn ($record) => $record->delete())
                ])
            ])
            ->bulkActions([
                // ...
            ]);
    }

    private function formEskul(): array
    {
        return [
            TextInput::make('name')
                        ->label('Nama Eskul')
                        ->required(),
                    TextInput::make('description')
                        ->label('Deskripsi')
                        ->required(),
                    FileUpload::make('image')
                        ->label('Gambar')
                        ->image()
                        ->directory('eskul/banner')
                        ->required()
                        ->visibility('private'),
                    FileUpload::make('banner_image')
                        ->label('Banner')
                        ->image()
                        ->directory('eskul/banner')
                        ->visibility('private'),
                    Radio::make('kouta_unlimited')
                        ->label('Kuota Unlimited')
                        ->options([
                            '1' => 'Aktif',
                            '0' => 'Tidak Aktif',
                        ])
                        ->required()
                        ->live()
                        ->default('1'),
                    TextInput::make('quota')
                        ->label('Kuota')
                        ->numeric()
                        ->visible(fn (Get $get) => $get('kouta_unlimited') == '0' ? true : false)
                        ->required(),
                    TextInput::make('meeting_location')
                        ->label('Lokasi Pertemuan')
                        ->required(),
                    TextInput::make('requirements')
                        ->label('Persyaratan')
                        ->required(),
                    TextInput::make('category')
                        ->label('Kategori')
                        ->required(),
                    Select::make('pelatih_id')
                        ->label('Pelatih')
                        ->options(
                            User::whereHas('roles', function ($query) {
                                $query->whereIn('name', ['pelatih']);
                            })->pluck('name', 'id')
                        )
                        ->required(),
                    Select::make('pembina_id')
                        ->label('Pembina')
                        ->options(
                            User::whereHas('roles', function ($query) {
                                $query->whereIn('name', ['pembina']);
                            })->pluck('name', 'id')
                        )
                        ->required(),
        ];
    }

    private function formMaterial(): array
    {
        return [
            TextInput::make('title')
                ->label('Judul')
                ->required(),
            TextInput::make('description')
                ->label('Deskripsi')
                ->required(),
            FileUpload::make('material')
                ->label('Material')
                ->directory('eskul/material')
                // ->multiple()
                ->maxFiles(5)
                ->required()
                ->visibility('private'),
        ];
    }
    
    private function formSchedule(): array
    {
        return [
            Repeater::make('schedules')
            ->label('Jadwal')
            ->schema([
            Select::make('day')
                ->label('Hari')
                ->options([
                    'Senin' => 'Senin',
                    'Selasa' => 'Selasa',
                    'Rabu' => 'Rabu',
                    'Kamis' => 'Kamis',
                    'Jumat' => 'Jumat',
                    'Sabtu' => 'Sabtu',
                    'Minggu' => 'Minggu',
                ])
                ->required(),
            TextInput::make('start_time')
                ->label('Waktu Mulai')
                ->type('time')
                ->required(),
            TextInput::make('end_time')
                ->label('Waktu Selesai')
                ->type('time')
                ->required(),
            TextInput::make('location')
                        ->label('Lokasi')
                        ->required(),
                ])
        ];
    }

    public function render()
    {
        return view('livewire.eksul-apps.dashboard-eskul');
    }
}
