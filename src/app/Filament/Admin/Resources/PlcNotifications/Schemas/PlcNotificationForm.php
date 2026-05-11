<?php

namespace App\Filament\Admin\Resources\PlcNotifications\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PlcNotificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('plc_id')
                    ->required()
                    ->numeric(),
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
                TextInput::make('spk_status')
                    ->required()
                    ->default('null'),
                TextInput::make('updated_by')
                    ->numeric(),
            ]);
    }
}
