<?php

namespace App\Notifications;

use App\Services\EmailBrandingService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomPasswordResetNotification extends ResetPassword
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $appName = \App\Models\Setting::get('company_name', 'SaaSNinja');
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $html = EmailBrandingService::renderHtmlEmail(
            title: "Reset Password Notification",
            greeting: "Hello {$notifiable->name},",
            paragraphs: [
                "You are receiving this email because we received a password reset request for your {$appName} account.",
                "Click the button below to choose a new password:"
            ],
            highlightBox: null,
            buttonUrl: $resetUrl,
            buttonText: "Reset Password",
            subtext: "This password reset link will expire in 60 minutes. If you did not request a password reset, no further action is required."
        );

        return (new MailMessage)
            ->subject("Reset Your {$appName} Account Password")
            ->view('emails.branded', ['htmlContent' => $html]);
    }
}
