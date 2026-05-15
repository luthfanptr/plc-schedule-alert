<?php

namespace App\Filament\Admin\Resources\PlcData\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlcDataTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('1s')
            ->defaultSort('id', 'asc')
            ->modifyQueryUsing(fn ($query) => $query
                ->select('plc_id', 'plant', 'line', 'line_name')
                ->selectRaw('MIN(id) as id, MAX(plc_date) as plc_date, MAX(created_at) as created_at, MAX(updated_at) as updated_at')
                ->groupBy('plc_id', 'plant', 'line', 'line_name')
            )
            ->columns([
                TextColumn::make('plc_id')
                    ->label('PLC ID')   
                    ->numeric()
                    ->sortable(),
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
