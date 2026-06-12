<?php

namespace App\Filament\Control\Widgets;

use App\Models\PlcStatus;
use App\Traits\FilamentPlantScope;
use LaravelDaily\FilaWidgets\Widgets\CompletionRateWidget;

// class SpkCompleted extends CompletionRateWidget
// {
//     use FilamentPlantScope;

//     protected static ?int $sort = 4;

//     protected int | string | array $columnSpan = 2;

//     protected ?string $widgetLabel = 'Spk Completed';

//     protected static bool $isLazy = false;

//     protected bool $showRangeFilter = false;

//     /**
//      * @return array{completed: int, total: int}
//      */

//     // hitung jumlah spk selesai dari semua spk yang ada
//     protected function getCounts(): array
//     {
//         $query = PlcStatus::query()->whereNotNull('spk_number');
//         $this->plantScope($query);

//         $total     = (clone $query)->distinct('plc_id')->count('plc_id');
//         $completed = (clone $query)->where('spk_status', 'done')->distinct('plc_id')->count('plc_id');

//         return [
//             'completed' => $completed,
//             'total'     => $total,
//         ];
//     }
// }
