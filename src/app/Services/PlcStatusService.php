<?php

namespace App\Services;
use App\Repositories\PlcStatusRepository;

class PlcStatusService
{
    protected $repo;

    public function __construct(PlcStatusRepository $repo)
    {
        $this->repo = $repo;
    }

    public function filterPlcStatus()
    {
        return $this->repo->execPlcStatus();
    }
}