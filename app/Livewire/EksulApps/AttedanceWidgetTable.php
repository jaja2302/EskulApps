<?php

namespace App\Livewire\EksulApps;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Attendance;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;

class AttedanceWidgetTable extends BaseWidget
{
    public $eskul;
    public function mount($eskul)
    {
        // dd($eskul);
        $this->eskul = $eskul;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(Attendance::query()->where('eskul_id', $this->eskul->id))
            ->columns([
                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                    TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('check_in_time')
                    ->label('Check In')
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->sortable(),
                TextColumn::make('is_verified')
                    ->label('Diverifikasi Pelatih')
                    ->formatStateUsing(fn ($state) => $state == 1 ? 'Ya' : 'Tidak')
                    ->color(fn ($state) => $state == 1 ? 'success' : 'danger')
                    ->sortable(),
                TextColumn::make('verified_at')
                    ->label('Diverifikasi Pada')
                    ->sortable(),
            ])
            ->filters([
               Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Tanggal Check In dari'),
                        DatePicker::make('created_until')
                            ->label('Tanggal Check In sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        // dd($data);
                        return $query
                            ->when(
                                $data['created_from'],
                                function (Builder $query, $date) {
                                    // dd($query->whereDate('tanggal_terima', '>=', $date));

                                    return $query->whereDate('created_at', '>=', $date);
                                }
                                // fn (Builder $query, $date): Builder => $query->whereDate('tanggal_terima', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                function (Builder $query, $date) {
                                    return $query->whereDate('created_at', '<=', $date);
                                }
                            );
                    }),
                SelectFilter::make('is_verified')
                    ->label('Terverifikasi')
                    ->options([
                        '1' => 'Ya',
                        '0' => 'Tidak',
                    ])
                    ->preload(),
            ])
            ->bulkActions([
                // In your bulk action for KUPA PDF export
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->label('Hapus Attendance')
                    ->icon('heroicon-m-trash')
                    ->visible(auth()->user()->can('edit attendance'))
                    ->color('danger')
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $records) {
                        $records->each(function (Attendance $record) {
                            $record->delete();
                            Notification::make()
                                ->title("Berhasil di Hapus")
                                ->body("Attendance berhasil dihapus")
                                ->success()
                                ->send();
                        });
                    }),
                ])
            ->actions([
                Action::make('verifikasi')
                    ->label(fn ($record) => $record->is_verified == 1 ? 'Sudah Diverifikasi' : 'Verifikasi')
                    ->color(fn ($record) => $record->is_verified == 1 ? 'success' : 'warning')
                    ->visible(auth()->user()->can('edit attendance'))
                    ->disabled(fn ($record) => $record->is_verified == 1)
                    ->action(function ($record) {
                        $record->update([
                            'is_verified' => 1,
                            'verified_by' => auth()->id(),
                            'verified_at' => now()
                        ]);
                    })
            ]);
    }
}

