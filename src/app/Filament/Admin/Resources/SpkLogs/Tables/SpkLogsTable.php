<?php

namespace App\Filament\Admin\Resources\SpkLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SpkLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plc_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('plant')
                    ->searchable(),
                TextColumn::make('line')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('line_name')
                    ->searchable(),
                TextColumn::make('component_name')
                    ->searchable(),
                TextColumn::make('counter')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('limit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user_update')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('spk_status')
                    ->searchable(),
                TextColumn::make('done_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
