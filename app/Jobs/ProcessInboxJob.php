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

    public $tries = 3;
    public $timeout = 120;

    public function __construct()
    {
        //
    }

    public function handle(
    MailReaderService $reader,
    MailSenderService $sender,
    RotationService $rotation
    ): void {
    Log::info('[MailRouter] ===== INICIO DEL JOB =====');

    $config = AppConfig::get();
    Log::info('[MailRouter] Config cargada: ' . json_encode([
        'email_address' => $config['email_address'] ?? 'VACÍO',
        'active'        => $config['active'] ?? 'VACÍO',
        'tiene_password'=> !empty($config['email_password']) ? 'SÍ' : 'NO',
    ]));

    if (empty($config['email_address']) || empty($config['email_password'])) {
        Log::warning('[MailRouter] Configuración incompleta. Saliendo.');
        return;
    }

    Log::info('[MailRouter] Llamando a fetchUnseen...');

    try {
        $emails = $reader->fetchUnseen($config);
        Log::info('[MailRouter] fetchUnseen completado. Emails: ' . count($emails));
    } catch (\Exception $e) {
        Log::error('[MailRouter] Error en fetchUnseen: ' . $e->getMessage());
        return;
    }

    if (count($emails) === 0) {
        Log::info('[MailRouter] No hay correos nuevos. Saliendo.');
        return;
    }

    foreach ($emails as $em) {
        Log::info("[MailRouter] Procesando UID: {$em['uid']} | Asunto: {$em['subject']}");

        if (Email::where('uid', $em['uid'])->exists()) {
            Log::info("[MailRouter] UID {$em['uid']} duplicado. Saltando.");
            continue;
        }

        Log::info("[MailRouter] Guardando en DB...");
        $email = Email::create([
            'uid'               => $em['uid'],
            'sender'            => $em['sender'] ?? '',
            'subject'           => $em['subject'] ?? 'Sin asunto',
            'body'              => $em['body'] ?? '',
            'attachments_count' => $em['attachments_count'] ?? 0,
            'status'            => 'pending',
        ]);
        Log::info("[MailRouter] Guardado con ID: {$email->id}");

        Log::info("[MailRouter] Obteniendo destinatario...");
        $recipient = $rotation->getNextRecipient();
        Log::info("[MailRouter] Destinatario: " . ($recipient ? "{$recipient->name} <{$recipient->email}>" : 'NULL'));

        if (!$recipient) {
            $email->update(['status' => 'no_recipients']);
            Log::warning('[MailRouter] Sin destinatarios. Saltando.');
            continue;
        }

        Log::info("[MailRouter] Intentando reenviar a {$recipient->email}...");

        try {
            $sender->forward($em['raw_message'], $recipient, $config);
            $email->update([
                'forwarded_to' => "{$recipient->name} <{$recipient->email}>",
                'forwarded_at' => now(),
                'status'       => 'forwarded',
            ]);
            Log::info("[MailRouter] ✓ Reenviado correctamente.");
        } catch (\Exception $e) {
            $email->update(['status' => 'failed']);
            Log::error("[MailRouter] ✕ Error en forward(): " . $e->getMessage());
            Log::error("[MailRouter] Traza: " . $e->getTraceAsString());
        }
    }

    Log::info('[MailRouter] ===== FIN DEL JOB =====');
}
}
