<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogComment;
use App\Models\NewsletterSubscriber;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Update/Add bios and profiles to existing Admin/Staff users
        $authors = User::role(['Super Admin', 'Content Manager'])->get();
        if ($authors->isEmpty()) {
            $authors = User::take(2)->get();
        }

        $bios = [
            "Lead Software Architect at SaaSNinja Software. Specializes in Laravel development, database modeling, and high-performance server architectures.",
            "Senior DevOps Engineer and system administrator. Passionate about Ubuntu server scripting, Docker containerization, and networking automation.",
        ];

        foreach ($authors as $index => $author) {
            $author->update([
                'bio' => $bios[$index % count($bios)],
                'twitter_handle' => 'saasninja_' . strtolower(explode(' ', $author->name)[0]),
                'github_handle' => strtolower(explode(' ', $author->name)[0]) . '_dev',
            ]);
        }

        $authorId = $authors->first()->id;
        $secondAuthorId = ($authors->count() > 1) ? $authors->get(1)->id : $authorId;

        // 2. Create Categories
        $categoriesData = [
            [
                'name' => 'Software Development',
                'slug' => 'software-development',
                'description' => 'Best practices, architectural patterns, clean code principles, and general programming guidelines.'
            ],
            [
                'name' => 'Laravel & PHP',
                'slug' => 'laravel-php',
                'description' => 'Eloquent ORM optimization, Livewire reactivity, package development, and PHP core tips.'
            ],
            [
                'name' => 'DevOps & Servers',
                'slug' => 'devops-servers',
                'description' => 'Linux administration, Nginx config tuning, Docker containers, Hetzner, and cloud deployments.'
            ],
            [
                'name' => 'Networking & ISP',
                'slug' => 'networking-isp',
                'description' => 'DNS routing, secure SSL certificate setups, networking topologies, and billing automation.'
            ],
            [
                'name' => 'Business Automation',
                'slug' => 'business-automation',
                'description' => 'How to optimize business operations using custom ERP, CRM, and POS software platforms.'
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[] = BlogCategory::updateOrCreate(['slug' => $catData['slug']], $catData);
        }

        // 3. Create Tags
        $tagsData = ['Laravel', 'PHP', 'Livewire', 'TailwindCSS', 'MySQL', 'DevOps', 'Linux', 'Hetzner', 'Docker', 'Security', 'Networking', 'CRM', 'ERP', 'SaaS'];
        $tags = [];
        foreach ($tagsData as $name) {
            $tags[] = BlogTag::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'slug' => Str::slug($name)]);
        }

        // 4. Create Blog Posts
        
        // Post 1: Laravel Eloquent Optimization (Featured)
        $post1 = BlogPost::updateOrCreate([
            'slug' => 'optimizing-laravel-eloquent-queries-for-enterprise-saas-architectures',
        ], [
            'user_id' => $authorId,
            'title' => 'Optimizing Laravel Eloquent Queries for Enterprise SaaS Architectures',
            'subtitle' => 'Unlocking massive performance boosts by avoiding the N+1 problem, using query scopes, and caching database indexes.',
            'summary' => 'Eloquent is an incredibly powerful ORM, but unoptimized models can easily bottleneck your web applications. Learn how to diagnose slow queries, load relational datasets efficiently, and configure indexing.',
            'content' => "## The Silent SaaS Killer: N+1 Query Problem

When building relational platforms, it is easy to accidentally make \$100\$ database calls when only \$2\$ are needed. Consider a CRM display where you load client representatives and their respective company logos.

> [!WARNING]
> Failing to eager load relationships will query the database once for the representatives list, and then trigger a separate query for *each individual company logo*!

Here is the wrong way:
```php
\$reps = Representative::all();
foreach (\$reps as \$rep) {
    echo \$rep->company->logo;
}
```

### Eager Loading to the Rescue
To optimize this, you must eager-load using the `with()` method:

```php
// Eager load company relations instantly
\$reps = Representative::with('company')->get();
```

This reduces the query count from \$N+1\$ down to exactly \$2\$!

## Database Indexing & Eloquent Scopes
### Using Database Indexes
Always ensure your foreign keys and heavily queried columns are indexed in your migrations:

```php
Schema::table('representatives', function (Blueprint \$table) {
    \$table->index('company_id');
});
```

### Implementing Local Query Scopes
Scopes keep your controllers clean and logic centralized:

```php
class Representative extends Model {
    public function scopeActive(\$query) {
        return \$query->where('status', 'active');
    }
}
```",
            'reading_time' => 5,
            'featured_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=800&q=80',
            'is_featured' => true,
            'seo_title' => 'Optimizing Laravel Eloquent Queries - SaaSNinja Blog',
            'seo_description' => 'Unlocking massive performance boosts in Laravel by eager loading relations, utilizing indexes, and leveraging Eloquent scopes.',
            'faq' => [
                ['question' => 'What is the N+1 problem?', 'answer' => 'It is when an application makes N database queries for children rows plus 1 query for parent rows instead of utilizing JOIN statements.'],
                ['question' => 'How does with() solve N+1?', 'answer' => 'It executes a single SELECT query using WHERE IN to fetch related rows in a single batch.']
            ],
            'attachments' => [
                ['name' => 'Optimal Eloquent Migrations Template', 'url' => 'https://github.com/saasninja/templates/eloquent-migrations.zip']
            ],
            'is_published' => true,
            'published_at' => now()->subDays(2),
        ]);
        $post1->categories()->sync([$categories[1]->id]); // Laravel & PHP
        $post1->tags()->sync([$tags[0]->id, $tags[1]->id, $tags[4]->id]); // Laravel, PHP, MySQL

        // Post 2: DevOps and Hetzner/Ubuntu Hosting
        $post2 = BlogPost::updateOrCreate([
            'slug' => 'setting-up-a-high-availability-vps-stack-on-hetzner-cloud-and-nginx',
        ], [
            'user_id' => $secondAuthorId,
            'title' => 'Setting Up a High-Availability VPS Stack on Hetzner Cloud and Nginx',
            'subtitle' => 'A step-by-step developer manual for configuring Ubuntu 24.04 servers, generating SSL keys, and tuning Nginx for reverse proxies.',
            'summary' => 'Shared hosting holds your SaaS backend back. In this tutorial, we will configure an Ubuntu VPS on Hetzner, configure secure Nginx reverse proxy stacks, and auto-deploy Let’s Encrypt certificates.',
            'content' => "## Initial Server Setup on Ubuntu 24.04

First, log into your new Hetzner VPS and update all system dependencies:

```bash
sudo apt update && sudo apt upgrade -y
```

> [!INFO]
> Always configure a custom SSH port and set up public key authentication to defend against automated brute force scripts.

## Installing and Tuning Nginx
Install the Nginx package:

```bash
sudo apt install nginx -y
```

### Optimal Virtual Host configuration
Open your domain's config file `/etc/nginx/sites-available/yourdomain.com` and paste:

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/yourdomain.com/public;

    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
    }
}
```",
            'reading_time' => 8,
            'featured_image' => 'https://images.unsplash.com/photo-1600132806370-bf17e65e942f?auto=format&fit=crop&w=800&q=80',
            'is_featured' => false,
            'seo_title' => 'VPS Setup on Hetzner with Nginx - SaaSNinja Blog',
            'seo_description' => 'A developer guide to setting up high-performance Ubuntu servers, configuring Nginx virtual hosts, and hardening security settings.',
            'faq' => [
                ['question' => 'Why choose VPS over shared hosting?', 'answer' => 'VPS hosting provides isolated resources, root server access, and maximum configurations for software automation.'],
                ['question' => 'What Ubuntu version is recommended?', 'answer' => 'Always use the latest LTS (Long-Term Support) version for production servers, such as Ubuntu 24.04.']
            ],
            'attachments' => [
                ['name' => 'Nginx Production VirtualHost Config', 'url' => 'https://github.com/saasninja/configs/nginx-vhost.conf']
            ],
            'is_published' => true,
            'published_at' => now()->subDays(5),
        ]);
        $post2->categories()->sync([$categories[2]->id]); // DevOps & Servers
        $post2->tags()->sync([$tags[5]->id, $tags[6]->id, $tags[7]->id]); // DevOps, Linux, Hetzner

        // Post 3: Business Automation POS systems
        $post3 = BlogPost::updateOrCreate([
            'slug' => 'designing-database-schemas-for-multi-tenant-pos-and-retail-software',
        ], [
            'user_id' => $authorId,
            'title' => 'Designing Database Schemas for Multi-Tenant POS and Retail Software',
            'subtitle' => 'Centralizing inventory logs, handling decimal precision for currencies, and ensuring atomic sales transactions.',
            'summary' => 'Retail POS systems demand extreme consistency and accuracy. We review database designs for inventory tracking, multi-tenancy architectures, and handling transaction race conditions.',
            'content' => "## Multi-Tenant Database Architectures

There are two primary ways to design a SaaS multi-tenant system:
1. **Single Database with Tenant ID Column:** Simple to scale, cost-effective, but requires strict row-level policies.
2. **Database-Per-Tenant:** Maximum isolation, complies with strict regulations, but harder to maintain.

### Database schema for sales items
Always store price columns as `decimal` with at least \$8\$ digits and \$2\$ decimal places, or as integer cents. Never use float:

```sql
CREATE TABLE sales_items (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    sale_id BIGINT,
    product_id BIGINT,
    quantity INT,
    unit_price DECIMAL(10,2),
    total_price DECIMAL(10,2)
);
```

> [!TIP]
> Use atomic database transactions in your backend code to ensure that inventory deductions and sales logs succeed together or fail together.

```php
DB::transaction(function () use (\$sale, \$items) {
    \$sale->save();
    foreach (\$items as \$item) {
        \$item->save();
        \$item->product->decrement('inventory_count', \$item->quantity);
    }
});
```",
            'reading_time' => 6,
            'featured_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80',
            'is_featured' => false,
            'seo_title' => 'POS SaaS Database Schema Designs - SaaSNinja Blog',
            'seo_description' => 'How to design atomic multi-tenant database tables for SaaS POS billing and inventory applications.',
            'faq' => [
                ['question' => 'Why avoid FLOAT for pricing?', 'answer' => 'Float columns use floating-point approximation which introduces rounding errors. Decimal columns use exact numeric values.'],
                ['question' => 'What is tenant separation?', 'answer' => 'Ensuring that one customer (tenant) cannot view or modify another customer\'s database rows.']
            ],
            'attachments' => [
                ['name' => 'POS Database SQL Schema Script', 'url' => 'https://github.com/saasninja/schemas/pos-schema.sql']
            ],
            'is_published' => true,
            'published_at' => now()->subWeek(),
        ]);
        $post3->categories()->sync([$categories[4]->id, $categories[0]->id]); // Business Automation, Software Development
        $post3->tags()->sync([$tags[4]->id, $tags[11]->id, $tags[12]->id]); // MySQL, CRM, ERP

        // Post 4: Networking security SSL
        $post4 = BlogPost::updateOrCreate([
            'slug' => 'securing-api-gateways-with-cloudflare-ssl-tls-and-rate-limiting',
        ], [
            'user_id' => $secondAuthorId,
            'title' => 'Securing API Gateways with Cloudflare, SSL/TLS, and Rate Limiting',
            'subtitle' => 'Securing your public endpoints against DDoS attempts, SQL injection, and API brute-force resource draining.',
            'summary' => 'APIs are prime targets for cyberattacks. We explore setting up Cloudflare proxies, configuring SSL certificate encryption standards, and applying rate limits.',
            'content' => "## Cloudflare Proxy Configuration

By routing your traffic through Cloudflare, you hide your origin IP address, deflecting direct attacks.

### Enabling Full SSL/TLS (Strict)
Ensure you set your SSL mode to **Full (Strict)**. This encrypts the path from visitor to Cloudflare, *and* from Cloudflare to your origin server:

> [!IMPORTANT]
> You must generate an Origin CA Certificate inside Cloudflare and install it directly on your Nginx server to allow Strict verification.

## Configuring API Rate Limiting
Prevent script exploits by configuring rate limits on public endpoints:

```nginx
# Nginx rate limit declaration
limit_req_zone \$binary_remote_addr zone=api_limit:10m rate=60r/m;

