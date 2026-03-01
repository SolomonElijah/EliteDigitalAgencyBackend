<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use App\Models\CampaignRecipient;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public EmailCampaign $campaign,
        public CampaignRecipient $recipient
    ) {}

    public function build()
    {
        $fromEmail = $this->campaign->from_email ?? config('mail.from.address');
        $fromName  = $this->campaign->from_name  ?? config('mail.from.name');

        // Replace merge tags in body: {{name}}, {{company}}
        $body = str_replace(
            ['{{name}}', '{{company}}'],
            [$this->recipient->name ?? 'Valued Customer', $this->recipient->company ?? ''],
            $this->campaign->body
        );

        return $this->from($fromEmail, $fromName)
                    ->subject($this->campaign->subject)
                    ->view('emails.campaign', ['body' => $body]);
    }
}
