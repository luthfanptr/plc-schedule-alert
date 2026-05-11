<?php

namespace App\Filament\Admin\Resources\SpkLogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SpkLogForm
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
                TextInput::make('updated_by')
                    ->numeric(),
                TextInput::make('user_update')
                    ->required(),
                TextInput::make('status')
                    ->required(),
                TextInput::make('spk_status')
                    ->required()
                    ->default('null'),
                DateTimePicker::make('done_at')
                    ->required(),
            ]);
    }
}
