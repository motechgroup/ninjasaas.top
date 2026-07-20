<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationOtpNotification extends Notification
{
    use Queueable;

    public string $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $appName = \App\Models\Setting::get('company_name', 'SaaSNinja');

        $html = \App\Services\EmailBrandingService::renderHtmlEmail(
            title: "{$this->otp} is your {$appName} Verification Code",
            greeting: "Hello {$notifiable->name},",
            paragraphs: [
                "Thank you for registering with {$appName}.",
                "Please enter the following 6-digit verification code to complete your account registration:"
            ],
            highlightBox: $this->otp,
            subtext: "This code will expire in 15 minutes. If you did not create an account, no further action is required."
        );

        return (new MailMessage)
            ->subject("{$this->otp} is your {$appName} Email Verification Code")
            ->view('emails.branded', ['htmlContent' => $html]);
    }
}
