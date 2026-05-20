<?php

namespace App\Filament\Admin\Resources\PlcStatuses\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PlcStatusInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('plc_id')
                    ->label('PLC ID')
                    ->numeric(),
                TextEntry::make('plant'),
                TextEntry::make('line')
                    ->numeric(),
                TextEntry::make('line_name')
                    ->label('Line Name'),
                // TextEntry::make('component_name'),
                // TextEntry::make('counter')
                //     ->numeric(),
                // TextEntry::make('limit')
                //     ->numeric(),
                // TextEntry::make('status'),
                TextEntry::make('plc_date')
                    ->label('PLC Date')
                    ->dateTime(),
                TextEntry::make('spk_number')
                    ->label('SPK Number'),
                TextEntry::make('spk_status')
                    ->label('SPK Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'progress' => 'warning',
                        'done' => 'success',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'progress' => 'heroicon-o-clock',
                        'done' => 'heroicon-o-document-check'
                    })
                    ->placeholder('-'),
                TextEntry::make('users.name')
                    ->label('Updated By')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('spk_start_date')
                    ->label('SPK Start Date')
                    ->placeholder('-'),
                TextEntry::make('spk_finish_date')
                    ->label('SPK Finish Date')
                    ->placeholder('-'),
                // TextEntry::make('created_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('updated_at')
                //     ->dateTime()
                //     ->placeholder('-'),

                // 'components' merujuk ke nama fungsi relasi hasMany di Model PlcStatuses
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
