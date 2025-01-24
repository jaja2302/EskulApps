<?php

namespace App\Livewire;

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

class DashboardEskul extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {	

        return $table
            ->query(eskul::query())
            ->headerActions([
                CreateAction::make()
                ->label('Tambah Eskul')
                ->icon('heroicon-o-plus')
                ->form([
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
                    Select::make('pelatih_id')
                    ->label('Pelatih')
                    ->options(
                        User::whereHas('roles', function ($query) {
                            $query->whereIn('name', ['pelatih']);
                        })->pluck('name', 'id')
                    )
                    ->required(),
                    Select::make('wakil_pelatih_id')
                    ->label('Wakil Pelatih')
                    ->options(
                        User::whereHas('roles', function ($query) {
                            $query->whereIn('name', ['wakil_pelatih']);
                        })->pluck('name', 'id')
                    )
                ])
                ->closeModalByClickingAway(false)
                ->using(function (array $data) {
                    $eskul = Eskul::create($data);

                    return $eskul;
                })
                
            ])
            ->columns([
                TextColumn::make('name')
                ->label('Nama Eskul'),
                TextColumn::make('description')
                ->label('Deskripsi'),
                TextColumn::make('image'),
                TextColumn::make('pelatih_id')
                ->label('Pelatih')
                ->formatStateUsing(fn ($record) => $record->pelatih->name),
                TextColumn::make('wakil_pelatih_id')
                ->label('Wakil Pelatih')
                ->formatStateUsing(fn ($record) => $record->wakil_pelatih->name),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
                Action::make('detail')
                ->label('Detail')
                ->url(fn ($record) => route('eskul.detail', ['hash' => HashHelper::encrypt($record->id)]))
            ])
            ->bulkActions([
                // ...
            ]);
    }


    public function render()
    {
        return view('livewire.dashboard-eskul');
    }
}
