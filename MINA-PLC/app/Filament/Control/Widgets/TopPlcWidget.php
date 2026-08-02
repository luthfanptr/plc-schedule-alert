<?php

namespace App\Filament\Control\Widgets;

use App\Models\PlcStatus;
use App\Traits\FilamentPlantScope;
use App\Traits\SupervisorView;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TopPlcWidget extends BaseWidget
{
    use SupervisorView;
    use FilamentPlantScope;

    protected static ?string $heading = 'Top PLC - Warning & Danger';

    protected static ?int $sort = 99;

    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): \Illuminate\Database\Eloquent\Builder {
                $query = PlcStatus::query()
                    ->selectRaw("
                        plc_id,
                        MAX(line)       AS line,
                        MAX(line_name)  AS line_name,
                        SUM(CASE WHEN status = 'WARNING' THEN 1 ELSE 0 END) AS warning_count,
                        SUM(CASE WHEN status = 'DANGER'  THEN 1 ELSE 0 END) AS danger_count,
                        COUNT(*)        AS total_count
                    ")
                    ->whereIn('status', ['WARNING', 'DANGER'])
                    ->groupBy('plc_id')
                    ->orderByRaw('COUNT(*) DESC')
                    ->limit(5);

                $this->plantScope($query);

                return $query;
            })
            ->columns([
                TextColumn::make('plc_id')
                    ->label('PLC ID'),
                TextColumn::make('line')
                    ->label('Line'),
                TextColumn::make('line_name')
                    ->label('Line Name'),
                TextColumn::make('warning_count')
                    ->label('Warning')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('danger_count')
                    ->label('Danger')
                    ->badge()
                    ->color('danger'),
                TextColumn::make('total_count')
                    ->label('Total')
                    ->badge()
                    ->color('gray'),
            ])
            ->defaultKeySort(false)
            ->paginated(false);
    }

    public function getTableRecordKey(Model | array $record): string
    {
        return (string) ($record instanceof Model ? $record->plc_id : $record['plc_id']);
    }
}