server {
    location /api/ {
        limit_req zone=api_limit burst=10 nodelay;
        proxy_pass http://api_backend;
    }
}
```",
            'reading_time' => 7,
            'featured_image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=800&q=80',
            'is_featured' => false,
            'seo_title' => 'Securing APIs with Cloudflare & SSL - SaaSNinja Blog',
            'seo_description' => 'Protect your API endpoints by setting up Cloudflare proxy servers, Strict SSL certs, and configuring rate limit rules.',
            'faq' => [
                ['question' => 'What is the difference between Full and Full (Strict) SSL?', 'answer' => 'Full mode allows self-signed certs on the origin. Full (Strict) requires a valid certificate signed by a trusted CA.']
            ],
            'is_published' => true,
            'published_at' => now()->subWeeks(2),
        ]);
        $post4->categories()->sync([$categories[3]->id]); // Networking & ISP
        $post4->tags()->sync([$tags[9]->id, $tags[10]->id]); // Security, Networking

        // 5. Seed comments
        BlogComment::firstOrCreate([
            'blog_post_id' => $post1->id,
            'email' => 'michael@dundermifflin.com',
        ], [
            'name' => 'Michael Scott',
            'content' => 'Outstanding write-up! Eager loading solved an issue in our corporate client list query that was driving the server CPU crazy.',
            'is_approved' => true,
        ]);

        BlogComment::firstOrCreate([
            'blog_post_id' => $post1->id,
            'email' => 'dwight@schrutebeats.com',
        ], [
            'name' => 'Dwight Schrute',
            'content' => 'Correct. I always use index on my company foreign keys. It is more efficient than looking through folders.',
            'is_approved' => true,
        ]);

        BlogComment::firstOrCreate([
            'blog_post_id' => $post2->id,
            'email' => 'pam@dundermifflin.com',
        ], [
            'name' => 'Pam Beesly',
            'content' => 'This Nginx config worked perfectly for my art gallery website VPS. Thanks for the documentation.',
            'is_approved' => true,
        ]);

        // 6. Seed subscribers
        NewsletterSubscriber::firstOrCreate(['email' => 'tech-admin@ninjasaas.top'], ['is_active' => true]);
        NewsletterSubscriber::firstOrCreate(['email' => 'code-contributor@saasninja.top'], ['is_active' => true]);
        NewsletterSubscriber::firstOrCreate(['email' => 'laravel-fan@gmail.com'], ['is_active' => true]);
    }
}
