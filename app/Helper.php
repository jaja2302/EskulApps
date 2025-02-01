<?php

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


if (!function_exists('formEskul')) {
function formEskul(): array
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
}   

if (!function_exists('formMaterial')) {
     function formMaterial(): array
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
}   

if (!function_exists('formSchedule')) {
function formSchedule(): array
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
}   

if (!function_exists('formGallery')) {
function formGallery(): array
{
    return [
        TextInput::make('title')
            ->label('Judul')
            ->required(),
        TextInput::make('description')
            ->label('Deskripsi')
            ->required(),
        FileUpload::make('media')
            ->label('Media (Foto/Video)')
            ->directory('eskul/gallery')
            ->acceptedFileTypes(['image/*', 'video/mp4'])
            ->maxSize(50000)
            ->required()
            ->visibility('private'),
        DatePicker::make('event_date')
            ->label('Tanggal Event')
            ->required(),
    ];
    }
}   

if (!function_exists('formEvent')) {
function formEvent(): array
{
    return [
        TextInput::make('title')
            ->label('Judul Event')
            ->required(),
        TextInput::make('description')
            ->label('Deskripsi')
            ->required(),
        DatePicker::make('start_datetime')
            ->label('Waktu Mulai')
            ->required()
            ->native(false),
        DatePicker::make('end_datetime')
            ->label('Waktu Selesai')
            ->required()
            ->native(false),
        TextInput::make('location')
            ->label('Lokasi')
            ->required(),
        Toggle::make('requires_registration')
            ->label('Memerlukan Pendaftaran')
            ->default(true),
        TextInput::make('quota')
            ->label('Kuota')
            ->numeric()
                ->required(fn (Get $get): bool => $get('requires_registration')),
        ];
    }
}   
