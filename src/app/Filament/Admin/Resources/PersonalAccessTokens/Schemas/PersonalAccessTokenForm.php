<?php

namespace App\Filament\Admin\Resources\PersonalAccessTokens\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

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

                CheckboxList::make('abilities')
                    ->label('Abilities')
                    ->options([
                    'read'       => 'Read PLC Statuses',
                    'update_spk' => 'Update SPK Number',
                    ])
                    ->required()
                    ->columns(2),
                    
                Textarea::make('description')
                    ->label('Description')
                    ->nullable(),

                Toggle::make('has_expiry')
                ->label('Set Expiration Date')
                ->default(false)
                ->live(),

                DateTimePicker::make('expires_at')
                    ->label('Expires At')
                    ->minDate(now())
                    ->nullable()
                    ->visible(fn($get) => $get('has_expiry')),
            ]);
    }
}
