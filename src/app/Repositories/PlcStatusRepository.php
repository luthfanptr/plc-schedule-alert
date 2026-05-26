<?php

declare(strict_types=1);

namespace App\Repositories;
use Illuminate\Support\Facades\DB;

class PlcStatusRepository 
{
    public function execPlcStatus()
    {
        return DB::statement('EXEC [dbo].[FILTER_PLC_STATUS]');
    }
}