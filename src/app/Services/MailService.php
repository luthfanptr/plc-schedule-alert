<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\DangerMail;
use App\Models\EmailLog;
use App\Models\PlcStatus;
use App\Repositories\PlcStatusRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class MailService
{
    public function __construct(
        protected PlcStatusRepository $plcStatusRepo, 
        protected UserRepository $userRepo
        ) {}


    public function sendDangerAlert(): void
    {
        try {
            $plants = $this->plcStatusRepo->getDanger();
            $loopIndex = 0;

            foreach ($plants as $plant) {
                $teknisiEmails = $this->userRepo->getTeknisiPlant($plant)->pluck('email')->toArray();

                $spvEmails = $this->userRepo->getSupervisor($plant)->pluck('email')->toArray();

                // Jika tidak ada teknisi di plant ini, lewati agar tidak error saat kirim email
                if (empty($teknisiEmails)) {
                    continue;
                }

                // 1 email per plant
                Mail::to($teknisiEmails)
                    ->cc($spvEmails)
                    ->later(now()->addSeconds(5 * $loopIndex), new DangerMail($plant));
                    // ->queue(new DangerMail($plant));// send/queue

                // Catat log | audit trail log
                EmailLog::create([
                    'plant'     => $plant,
                    'date_sent' => now(),
                ]);

                PlcStatus::where('plant', $plant)
                    ->where('status', 'DANGER')
                    ->where('escalated', 1)
                    ->update(['escalated'=> 0]);

                $loopIndex++;
            }
        } catch (Exception $e) {
            Log::error('Error Send Danger Alert: '.$e->getMessage());
            throw $e;
        }
    }
}
