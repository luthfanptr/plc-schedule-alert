<?php

namespace App\Filament\Admin\Resources\PlcData\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
// use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlcDataTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll(fn ($livewire) => $livewire->isSyncing ? '3s' : null)
            ->recordAction(null)
            ->recordUrl(null)
            ->defaultSort('id', 'asc')
            // untuk filter view details
            ->modifyQueryUsing(fn ($query) => $query
                ->select('plc_id', 'plant', 'line', 'line_name')
                ->selectRaw('MIN(id) as id, MAX(plc_date) as plc_date, MAX(created_at) as created_at, MAX(updated_at) as updated_at')

                ->selectRaw("SUM(CASE WHEN status = 'STANDARD' THEN 1 ELSE 0 END) as standard_count")
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
                    $standard = $record->standard_count ?? 0;
                    $warning = $record->warning_count ?? 0;
                    $danger = $record->danger_count ?? 0;

                    // Tampung badge secara dinamis
                    $badgeList = '';

                    // 1. STANDARD (JIKA ADA)
                    if ($standard > 0) {
                        $badgeList .= "<span class='inline-flex items-center justify-center min-w-[18px] h-3.5 px-1 text-[9px] font-black rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' title='Standard'>{$standard}</span>";
                    }

                    // 2. WARNING (JIKA ADA)
                    if ($warning > 0) {
                        $badgeList .= "<span class='inline-flex items-center justify-center min-w-[18px] h-3.5 px-1 text-[9px] font-black rounded bg-amber-500/10 text-amber-400 border border-amber-500/20' title='Warning'>{$warning}</span>";
                    }

                    // 3. DANGER (JIKA ADA)
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
                //     ->badge()
                //     ->color(fn (string $state): string => match ($state){
                //         'STANDARD' => 'success',
                //         'WARNING' => 'warning',
                //         'DANGER' => 'danger',
                //         default => 'success', 
                //     })
                //     ->icon(fn (string $state): Heroicon => match ($state) {
                //         'STANDARD' => Heroicon::OutlinedShieldCheck,
                //         'WARNING' => Heroicon::OutlinedExclamationCircle,
                //         'DANGER' => Heroicon::OutlinedExclamationTriangle,
                //         default => Heroicon::OutlinedShieldCheck,
                //     })
                //     ->searchable(),
                TextColumn::make('plc_date')
                    ->label('PLC Date')
                    ->dateTime()
                    ->sortable(),
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
                //EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
