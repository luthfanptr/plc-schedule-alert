<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PlcNotification;
use Illuminate\Support\Facades\DB;

class PlcDataRepository
{
    public function execPlcData()
    {
        return DB::statement('EXEC [dbo].[SELECT_PLC_FINISH]');
    }
}
