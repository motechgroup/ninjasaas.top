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

        return (new MailMessage)
            ->subject("{$this->otp} is your {$appName} Email Verification Code")
            ->greeting("Hello {$notifiable->name},")
            ->line("Thank you for registering with {$appName}.")
            ->line("Your 6-digit email verification code is:")
            ->line("# {$this->otp}")
            ->line("Copy and paste this code on the email verification page to complete your account setup.")
            ->line("This code will expire in 15 minutes.")
            ->salutation("Best regards,\nThe {$appName} Team");
    }
}
