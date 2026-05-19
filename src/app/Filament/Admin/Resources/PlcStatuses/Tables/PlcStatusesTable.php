<?php

namespace App\Filament\Admin\Resources\PlcStatuses\Tables;

use App\Models\PlcStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\options;

class PlcStatusesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('1s')
            // untuk filter view details
            ->defaultSort('id', 'asc')
            ->modifyQueryUsing(fn ($query) => $query
            ->select('plc_id', 'plant', 'line', 'line_name')
            ->selectRaw('MIN(id) as id, MAX(plc_date) as plc_date, MAX(created_at) as created_at, MAX(updated_at) as updated_at, MAX(spk_status) as spk_status, MAX(updated_by) as updated_by')
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
                    ->label('Plant Name')
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
                SelectColumn::make('spk_status')
                    ->label('SPK Status')
                    ->options([
                        'progress' => 'Progress',
                        'done'        => 'Done',
                    ])
                    ->placeholder('NULL')
                    ->searchable()
                    ->selectablePlaceholder(fn ($record) => $record?->spk_status === null)
                    ->updateStateUsing(function ($record, $state) {
                        // mass update (query builder), model hook booted() ga akan terpicu otomatis
                        //update sekalian status spk, upd_by, upd_at utk smua komponen plc_id
                        PlcStatus::where('plc_id', $record->plc_id)
                        ->update([
                            'spk_status' => $state,
                            'updated_by' => Auth::id(),
                            'updated_at' => now(),
                        ]);
                    }),
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
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
