<?php

namespace App\Filament\Control\Resources\PlcStatuses\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PlcStatusInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('plc_id')
                    ->numeric(),
                TextEntry::make('plant'),
                TextEntry::make('line')
                    ->numeric(),
                TextEntry::make('line_name'),
                TextEntry::make('component_name'),
                TextEntry::make('counter')
                    ->numeric(),
                TextEntry::make('limit')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('plc_date')
                    ->dateTime(),
                TextEntry::make('spk_status')
                    ->placeholder('-'),
                TextEntry::make('updated_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
