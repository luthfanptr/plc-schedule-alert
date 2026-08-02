<?php

namespace App\Filament\Admin\Resources\PersonalAccessTokens\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;

class PersonalAccessTokenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Token Name')
                    ->required()
                    ->maxLength(255),

                ToggleButtons::make('abilities')
                    ->label('Abilities')
                    ->options([
                    'read' => 'Read',
                    'update' => 'Update',
                    ])
                    ->multiple()
                    ->required()
                    ->grouped(true)
                    ->colors([
                        'read' => Color::Blue,
                        'update' => Color::Orange,
                    ])
                    ->icons([
                        'read' => 'heroicon-o-eye',
                        'update' => 'heroicon-o-pencil-square',
                    ])
                    ->columns(2)
                    ->inline(false)
                    ->columnSpanFull(), //
                    
                // Textarea::make('description')
                //     ->label('Description')
                //     ->nullable(),

                Toggle::make('has_expiry')
                ->label('Set Expiration Date')
                ->default(false)
                ->live(),

                DateTimePicker::make('expires_at')
                    ->label('Expires At')
                    ->minDate(now())
                    ->visible(fn ($get) => (bool) $get('has_expiry'))
                    ->required(fn ($get) => (bool) $get('has_expiry'))
                    ->columnSpanFull()
            ]);
    }
}
