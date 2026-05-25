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
                    ->view('components.plc-badge', function ($record) {
                        return [
                            'plcId'   => $record->plc_id,
                            'standard' => $record->standard_count ?? 0,
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
