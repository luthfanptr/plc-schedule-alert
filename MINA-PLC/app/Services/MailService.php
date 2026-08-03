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
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DangerExport;

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

                // Generate Excel Raw Data
                $fileName = str_replace(' ', '_', trim($plant)) . "_dangerlist_" . now()->format('d-m-Y') . ".xlsx";
                $excelData = Excel::raw(new DangerExport($plant), \Maatwebsite\Excel\Excel::XLSX);

                // Kirim request ke Notification Service API
                Http::attach(
                    'attachment', $excelData, $fileName
                )->post('http://127.0.0.1:8001/api/send-email', [
                    'to' => $teknisiEmails,
                    'cc' => $spvEmails,
                    'subject' => "{$plant} Danger Component Maintenance Follow Up — " . now()->format('d/m/Y'),
                    'body' => "Berikut adalah daftar komponen dengan status DANGER pada area {$plant} yang membutuhkan perhatian segera.\n\nDetail lengkap terlampir pada file Excel.",
                ]);

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
