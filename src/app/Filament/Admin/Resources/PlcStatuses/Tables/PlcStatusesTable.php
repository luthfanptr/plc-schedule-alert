<?php

namespace App\Filament\Admin\Resources\PlcStatuses\Tables;

use App\Models\PlcStatus;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
//use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\options;

class PlcStatusesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll(fn ($livewire) => $livewire->isSyncing ? '3s' : null)
            ->recordAction(null)
            ->recordUrl(null)
            // untuk filter view details
            ->defaultSort('id', 'asc')
            ->modifyQueryUsing(fn ($query) => $query
            ->select('plc_id', 'plant', 'line', 'line_name')
            ->selectRaw('MIN(id) as id, MAX(plc_date) as plc_date, MAX(created_at) as created_at, MAX(updated_at) as updated_at, 
                        MAX(spk_status) as spk_status, MAX(updated_by) as updated_by, MAX(spk_number) as spk_number, 
                        MAX(spk_start_date) as spk_start_date, MAX(spk_finish_date) as spk_finish_date')

            ->selectRaw("SUM(CASE WHEN status = 'WARNING' THEN 1 ELSE 0 END) as warning_count")
            ->selectRaw("SUM(CASE WHEN status = 'DANGER' THEN 1 ELSE 0 END) as danger_count")

            ->groupBy('plc_id', 'plant', 'line', 'line_name')
            )
            ->columns([
                TextColumn::make('plc_id')
                    ->label('PLC ID')
                    ->numeric()
                    ->sortable()
                    ->html()
                    ->state(function ($record): string {
                    $plcId = $record->plc_id;
                    $warning = $record->warning_count ?? 0;
                    $danger = $record->danger_count ?? 0;

                    // Tampung badge secara dinamis
                    $badgeList = '';

                    // 1. WARNING (JIKA ADA)
                    if ($warning > 0) {
                        $badgeList .= "<span class='inline-flex items-center justify-center min-w-[18px] h-3.5 px-1 text-[9px] font-black rounded bg-amber-500/10 text-amber-400 border border-amber-500/20' title='Warning'>{$warning}</span>";
                    }

                    // 2. DANGER (JIKA ADA)
                    if ($danger > 0) {
                        $badgeList .= "<span class='inline-flex items-center justify-center min-w-[18px] h-3.5 px-1 text-[9px] font-black rounded bg-rose-500/10 text-rose-400 border border-rose-500/20' title='Danger'>{$danger}</span>";
                    }

                    // Render susunan vertikal jika ada minimal satu badge yang aktif
                    if (!empty($badgeList)) {
                        return "
                            <div class='flex items-center gap-3'>
                                <span class='font-normal text-gray-900 dark:text-white text-base'>{$plcId}</span>
                                <div class='flex flex-col gap-0.5'>
                                    {$badgeList}
                                </div>
                            </div>
                        ";
                    }

                    // Jika data kosong semua (fail-safe), tampilkan PLC ID polosan
                    return "<span class='font-medium text-gray-900 dark:text-white'>{$plcId}</span>";
                }),

                TextColumn::make('plant')
                    ->searchable(),
                TextColumn::make('line')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('line_name')
                    ->label('Line Name')
                    ->searchable(),
                // TextColumn::make('component_name')
                //     ->label('Component Name')
                //     ->searchable(),
                // TextColumn::make('counter')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('limit')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('status')
                //     ->searchable(),
                TextColumn::make('plc_date')
                    ->label('PLC Date')
                    ->dateTime()
                    ->sortable(),
                TextInputColumn::make('spk_number')
                    ->label('SPK Number')
                    ->placeholder('-')
                    ->sortable(query: fn ($query, $direction) => $query->orderByRaw("MAX(spk_number) {$direction}"))
                    ->updateStateUsing(function ($record, $state) {
                        PlcStatus::where('plc_id', $record->plc_id)
                            ->update(['spk_number' => $state]);
                    }),
                SelectColumn::make('spk_status')
                    ->label('SPK Status')
                    ->options([
                        'progress' => 'Progress',
                        'done'        => 'Done',
                    ])
                    ->placeholder('-')
                    ->searchable()
                    // ->selectablePlaceholder(fn ($record) => $record?->spk_status === null)
                    // ->updateStateUsing(function ($record, $state) {
                    //     // mass update (query builder), model hook booted() ga akan terpicu otomatis
                    //     //update sekalian status spk, upd_by, upd_at utk smua komponen plc_id
                    //     PlcStatus::where('plc_id', $record->plc_id)
                    //     ->update([
                    //         'spk_status' => $state,
                    //         'updated_by' => Auth::id(),
                    //         'updated_at' => now(),
                    //     ]);
                    // }),
                    ->rules(fn ($record) => $record?->spk_status !== null ? ['required'] : [])
    
                    ->updateStateUsing(function ($record, $state) {
                        // Jika spk_status di DB sudah ada isinya, tapi user pilih opsi NULL proses gagal
                        if ($record->spk_status !== null && empty($state)) {
                            return;
                        }

                        $currentSpkNumber = $record->spk_number ?? PlcStatus::where('plc_id', $record->plc_id)->value('spk_number');
                        
                        // validasi kalau mau mulai progress tapi spk no belum diketik
                        if ($state === 'progress' && empty($currentSpkNumber)) {
                            Notification::make()
                                ->title('Gagal Memulai Progress')
                                ->body('SPK Number harus diisi terlebih dahulu pada kolom yang tersedia!')
                                ->danger()
                                ->send();

                            return;
                        }

                        // array yang pasti akan diupdate
                        $updateData = [
                            'spk_status' => $state,
                            'updated_by' => Auth::id(),
                            'updated_at' => now(),
                        ];

                        // logic pencatatan tanggal
                        if($state === 'progress') {
                            // catat waktu mulai (kalo sblmnya kosong, biar ga ketimpa kalo 2x klik)
                            $updateData['spk_start_date'] = $record->spk_start_date ?? now();
                        } elseif ($state === 'done') {
                            // catat waktu selesai ketika done
                            $updateData['spk_finish_date'] = now();
                        }

                        // tembak mass update (query builder) utk semua komponen plc_id tsb
                        PlcStatus::where('plc_id', $record->plc_id)->update($updateData);
                    }),
                TextColumn::make('users.name') // updated_by mapping username akun
                    ->label('Updated By')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('spk_start_date')
                    ->label('SPK Start Date')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('spk_finish_date')
                    ->label('SPK Finish Date')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                ->url(null)
                ->modal()
                ->modalHeading('Log Details')
                ->modalWidth('5xl'),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
