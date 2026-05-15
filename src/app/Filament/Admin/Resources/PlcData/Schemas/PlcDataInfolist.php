<?php

namespace App\Filament\Admin\Resources\PlcData\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PlcDataInfolist
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
                // TextEntry::make('component_name'),
                // TextEntry::make('counter')
                //     ->numeric(),
                // TextEntry::make('limit')
                //     ->numeric(),
                // TextEntry::make('status'),
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
}
