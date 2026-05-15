<?php

namespace App\Filament\Admin\Resources\PlcData\PlcDataResource\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ComponentsRelationManager extends RelationManager
{
    protected static string $relationship = 'components';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('plant')
                    ->required(),
                TextInput::make('line')
                    ->required()
                    ->numeric(),
                TextInput::make('line_name')
                    ->required(),
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
                DateTimePicker::make('plc_date')
                    ->required(),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('plant'),
                // TextEntry::make('line')
                //     ->numeric(),
                // TextEntry::make('line_name'),
                TextEntry::make('component_name'),
                TextEntry::make('counter')
                    ->numeric(),
                TextEntry::make('limit')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('plc_date')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
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
                // ViewAction::make(),
                // EditAction::make(),
                // DissociateAction::make(),
                // DeleteAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DissociateBulkAction::make(),
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
