<?php

declare(strict_types=1);

namespace App\Services;
use App\Repositories\PlcStatusRepository;

class PlcStatusService
{
    protected $plcStatusRepository;

    public function __construct(PlcStatusRepository $plcStatusRepository)
    {
        $this->plcStatusRepository = $plcStatusRepository;
    }

    public function filterPlcStatus()
    {
        return $this->plcStatusRepository->execPlcStatus();
    }
}