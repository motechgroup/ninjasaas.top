<?php

namespace App\Services;

use App\Models\Setting;

class EmailBrandingService
{
    /**
     * Render a beautifully branded HTML email wrapper for all outgoing site communications.
     */
    public static function renderHtmlEmail(
        string $title,
        string $greeting,
        array $paragraphs,
        ?string $highlightBox = null,
        ?string $buttonUrl = null,
        ?string $buttonText = null,
        ?string $subtext = null
    ): string {
        $companyName = Setting::get('company_name', 'SaaSNinja');
        $supportEmail = Setting::get('support_email', 'support@saasninja.top');
        $primaryColor = Setting::get('theme_primary_color', '#004ac6');
        $logoUrl = Setting::get('site_logo');

        $bodyContent = '';
        foreach ($paragraphs as $p) {
            $bodyContent .= "<p style='color: #475569; font-size: 14px; line-height: 1.6; margin-bottom: 16px;'>{$p}</p>";
        }

        $highlightSection = '';
        if ($highlightBox) {
            $highlightSection = "
                <div style='background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center; font-size: 28px; font-weight: bold; letter-spacing: 6px; color: #0f172a; margin: 24px 0;'>
                    {$highlightBox}
                </div>
            ";
        }

        $buttonSection = '';
        if ($buttonUrl && $buttonText) {
            $buttonSection = "
                <div style='text-align: center; margin: 28px 0;'>
                    <a href='{$buttonUrl}' target='_blank' style='background-color: {$primaryColor}; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-size: 14px; font-weight: bold; display: inline-block; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);'>
                        {$buttonText}
                    </a>
                </div>
            ";
        }

        $subtextSection = '';
        if ($subtext) {
            $subtextSection = "<p style='color: #94a3b8; font-size: 12px; line-height: 1.5; margin-top: 20px;'>{$subtext}</p>";
        }

        $logoHeader = $logoUrl
            ? "<img src='{$logoUrl}' alt='{$companyName}' style='max-height: 40px; width: auto; display: block; margin: 0 auto;'>"
            : "<h1 style='color: {$primaryColor}; font-size: 24px; font-weight: 800; margin: 0;'>{$companyName}</h1>";

        $currentYear = date('Y');

        return "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>{$title}</title>
            </head>
            <body style='background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 40px 16px;'>
                <div style='max-width: 560px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;'>
                    
                    <!-- Header -->
                    <div style='padding: 32px 32px 24px 32px; border-b: 1px solid #f1f5f9; text-align: center; background-color: #ffffff;'>
                        {$logoHeader}
                    </div>

                    <!-- Main Body -->
                    <div style='padding: 32px;'>
                        <h2 style='color: #0f172a; font-size: 18px; font-weight: 700; margin-top: 0; margin-bottom: 16px;'>{$greeting}</h2>
                        {$bodyContent}
                        {$highlightSection}
                        {$buttonSection}
                        {$subtextSection}
                    </div>

                    <!-- Footer -->
                    <div style='background-color: #f8fafc; padding: 24px 32px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8;'>
                        <p style='margin: 0 0 8px 0;'>Need help? Contact our support team at <a href='mailto:{$supportEmail}' style='color: {$primaryColor}; text-decoration: none;'>{$supportEmail}</a></p>
                        <p style='margin: 0;'>&copy; {$currentYear} {$companyName}. All rights reserved.</p>
                    </div>

                </div>
            </body>
            </html>
        ";
    }
}
