<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TwoFactorCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;

    /**
     * Create a new message instance.
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your SaaSNinja Security Verification Code',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $appName = \App\Models\Setting::get('company_name', 'SaaSNinja');

        $html = \App\Services\EmailBrandingService::renderHtmlEmail(
            title: "{$this->code} is your {$appName} Security Code",
            greeting: "Security Verification",
            paragraphs: [
                "A login request was initiated for your {$appName} account.",
                "Use the 6-digit security verification code below to complete your authentication session:"
            ],
            highlightBox: $this->code,
            subtext: "This code will expire in 10 minutes. If you did not request this login, please secure your account password immediately."
        );

        return new Content(
            htmlString: $html
        );
    }
}
