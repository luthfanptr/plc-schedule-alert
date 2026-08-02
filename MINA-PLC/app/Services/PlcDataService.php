<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PlcDataRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class PlcDataService 
{
    protected $plcDataRepository;

    public function __construct(PlcDataRepository $plcDataRepository)
    {
        $this->plcDataRepository = $plcDataRepository;
    }

    public function syncPlcData()
    {
        try {
            return $this->plcDataRepository->execPlcData();
        } catch (Exception $e) {
            Log::error('Error Sync PLC Data: ' . $e->getMessage());
            throw new Exception('Failed to sync');
        }
    }
}