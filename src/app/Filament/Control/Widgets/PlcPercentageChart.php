<?php

namespace App\Filament\Control\Widgets;

use App\Models\PlcData;
use App\Traits\FilamentPlantScope;
use App\Traits\SupervisorView;
use Filament\Widgets\ChartWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;

class PlcPercentageChart extends BaseWidget
{
    use FilamentPlantScope, SupervisorView;

    protected ?string $heading = 'Component Status Overview';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 2;

    protected static bool $isLazy = false;

    protected int $totalPlc = 0;

    public function getDescription(): string|Htmlable|null
    {
        $query = PlcData::query();
        $this->plantScope($query);
        $total = (clone $query)->distinct('plc_id')->count('plc_id');

        $statusQuery = \App\Models\PlcStatus::query();
        $this->plantScope($statusQuery);
        $affected = (clone $statusQuery)
            ->whereIn('status', ['WARNING', 'DANGER'])
            ->where(fn ($q) => $q->where('spk_status', '!=', 'done')->orWhereNull('spk_status'))
            ->distinct('plc_id')
            ->count('plc_id');

        return "{$affected} of {$total} PLC affected";
    }

    protected function getData(): array
    {
        $query = PlcData::query();
        $this->plantScope($query);

        $totalStandard = (clone $query)->where('status', 'STANDARD')->count();

        // Warning/Danger exclude yang sudah done
        $statusQuery = \App\Models\PlcStatus::query();
        $this->plantScope($statusQuery);

        $totalWarning = (clone $statusQuery)->where('status', 'WARNING')
            ->where(fn ($q) => $q->where('spk_status', '!=', 'done')->orWhereNull('spk_status'))
            ->count();

        $totalDanger = (clone $statusQuery)->where('status', 'DANGER')
            ->where(fn ($q) => $q->where('spk_status', '!=', 'done')->orWhereNull('spk_status'))
            ->count();

        $this->totalPlc = (clone $query)->distinct('plc_id')->count('plc_id');

        return [
            'datasets' => [[
                'data' => [
                    $totalStandard,
                    $totalWarning,
                    $totalDanger,
                ],
                'backgroundColor'     => ['#22c55e', '#f59e0b', '#ef4444'],
                'hoverBackgroundColor' => ['#16a34a', '#d97706', '#dc2626'],
            ]],
            'labels' => ['Standard', 'Warning', 'Danger'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['position' => 'bottom'],
                'doughnutLabel' => [
                    'labels' => [
                        ['text' => (string) $this->totalPlc, 'font' => ['size' => 24, 'weight' => 'bold']],
                        ['text' => 'Total PLC', 'font' => ['size' => 13]],
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}