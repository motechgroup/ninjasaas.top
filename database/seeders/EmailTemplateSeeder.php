<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'key' => 'welcome_email',
                'name' => 'Welcome Registration Email',
                'subject' => 'Welcome to SaaSNinja!',
                'body' => '<div style="font-family: sans-serif; padding: 20px; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 10px;">
    <h2 style="color: #004ac6; margin-top: 0;">Welcome, {name}!</h2>
    <p>Thank you for registering on the SaaSNinja Portal. You can now link your Envato purchases, download products, and request premium custom development services.</p>
    <p style="margin: 24px 0;"><a href="{site_url}/login" style="background: #004ac6; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Login to Portal</a></p>
    <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 24px 0;">
    <p style="font-size: 12px; color: #718096;">Best Regards,<br>The SaaSNinja Team</p>
</div>',
            ],
            [
                'key' => 'ticket_created',
                'name' => 'New Support Ticket Confirmation',
                'subject' => 'Support Ticket #{ticket_id} Opened',
                'body' => '<div style="font-family: sans-serif; padding: 20px; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 10px;">
    <h2 style="color: #004ac6; margin-top: 0;">Support Ticket Opened</h2>
    <p>Hello {name},</p>
    <p>Your support ticket has been opened successfully. Our engineering staff is reviewing your request and will get back to you shortly.</p>
    <div style="background: #f7f9fb; padding: 16px; border-radius: 8px; border-left: 4px solid #004ac6; margin: 20px 0;">
        <strong>Ticket #{ticket_id}</strong>: {ticket_subject}<br>
        <strong>Priority</strong>: {ticket_priority}
    </div>
    <p style="margin: 24px 0;"><a href="{site_url}/portal/tickets" style="background: #004ac6; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">View Ticket Workspace</a></p>
    <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 24px 0;">
    <p style="font-size: 12px; color: #718096;">Best Regards,<br>The SaaSNinja Helpdesk</p>
</div>',
            ],
            [
                'key' => 'ticket_reply',
                'name' => 'New Support Reply Notification',
                'subject' => 'New reply on Support Ticket #{ticket_id}',
                'body' => '<div style="font-family: sans-serif; padding: 20px; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 10px;">
    <h2 style="color: #004ac6; margin-top: 0;">New Helpdesk Reply</h2>
    <p>Hello {name},</p>
    <p>A new reply has been added to your support ticket by <strong>{sender_name}</strong>:</p>
    <div style="background: #f7f9fb; padding: 16px; border-radius: 8px; font-style: italic; margin: 20px 0; border-left: 4px solid #515f74;">
        "{reply_message}"
    </div>
    <p style="margin: 24px 0;"><a href="{site_url}/portal/tickets" style="background: #004ac6; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Reply to Ticket</a></p>
    <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 24px 0;">
    <p style="font-size: 12px; color: #718096;">Best Regards,<br>The SaaSNinja Helpdesk</p>
</div>',
            ],
        ];

        foreach ($templates as $t) {
            EmailTemplate::updateOrCreate(['key' => $t['key']], $t);
        }
    }
}
