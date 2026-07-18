<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\EnvatoItem;
use App\Models\EnvatoPurchase;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\ServiceRequest;
use App\Models\Service;
use App\Enums\TicketStatus;
use App\Enums\Priority;
use App\Enums\ServiceRequestStatus;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Super Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@saasninja.top'],
            [
                'name' => 'SaaSNinja Super Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
            ]
        );
        $admin->assignRole('Super Admin');

        // 2. Create Support Staff User
        $support = User::firstOrCreate(
            ['email' => 'support@saasninja.top'],
            [
                'name' => 'Ninja Support Engineer',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
            ]
        );
        $support->assignRole('Support Staff');

        // 3. Create Content Manager User
        $manager = User::firstOrCreate(
            ['email' => 'manager@saasninja.top'],
            [
                'name' => 'Ninja Blog Editor',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
            ]
        );
        $manager->assignRole('Content Manager');

        // 4. Create Normal Customer User
        $customer = User::firstOrCreate(
            ['email' => 'customer@saasninja.top'],
            [
                'name' => 'Verified Envato Buyer',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
                'envato_username' => 'codecanyon_buyer_99',
            ]
        );

        // 5. Create Mock purchases and support tickets for Customer
        // Ensure Envato Items exist or pull from database
        $item = EnvatoItem::firstOrCreate(
            ['item_id' => '12345678'],
            [
                'name' => 'LexCore - Premium Multipurpose SaaS Landing Engine',
                'url' => 'https://codecanyon.net/item/lexcore-multipurpose-landing-engine/12345678'
            ]
        );

        // Link a purchase code to the customer
        $purchase = EnvatoPurchase::firstOrCreate(
            ['purchase_code' => '99999999-aaaa-bbbb-cccc-888888888888'],
            [
                'user_id' => $customer->id,
                'envato_item_id' => $item->id,
                'envato_username' => 'codecanyon_buyer_99',
                'purchase_date' => Carbon::now()->subMonths(2),
                'support_expiry' => Carbon::now()->addMonths(4),
                'license_type' => 'Regular License',
                'is_active' => true,
            ]
        );

        // Create an open support ticket
        $ticket = SupportTicket::firstOrCreate(
            ['subject' => 'Issue setting Nginx configurations'],
            [
                'user_id' => $customer->id,
                'envato_purchase_id' => $purchase->id,
                'category' => 'Installation',
                'priority' => Priority::HIGH,
                'status' => TicketStatus::OPEN,
            ]
        );

        // Create the initial query from the customer
        TicketReply::firstOrCreate(
            [
                'support_ticket_id' => $ticket->id,
                'message' => 'Hello team, I am trying to run LexCore on Nginx. I keep getting a 404 error when clicking on inner route pages. Can you help me?',
            ],
            [
                'user_id' => $customer->id,
                'is_internal' => false,
            ]
        );

        // Create an Answer reply from Support Staff
        TicketReply::firstOrCreate(
            [
                'support_ticket_id' => $ticket->id,
                'message' => "Hi there!\n\nThis is a standard Nginx route handling configuration issue. In your Nginx site block, make sure you have the following directive inside the location block:\n\n```nginx\nlocation / {\n    try_files \$uri \$uri/ /index.php?\$query_string;\n}\n```\n\nRestart Nginx after making the changes: `sudo systemctl restart nginx`.",
            ],
            [
                'user_id' => $support->id,
                'is_internal' => false,
            ]
        );
        $ticket->update(['status' => TicketStatus::ANSWERED]);

        // Create a custom service request
        $service = Service::first();
        if ($service) {
            ServiceRequest::firstOrCreate(
                [
                    'user_id' => $customer->id,
                    'service_id' => $service->id,
                    'description' => 'I would like your team to install SaaSNinja CRM and configure Nginx rewrite rules on my Ubuntu VPS server. I will share server credentials.',
                ],
                [
                    'budget' => 49.00,
                    'status' => ServiceRequestStatus::PENDING,
                ]
            );
        }
    }
}
