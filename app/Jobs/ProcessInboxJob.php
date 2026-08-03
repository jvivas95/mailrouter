<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\{Email, AppConfig};
use App\Services\{MailReaderService, MailSenderService, RotationService};
use Illuminate\Support\Facades\Log;


class ProcessInboxJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3; // Number of attempts before failing the job
    public $timeout = 120; // Timeout in seconds for the job execution

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
    public function handle(
        MailReaderService $reader,
        MailSenderService $sender,
        RotationService $rotation
    ): void
    {
        // Get the application configuration
        $config = AppConfig::get();

        if (empty($config['email_address']) || empty($config['email_password'])) {
            return; // Exit if email configuration is not set
        }

        // Fetch unseen emails from the inbox
        $emails = $reader->fetchUnseen($config);

        foreach ($emails as $em){
            // Avoid duplicated mails
            if (Email::where('uid', $em['uid'])->exists()) {
                continue;
            }

            // Save the email to the database
            $email = Email::create([
                'uid' => $em['uid'],
                'sender' => $em['sender'],
                'subject' => $em['subject'],
                'body' => $em['body'],
                'attatchments_count' => $em['attachments_count'],
                'status' => 'pending',
            ]);

            // Get the next recipient in rotation
            $recipient = $rotation->getNextRecipient();
            if (!$recipient) {
                $email->update(['status' => 'no_recipient']);
                continue; // Exit if no recipient is available
            }

            try {
                $sender->forward($em['raw_message'], $recipient, $config);
                $email->update([
                    'forwarded_to' => "{$recipient->name} <{$recipient->email}>",
                    'forwarded_at' => now(),
                    'status' => 'forwarded',
                ]);
            } catch (\Exception $e) {
                $email->update(['status' => 'failed']);
                Log::error('[MailRouter] Error al reenviar: ' . $e->getMessage());
            }
        }
    }
}
