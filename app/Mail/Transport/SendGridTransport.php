<?php

namespace App\Mail\Transport;

use Illuminate\Mail\Transport\Transport;
use Illuminate\Support\Facades\Http;
use Swift_Mime_SimpleMessage;

class SendGridTransport extends Transport
{
    protected $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $to = [];
        foreach ($message->getTo() as $address => $name) {
            $to[] = [
                'email' => $address,
                'name' => $name ?? '',
            ];
        }

        $from = $message->getFrom();
        $fromAddress = key($from);
        $fromName = current($from);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.sendgrid.com/v3/mail/send', [
            'personalizations' => [
                [
                    'to' => $to,
                    'subject' => $message->getSubject(),
                ],
            ],
            'from' => [
                'email' => $fromAddress,
                'name' => $fromName ?? '',
            ],
            'content' => [
                [
                    'type' => 'text/html',
                    'value' => $message->getBody(),
                ],
            ],
        ]);

        $this->sendPerformed($message);

        return $response->successful() ? $response->getStatusCode() : 0;
    }
}
