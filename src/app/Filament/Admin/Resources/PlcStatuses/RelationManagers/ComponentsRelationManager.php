<?php

namespace App\Filament\Admin\Resources\PlcStatuses\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ComponentsRelationManager extends RelationManager
{
    protected static string $relationship = 'components';

    protected static ?string $title = 'Component Details'; //? Perlu ga?

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('plant')
                //     ->required(),
                // TextInput::make('line')
                //     ->required()
                //     ->numeric(),
                // TextInput::make('line_name')
                //     ->required(),
                TextInput::make('component_name')
                    ->required(),
                TextInput::make('counter')
                    ->required()
                    ->numeric(),
                TextInput::make('limit')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required(),
                // DateTimePicker::make('plc_date')
                //     ->required(),
                // TextInput::make('spk_status'),
                // TextInput::make('updated_by')
                //     ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('component_name')
            ->columns([
                // TextColumn::make('plant')
                //     ->searchable(),
                // TextColumn::make('line')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('line_name')
                //     ->searchable(),
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
                    ->badge()
                    ->color(fn (string $state): string => match ($state){
                        'STANDARD' => 'success',
                        'WARNING' => 'warning',
                        'DANGER' => 'danger',
                        default => 'success', 
                    })
                    ->icon(fn (string $state): Heroicon => match ($state) {
                        'STANDARD' => Heroicon::OutlinedShieldCheck,
                        'WARNING' => Heroicon::OutlinedExclamationCircle,
                        'DANGER' => Heroicon::OutlinedExclamationTriangle,
                        default => Heroicon::OutlinedShieldCheck,
                    })    
                    ->searchable(),
                // TextColumn::make('plc_date')
                //     ->dateTime()
                //     ->sortable(),
                // TextColumn::make('spk_status')
                //     ->searchable(),
                // TextColumn::make('updated_by')
                //     ->numeric()
                //     ->sortable(),
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
            ->headerActions([
                // CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                // EditAction::make(),
                // DissociateAction::make(),
                // DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
