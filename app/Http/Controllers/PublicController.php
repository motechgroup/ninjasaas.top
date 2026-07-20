<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogComment;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use App\Models\DocumentationCategory;
use App\Models\DocumentationArticle;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $products = Product::where('is_active', true)->with('category')->take(4)->get();
        $recentPosts = BlogPost::where('is_published', true)->orderByDesc('published_at')->take(3)->get();
        
        return view('home', compact('products', 'recentPosts'));
    }

    public function products()
    {
        $products = Product::where('is_active', true)->with('category')->get();
        $categories = ProductCategory::withCount('products')->get();
        
        return view('products.index', compact('products', 'categories'));
    }

    public function productShow(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['features', 'screenshots', 'versions.changelogs', 'salesChannels'])
            ->firstOrFail();
            
        return view('products.show', compact('product'));
    }

    public function services()
    {
        $services = Service::where('is_active', true)->get();
        return view('services.index', compact('services'));
    }

    public function docs(Request $request)
    {
        $user = auth()->user();
        
        $purchasedProductIds = collect();
        if ($user) {
            $envatoIds = \App\Models\EnvatoPurchase::where('user_id', $user->id)->pluck('product_id')->filter();
            $licenseIds = \App\Models\LicenseKey::where('user_id', $user->id)->pluck('product_id')->filter();
            $purchasedProductIds = $envatoIds->merge($licenseIds)->unique();
        }

        $products = Product::where('is_active', true)->with('docCategories.articles')->get();
        return view('docs.index', compact('products', 'purchasedProductIds'));
    }

    public function docShow(string $productSlug, string $categorySlug, string $articleSlug)
    {
        $product = Product::where('slug', $productSlug)->where('is_active', true)->firstOrFail();
        
        $category = DocumentationCategory::where('product_id', $product->id)
            ->where('slug', $categorySlug)
            ->firstOrFail();
            
        $article = DocumentationArticle::where('documentation_category_id', $category->id)
            ->where('slug', $articleSlug)
            ->where('is_published', true)
            ->firstOrFail();

        $allCategories = DocumentationCategory::where('product_id', $product->id)
            ->with(['articles' => function($q) {
                $q->where('is_published', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('docs.show', compact('product', 'category', 'article', 'allCategories'));
    }

    public function blog(Request $request)
    {
        $query = BlogPost::published()->with(['author', 'categories']);

        // Full-text search
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->input('category'));
            });
        }

        // Tag filter
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->input('tag'));
            });
        }

        // Date filter (Year-Month)
        if ($request->filled('date')) {
            $date = $request->input('date');
            $parts = explode('-', $date);
            if (count($parts) === 2) {
                $query->whereYear('published_at', $parts[0])
                      ->whereMonth('published_at', $parts[1]);
            }
        }

        // Popularity / Date Sorting
        if ($request->input('sort') === 'popular') {
            $query->withCount('comments')->orderByDesc('comments_count');
        } else {
            $query->latest('published_at');
        }

        $posts = $query->paginate(6)->withQueryString();

        // Get sidebar widgets context
        $featuredPost = BlogPost::published()->where('is_featured', true)->latest()->first();
        $popularPosts = BlogPost::published()->withCount('comments')->orderByDesc('comments_count')->take(4)->get();
        $categories = BlogCategory::withCount('posts')->get();
        $tags = BlogTag::take(20)->get();

        return view('blog.index', compact('posts', 'featuredPost', 'popularPosts', 'categories', 'tags'));
    }

    public function blogShow(string $slug)
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->with(['author', 'categories', 'tags', 'approvedComments.user'])
            ->firstOrFail();

        // Convert Markdown content to HTML using Laravel's built-in parser
        $post->html_content = \Illuminate\Support\Str::markdown($post->content);

        // Fetch related posts (same category or tags)
        $categoryIds = $post->categories->pluck('id');
        $tagIds = $post->tags->pluck('id');
        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where(function ($q) use ($categoryIds, $tagIds) {
                $q->whereHas('categories', function ($sq) use ($categoryIds) {
                    $sq->whereIn('blog_categories.id', $categoryIds);
                })->orWhereHas('tags', function ($sq) use ($tagIds) {
                    $sq->whereIn('blog_tags.id', $tagIds);
                });
            })
            ->take(3)
            ->get();

        // Previous and Next Articles
        $prevPost = BlogPost::published()
            ->where('published_at', '<', $post->published_at)
            ->orderByDesc('published_at')
            ->first();

        $nextPost = BlogPost::published()
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at')
            ->first();

        return view('blog.show', compact('post', 'relatedPosts', 'prevPost', 'nextPost'));
    }

    public function blogCategory(string $slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        $posts = BlogPost::published()
            ->whereHas('categories', function ($q) use ($category) {
                $q->where('blog_categories.id', $category->id);
            })
            ->latest('published_at')
            ->paginate(6);

        $categories = BlogCategory::withCount('posts')->get();

        return view('blog.category', compact('category', 'posts', 'categories'));
    }

    public function blogTag(string $slug)
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();
        $posts = BlogPost::published()
            ->whereHas('tags', function ($q) use ($tag) {
                $q->where('blog_tags.id', $tag->id);
            })
            ->latest('published_at')
            ->paginate(6);

        $tags = BlogTag::all();

        return view('blog.tag', compact('tag', 'posts', 'tags'));
    }

    public function blogAuthor(int $id)
    {
        $author = User::findOrFail($id);
        $posts = BlogPost::published()
            ->where('user_id', $author->id)
            ->latest('published_at')
            ->paginate(6);

        return view('blog.author', compact('author', 'posts'));
    }

    public function storeComment(Request $request, BlogPost $post)
    {
        $rules = [
            'content' => 'required|string|min:5|max:1000',
        ];

        // Validate guest details if not authenticated
        if (!auth()->check()) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255';
        }

        $request->validate($rules);

        $commentData = [
            'blog_post_id' => $post->id,
            'content' => $request->input('content'),
            'is_approved' => auth()->check(), // Auto-approve registered users
        ];

        if (auth()->check()) {
            $commentData['user_id'] = auth()->id();
        } else {
            $commentData['name'] = $request->input('name');
            $commentData['email'] = $request->input('email');
        }

        BlogComment::create($commentData);

        $msg = auth()->check() 
            ? 'Comment posted successfully.' 
            : 'Comment submitted. It will be visible after admin approval.';

        return redirect()->back()->with('success', $msg);
    }

    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => $request->input('email')],
            ['is_active' => true]
        );

        return redirect()->back()->with('success', 'Thank you for subscribing to our technical newsletter!');
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // In a production app, we would fire a notification/email here
        return redirect()->back()->with('success', 'Your message has been sent successfully. We will get back to you shortly.');
    }

    public function about()
    {
        return view('about');
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function terms()
    {
        return view('terms');
    }

    public function refunds()
    {
        return view('refunds');
    }

    public function sitemap()
    {
        $posts = BlogPost::published()->latest('published_at')->get();
        $products = Product::where('is_active', true)->get();
        
        $content = view('sitemap', compact('posts', 'products'));
        
        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function rssFeed()
    {
        $posts = BlogPost::published()->latest('published_at')->take(20)->get();
        
        $content = view('rss', compact('posts'));
        
        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));
        $products = collect();
        $services = collect();
        $posts = collect();
        $articles = collect();
        $totalResults = 0;

        if ($query !== '') {
            // 1. Search Products
            $products = Product::where('is_active', true)
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('short_description', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })->get();

            // 2. Search Services
            $services = Service::where('is_active', true)
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('short_description', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })->get();

            // 3. Search Blog Posts
            $posts = BlogPost::published()
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('summary', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%");
                })->get();

            // 4. Search Documentation Articles
            $articles = DocumentationArticle::where('is_published', true)
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%");
                })->with(['category.product'])->get();

            $totalResults = $products->count() + $services->count() + $posts->count() + $articles->count();
        }

        return view('search', compact('products', 'services', 'posts', 'articles', 'query', 'totalResults'));
    }

    public function serviceShow(string $slug)
    {
        // 12 SEO Service configurations
        $staticServices = [
            'custom-software-development' => [
                'name' => 'Custom Software Development',
                'title' => 'Custom Software Development Services - SaaSNinja',
                'meta_description' => 'Get bespoke software engineering and development services from experienced engineers. We design scalable, high-performance applications tailored to your business.',
                'keywords' => 'custom software development, software engineering, bespoke software, web app development, enterprise systems, laravel development',
                'heading' => 'Tailored Software Solutions Built for Scale and Performance',
                'tagline' => 'We translate complex operational workflows into clean, reliable, and secure software applications.',
                'description' => 'Our custom software development services are built on the principles of modern engineering, security-first architecture, and long-term maintainability. We work closely with SMEs, large enterprises, and startups to build robust web and API architectures using Laravel, TypeScript, Vue, and React.',
                'faqs' => [
                    ['q' => 'What technologies do you specialize in?', 'a' => 'We primarily build with Laravel, PHP, JavaScript, TypeScript, Livewire, React, Vue, and Flutter. We deploy using modern cloud infrastructures like AWS, Google Cloud, and DigitalOcean.'],
                    ['q' => 'How do you handle project support after launch?', 'a' => 'Every custom software build includes a dedicated maintenance period. We also offer long-term support SLA contracts covering security patches, backups, and regular package updates.']
                ]
            ],
            'business-software-solutions' => [
                'name' => 'Business Software Solutions',
                'title' => 'Business Software Solutions & Management Systems - SaaSNinja',
                'meta_description' => 'Empower your teams with high-efficiency business management software. Simplify daily operations, improve productivity, and accelerate growth.',
                'keywords' => 'business software solutions, business management software, digital transformation, workflow automation, operational software',
                'heading' => 'Empowering Modern Teams with Intelligent Software Systems',
                'tagline' => 'Streamline daily operations and eliminate manual bottlenecks with custom-built tools.',
                'description' => 'SaaSNinja develops custom business management systems tailored to automate scheduling, resource allocation, inventory tracking, and sales analytics. Our solutions integrate cleanly with legacy setups to provide unified operations dashboards.',
                'faqs' => [
                    ['q' => 'Can your software integrate with our existing tools?', 'a' => 'Yes. We specialize in building secure API adapters and connectors to sync data between CRM, ERP, and payment platforms in real time.'],
                    ['q' => 'Is the software responsive on mobile devices?', 'a' => 'Absolutely. All our web architectures use mobile-first responsive design standards via Tailwind CSS.']
                ]
            ],
            'enterprise-software-development' => [
                'name' => 'Enterprise Software Development',
                'title' => 'Enterprise Software Development & Systems Engineering - SaaSNinja',
                'meta_description' => 'Secure, scalable, and high-performance enterprise applications built for large organizations, governments, and NGOs.',
                'keywords' => 'enterprise software development, enterprise systems, custom erp, cloud solutions, high availability, database scaling',
                'heading' => 'Robust Software Ecosystems for Large-Scale Operations',
                'tagline' => 'High-availability architectures engineered for strict performance and security standards.',
                'description' => 'Our enterprise engineering team focuses on building distributed web platforms, custom ERP modules, and high-volume billing gateways. We leverage PostgreSQL, server caching, and queue workers to guarantee uptime and fast response rates.',
                'faqs' => [
                    ['q' => 'How do you handle security and compliance?', 'a' => 'We adopt security-first design patterns, including encrypted database storage, tokenized APIs, CSRF/XSS protection, and compliance with modern data privacy frameworks.'],
                    ['q' => 'Do you provide source code ownership?', 'a' => 'Yes. Direct custom contract projects include full repository ownership and deployment configurations.']
                ]
            ],
            'website-development' => [
                'name' => 'Website Development',
                'title' => 'Website Development Services & Corporate Landing Pages - SaaSNinja',
                'meta_description' => 'Stunning, high-performance corporate websites and landing pages built to convert visitors into loyal customers.',
                'keywords' => 'website development, web development, landing pages, corporate websites, vite, tailwind css, responsive website',
                'heading' => 'High-Conversion Digital Experiences That Tell Your Story',
                'tagline' => 'Blazing-fast websites built using modern UI/UX design standards and SEO best practices.',
                'description' => 'A website is your primary digital storefront. We craft responsive corporate websites, landing pages, and interactive marketing platforms utilizing Vite, Tailwind CSS, and AlpineJS to ensure peak Performance scores and Core Web Vitals compliance.',
                'faqs' => [
                    ['q' => 'Do you optimize websites for search engines?', 'a' => 'Yes. Every website we build includes semantic HTML5 layouts, descriptive title tags, meta descriptions, XML sitemaps, and Schema structured data markup.'],
                    ['q' => 'Are your web templates customizable?', 'a' => 'Yes. We build using modular Blade or React components, allowing quick styling edits.']
                ]
            ],
            'mobile-app-development' => [
                'name' => 'Mobile App Development',
                'title' => 'Mobile App Development for iOS & Android - SaaSNinja',
                'meta_description' => 'Build native and cross-platform mobile apps. We design and program responsive user interfaces using Flutter and React Native.',
                'keywords' => 'mobile app development, iOS app development, Android app development, flutter app development, cross-platform apps',
                'heading' => 'Seamless Mobile Ecosystems Engineered for Performance',
                'tagline' => 'Connect with your audience directly on their mobile screens with fluid user experiences.',
                'description' => 'Our mobile engineering department specializes in writing clean, reliable cross-platform systems using Flutter. We create custom mobile applications integrating push notifications, local SQLite databases, biometric security, and offline support.',
                'faqs' => [
                    ['q' => 'Do you publish the apps to Apple App Store and Google Play?', 'a' => 'Yes. We manage the entire publishing workflow, including generating build credentials, configuring store listings, and complying with developer guidelines.'],
                    ['q' => 'Can the mobile app work offline?', 'a' => 'Yes. We design local database caching layers to allow core features to work without an internet connection, auto-syncing data once online.']
                ]
            ],
            'cloud-deployment' => [
                'name' => 'Cloud Deployment',
                'title' => 'Cloud Deployment Services & VPS Configurations - SaaSNinja',
                'meta_description' => 'Professional cloud deployment, VPS configuration, SSL setup, and application hosting on AWS, Google Cloud, and DigitalOcean.',
                'keywords' => 'cloud deployment, server setup, vps configuration, ssl installation, aws, digitalocean, nginx configuration',
                'heading' => 'Secure, Automated Cloud Infrastructure Setup',
                'tagline' => 'Deploy your applications on production-grade servers optimized for high traffic.',
                'description' => 'Setting up production servers requires strict security and performance configurations. Our deployment engineers containerize applications and configure high-performance web servers (Nginx) using SSH, SSL automation, and database replica controls.',
                'faqs' => [
                    ['q' => 'Which cloud providers do you support?', 'a' => 'We work with all major providers, including AWS, Google Cloud, DigitalOcean, Hetzner, Vultr, and Linode.'],
                    ['q' => 'Do you set up free SSL certificates?', 'a' => 'Yes. We configure Let\'s Encrypt SSL certificates with auto-renewal tasks to ensure your site is always HTTPS secure.']
                ]
            ],
            'hosting-solutions' => [
                'name' => 'Hosting Solutions',
                'title' => 'Managed Hosting Solutions & Server Infrastructure - SaaSNinja',
                'meta_description' => 'High-availability VPS, Dedicated, and Cloud hosting solutions managed by expert system administrators.',
                'keywords' => 'hosting solutions, managed hosting, vps hosting, dedicated hosting, cloud hosting, server administration',
                'heading' => 'Reliable Hosting Engineered for Speed and Uptime',
                'tagline' => 'Host your applications on optimized environments with dedicated resource allocations.',
                'description' => 'SaaSNinja offers managed hosting setups for our business applications. We configure custom VPS servers, implement automatic hourly database backups, and monitor resource loads to ensure maximum application loading speed.',
                'faqs' => [
                    ['q' => 'Do you include automated database backups?', 'a' => 'Yes. All our hosting setups include automated offsite backups saved securely in cloud buckets.'],
                    ['q' => 'Is there technical support for server issues?', 'a' => 'Yes, our team monitors server health and resolves hosting disruptions under standard SLA timelines.']
                ]
            ],
            'system-integration' => [
                'name' => 'System Integration',
                'title' => 'System Integration & Enterprise API Bridges - SaaSNinja',
                'meta_description' => 'Integrate CRM, ERP, payment gateways, and databases into a unified, secure system with zero data duplication.',
                'keywords' => 'system integration, api bridge, database sync, crm integration, payment gateway integration, erp link',
                'heading' => 'Unifying Disparate Software into One Connected Ecosystem',
                'tagline' => 'Remove data silos and automate cross-platform operations in real time.',
                'description' => 'Connecting software applications prevents human error and speeds up invoicing. We design custom ETL pipelines and webhook listeners to synchronize product catalog inventories, orders, support tickets, and invoicing databases.',
                'faqs' => [
                    ['q' => 'Can you integrate payment gateways like Stripe or PayPal?', 'a' => 'Yes. We are experts in implementing Stripe, PayPal, and local checkout processors with secure webhook validation.'],
                    ['q' => 'How do you handle API request limits?', 'a' => 'We build queue systems and request throttling controllers to avoid hitting rate limits on external services.']
                ]
            ],
            'api-development' => [
                'name' => 'API Development',
                'title' => 'Custom API Development & Gateway Architecture - SaaSNinja',
                'meta_description' => 'Secure, documented REST and GraphQL APIs. We develop robust gateways and microservices for modern applications.',
                'keywords' => 'api development, custom api, rest api, graphql, api documentation, microservices, webhook, laravel api',
                'heading' => 'Secure, High-Performance APIs for Mobile and Web',
                'tagline' => 'Document-first API architecture designed for clean cross-platform integrations.',
                'description' => 'Our API engineering focus targets fast database queries and strict verification schemas. We develop REST APIs, document them using OpenAPI/Swagger schemas, and protect endpoints with OAuth2, JWT tokens, and rate limiters.',
                'faqs' => [
                    ['q' => 'Do you provide API documentation?', 'a' => 'Yes. Every API we deliver includes interactive documentation (Swagger or Postman) for quick integration.'],
                    ['q' => 'How do you verify API requests?', 'a' => 'We use secure token-based authentication (Laravel Sanctum/Passport) and encrypted payload structures.']
                ]
            ],
            'business-automation' => [
                'name' => 'Business Automation',
                'title' => 'Business Automation & Workflow Engineering - SaaSNinja',
                'meta_description' => 'Automate manual business tasks, email marketing, client invoicing, and custom reporting to optimize productivity.',
                'keywords' => 'business automation, workflow automation, automated billing, invoice generator, reporting system, digital transformation',
                'heading' => 'Automating Core Tasks to Maximize Operational Efficiency',
                'tagline' => 'Spend less time on repetitive admin work and more time growing your business.',
                'description' => 'We build automated workflows to handle invoice generation, customer notification emails, recurring billing cycles, inventory thresholds, and data aggregation dashboards, allowing your organization to operate efficiently.',
                'faqs' => [
                    ['q' => 'What kind of business workflows can you automate?', 'a' => 'We automate customer onboarding, digital deliveries, billing reminders, inventory reordering alerts, and custom PDF reporting.'],
                    ['q' => 'Will this require manual server tasks?', 'a' => 'No. We configure background Cron tasks and queue systems (Laravel Horizon) to run all operations automatically.']
                ]
            ],
            'software-maintenance' => [
                'name' => 'Software Maintenance',
                'title' => 'Software Maintenance, Security Patches & Upgrades - SaaSNinja',
                'meta_description' => 'Keep your software applications fast, secure, and compatible with the latest PHP and database versions.',
                'keywords' => 'software maintenance, security patching, library upgrade, php upgrade, bug fixes, website maintenance',
                'heading' => 'Continuous Security and Optimization for Your Applications',
                'tagline' => 'Proactive server management, codebase updates, and performance tuning.',
                'description' => 'Outdated packages lead to security breaches and slow page speeds. Our software maintenance plans include regular PHP updates, composer package security patches, database index optimizations, and server log cleanups.',
                'faqs' => [
                    ['q' => 'How often do you check for updates?', 'a' => 'We perform weekly security audits and apply patches proactively on staging environments before deploying to production.'],
                    ['q' => 'What happens if the website encounters a crash?', 'a' => 'Our monitoring system notifies our support queue instantly, allowing us to roll back changes or patch memory bugs.']
                ]
            ],
            'software-consulting' => [
                'name' => 'Software Consulting',
                'title' => 'Technical Consulting & Software Architecture Audit - SaaSNinja',
                'meta_description' => 'Expert software consulting, codebase audits, database structure design, and technology roadmap planning.',
                'keywords' => 'software consulting, technical consulting, software architecture audit, codebase audit, database design, technology consulting',
                'heading' => 'Strategic Technology Architecture and System Consulting',
                'tagline' => 'Align your software infrastructure with your business objectives.',
                'description' => 'Choosing the wrong tech stack or database structure can cost thousands. Our experienced software consultants evaluate your codebase, map database structures, and provide a clear technological roadmap to ensure your team is set up for scale.',
                'faqs' => [
                    ['q' => 'Do you provide codebase security audits?', 'a' => 'Yes. We analyze security standards, verify storage configurations, and evaluate external dependencies for potential threats.'],
                    ['q' => 'Can you help us choose a tech stack for a new app?', 'a' => 'Yes. We recommend optimal databases, hosting architectures, and frontend/backend frameworks based on budget and goals.']
                ]
            ]
        ];

        // Check if slug matches static config
        if (array_key_exists($slug, $staticServices)) {
            $serviceData = $staticServices[$slug];
            $serviceData['slug'] = $slug;
            $service = (object) $serviceData;
        } else {
            // Check database fallback
            $dbService = Service::where('slug', $slug)->where('is_active', true)->firstOrFail();
            $service = (object) [
                'slug' => $dbService->slug,
                'name' => $dbService->name,
                'title' => $dbService->name . ' - SaaSNinja Service',
                'meta_description' => $dbService->short_description,
                'keywords' => 'custom service, saasninja support, installation',
                'heading' => $dbService->name,
                'tagline' => 'Professional execution and support for your business tools.',
                'description' => $dbService->description,
                'faqs' => [
                    ['q' => 'How can I order this service?', 'a' => 'You can request this service by logging into the customer portal and submitting a Service Request ticket.'],
                    ['q' => 'Is support included?', 'a' => 'Yes, our installation and custom works include initial onboarding and configuration validation support.']
                ]
            ];
        }

        // Get 3 active related products to display on the sidebar
        $relatedProducts = Product::where('is_active', true)->take(3)->get();

        return view('services.show', compact('service', 'relatedProducts'));
    }

    public function seoCategoryShow(string $slug)
    {
        // 8 SEO Software Category configurations
        $categories = [
            'law-firm-management-software' => [
                'name' => 'Law Firm Management Software',
                'title' => 'Law Firm Management Software & Legal Practice CRM - SaaSNinja',
                'meta_description' => 'Simplify legal practice management. Track cases, organize client files, automate invoices, and secure legal documents with custom software.',
                'keywords' => 'law firm management software, legal practice software, case management, attorney CRM, legal invoicing, lawyer booking system',
                'heading' => 'Legal Practice Software Built for Security and Efficiency',
                'subheading' => 'Help your attorneys organize cases, manage billable hours, and protect sensitive client records.',
                'problems' => [
                    'Disorganized case files scattered across multiple email threads and storage buckets.',
                    'Inaccurate billable hours tracking leading to lost law firm revenues.',
                    'Security risks associated with storing confidential legal documents on unencrypted hosts.',
                    'Delayed client invoicing and complex billing templates.'
                ],
                'features' => [
                    'Case Lifecycle Tracking' => 'Centralized timeline showing case status, milestones, documents, and associated attorneys.',
                    'Secure Document Locker' => 'Encrypted cloud storage with role-based folder access controls and electronic signatures.',
                    'Legal Invoicing & Hours' => 'Log hours dynamically and generate clean, compliance-ready invoices directly from client folders.',
                    'Calendar Integrations' => 'Synchronized hearing schedules, client consult bookings, and court dates across platforms.'
                ],
                'industries' => 'Law Firms, Independent Attorneys, Legal Advisory Agencies, Corporate Compliance Teams.',
                'faqs' => [
                    ['q' => 'Is client data encrypted?', 'a' => 'Yes. We apply security-first designs including encrypted database columns for personal client details and SSL secured document transmittals.'],
                    ['q' => 'Can we customize billing rates per attorney?', 'a' => 'Absolutely. The CRM billing system supports role-based and case-specific custom hourly rates.']
                ],
                'product_slug' => 'saasninja-crm-portal'
            ],
            'hospital-management-system' => [
                'name' => 'Hospital Management System',
                'title' => 'Hospital Management System & Healthcare Portals - SaaSNinja',
                'meta_description' => 'Streamline healthcare administration. Manage patient admissions, doctor schedules, billing, and electronic health records (EHR) securely.',
                'keywords' => 'hospital management system, healthcare software, patient portal, doctor scheduling, electronic health records, clinic system',
                'heading' => 'Patient-First Healthcare Portals and Hospital Administration',
                'subheading' => 'Connect clinical workflows, doctor availability, and billing into a unified, secure system.',
                'problems' => [
                    'Long patient wait times due to manual scheduling and appointment clashes.',
                    'Fragmented patient histories leading to slow clinical diagnosis.',
                    'Billing errors across medical tests, prescriptions, and ward stays.',
                    'Security risks in health data transfer.'
                ],
                'features' => [
                    'Patient Registrations' => 'Digital intake forms capturing medical histories, allergies, and insurance records.',
                    'Doctor Appointments' => 'Smart calendar checking doctor availability and automated booking notifications.',
                    'EHR Integrations' => 'Centralized patient health dashboard tracking prescriptions, lab results, and diagnostic notes.',
                    'Medical Invoicing' => 'Accurate itemized bills mapping clinic visits, tests, and prescriptions.'
                ],
                'industries' => 'Hospitals, Private Clinics, Specialist Practices, Diagnostic Laboratories.',
                'faqs' => [
                    ['q' => 'How does the system ensure patient privacy?', 'a' => 'Our applications are designed with strict access control layers, restricting medical file access solely to authorized doctors and nurses.'],
                    ['q' => 'Can patients view their lab results online?', 'a' => 'Yes. We build patient portal sections allowing patients to download PDF reports and securely view doctor recommendations.']
                ],
                'product_slug' => 'saasninja-crm-portal'
            ],
            'isp-billing-software' => [
                'name' => 'ISP Billing Software',
                'title' => 'ISP Billing Systems & Internet Service Provider Software - SaaSNinja',
                'meta_description' => 'Automate ISP billing, subscriber bandwidth packages, ticket tracking, and router integrations with secure custom apps.',
                'keywords' => 'isp billing software, internet service provider billing, subscriber manager, bandwidth package software, isp ticket system',
                'heading' => 'ISP Subscriber Management and Billing Automation',
                'subheading' => 'Automate recurring billing, bandwidth provisioning, and support ticketing for local and regional ISPs.',
                'problems' => [
                    'Manual subscription suspension for unpaid internet service accounts.',
                    'Complicated package switching and custom pricing models.',
                    'Slow support response for fiber line disruptions.',
                    'Inefficient billing tracking for prepaid and postpaid users.'
                ],
                'features' => [
                    'Subscriber Accounts' => 'Profiles tracking subscriber fiber boxes, router IP configurations, and subscription status.',
                    'Automated Billing' => 'Auto-generate monthly billing invoices and auto-suspend accounts if payment is overdue.',
                    'Ticket Dispatcher' => 'Speed up response times by routing network issues to field technicians.',
                    'Bandwidth Controls' => 'Integrate with router APIs to sync package bandwidth limits dynamically.'
                ],
                'industries' => 'Internet Service Providers (ISPs), Managed Service Providers (MSPs), WISP operators.',
                'faqs' => [
                    ['q' => 'Does the system integrate with payment gateways?', 'a' => 'Yes. We implement Stripe, PayPal, and regional mobile payment processors to automate invoice settlements.'],
                    ['q' => 'How does auto-suspension work?', 'a' => 'A daily cron task checks invoice statuses. Accounts past their grace period are marked suspended, triggering an API call to block internet access.']
                ],
                'product_slug' => 'saasninja-crm-portal'
            ],
            'point-of-sale-software' => [
                'name' => 'Point of Sale (POS) Software',
                'title' => 'Point of Sale (POS) Software & Inventory Systems - SaaSNinja',
                'meta_description' => 'Boost retail checkout efficiency. Manage store inventories, track sales, print invoices, and review analytics in real time.',
                'keywords' => 'point of sale software, retail pos, inventory system, barcode scanner pos, sales reports, store management',
                'heading' => 'POS Software Designed for Fast Checkout and Stock Sync',
                'subheading' => 'Keep your inventory, retail sales, and checkout counters in perfect sync.',
                'problems' => [
                    'Slow checkout queues due to manual item lookup.',
                    'Inaccurate inventory levels leading to stockouts or overstocking.',
                    'Lack of real-time sales reporting across multiple store locations.',
                    'Complex invoice printing configurations.'
                ],
                'features' => [
                    'Barcode Scanning' => 'Add items to carts instantly using standard USB or Bluetooth barcode scanners.',
                    'Inventory Alerts' => 'Set low-stock thresholds to auto-notify buyers when items need reordering.',
                    'Multi-Store Sync' => 'Consolidate sales records and inventory levels across multiple retail outlets.',
                    'Sales Telemetry' => 'Intuitive dashboards displaying top-selling products, margins, and peak sales hours.'
                ],
                'industries' => 'Retail Stores, Supermarkets, Boutique Shops, Wholesale Outlets, SMEs.',
                'faqs' => [
                    ['q' => 'Can this software run on tablet devices?', 'a' => 'Yes. The interface is optimized to run smoothly on iPads, Android tablets, and touch-screen monitors.'],
                    ['q' => 'Does it support barcode label generation?', 'a' => 'Yes. You can generate and print barcode stickers directly from the inventory dashboard.']
                ],
                'product_slug' => 'saasninja-crm-portal'
            ],
            'crm-software' => [
                'name' => 'CRM Software',
                'title' => 'Customer Relationship Management (CRM) Software - SaaSNinja',
                'meta_description' => 'Build customer loyalty. Manage lead funnels, organize customer contact logs, handle support tickets, and track sales channels.',
                'keywords' => 'crm software, customer relationship management, sales crm, support ticketing, client portal, lead tracker',
                'heading' => 'Customer Portals and Sales CRM Built for Digital Teams',
                'subheading' => 'Centralize customer support, active license allocations, and lead workflows.',
                'problems' => [
                    'Missed sales opportunities due to slow lead response times.',
                    'Support tickets lost in email inboxes causing client frustration.',
                    'Inefficient licensing logs for digital product distribution.',
                    'Scattered customer communication records.'
                ],
                'features' => [
                    'Client Portals' => 'Secure dashboard for customers to download purchases, view keys, and submit support tickets.',
                    'Ticketing Queue' => 'Organize requests with priority tags, status updates, and internal staff discussions.',
                    'Licensing Manager' => 'Auto-generate and verify license activation domains for software packages.',
                    'Contact Log' => 'History of all correspondence, purchases, and requests per customer profile.'
                ],
                'industries' => 'Software Developers, SaaS Startups, Agencies, Client-focused Service Teams.',
                'faqs' => [
                    ['q' => 'How does client support ticketing work?', 'a' => 'Clients log into their portal to submit tickets. Staff respond via the admin panel, sending email alerts to both parties.'],
                    ['q' => 'Can we customize the customer registration fields?', 'a' => 'Yes, the portal registration and profiles can be customized to collect specific business requirements.']
                ],
                'product_slug' => 'saasninja-crm-portal'
            ],
            'erp-software' => [
                'name' => 'ERP Software',
                'title' => 'Enterprise Resource Planning (ERP) Systems - SaaSNinja',
                'meta_description' => 'Unify finance, HR, inventory, procurement, and operations in a single secure Enterprise Resource Planning database.',
                'keywords' => 'erp software, enterprise resource planning, business erp, procurement system, finance database, hr management',
                'heading' => 'Unified Enterprise Resource Planning for Operations',
                'subheading' => 'Align resources, track capital, and automate reporting across all corporate departments.',
                'problems' => [
                    'Inefficient communication between finance, procurement, and warehouse teams.',
                    'Double entries and errors across manual spreadsheets.',
                    'Lack of consolidated financial reporting for executives.',
                    'Procurement delays due to manual approval chains.'
                ],
                'features' => [
                    'Financial Ledgers' => 'Accurate double-entry bookkeeping, tax allocations, and automated budget sheets.',
                    'Procurement Flows' => 'Manage purchase requests, supplier ratings, and approval chains digitally.',
                    'HR & Payroll' => 'Log employee attendance, manage leave requests, and calculate payroll metrics.',
                    'Operations Analytics' => 'Consolidated reports comparing department costs, margins, and operational delays.'
                ],
                'industries' => 'Manufacturing Firms, Large Distributors, Construction Corporations, Multi-brand Enterprises.',
                'faqs' => [
                    ['q' => 'Is this software hosted on-premise or in the cloud?', 'a' => 'We configure the ERP system to run either on secure private clouds (AWS/GCP) or on-premise servers based on compliance requirements.'],
                    ['q' => 'Can we restrict data access based on departments?', 'a' => 'Yes. Every module has granular permissions restricting access to finance, HR, or procurement tools based on staff roles.']
                ],
                'product_slug' => 'saasninja-crm-portal'
            ],
            'school-management-system' => [
                'name' => 'School Management System',
                'title' => 'School Management Systems & Academy Portals - SaaSNinja',
                'meta_description' => 'Organize classes, track student grades, automate tuition fee collection, and coordinate teacher timetables with custom education software.',
                'keywords' => 'school management system, academy portal, student database, tuition fee manager, class timetable, education software',
                'heading' => 'Academy Administration and Student Performance Portals',
                'subheading' => 'Keep parents, teachers, and student records connected in one secure digital academy.',
                'problems' => [
                    'Inefficient grade reporting and manual transcript calculation.',
                    'Lost school tuition fees due to manual invoice tracking.',
                    'Teacher timetable conflicts and classroom scheduling clashes.',
                    'Lack of direct communication channels between parents and teachers.'
                ],
                'features' => [
                    'Student Databases' => 'Profiles tracking student attendance, grades, medical logs, and emergency contacts.',
                    'Tuition Portal' => 'Generate student fee invoices, accept online tuition payments, and track outstanding balances.',
                    'Timetable Engines' => 'Coordinate school hours, class locations, and teacher schedules without conflicts.',
                    'Parent Dashboards' => 'Secure portal for parents to check grades, attendance logs, and pay fees.'
                ],
                'industries' => 'Schools, Colleges, Language Academies, Training Institutes, Coaching Centers.',
                'faqs' => [
                    ['q' => 'Can teachers input student grades remotely?', 'a' => 'Yes. Teachers log into their profiles to update attendance records, grade sheets, and assignment reviews.'],
                    ['q' => 'Does the tuition portal send billing alerts?', 'a' => 'Yes. The system automatically sends email reminders to parents before fee deadlines.']
                ],
                'product_slug' => 'saasninja-crm-portal'
            ],
            'property-management-software' => [
                'name' => 'Property Management Software',
                'title' => 'Property Management Software & Tenant Portals - SaaSNinja',
                'meta_description' => 'Simplify property rentals. Track tenant leases, automate rent invoicing, schedule property maintenance, and review yield reports.',
                'keywords' => 'property management software, tenant portal, rental tracker, lease agreement, maintenance requests, rent invoice',
                'heading' => 'Property Rental tracking and Tenant Support Portals',
                'subheading' => 'Automate lease billing, track security deposits, and resolve maintenance tickets.',
                'problems' => [
                    'Unpaid rent invoices and delayed tenant payment notifications.',
                    'Unresolved property maintenance requests causing tenant complaints.',
                    'Lease agreement expiries slipping past property managers.',
                    'Complex financial reporting across multiple rental units.'
                ],
                'features' => [
                    'Lease Tracking' => 'Profiles mapping rental agreements, security deposits, tenant identities, and expiries.',
                    'Rent Automation' => 'Monthly automated rent invoicing with regional checkout options.',
                    'Maintenance Desk' => 'Tenants submit repair tickets. Property managers assign plumbers or electricians.',
                    'Yield Portfolios' => 'Financial dashboards comparing property values, rental yields, and repair costs.'
                ],
                'industries' => 'Property Managers, Real Estate Agencies, Apartment Complexes, Commercial Landlords.',
                'faqs' => [
                    ['q' => 'Can tenants submit repair photos?', 'a' => 'Yes. Tenants can upload photos and describe maintenance issues directly inside their tenant portal.',],
                    ['q' => 'How does the lease expiry reminder work?', 'a' => 'The system triggers automated email notifications 30 and 60 days before lease agreements expire.']
                ],
                'product_slug' => 'saasninja-crm-portal'
            ]
        ];

        // Retrieve configuration
        if (!array_key_exists($slug, $categories)) {
            abort(404);
        }

        $categoryData = $categories[$slug];
        $categoryData['slug'] = $slug;
        $category = (object) $categoryData;

        // Retrieve specific product model linked to this category if database matches, or fallback to CRM product
        $relatedProduct = Product::where('slug', $category->product_slug)->where('is_active', true)->first();
        if (!$relatedProduct) {
            $relatedProduct = Product::where('is_active', true)->first();
        }

        return view('seo.category', compact('category', 'relatedProduct'));
    }
}

