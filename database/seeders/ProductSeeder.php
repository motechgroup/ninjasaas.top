<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductFeature;
use App\Models\ProductScreenshot;
use App\Models\ProductVersion;
use App\Models\ProductChangelog;
use App\Models\Service;
use App\Models\DocumentationCategory;
use App\Models\DocumentationArticle;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories
        $webAppCategory = ProductCategory::create([
            'name' => 'Laravel Web Applications',
            'slug' => 'laravel-web-applications',
            'description' => 'Self-hosted web applications built with Laravel framework.'
        ]);

        $saasCategory = ProductCategory::create([
            'name' => 'SaaS Landing Templates',
            'slug' => 'saas-landing-templates',
            'description' => 'Beautiful premium website designs for SaaS startups.'
        ]);

        // 2. Products
        $lexCore = Product::create([
            'product_category_id' => $saasCategory->id,
            'name' => 'LexCore - Premium Multipurpose SaaS Landing Engine',
            'slug' => 'lexcore-landing-engine',
            'short_description' => 'A customizable high-conversion landing page framework built using Laravel & TailwindCSS.',
            'description' => 'LexCore is the ultimate marketing engine for SaaS startups. It features a modular Blade component architecture, pre-configured Tailwind variables, Outfit typography, an SEO controller, mail newsletter integrations, and multiple preset demos.',
            'image_url' => null,
            'demo_url' => 'https://demo.saasninja.top/lexcore',
            'buy_url' => 'https://codecanyon.net/item/lexcore-multipurpose-landing-engine/12345678',
            'docs_url' => 'docs/lexcore-landing-engine/getting-started/installation',
            'version' => '1.2.0',
            'is_active' => true,
            'envato_item_id' => '12345678',
        ]);

        $crm = Product::create([
            'product_category_id' => $webAppCategory->id,
            'name' => 'SaaSNinja CRM - Enterprise Customer Portal',
            'slug' => 'saasninja-crm-portal',
            'short_description' => 'A complete user dashboard, license manager, and ticket helpdesk system for software authors.',
            'description' => 'SaaSNinja CRM is a ready-to-deploy, production-ready portal that connects with Envato API. Features include purchase verification, customer support ticket management, paid custom server service requests, and blog CMS integrations.',
            'image_url' => null,
            'demo_url' => 'https://demo.saasninja.top/crm',
            'buy_url' => 'https://codecanyon.net/item/saasninja-crm-customer-portal/87654321',
            'docs_url' => 'docs/saasninja-crm-portal/getting-started/installation',
            'version' => '1.0.0',
            'is_active' => true,
            'envato_item_id' => '87654321',
        ]);

        // 3. Product Features
        ProductFeature::create([
            'product_id' => $lexCore->id,
            'title' => 'TailwindCSS v4 Scaffolding',
            'description' => 'Integrated with TailwindCSS v4 and Vite for lightning fast compilation.',
            'sort_order' => 1
        ]);
        ProductFeature::create([
            'product_id' => $lexCore->id,
            'title' => 'Outfit Typography',
            'description' => 'Curated typography using Google Fonts Outfit and Inter fonts.',
            'sort_order' => 2
        ]);
        ProductFeature::create([
            'product_id' => $lexCore->id,
            'title' => 'Newsletter Integrations',
            'description' => 'Direct integration with Mailchimp, ActiveCampaign, and local databases.',
            'sort_order' => 3
        ]);

        ProductFeature::create([
            'product_id' => $crm->id,
            'title' => 'Envato Purchase Check',
            'description' => 'Connects to Envato API v3 with personal token and OAuth login support.',
            'sort_order' => 1
        ]);
        ProductFeature::create([
            'product_id' => $crm->id,
            'title' => 'Helpdesk Chat Workspace',
            'description' => 'Double-pane reactive support ticket chat workspace supporting file uploads.',
            'sort_order' => 2
        ]);

        // 4. Product Screenshots (Using placeholder or mock)
        ProductScreenshot::create([
            'product_id' => $lexCore->id,
            'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=600',
            'caption' => 'LexCore SaaS Template Home Header Preview',
            'sort_order' => 1
        ]);
        ProductScreenshot::create([
            'product_id' => $crm->id,
            'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=600',
            'caption' => 'Customer Helpdesk Ticket Manager Chat Interface',
            'sort_order' => 1
        ]);

        // 5. Product Versions & Changelogs
        $lexV1 = ProductVersion::create([
            'product_id' => $lexCore->id,
            'version' => '1.0.0',
            'release_date' => Carbon::now()->subMonths(6),
            'is_stable' => true,
        ]);
        ProductChangelog::create([
            'product_version_id' => $lexV1->id,
            'type' => 'added',
            'description' => 'Initial release of LexCore landing engine template.'
        ]);

        $lexV2 = ProductVersion::create([
            'product_id' => $lexCore->id,
            'version' => '1.2.0',
            'release_date' => Carbon::now()->subMonths(1),
            'is_stable' => true,
        ]);
        ProductChangelog::create([
            'product_version_id' => $lexV2->id,
            'type' => 'changed',
            'description' => 'Upgraded layout stack to support TailwindCSS v4 compilation.'
        ]);
        ProductChangelog::create([
            'product_version_id' => $lexV2->id,
            'type' => 'fixed',
            'description' => 'Fixed mobile responsiveness of collapsing header navigation.'
        ]);

        $crmV1 = ProductVersion::create([
            'product_id' => $crm->id,
            'version' => '1.0.0',
            'release_date' => Carbon::now()->subDays(15),
            'is_stable' => true,
        ]);
        ProductChangelog::create([
            'product_version_id' => $crmV1->id,
            'type' => 'added',
            'description' => 'Initial release of SaaSNinja CRM Customer Helpdesk Portal.'
        ]);

        // 6. Paid Services
        Service::create([
            'name' => 'Application Installation Service',
            'slug' => 'application-installation',
            'short_description' => 'We will deploy and configure your purchased SaaSNinja product on your server.',
            'description' => "Our team will handle the full deployment lifecycle:\n- Setting up web server configs (Nginx/Apache)\n- Database creation and environment configuration\n- SSL Certificate installation (Let's Encrypt)\n- Running database migrations & asset builds",
            'price' => 49.00,
            'icon' => null,
            'is_active' => true,
        ]);

        Service::create([
            'name' => 'Custom Feature Development',
            'slug' => 'custom-feature-development',
            'short_description' => 'Need additional features? Hire SaaSNinja engineers to write custom integrations.',
            'description' => "We provide tailor-made adjustments for your needs:\n- Custom API integrations\n- Visual style customizations\n- Database extension and custom controllers\n- Clean git-based modifications",
            'price' => 199.00,
            'icon' => null,
            'is_active' => true,
        ]);

        // 7. Documentation (Categories & Articles)
        $lexDocsCat = DocumentationCategory::create([
            'product_id' => $lexCore->id,
            'name' => 'Getting Started',
            'slug' => 'getting-started',
            'description' => 'General onboarding guide for LexCore template.',
            'sort_order' => 1
        ]);

        DocumentationArticle::create([
            'documentation_category_id' => $lexDocsCat->id,
            'title' => 'Server Installation Requirements',
            'slug' => 'installation-requirements',
            'content' => "Before installing LexCore, ensure your environment meets these standards:\n- PHP 8.2 or higher\n- Composer 2.0 or higher\n- BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, and XML extensions active.\n- MySQL 8.0+ or SQLite 3.0+\n- Node.js & NPM for asset compilation.",
            'sort_order' => 1,
            'is_published' => true,
        ]);

        DocumentationArticle::create([
            'documentation_category_id' => $lexDocsCat->id,
            'title' => 'Tailwind Customization Guide',
            'slug' => 'tailwind-customization',
            'content' => "LexCore uses TailwindCSS v4. To customize colors, fonts, or breakpoints, edit resources/css/app.css.\nAdd theme overrides using variables:\n\n```css\n@theme {\n    --color-primary: #4f46e5;\n    --font-sans: 'Inter', sans-serif;\n}\n```",
            'sort_order' => 2,
            'is_published' => true,
        ]);

        $crmDocsCat = DocumentationCategory::create([
            'product_id' => $crm->id,
            'name' => 'API Integrations',
            'slug' => 'api-integrations',
            'description' => 'Connect your SaaSNinja CRM to external networks.',
            'sort_order' => 1
        ]);

        DocumentationArticle::create([
            'documentation_category_id' => $crmDocsCat->id,
            'title' => 'Envato Personal Token Config',
            'slug' => 'envato-token-setup',
            'content' => "To authenticate purchases:\n1. Log in to build.envato.com.\n2. Create a personal token with 'verify purchases of your items' permission.\n3. Copy the token into your SaaSNinja CRM .env configuration under `ENVATO_PERSONAL_TOKEN=your_token`.\n4. Save settings and clear configurations cache.",
            'sort_order' => 1,
            'is_published' => true,
        ]);

        // 8. Blog CMS Articles
        // Fetch an author user (seeded in UserSeeder)
        $author = User::first() ?: User::factory()->create([
            'name' => 'SaaSNinja Chief Architect',
            'email' => 'author@saasninja.top',
            'password' => bcrypt('password'),
        ]);

        BlogPost::create([
            'user_id' => $author->id,
            'title' => 'Migrating to TailwindCSS v4: Lessons Learned',
            'slug' => 'migrating-to-tailwindcss-v4-lessons',
            'summary' => 'A practical walkthrough of how we transitioned our landing page products to Tailwind v4 and Vite.',
            'content' => "We recently migrated our entire SaaS product line to TailwindCSS v4. Here is a breakdown of the benefits:\n- Compilation speed increased by 3.5x using the official Vite plugin.\n- Clean theme declaration using CSS variables instead of tailwind.config.js configuration files.\n- Automatic container query support built directly into utility namespaces.\n\nMake sure to run npm audit after upgrading packages to ensure lockfile integrity.",
            'featured_image' => null,
            'seo_title' => 'TailwindCSS v4 Migration Guide - SaaSNinja',
            'seo_description' => 'A complete guide of how we migrated our templates from Tailwind v3 to v4 using Vite.',
            'is_published' => true,
            'published_at' => Carbon::now(),
        ]);
    }
}
