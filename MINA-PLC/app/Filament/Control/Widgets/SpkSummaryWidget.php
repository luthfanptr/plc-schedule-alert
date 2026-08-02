<?php

namespace App\Filament\Control\Widgets;

use App\Models\PlcStatus;
use App\Models\PlcStatusLog;
use App\Traits\FilamentPlantScope;
use App\Traits\SupervisorView;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class SpkSummaryWidget extends Widget
{
    use FilamentPlantScope;
    use InteractsWithPageFilters;
    use SupervisorView;

    protected string $view = 'filament.control.widgets.spk-summary-widget';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected function getDateRange(): array
    {
        $preset    = $this->filters['preset'] ?? '30';
        $startDate = $preset === 'custom'
            ? Carbon::parse($this->filters['startDate'] ?? now()->subDays(30))
            : Carbon::now()->subDays((int) $preset)->startOfDay();
        $endDate   = $preset === 'custom'
            ? Carbon::parse($this->filters['endDate'] ?? now())
            : Carbon::now()->endOfDay();

        return [$startDate, $endDate];
    }

    protected function getSpkData(): Collection
    {
        [$start, $end] = $this->getDateRange();

        $logBaseQuery = PlcStatusLog::query()
            ->whereNotNull('spk_number')
            ->whereBetween('spk_start_date', [$start, $end]);
        $this->plantScope($logBaseQuery);

        $resolvedSpkNumbers = (clone $logBaseQuery)->distinct()->pluck('spk_number')->toArray();

        $activeQuery = PlcStatus::query()
            ->whereNotNull('spk_number')
            ->whereBetween('spk_start_date', [$start, $end])
            ->when(!empty($resolvedSpkNumbers), fn ($q) => $q->whereNotIn('spk_number', $resolvedSpkNumbers))
            ->with('users');
        $this->plantScope($activeQuery);

        $active = $activeQuery->get()->toBase()->map(fn ($r) => (object) [
            'spk_number'      => $r->spk_number,
            'plc_id'          => $r->plc_id,
            'line'            => $r->line,
            'line_name'       => $r->line_name,
            'component_name'  => $r->component_name,
            'status'          => $r->status,
            'spk_status'      => match($r->spk_status) {
                'progress' => 'On Progress',
                'done'     => 'Done',
                default    => 'Pending',
            },
            'teknisi'         => $r->users?->name ?? '-',
            'spk_start_date'  => $r->spk_start_date,
            'spk_finish_date' => $r->spk_finish_date,
        ]);

        $teknisiMap = PlcStatus::query()
            ->whereNotNull('spk_number')
            ->whereIn('spk_number', $resolvedSpkNumbers)
            ->with('users')
            ->get()
            ->groupBy('spk_number')
            ->map(fn ($rows) => $rows->first()->users?->name ?? '-');

        $resolved = (clone $logBaseQuery)->get()->toBase()->map(fn ($r) => (object) [
            'spk_number'      => $r->spk_number,
            'plc_id'          => $r->plc_id,
            'line'            => $r->line,
            'line_name'       => $r->line_name,
            'component_name'  => $r->component_name,
            'status'          => $r->status,
            'spk_status'      => 'Done',
            'teknisi'         => $teknisiMap[$r->spk_number] ?? '-',
            'spk_start_date'  => $r->spk_start_date,
            'spk_finish_date' => $r->spk_finish_date,
        ]);

        return $active->merge($resolved)->sortBy('spk_number')->values();
    }

    public function exportExcel()
    {
        $data     = $this->getSpkData();
        $filename = 'SPK_Report_' . now()->format('Y_m_d') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\SpkReport($data),
            $filename
        );
    }

    protected function getViewData(): array
    {
        [$start, $end] = $this->getDateRange();

        $doneQuery = PlcStatusLog::query()
            ->where('spk_status', 'done')
            ->whereBetween('spk_finish_date', [$start, $end]);
        $this->plantScope($doneQuery);
        $doneCount = $doneQuery->distinct('spk_number')->count('spk_number');

        $delayQuery = PlcStatus::query()
            ->whereNotNull('spk_number')
            ->where(fn ($q) => $q->whereNull('spk_status')->orWhere('spk_status', 'progress'));
        $this->plantScope($delayQuery);
        $delayCount = $delayQuery->distinct('spk_number')->count('spk_number');

        $totalSpk = $doneCount + $delayCount;

        $avgResolutionQuery = PlcStatusLog::query()
            ->where('spk_status', 'done')
            ->whereBetween('spk_finish_date', [$start, $end])
            ->whereNotNull('spk_start_date')
            ->whereNotNull('spk_finish_date')
            ->selectRaw('AVG(DATEDIFF(minute, spk_start_date, spk_finish_date)) as avg_minutes');
        $this->plantScope($avgResolutionQuery);
        $avgResolutionMinutes = $avgResolutionQuery->value('avg_minutes');

        $avgResolution = '-';
        if ($avgResolutionMinutes) {
            $days          = floor($avgResolutionMinutes / 1440);
            $hours         = floor(($avgResolutionMinutes % 1440) / 60);
            $minutes       = $avgResolutionMinutes % 60;
            $avgResolution = "{$days}d {$hours}h {$minutes}m";
        }

        $longestDelayQuery = PlcStatus::query()
            ->whereNotNull('spk_number')
            ->whereNotNull('spk_start_date')
            ->where(fn ($q) => $q->whereNull('spk_status')->orWhere('spk_status', 'progress'))
            ->selectRaw('plc_id, spk_number, DATEDIFF(minute, spk_start_date, GETDATE()) as delay_minutes');
        $this->plantScope($longestDelayQuery);
        $longestDelay = $longestDelayQuery->orderByDesc('delay_minutes')->first();

        $longestDelayFormatted = null;
        if ($longestDelay) {
            $d                     = floor($longestDelay->delay_minutes / 1440);
            $h                     = floor(($longestDelay->delay_minutes % 1440) / 60);
            $m                     = $longestDelay->delay_minutes % 60;
            $longestDelayFormatted = "PLC {$longestDelay->plc_id} ({$d}d {$h}h {$m}m)";
        }

        $preset = $this->filters['preset'] ?? '30';
        $rangeLabel = match($preset) {
            '7'      => 'Last 7 Days',
            '30'     => 'Last 30 Days',
            '90'     => 'Last 3 Months',
            '180'    => 'Last 6 Months',
            '365'    => 'Last 1 Year',
            'custom' => $start->format('d M Y') . ' — ' . $end->format('d M Y'),
            default  => 'Last 30 Days',
        };

        return [
            'totalSpk'              => $totalSpk,
            'doneCount'             => $doneCount,
            'delayCount'            => $delayCount,
            'donePercent'           => $totalSpk > 0 ? round(($doneCount / $totalSpk) * 100) : 0,
            'delayPercent'          => $totalSpk > 0 ? round(($delayCount / $totalSpk) * 100) : 0,
            'avgResolution'         => $avgResolution,
            'longestDelay'          => $longestDelay,
            'longestDelayFormatted' => $longestDelayFormatted,
            'printData'             => $this->getSpkData(),
            'rangeLabel'            => $rangeLabel,
        ];
    }
}