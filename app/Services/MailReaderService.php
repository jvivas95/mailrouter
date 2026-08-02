<?php
namespace App\Services;

use Webklex\PHPIMAP\ClientManager;
use App\Models\Email;

class MailReaderService
{
    public function fetchUnseen(array $config): array
    {
        // Create a new ClientManager instance
        $cm = new ClientManager();
        $client = $cm->make([
            'host'          => $config['imap_host'] ?? 'imap.gmail.com',
            'port'          => $config['imap_port'] ?? 993,
            'encryption'    => 'ssl',
            'validate_cert' => true,
            'username'      => $config['email_address'],
            'password'      => $config['email_password'],
            'protocol'      => 'imap'
        ]);

        $client->connect();
        $folder = $client->getFolder('INBOX');
        $messages = $folder->query()->unseen()->get();

        $results = [];
        foreach ($messages as $message) {
            $results[] = [
                'uid'       => (string) $message->getUid(),
                'sender'    => (string) $message->getFrom(),
                'subject'   => (string) $message->getSubject(),
                'body'      => (string) $message->getTextBody(),
                'attachments_count' => $message->getAttachments()->count(),
                'raw_message' => $message, // Complete message object for forward
            ];
        }

        $client->disconnect();
        return $results;
    }

}
