<?php

namespace App\Filament\Admin\Resources\PlcStatuses\Widgets;

use App\Models\PlcStatus;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class StatusOverview extends StatsOverviewWidget
{
    public ?Model $record = null;

    protected function getStats(): array
    {
        $warningCount = PlcStatus::where('status', 'WARNING')->count();
        $dangerCount = PlcStatus::where('status', 'DANGER')->count();

        $spkProgress = PlcStatus::where('spk_status', 'progress')->count();
        $spkDone = PlcStatus::where('spk_status', 'done')->count();

        return [
            Stat::make('WARNING', $warningCount)
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->description('Component Needs Attention')
                ->color("warning")
                ->chart([1,1]),

            Stat::make('DANGER', $dangerCount)
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->description('Component Critical Issue')
                ->color('danger')
                ->chart([1,1]),

            Stat::make('On Progress', $spkProgress)
                ->descriptionIcon('heroicon-o-clock')
                ->description('SPK In Progress')
                ->color('info')
                ->chart([1,1]),

            Stat::make('Done', $spkDone)
                ->descriptionIcon('heroicon-o-document-check')
                ->description('SPK Completed')
                ->color('success')
                ->chart([1,1]),
        ];
    }
}
