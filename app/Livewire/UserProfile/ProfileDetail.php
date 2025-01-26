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

class ProfileDetail extends Component implements HasForms
{
    use InteractsWithForms;
    
    public ?array $data = [];
    public User $user;

    public function mount(): void
    {
        $this->user = auth()->user();
        // $this->form->fill($this->user->detail?->toArray() ?? []);
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
                ->maxLength(255),
            TextInput::make('email')
                ->email()
                ->required()
                ->default($this->user->email)
                ->maxLength(255),
            TextInput::make('phone')
                ->label('No. Telepon')
                ->tel(),
        ];

        // Jika user adalah siswa, tambahkan field lengkap
        if ($this->user->hasRole('siswa')) {
            return $form
                ->schema([
                    Section::make('Informasi Pribadi')
                        ->schema([
                            Grid::make(2)->schema([
                                ...$basicFields,
                                TextInput::make('nis')
                                    ->label('NIS')
                                    ->maxLength(20),
                                TextInput::make('nisn')
                                    ->label('NISN')
                                    ->maxLength(20),
                                Select::make('gender')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ])
                                    ->required(),
                                TextInput::make('birth_place')
                                    ->label('Tempat Lahir'),
                                DatePicker::make('birth_date')
                                    ->label('Tanggal Lahir')
                                    ->format('d/m/Y'),
                                Select::make('religion')
                                    ->label('Agama')
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
                                    ->columnSpanFull(),
                            ]),
                        ]),

                    Section::make('Informasi Akademik')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('class')
                                    ->label('Kelas'),
                                TextInput::make('academic_year')
                                    ->label('Tahun Akademik'),
                            ]),
                        ]),

                    Section::make('Informasi Orang Tua')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('father_name')
                                    ->label('Nama Ayah'),
                                TextInput::make('father_occupation')
                                    ->label('Pekerjaan Ayah'),
                                TextInput::make('father_phone')
                                    ->label('No. Telepon Ayah')
                                    ->tel(),
                                TextInput::make('mother_name')
                                    ->label('Nama Ibu'),
                                TextInput::make('mother_occupation')
                                    ->label('Pekerjaan Ibu'),
                                TextInput::make('mother_phone')
                                    ->label('No. Telepon Ibu')
                                    ->tel(),
                            ]),
                        ]),

                    Section::make('Informasi Wali (Opsional)')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('guardian_name')
                                    ->label('Nama Wali'),
                                TextInput::make('guardian_occupation')
                                    ->label('Pekerjaan Wali'),
                                TextInput::make('guardian_phone')
                                    ->label('No. Telepon Wali')
                                    ->tel(),
                                TextInput::make('guardian_relation')
                                    ->label('Hubungan dengan Siswa'),
                            ]),
                        ]),

                    Section::make('Informasi Tambahan')
                        ->schema([
                            Grid::make(1)->schema([
                                Textarea::make('medical_history')
                                    ->label('Riwayat Kesehatan'),
                                Textarea::make('special_needs')
                                    ->label('Kebutuhan Khusus'),
                                Textarea::make('notes')
                                    ->label('Catatan Tambahan'),
                            ]),
                        ]),
                ])
                ->statePath('data');
        }

        // Form sederhana untuk admin/pembimbing/pelatih
        return $form
            ->schema([
                Section::make('Informasi Pribadi')
                    ->schema([
                        Grid::make(2)->schema($basicFields),
                    ]),
            ])
            ->statePath('data');
    }
    
    public function save(): void
    {
        $data = $this->form->getState();
        
        // Update user data
        $this->user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        
        // Update or create user detail
        $this->user->detail()->updateOrCreate(
            ['user_id' => $this->user->id],
            collect($data)->except(['name', 'email'])->toArray()
        );
        
        $this->dispatch('profile-updated');
        
        $this->notify('success', 'Profil berhasil diperbarui');
    }

    public function render(): View
    {
        return view('livewire.user-profile.profile-detail');
    }
}
