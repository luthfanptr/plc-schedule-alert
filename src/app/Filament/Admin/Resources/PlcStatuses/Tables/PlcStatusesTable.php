<?php

namespace App\Filament\Admin\Resources\PlcStatuses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use function Pest\Laravel\options;

class PlcStatusesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('1s')
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
                    ->label('Plant Name')
                    ->searchable(),
                TextColumn::make('component_name')
                    ->label('Component Name')
                    ->searchable(),
                TextColumn::make('counter')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('limit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('plc_date')
                    ->label('PLC Date')
                    ->dateTime()
                    ->sortable(),
                    SelectColumn::make('spk_status')
                    ->label('SPK Status')
                    ->options([
                        'progress' => 'Progress',
                        'done'        => 'Done',
                    ])
                    ->placeholder('NULL')
                    ->searchable()
                    ->selectablePlaceholder(fn ($record) => $record?->spk_status === null),
                TextColumn::make('users.name') // updated_by mapping username akun
                    ->label('Updated By')
                    ->searchable()
                    ->sortable()
                    ->placeholder('NULL'),
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
