<?php

namespace App\Filament\Control\Widgets;

use App\Models\PlcStatus;
use App\Models\PlcStatusLog;
use App\Traits\FilamentPlantScope;
use App\Traits\SupervisorView;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class SpkChart extends BaseWidget
{
    use InteractsWithPageFilters, FilamentPlantScope, SupervisorView;

    protected ?string $heading = 'Spk Chart';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 2;

    protected static bool $isLazy = false;

    protected function getData(): array
    {
        // =========================================================
        // TODO: Uncomment block ini klo data udah live/realtime
        // =========================================================
        // $preset    = $this->filters['preset'] ?? '7';
        // $startDate = $preset === 'custom'
        //     ? Carbon::parse($this->filters['startDate'] ?? now()->subDays(7))
        //     : Carbon::now()->subDays((int) $preset)->startOfDay();
        // $endDate   = $preset === 'custom'
        //     ? Carbon::parse($this->filters['endDate'] ?? now())
        //     : Carbon::now()->endOfDay();
        //
        // $labels        = [];
        // $warningDanger = [];
        // $spkCreated    = [];
        // $spkDone       = [];
        //
        // $daysDiff = $startDate->diffInDays($endDate);
        //
        // if ($daysDiff <= 31) {
        //     $current = $startDate->copy();
        //     while ($current <= $endDate) {
        //         $labels[] = $current->translatedFormat('d M');
        //         $this->appendData($warningDanger, $spkCreated, $spkDone, $current->copy()->startOfDay(), $current->copy()->endOfDay());
        //         $current->addDay();
        //     }
        // } elseif ($daysDiff <= 180) {
        //     $current = $startDate->copy()->startOfWeek();
        //     while ($current <= $endDate) {
        //         $labels[] = $current->translatedFormat('d M');
        //         $this->appendData($warningDanger, $spkCreated, $spkDone, $current->copy()->startOfWeek(), $current->copy()->endOfWeek());
        //         $current->addWeek();
        //     }
        // } else {
        //     $current = $startDate->copy()->startOfMonth();
        //     while ($current <= $endDate) {
        //         $labels[] = $current->translatedFormat('M Y');
        //         $this->appendData($warningDanger, $spkCreated, $spkDone, $current->copy()->startOfMonth(), $current->copy()->endOfMonth());
        //         $current->addMonth();
        //     }
        // }
        // =========================================================

        // Current state — query langsung tanpa filter date
        $wdQuery = PlcStatus::query()->whereIn('status', ['WARNING', 'DANGER']);
        $this->plantScope($wdQuery);
        $warningDangerCount = $wdQuery->distinct('plc_id')->count('plc_id');

        $resolvedNumbers = PlcStatusLog::query()
            ->whereNotNull('spk_number')
            ->tap(fn ($q) => $this->plantScope($q))
            ->distinct()
            ->pluck('spk_number')
            ->toArray();

        $activeCount = PlcStatus::query()
            ->whereNotNull('spk_number')
            ->when(!empty($resolvedNumbers), fn ($q) => $q->whereNotIn('spk_number', $resolvedNumbers))
            ->tap(fn ($q) => $this->plantScope($q))
            ->distinct('spk_number')
            ->count('spk_number');

        $spkCreatedCount = $activeCount + count($resolvedNumbers);

        $spkDoneCount = PlcStatusLog::query()
            ->where('spk_status', 'done')
            ->tap(fn ($q) => $this->plantScope($q))
            ->distinct('spk_number')
            ->count('spk_number');

        return [
            'datasets' => [
                [
                    'label'           => 'PLC Warning/Danger',
                    'data'            => [$warningDangerCount],
                    'backgroundColor' => '#ef4444',
                ],
                [
                    'label'           => 'SPK Created',
                    'data'            => [$spkCreatedCount],
                    'backgroundColor' => '#f59e0b',
                ],
                [
                    'label'           => 'SPK Done',
                    'data'            => [$spkDoneCount],
                    'backgroundColor' => '#22c55e',
                ],
            ],
            'labels' => [now()->translatedFormat('d F Y')],
        ];
    }

    // =========================================================
    // TODO: Uncomment method ini klo data udh live/realtime
    // =========================================================
    // private function appendData(array &$warningDanger, array &$spkCreated, array &$spkDone, Carbon $start, Carbon $end): void
    // {
    //     $wdQuery = PlcStatus::query()
    //         ->whereIn('status', ['WARNING', 'DANGER'])
    //         ->whereBetween('plc_date', [$start, $end]);
    //     $this->plantScope($wdQuery);
    //     $warningDanger[] = $wdQuery->distinct('plc_id')->count('plc_id');
    //
    //     $spkActiveQuery = PlcStatus::query()
    //         ->whereNotNull('spk_number')
    //         ->whereBetween('spk_start_date', [$start, $end]);
    //     $this->plantScope($spkActiveQuery);
    //
    //     $spkLogQuery = PlcStatusLog::query()
    //         ->whereNotNull('spk_number')
    //         ->whereBetween('spk_start_date', [$start, $end]);
    //     $this->plantScope($spkLogQuery);
    //
    //     $spkCreated[] = $spkActiveQuery->distinct('spk_number')->count('spk_number')
    //         + $spkLogQuery->distinct('spk_number')->count('spk_number');
    //
    //     $spkDoneQuery = PlcStatusLog::query()
    //         ->where('spk_status', 'done')
    //         ->whereBetween('spk_finish_date', [$start, $end]);
    //     $this->plantScope($spkDoneQuery);
    //     $spkDone[] = $spkDoneQuery->distinct('spk_number')->count('spk_number');
    // }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['position' => 'top'],
            ],
            'scales' => [
                'x' => ['stacked' => false],
                'y' => ['stacked' => false, 'beginAtZero' => true],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}