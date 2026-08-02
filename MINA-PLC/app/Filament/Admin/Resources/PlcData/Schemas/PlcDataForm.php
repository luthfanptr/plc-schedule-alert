<?php

namespace App\Filament\Admin\Resources\PlcData\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PlcDataForm
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
                DateTimePicker::make('plc_date')
                    ->required(),
            ]);
    }
}
