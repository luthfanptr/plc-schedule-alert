<?php

namespace App\Filament\Admin\Resources\PlcData\PlcDataResource\Widgets;

use App\Filament\Admin\Resources\PlcData\Pages\ListPlcData;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use App\Models\PlcData;

class DataStatsOverview extends StatsOverviewWidget
{
    public ?Model $record = null;

    //use InteractsWithPageTable;

    // protected function getTablePage(): string
    // {
    //     return ListPlcData::class;
    // }

    protected function getStats(): array
    {
        $standardCount = PlcData::where('status', 'STANDARD')->count();
        $warningCount = PlcData::where('status', 'WARNING')->count();
        $dangerCount = PlcData::where('status', 'DANGER')->count();

        return [
            Stat::make('STANDARD', $standardCount)
                ->descriptionIcon('heroicon-o-shield-check')
                ->description('Components Healthy')
                ->color('success')
                ->chart([1,1]),

            Stat::make('WARNING', $warningCount)
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->description('Components Need Attention')
                ->color('warning')
                ->chart([1,1]),

            Stat::make('DANGER', $dangerCount)
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->description('Critical Components')
                ->color('danger')
                ->chart([1,1]),
        ];
    }
}
