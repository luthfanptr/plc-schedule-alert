<?php

namespace App\Jobs;

use App\Services\PlcDataService;
use App\Services\PlcStatusService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StatusJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */

    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(PlcDataService $dataService, PlcStatusService $statusService )
    {
        // Panggil Service
        $dataService->syncPlcData();
        $statusService->filterPlcStatus();
    }
}
