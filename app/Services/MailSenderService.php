<?php
namespace App\Services;

use App\Models\Recipient;
use Illuminate\Support\Facades\Storage;
use  Webklex\PHPIMAP\Message;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\SMimePart;

class MailSenderService
{

    // Resend email to the recipient
    public function forward(Message $message, Recipient $recipient, array $config): void
    {
        $raw = $message->getRawBody();

        $this->sendRaw($raw, $recipient, $config, (string) $message->getSubject());
    }

    // Resend email from .eml saved in disk
    public function forwardFromEml(string $emlPath, Recipient $recipient, array $config): void
    {
        $raw = Storage::get($emlPath);

        if (!$raw) {
            throw new \Exception("Archivo .eml no encontrado: {$emlPath}");
        }

        // Extract subject from the .eml file
        preg_match('/^Subject: (.+)$/m', $raw, $matches);
        $subject = $matches[1] ?? 'No Subject';

        $this->sendRaw($raw, $recipient, $config, $subject);
    }

    // Send raw email
    private function sendRaw(string $raw, Recipient $recipient, array $config, string $subject): void
    {
        // Replace the header
        $modified = $this->replaceHeaders($raw, [
            'From'      => $config['email_adress'],
            'To'        => $recipient->email,
            'Subject'   => 'Fwd: ' . $subject,
        ]);

        // Conect to the SMTP server and resend the email
        $transport = Transport::fromDsn(
            "smtp://{$config['smtp_user']}:{$config['smtp_password']}@{$config['smtp_host']}:{$config['smtp_port']}"
        );

        $mailer = new Mailer($transport);

        $smimePart = new SMimePart($modified, 'message', 'rfc822', []);

        // Create a new email message with the modified raw content
        $email = new Email();
        $email->from($config['email_adress'])
              ->to($recipient->email)
              ->subject('Fwd: ' . $subject)
              ->setBody($smimePart);

        $mailer->send($email);
    }

    // Replace headers in the raw email content
    private function replaceHeaders(string $raw, array $headers): string
    {
        // Split the raw email into headers and body
        $parts = explode("\r\n\r\n", $raw, 2);
        $rawHeaders = $parts[0] ?? '';
        $body = $parts[1] ?? '';

        // Replace or add the specified headers
        foreach ($headers as $name => $value) {
            if (preg_match("/^{$name}:/mi", $rawHeaders)) {
                // If exists, replace the header
                $rawHeaders = preg_replace("/^{$name}:.*/mi", "{$name}: {$value}", $rawHeaders);
            }
            else {
                // If not exists, add the header
                $rawHeaders = "{$name}: {$value}\r\n" . $rawHeaders;
            }
        }

        return $rawHeaders . "\r\n\r\n" . $body;
    }
}
