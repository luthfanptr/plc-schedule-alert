<?php

namespace App\Services;

use App\Repositories\PlcDataRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class PlcDataService 
{
    protected $repo;

    public function __construct(PlcDataRepository $repo)
    {
        $this->repo = $repo;
    }

    public function syncPlcData()
    {
        try {
            return $this->repo->execPlcData();
        } catch (Exception $e) {
            Log::error('Error Sync PLC Data: ' . $e->getMessage());
            throw new Exception('Failed to sync');
        }
    }
}