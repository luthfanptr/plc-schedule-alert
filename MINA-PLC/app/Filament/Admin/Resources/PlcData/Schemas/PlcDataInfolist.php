<?php

namespace App\Filament\Admin\Resources\PlcData\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
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

                // 'components' merujuk ke nama fungsi relasi hasMany di Model PlcData
                RepeatableEntry::make('components')
                    ->label('Component Lists')
                    ->schema([
                        TextEntry::make('component_name')
                            ->label('Component Name'),
                        TextEntry::make('counter')
                            ->numeric(),
                        TextEntry::make('limit')
                            ->numeric(),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'STANDARD' => 'success',
                                'WARNING' => 'warning',
                                'DANGER' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn (string $state): string => match ($state) {
                                'STANDARD' => 'heroicon-m-check-circle',
                                'WARNING' => 'heroicon-m-exclamation-triangle',
                                'DANGER' => 'heroicon-m-x-circle',
                                default => 'heroicon-m-question-mark-circle',
                            }),
                    ])
                    ->columns(4) // Menyejajarkan 4 kolom informasi komponen agar menyerupai tabel
                    ->columnSpanFull(), // Memaksa bagian komponen memakan lebar penuh modal
            ]);
    }
}
