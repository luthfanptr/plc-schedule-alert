<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PlcStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PlcStatusRepository
{
    public function execPlcStatus()
    {
        return DB::statement('EXEC [dbo].[FILTER_PLC_STATUS]');
    }

    public function getDanger(): Collection
    {
        // return PlcStatus::where('status', 'DANGER')
        //     ->orderBy('plant')
        //     ->orderBy('plc_id')
        //     ->get()
        //     ->groupBy('plant');
        return PlcStatus::where('status', 'DANGER')
        ->where('escalated', 1) // kolom eskalasi
        ->where(function ($query) {
            $query->where('spk_status', '!=', 'done')
              ->orWhereNull('spk_status');
        })
        ->distinct()
        ->selectRaw('TRIM(plant) as plant')
        ->pluck('plant');
    }
}
