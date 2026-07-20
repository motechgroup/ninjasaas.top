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
        return new Content(
            htmlString: "
                <div style='font-family: sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; rounded-radius: 12px;'>
                    <h2 style='color: #004ac6; margin-bottom: 8px;'>SaaSNinja Two-Factor Authentication</h2>
                    <p style='color: #475569; font-size: 14px;'>Use the following 6-digit security verification code to complete your login session:</p>
                    <div style='background-color: #f1f5f9; padding: 16px; text-align: center; border-radius: 8px; font-size: 28px; font-weight: bold; letter-spacing: 6px; color: #0f172a; margin: 20px 0;'>
                        {$this->code}
                    </div>
                    <p style='color: #94a3b8; font-size: 12px;'>This code will expire in 10 minutes. If you did not request this code, please secure your account immediately.</p>
                </div>
            "
        );
    }
}
