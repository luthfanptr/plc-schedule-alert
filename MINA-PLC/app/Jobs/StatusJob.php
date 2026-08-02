<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\MailService;
use App\Services\PlcDataService;
use App\Services\PlcStatusService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class StatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public function handle(PlcDataService $dataService, PlcStatusService $statusService, MailService $mailService)
    {
        // Panggil Service
        $dataService->syncPlcData();

        $statusService->filterPlcStatus();

        $mailService->sendDangerAlert();
    }
}
