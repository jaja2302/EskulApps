<?php

namespace App\Livewire\Manageuser;

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
use App\Helpers\HashHelper;
use Filament\Tables\Actions\ActionGroup;

class Managementuser extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    use WithFileUploads;

   

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query())
            ->headerActions([
                CreateAction::make()
                    ->closeModalByClickingAway(false)
                    ->form([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->required()
                            ->email(),
                        TextInput::make('password')
                            ->required()
                            ->password(),
                        Select::make('roles')
                            ->options(Role::all()->pluck('name', 'name'))
                            // ->multiple()
                            ->required(),
                    ])
                    ->using(function (array $data) {
                        $user = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => bcrypt($data['password']),
                        ]);
                        
                        $user->assignRole($data['roles']);
                        
                        return $user;
                    }),
                Action::make('import')
                    ->label('Import Excel')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        FileUpload::make('excelFile')
                            ->label('File Excel')
                            ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->directory('temp')
                            ->required()
                            ->visibility('public')
                    ])
                    ->action(function (array $data): void {
                        try {
                            if (!isset($data['excelFile'])) {
                                Notification::make()
                                    ->title('Error')
                                    ->body('No file was uploaded')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $path = storage_path('app/public/' . $data['excelFile']);
                            
                            if (!file_exists($path)) {
                                Notification::make()
                                    ->title('Error')
                                    ->body('File not found at: ' . $path)
                                    ->danger()
                                    ->send();
                                return;
                            }

                            DB::beginTransaction();
                            
                            $import = new UsersImport();
                            Excel::import($import, $path);

                            // Check if there were any errors during import
                            $errors = $import->getErrors();
                            if (!empty($errors)) {
                                DB::rollBack();
                                
                                Notification::make()
                                    ->title('Import Failed')
                                    ->body(implode("\n", $errors))
                                    ->danger()
                                    ->send();
                                    
                                return;
                            }

                            DB::commit();

                            // Clean up the temporary file
                            if (file_exists($path)) {
                                unlink($path);
                            }

                            Notification::make()
                                ->title('Success')
                                ->body('Users imported successfully')
                                ->success()
                                ->send();

                        } catch (\Exception $e) {
                            DB::rollBack();
                            
                            Notification::make()
                                ->title('Error')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('created_at'),
                TextColumn::make('updated_at'),
                TextColumn::make('roles.name')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'success',
                        'pelatih' => 'info',
                        'pembina' => 'info',
                        'siswa' => 'primary',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state))),
                TextColumn::make('user.detail.created_at')
                    ->label('Kelengkapan Biodata')
                    ->badge()
                    ->state(function (User $record) {
                        // Check if the detail relationship exists and is not null
                        if ($record->detail && $record->detail->created_at) {
                            return 'Sudah lengkap'; // return 'lengkap' if created_at exists
                        }
                        return 'Belum lengkap'; // return 'belum lengkap' if created_at is null or detail is missing
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Sudah lengkap' => 'success',
                        'Belum lengkap' => 'danger',
                        default => 'secondary',
                    })
                
            ])
            ->filters([
                // ...
            ])
            ->actions([
                ActionGroup::make([
                Action::make('detail')
                    ->label('Detail')
                    ->color('success')
                    ->icon('heroicon-o-eye')
                    ->url(fn (User $record) => route('user.profile',['hash' => HashHelper::encrypt($record->id)])),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Delete User')
                    ->modalDescription('Are you sure you want to delete this user? This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, delete user')
                    ->modalCancelActionLabel('No, cancel')
                    ->action(function (User $record) {
                        try {
                            DB::beginTransaction();
                            
                            // Remove all roles first
                            $record->roles()->detach();
                            
                            // Then delete the user
                            $record->delete();
                            
                            DB::commit();

                            Notification::make()
                                ->title('Success')
                                ->body('User deleted successfully')
                                ->success()
                                ->send();

                        } catch (\Exception $e) {
                            DB::rollBack();
                            
                            Notification::make()
                                ->title('Error')
                                ->body('Failed to delete user: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Selected Users')
                        ->modalDescription('Are you sure you want to delete these users? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete users')
                        ->modalCancelActionLabel('No, cancel')
                        ->action(function (Collection $records) {
                            try {
                                DB::beginTransaction();
                                
                                foreach ($records as $record) {
                                    // Remove all roles first
                                    $record->roles()->detach();
                                    
                                    // Then delete the user
                                    $record->delete();
                                }
                                
                                DB::commit();

                                Notification::make()
                                    ->title('Success')
                                    ->body('Selected users deleted successfully')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                DB::rollBack();
                                
                                Notification::make()
                                    ->title('Error')
                                    ->body('Failed to delete users: ' . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
                ])
            ]);
    }
    
    public function render(): View
    {
        $rolePermissions = [
            'admin' => Role::findByName('admin')->permissions->pluck('name'),
            'pelatih' => Role::findByName('pelatih')->permissions->pluck('name'),
            'pembina' => Role::findByName('pembina')->permissions->pluck('name'),
            'siswa' => Role::findByName('siswa')->permissions->pluck('name'),
        ];

        return view('livewire.manageuser.managementuser', [
            'rolePermissions' => $rolePermissions
        ]);
    }
}
