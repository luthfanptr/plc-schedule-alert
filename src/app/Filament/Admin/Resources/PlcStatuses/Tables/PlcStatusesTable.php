<?php

namespace App\Filament\Admin\Resources\PlcStatuses\Tables;

use App\Models\PlcStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

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
                    ->view('components.plc-badge', function ($record) {
                        return [
                            'plcId'   => $record->plc_id,
                            'warning' => $record->warning_count ?? 0,
                            'danger'  => $record->danger_count ?? 0,
                        ];
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
                    ->searchable()
                    ->sortable(query: fn ($query, $direction) => $query->orderByRaw("MAX(spk_number) {$direction}"))
                    ->disabled(function () {
                        /** @var \App\Models\User $user */
                        $user = Auth::user();
                        
                        return ! $user || ! $user->hasRole(['super_admin', 'teknisi']);
                    })
                    ->updateStateUsing(function ($record, $state) {

                        // Abaikan jika input kosong
                        if (empty(trim($state ?? ''))) {
                            PlcStatus::where('plc_id', $record->plc_id)
                                ->update(['spk_number' => null]);
                            return;
                        }

                        // cek nomor spk supaya identik
                        $existing = PlcStatus::where('spk_number', $state)
                            ->where('plc_id', '!=', $record->plc_id)
                            ->select('plc_id')
                            ->first();

                        if ($existing) {
                            Notification::make()
                                ->title('Nomor SPK Sudah Digunakan')
                                ->body("Nomor SPK \"{$state}\" sudah dipakai oleh PLC {$existing->plc_id}.")
                                ->danger()
                                ->persistent() // notif harus tutup manual
                                ->send();

                            // Reset input ke nilai sebelumnya agar tidak tersimpan
                            $record->spk_number = $record->getOriginal('spk_number');
                            return;
                        }

                        // Aman, lanjut save
                        PlcStatus::where('plc_id', $record->plc_id)
                            ->update(['spk_number' => $state]);
                    }),
                SelectColumn::make('spk_status')
                    ->label('SPK Status')
                    ->options([
                        'progress' => 'Progress',
                        'done'     => 'Done',
                    ])
                    ->disabled(function () {
                        /** @var \App\Models\User $user */
                        $user = Auth::user();

                        // Jika user tidak login, atau user BUKAN super_admin/teknisi, maka disable kolomnya
                        return ! $user || ! $user->hasRole(['super_admin', 'teknisi']);
                    })
                    ->placeholder('-')
                    ->selectablePlaceholder(true)
                    ->searchable()

                    // blokir opsi di level UI sebelum user bisa klik
                    ->disableOptionWhen(function (string $value, $record) {
                        $spkNumber = trim($record->spk_number ?? '');

                        // Jika SPK Number kosong, blokir SEMUA opsi (progress & done)
                        if (empty($spkNumber)) {
                            return true;
                        }

                        //Blokir 'done' jika belum pernah 'progress' dulu
                        if ($value === 'done' && $record->spk_status !== 'progress') {
                            return true;
                        }

                        // Jika sudah 'done', blokir opsi 'progress' (tidak boleh mundur)
                        if ($record->spk_status === 'done' && $value === 'progress') {
                            return true;
                        }

                        return false;
                    })

                    ->updateStateUsing(function ($record, $state) {
                        $realData = PlcStatus::where('plc_id', $record->plc_id)->first();
                        $statusAsli = $realData?->spk_status;

                        // cegah pengosongan status yang sudah ada
                        if ($statusAsli !== null && empty($state)) {
                            Notification::make()
                                ->title('Aksi Ditolak')
                                ->body('Status tidak dapat dikosongkan.')
                                ->danger()
                                ->send();
                            return;
                        }

                        // validasi SPK Number wajib ada untuk SEMUA state (progress & done)
                        $spkNumber = trim($record->spk_number ?? $realData?->spk_number ?? '');
                        if (!empty($state) && empty($spkNumber)) {
                            Notification::make()
                                ->title('Gagal')
                                ->body('Isi SPK Number terlebih dahulu sebelum mengubah status!')
                                ->danger()
                                ->send();

                            // reset memori record agar dropdown kembali ke nilai sebelumnya
                            $record->spk_status = $statusAsli;
                            return;
                        }

                        // cegah mundur dari 'done' ke 'progress'
                        if ($statusAsli === 'done' && $state === 'progress') {
                            Notification::make()
                                ->title('Aksi Ditolak')
                                ->body('Status tidak dapat dikembalikan ke Progress.')
                                ->danger()
                                ->send();

                            $record->spk_status = $statusAsli;
                            return;
                        }

                        // --- Proses update database ---
                        $updateData = [
                            'spk_status' => $state,
                            'updated_by' => Auth::id(),
                            'updated_at' => now(),
                        ];

                        if ($state === 'progress') {
                            $updateData['spk_start_date'] = $record->spk_start_date ?? now();
                        } elseif ($state === 'done') {
                            $updateData['spk_finish_date'] = now();
                        }

                        PlcStatus::where('plc_id', $record->plc_id)->update($updateData);

                        // Sinkronisasi memori
                        $record->spk_status = $state;

                        Notification::make()
                            ->title('Status Berhasil Diperbarui')
                            ->success()
                            ->send();
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
                ->label('Details')
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
