<?php
namespace App\Services;

use App\Models\Recipient;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Webklex\PHPIMAP\Message;

class MailSenderService
{
    public function forward(Message $message, Recipient $recipient, array $config): void
    {
        $this->configureMailer($config);

        $subject     = (string) $message->getSubject();
        $from        = (string) $message->getFrom();
        $body        = (string) $message->getTextBody()
                        ?: strip_tags((string) $message->getHTMLBody());
        $attachments = $message->getAttachments();

        Mail::html(
            $this->buildHtml($subject, $from, $config['email_address'], $recipient->name, $body),
            function ($mail) use ($subject, $recipient, $config, $attachments) {
                $mail->from($config['email_address'])
                     ->to($recipient->email)
                     ->subject('Fwd: ' . $subject);

                foreach ($attachments as $attachment) {
                    $mail->attachData(
                        $attachment->getContent(),
                        $attachment->getName(),
                        ['mime' => $attachment->getMimeType()]
                    );
                }
            }
        );
    }

    public function forwardFromEml(string $emlPath, Recipient $recipient, array $config): void
    {
        $raw = Storage::get($emlPath);
        if (!$raw) {
            throw new \Exception("Archivo .eml no encontrado: {$emlPath}");
        }

        preg_match('/^Subject: (.+)$/mi', $raw, $matches);
        $subject = trim($matches[1] ?? '(sin asunto)');

        preg_match('/^From: (.+)$/mi', $raw, $fromMatches);
        $from = trim($fromMatches[1] ?? '');

        // Extraer el cuerpo del .eml
        $parts = explode("\r\n\r\n", $raw, 2);
        $body  = $parts[1] ?? '';

        $this->configureMailer($config);

        Mail::html(
            $this->buildHtml($subject, $from, $config['email_address'], $recipient->name, $body),
            function ($mail) use ($subject, $recipient, $config) {
                $mail->from($config['email_address'])
                     ->to($recipient->email)
                     ->subject('Fwd: ' . $subject);
            }
        );
    }

    /**
     * Configura el mailer de Laravel con las credenciales guardadas en DB.
     * Esto permite cambiar la cuenta de correo desde el dashboard sin tocar el .env.
     */
    private function configureMailer(array $config): void
    {
        config([
            'mail.mailers.smtp.host'       => $config['smtp_host'] ?? 'smtp.gmail.com',
            'mail.mailers.smtp.port'       => $config['smtp_port'] ?? 587,
            'mail.mailers.smtp.username'   => $config['email_address'],
            'mail.mailers.smtp.password'   => $config['email_password'],
            'mail.mailers.smtp.encryption' => 'tls',
            'mail.from.address'            => $config['email_address'],
            'mail.from.name'               => 'MailRouter',
        ]);

        // Forzar que el mailer use la nueva configuración
        app()->forgetInstance('mailer');
        app()->forgetInstance('swift.mailer');
        app()->forgetInstance('swift.transport');
    }

    private function buildHtml(
        string $subject,
        string $from,
        string $fromAddress,
        string $recipientName,
        string $body
    ): string {
        return "
        <div style='font-family:Arial,sans-serif;max-width:680px;margin:0 auto;'>
          <div style='background:#1a1a2e;color:#e0e0e0;padding:16px 24px;border-radius:8px 8px 0 0;'>
            <p style='margin:0;font-size:13px;color:#7c83fd;'>
              📨 Reenviado desde <strong>{$fromAddress}</strong>
            </p>
          </div>
          <div style='border:1px solid #e0e0e0;border-top:none;padding:24px;border-radius:0 0 8px 8px;'>
            <table style='width:100%;font-size:13px;color:#555;margin-bottom:16px;'>
              <tr><td style='font-weight:600;width:80px;padding:4px 0;'>De:</td><td>{$from}</td></tr>
              <tr><td style='font-weight:600;padding:4px 0;'>Asunto:</td><td>{$subject}</td></tr>
            </table>
            <hr style='border:none;border-top:1px solid #eee;margin:16px 0;'>
            <div style='white-space:pre-wrap;color:#222;line-height:1.6;'>{$body}</div>
          </div>
          <p style='text-align:center;font-size:11px;color:#aaa;margin-top:12px;'>
            Asignado a: <strong>{$recipientName}</strong> — MailRouter
          </p>
        </div>";
    }
}
