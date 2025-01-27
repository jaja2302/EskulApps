<?php

namespace App\Livewire\UserProfile;

use Livewire\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Radio;
use App\Helpers\HashHelper;

class ProfileDetail extends Component implements HasForms
{
    use InteractsWithForms;
    
    public ?array $data = [];
    public User $user;
    public ?string $hash = null;

    public function mount(?string $hash = null): void
    {
        if ($hash) {
            // Jika ada hash, decode untuk mendapatkan user ID
            $userId = HashHelper::decrypt($hash);
            $this->user = User::findOrFail($userId);
        } else {
            // Jika tidak ada hash, gunakan user yang sedang login
            $this->user = auth()->user();
        }
        
        $this->form->fill();
    }
    
    public function form(Form $form): Form
    {   
        // Basic fields untuk semua role
        $basicFields = [
            TextInput::make('name')
                ->label('Nama Lengkap')
                ->required()
                ->default($this->user->name)
                ->disabled(fn () => !auth()->user()->hasRole('admin'))
                ->maxLength(255),
            TextInput::make('email')
                ->email()
                ->disabled(fn () => !auth()->user()->hasRole('admin'))
                ->default($this->user->email)
                ->maxLength(255),
            TextInput::make('phone')
                ->label('No. Telepon')
                ->numeric()
                ->default($this->user->detail?->phone ?? ''),
        ];

        // Jika user yang dilihat adalah siswa, tampilkan form lengkap
        if ($this->user->hasRole('siswa')) {
            return $form
                ->schema([
                    Section::make('Informasi Pribadi')
                        ->schema([
                            Grid::make(2)->schema([
                                ...$basicFields,
                                TextInput::make('nis')
                                    ->label('NIS')
                                    ->numeric()
                                    ->placeholder('Cth : 1234567890')
                                    ->maxLength(50)
                                    ->default($this->user->detail?->nis ?? ''),
                                TextInput::make('nisn')
                                    ->label('NISN')
                                    ->numeric()
                                    ->placeholder('Cth : 1234567890')
                                    ->maxLength(50)
                                    ->default($this->user->detail?->nisn ?? ''),
                                Select::make('gender')
                                    ->default($this->user->detail?->gender ?? '')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ])
                                    ->required(),
                                TextInput::make('birth_place')
                                    ->label('Tempat Lahir')
                                    ->maxLength(255)
                                    ->placeholder('Cth : Jakarta, Bandung, dll')
                                    ->required()
                                    ->default($this->user->detail?->birth_place ?? ''),
                                DatePicker::make('birth_date')
                                    ->label('Tanggal Lahir')
                                    ->format('d/m/Y')
                                    ->required()
                                    ->default($this->user->detail?->birth_date ?? ''),
                                Select::make('religion')
                                    ->label('Agama')
                                    ->default($this->user->detail?->religion ?? '')
                                    ->required()
                                    ->options([
                                        'Islam' => 'Islam',
                                        'Kristen' => 'Kristen',
                                        'Katolik' => 'Katolik',
                                        'Hindu' => 'Hindu',
                                        'Buddha' => 'Buddha',
                                        'Konghucu' => 'Konghucu',
                                    ]),
                                Textarea::make('address')
                                    ->label('Alamat')
                                    ->maxLength(255)
                                    ->placeholder('Cth : Jl. Raya, No. 123, Kel. Sukajadi, Kec. Bandung Barat, Kota Bandung, Jawa Barat 40115')
                                    ->default($this->user->detail?->address ?? '')
                                    ->columnSpanFull()
                                    ->required(),
                            ]),
                        ]),

                    Section::make('Informasi Akademik')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('class')
                                    ->label('Kelas')
                                    ->maxLength(10)
                                    ->placeholder('Cth : XII RPL 1')
                                    ->default($this->user->detail?->class ?? '')
                                    ->required(),
                                TextInput::make('academic_year')
                                    ->label('Tahun Akademik')
                                    ->maxLength(20)
                                    ->placeholder('Cth : 2024/2025')
                                    ->default($this->user->detail?->academic_year ?? '')
                                    ->required(),
                            ]),
                        ]),

                    Section::make('Status Tempat Tinggal')
                        ->schema([
                            Radio::make('living_with')
                                ->label('Tinggal Bersama')
                                ->options([
                                    'parents' => 'Orang Tua',
                                    'guardian' => 'Wali'
                                ])
                                ->required()
                                ->default($this->user->detail?->living_with ?? 'parents')
                                ->live(),
                        ]),

                    Section::make('Informasi Orang Tua')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('father_name')
                                    ->label('Nama Ayah')
                                    ->maxLength(255)
                                    ->placeholder('Cth : Nama ayah kandung')
                                    ->default($this->user->detail?->father_name ?? '')
                                    ->required(fn (callable $get) => $get('living_with') === 'parents'),
                                TextInput::make('father_occupation')
                                    ->label('Pekerjaan Ayah')
                                    ->maxLength(255)
                                    ->placeholder('Cth : Karyawan, Wiraswasta, dll')
                                    ->default($this->user->detail?->father_occupation ?? '')
                                    ->required(fn (callable $get) => $get('living_with') === 'parents'),
                                TextInput::make('father_phone')
                                    ->label('No. Telepon Ayah')
                                    ->numeric()
                                    ->maxLength(15)
                                    ->placeholder('Cth : 081234567890')
                                    ->default($this->user->detail?->father_phone ?? '')
                                    ->required(fn (callable $get) => $get('living_with') === 'parents'),
                                TextInput::make('mother_name')
                                    ->label('Nama Ibu')
                                    ->maxLength(255)
                                    ->placeholder('Cth : Nama ibu kandung')
                                    ->default($this->user->detail?->mother_name ?? '')
                                    ->required(fn (callable $get) => $get('living_with') === 'parents'),
                                TextInput::make('mother_occupation')
                                    ->label('Pekerjaan Ibu')
                                    ->maxLength(255)
                                    ->placeholder('Cth : Karyawan, Wiraswasta, dll')
                                    ->default($this->user->detail?->mother_occupation ?? '')
                                    ->required(fn (callable $get) => $get('living_with') === 'parents'),
                                TextInput::make('mother_phone')
                                    ->label('No. Telepon Ibu')
                                    ->numeric()
                                    ->maxLength(15)
                                    ->placeholder('Cth : 081234567890')
                                    ->default($this->user->detail?->mother_phone ?? '')
                                    ->required(fn (callable $get) => $get('living_with') === 'parents'),
                                TextInput::make('parent_email')
                                    ->label('Email Orang Tua')
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('Email ayah atau ibu')
                                    ->default($this->user->detail?->parent_email ?? '')
                                    ->required(fn (callable $get) => $get('living_with') === 'parents'),
                            ]),
                        ])
                        ->hidden(fn (callable $get) => $get('living_with') === 'guardian'),

                    Section::make('Informasi Wali')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('guardian_name')
                                    ->label('Nama Wali')
                                    ->maxLength(255)
                                    ->default($this->user->detail?->guardian_name ?? '')
                                    ->required(fn (callable $get) => $get('living_with') === 'guardian'),
                                TextInput::make('guardian_occupation')
                                    ->label('Pekerjaan Wali')
                                    ->maxLength(255)
                                    ->default($this->user->detail?->guardian_occupation ?? '')
                                    ->required(fn (callable $get) => $get('living_with') === 'guardian'),
                                TextInput::make('guardian_phone')
                                    ->label('No. Telepon Wali')
                                    ->numeric()
                                    ->maxLength(15)
                                    ->default($this->user->detail?->guardian_phone ?? '')
                                    ->required(fn (callable $get) => $get('living_with') === 'guardian'),
                                TextInput::make('guardian_relation')
                                    ->label('Hubungan dengan Siswa')
                                    ->maxLength(255)
                                    ->default($this->user->detail?->guardian_relation ?? '')
                                    ->required(fn (callable $get) => $get('living_with') === 'guardian'),
                                TextInput::make('guardian_email')
                                    ->label('Email Wali')
                                    ->email()
                                    ->maxLength(255)
                                    ->default($this->user->detail?->guardian_email ?? '')
                                    ->required(fn (callable $get) => $get('living_with') === 'guardian'),
                            ]),
                        ])
                        ->hidden(fn (callable $get) => $get('living_with') === 'parents'),

                    Section::make('Informasi Tambahan')
                        ->schema([
                            Grid::make(1)->schema([
                                Textarea::make('medical_history')
                                    ->label('Riwayat Kesehatan')
                                    ->maxLength(255)
                                    ->placeholder('Cth : Riwayat penyakit, alergi, dll jika tidak ada tidak perlu diisi')
                                    ->default($this->user->detail?->medical_history ?? ''),
                                Textarea::make('special_needs')
                                    ->label('Kebutuhan Khusus')
                                    ->maxLength(255)
                                    ->placeholder('Cth : Kebutuhan khusus, keterangan, dll jika tidak ada tidak perlu diisi')
                                    ->default($this->user->detail?->special_needs ?? ''),
                                Textarea::make('notes')
                                    ->label('Catatan Tambahan')
                                    ->maxLength(255)
                                    ->placeholder('Cth : Catatan tambahan, keterangan, dll jika tidak ada tidak perlu diisi')
                                    ->default($this->user->detail?->notes ?? ''),
                            ]),
                        ]),
                ])
                ->statePath('data')
                ->disabled(fn () => !$this->canEdit()); // Disable form jika tidak punya hak edit
        }

        // Form sederhana untuk admin/pembimbing/pelatih
        return $form
            ->schema([
                Section::make('Informasi Pribadi')
                    ->schema([
                        Grid::make(2)->schema($basicFields),
                    ]),
            ])
            ->statePath('data')
            ->disabled(fn () => !$this->canEdit()); // Disable form jika tidak punya hak edit
    }
    
    protected function canEdit(): bool
    {
        $authUser = auth()->user();
        
        // Admin bisa edit semua user
        if ($authUser->hasRole('admin')) {
            return true;
        }
        
        // User hanya bisa edit profilnya sendiri
        return $authUser->id === $this->user->id;
    }
    
    public function save(): void
    {
        // Cek apakah user punya hak untuk mengedit
        if (!$this->canEdit()) {
            Notification::make()
                ->title('Akses Ditolak')
                ->body('Anda tidak memiliki hak untuk mengedit profil ini.')
                ->danger()
                ->send();
            return;
        }

        $data = $this->form->getState();
        
        // Update user data
        $this->user->update([
            'name' => $data['name'],
            'email' => $data['email'] ?? $this->user->email,
        ]);
        
        // Update or create user detail
        $this->user->detail()->updateOrCreate(
            ['user_id' => $this->user->id],
            collect($data)->except(['name', 'email'])->toArray()
        );
        
        Notification::make()
            ->title('Profil berhasil diperbarui')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.user-profile.profile-detail', [
            'canEdit' => $this->canEdit(),
        ]);
    }
}
