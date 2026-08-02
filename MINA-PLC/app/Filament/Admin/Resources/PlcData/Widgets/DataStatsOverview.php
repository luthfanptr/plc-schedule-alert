<?php

namespace App\Filament\Admin\Resources\PlcData\Widgets;

use App\Models\PlcData;
use App\Traits\FilamentPlantScope;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class DataStatsOverview extends StatsOverviewWidget
{
    use FilamentPlantScope;

    public ?Model $record = null;

    protected function getStats(): array
    {

        $databaseQuery = PlcData::query();

        // filter data masuk di filament panel berdasarkan assignment plant
        if (Filament::getCurrentPanel()->getId() === 'control') {
            $this->plantScope($databaseQuery);
        }

        // $standardCount = PlcData::where('status', 'STANDARD')->count();
        // $warningCount = PlcData::where('status', 'WARNING')->count();
        // $dangerCount = PlcData::where('status', 'DANGER')->count();

        // clone $baseQuery untuk filter data based on plants 
        $standardCount = (clone $databaseQuery)->where('status', 'STANDARD')->count();
        $warningCount = (clone $databaseQuery)->where('status', 'WARNING')->count();
        $dangerCount = (clone $databaseQuery)->where('status', 'DANGER')->count();

        return [
            Stat::make('STANDARD', $standardCount)
                ->descriptionIcon('heroicon-o-shield-check')
                ->description('Components Healthy')
                ->color('success')
                ->chart([1, 1]),

            Stat::make('WARNING', $warningCount)
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->description('Components Need Attention')
                ->color('warning')
                ->chart([1, 1]),

            Stat::make('DANGER', $dangerCount)
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->description('Critical Components')
                ->color('danger')
                ->chart([1, 1]),
        ];
    }
}
