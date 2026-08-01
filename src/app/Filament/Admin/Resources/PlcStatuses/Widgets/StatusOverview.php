<?php

namespace App\Filament\Admin\Resources\PlcStatuses\Widgets;

use App\Models\PlcStatus;
use App\Traits\FilamentPlantScope;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class StatusOverview extends StatsOverviewWidget
{
    use FilamentPlantScope;

    public ?Model $record = null;

    protected int | string | array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 5;
    }

    protected function getStats(): array
    {

        $statusBaseQuery = PlcStatus::query();

        // filter data masuk di filament panel berdasarkan assignment plant
        if (Filament::getCurrentPanel()->getId() === 'control') {
            $this->plantScope($statusBaseQuery);
        }

        // Hitung jumlah komponen warning
        $warningCount = (clone $statusBaseQuery)->where('status', 'WARNING')
            ->where(fn($query) => $query->where('spk_status', '!=', 'done')->orWhereNull('spk_status'))
            ->count();

        // Hitung jumlah komponen danger
        $dangerCount = (clone $statusBaseQuery)->where('status', 'DANGER')
            ->where(fn ($query) => $query->where('spk_status', '!=', 'done')->orWhereNull('spk_status'))
            ->count();

        // $spkProgress = (clone $statusBaseQuery)->where('spk_status', 'progress')
        //     ->distinct()
        //     ->count('plc_id');

        $spkIssued = (clone $statusBaseQuery)
            ->whereNotNull('spk_number')
            ->where(fn ($query) => $query->whereNull('spk_status'))
            ->distinct()
            ->count('plc_id');

        $spkProgress = (clone $statusBaseQuery)
            ->whereNotNull('spk_number')
            ->where(fn ($query) => $query->where('spk_status', 'progress'))
            ->distinct()
            ->count('plc_id');

        $spkDone = (clone $statusBaseQuery)->where('spk_status', 'done')
            ->distinct()
            ->count('plc_id');

        // Hitung jumlah plc warning
        $plcWarning = (clone $statusBaseQuery)->where('status', 'WARNING')
            ->where(fn ($query) => $query->where('spk_status', '!=', 'done')->orWhereNull('spk_status'))
            ->distinct()
            ->count('plc_id');

        // Hitung jumlah plc danger
        $plcDanger = (clone $statusBaseQuery)->where('status', 'DANGER')
            ->where(fn ($query) => $query->where('spk_status', '!=', 'done')->orWhereNull('spk_status'))
            ->distinct()
            ->count('plc_id');

        return [
            Stat::make('Warning Components', $warningCount)
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->description("From {$plcWarning} PLC • Require Inspection")
                ->color(Color::Amber)
                ->chart([1, 1]),

            Stat::make('Danger Components', $dangerCount)
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->description("From {$plcDanger} PLC • Immediate Action Needed")
                ->color(Color::Red)
                ->chart([1, 1]),

            Stat::make('Unassigned', $spkIssued)
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->description('Awaiting Action')
                ->color(Color::Purple)
                ->chart([1, 1]),

            Stat::make('Progress', $spkProgress)
                ->descriptionIcon('heroicon-o-clock')
                ->description('SPK in Progress')
                ->color(Color::Blue)
                ->chart([1, 1]),

            Stat::make('Done', $spkDone)
                ->descriptionIcon('heroicon-o-document-check')
                ->description('Completed SPK Tasks')
                ->color(Color::Emerald)
                ->chart([1, 1]),
        ];
    }
}
