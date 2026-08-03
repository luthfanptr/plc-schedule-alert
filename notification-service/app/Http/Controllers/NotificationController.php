<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\GenericMail;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'to' => 'required|array',
            'cc' => 'required|array',
            'subject' => 'required|string',
            'body' => 'required|string',
            'attachment' => 'nullable|file',
        ]);

        try {
            $attachmentPath = null;
            $attachmentName = null;

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                // Simpan sementara di storage local
                $attachmentPath = $file->store('temp_attachments');
                $attachmentPath = storage_path('app/private/' . $attachmentPath);
                $attachmentName = $file->getClientOriginalName();
            }

            $mail = new GenericMail(
                $request->subject,
                $request->body,
                $attachmentPath,
                $attachmentName
            );

            $pendingMail = Mail::to($request->to);
            
            if ($request->filled('cc')) {
                $pendingMail->cc($request->cc);
            }

            // Pastikan email ini masuk ke antrean bernama 'emails' agar tidak bentrok dengan MINA-PLC
            $mail->onQueue('emails');
            $pendingMail->queue($mail);

            // Hapus file sementara ditangani oleh Queue Worker nanti, jangan dihapus sekarang

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Notification Service Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
}
