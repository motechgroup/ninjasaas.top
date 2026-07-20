<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SalesChannel;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoRouteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic settings or structure required for layout
        \App\Models\Setting::set('seo_title', 'SaaSNinja Premium Software');
        \App\Models\Setting::set('seo_description', 'SaaSNinja develops high performance systems.');

        // Seed a product category
        $category = ProductCategory::create([
            'name' => 'CRM Suite',
            'slug' => 'crm-suite',
            'description' => 'Customer relationship platforms'
        ]);

        // Seed a target product linked to our SEO categories
        $product = Product::create([
            'name' => 'SaaSNinja CRM Portal',
            'slug' => 'saasninja-crm-portal',
            'short_description' => 'High-efficiency customer portal management system.',
            'description' => 'Complete customer ticketing, licensing tracking, and email analytics.',
            'product_category_id' => $category->id,
            'is_active' => true,
            'version' => '1.0.0',
            'buy_url' => 'https://envato.com',
            'demo_url' => 'https://demo.example.com'
        ]);

        // Connect sales channel to product
        $channel = SalesChannel::create([
            'name' => 'SaaSNinja Direct',
            'slug' => 'saasninja-direct',
            'is_active' => true
        ]);
        $product->salesChannels()->attach($channel->id, [
            'price' => 49.00,
            'status' => 'active',
            'priority' => 1,
            'purchase_url' => 'https://example.com/checkout'
        ]);

        // Seed a DB service fallback
        Service::create([
            'name' => 'Application Installation',
            'slug' => 'application-installation',
            'short_description' => 'Professional installer assistance for PHP systems.',
            'description' => 'We assist with configuration, virtual host setups, and secure deployment.',
            'price' => 29.00,
            'is_active' => true
        ]);
    }

    public function test_seo_category_pages_render_successfully(): void
    {
        $categories = [
            '/law-firm-management-software' => 'Law Firm Management Software',
            '/hospital-management-system' => 'Hospital Management System',
            '/isp-billing-software' => 'ISP Billing Software',
            '/point-of-sale-software' => 'Point of Sale (POS) Software',
            '/crm-software' => 'CRM Software',
            '/erp-software' => 'ERP Software',
            '/school-management-system' => 'School Management System',
            '/property-management-software' => 'Property Management Software'
        ];

        foreach ($categories as $url => $title) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $response->assertSee($title);
            $response->assertSee('SaaSNinja CRM Portal');
            $response->assertSee('Explore Product');
        }
    }

    public function test_seo_services_detail_pages_render_successfully(): void
    {
        $services = [
            '/services/custom-software-development' => 'Custom Software Development',
            '/services/website-development' => 'Website Development',
            '/services/mobile-app-development' => 'Mobile App Development',
            '/services/api-development' => 'API Development',
            '/services/hosting-solutions' => 'Hosting Solutions',
            '/services/application-installation' => 'Application Installation' // db service
        ];

        foreach ($services as $url => $name) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $response->assertSee($name);
            $response->assertSee('Frequently Asked Questions');
        }
    }

    public function test_global_search_returns_highlighted_results(): void
    {
        $response = $this->get('/search?q=CRM');
        $response->assertStatus(200);
        $response->assertSee('SaaSNinja');
        $response->assertSee('Portal');
        $response->assertSee('<mark class="bg-yellow-200 dark:bg-yellow-900/60 text-slate-900 dark:text-white px-1 py-0.5 rounded font-semibold">CRM</mark>', false);

        // Empty query
        $responseEmpty = $this->get('/search');
        $responseEmpty->assertStatus(200);
        $responseEmpty->assertSee('Search SaaSNinja Software');
    }

    public function test_xml_sitemap_returns_valid_content(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee('/law-firm-management-software');
        $response->assertSee('/services/custom-software-development');
        $response->assertSee('/products/saasninja-crm-portal');
    }
}
