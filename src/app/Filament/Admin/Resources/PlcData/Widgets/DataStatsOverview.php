<?php

namespace App\Filament\Admin\Resources\PlcData\Widgets;

use App\Models\PlcData;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DataStatsOverview extends StatsOverviewWidget
{
    public ?Model $record = null;

    protected function getStats(): array
    {

        $databaseQuery = PlcData::query();

        // filter data masuk di filament panel berdasarkan assignment plant
        if (Filament::getCurrentPanel()->getId() === 'control'){
            /** @var App\Models\User $user */
            $user = Auth::user();

            if ($user && ! $user->hasRole('super_admin')){
                $assignedPlant = $user->plants->pluck('Name')->toArray();
                $databaseQuery->whereIn('plant', $assignedPlant);
            }
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
