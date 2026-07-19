<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EnvatoPurchase;
use App\Models\SupportTicket;
use App\Models\ServiceRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SalesChannel;
use App\Models\Setting;
use App\Models\BlogPost;
use App\Models\DocumentationArticle;
use App\Models\DocumentationCategory;
use App\Enums\ServiceRequestStatus;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AdminController extends Controller
{
    public function dashboard()
    {
        $usersCount = User::count();
        $purchasesCount = EnvatoPurchase::count();
        $ticketsCount = SupportTicket::where('status', '!=', 'closed')->count();
        $requestsCount = ServiceRequest::where('status', ServiceRequestStatus::PENDING)->count();

        $recentRequests = ServiceRequest::with(['user', 'service'])->latest()->take(5)->get();
        
        // Retrieve Spatie Activity Logs
        $activityLogs = Activity::with('causer')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'usersCount', 
            'purchasesCount', 
            'ticketsCount', 
            'requestsCount', 
            'recentRequests', 
            'activityLogs'
        ));
    }

    public function users()
    {
        $staff = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Super Admin', 'Support Staff', 'Content Manager']);
        })->with('roles')->get();

        $customers = User::whereDoesntHave('roles', function($q) {
            $q->whereIn('name', ['Super Admin', 'Support Staff', 'Content Manager']);
        })->withCount('purchases')->latest()->paginate(15);

        return view('admin.users', compact('staff', 'customers'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|in:Super Admin,Support Staff,Content Manager,Customer'
        ]);

        $role = $request->input('role');
        
        // Remove existing roles
        $user->syncRoles([]);
        
        if ($role !== 'Customer') {
            $user->assignRole($role);
        }

        return redirect()->back()->with('success', "Role for {$user->name} updated to {$role}.");
    }

    public function settings()
    {
        $settings = Setting::all();
        $emailTemplates = \App\Models\EmailTemplate::all();
        return view('admin.settings', compact('settings', 'emailTemplates'));
    }

    public function updateSettings(Request $request)
    {
        if ($request->has('templates')) {
            $templatesData = $request->input('templates');
            foreach ($templatesData as $id => $tplData) {
                $template = \App\Models\EmailTemplate::find($id);
                if ($template) {
                    $template->update([
                        'subject' => $tplData['subject'],
                        'body' => $tplData['body'],
                    ]);
                }
            }
        }

        $data = $request->except(['_token', 'templates']);
        
        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Global system settings updated.');
    }

    public function testSmtp(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            \Illuminate\Support\Facades\Mail::raw('This is a confirm test email confirming that SaaSNinja SMTP settings are active and connected successfully.', function ($message) use ($request) {
                $message->to($request->input('test_email'))
                    ->subject('SaaSNinja SMTP Connection Success');
            });

            return redirect()->back()->with('success', 'Test email dispatched successfully! Verify your inbox.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'SMTP connection failed: ' . $e->getMessage());
        }
    }

    public function updateEmailTemplate(Request $request, \App\Models\EmailTemplate $emailTemplate)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $emailTemplate->update($request->only('subject', 'body'));

        return redirect()->back()->with('success', 'Email template updated successfully.');
    }

    public function purchases()
    {
        $purchases = EnvatoPurchase::with(['user', 'item'])->latest()->paginate(15);
        return view('admin.purchases', compact('purchases'));
    }

    public function tickets()
    {
        return view('admin.tickets');
    }

    public function services()
    {
        $requests = ServiceRequest::with(['user', 'service', 'product'])->latest()->paginate(15);
        return view('admin.services', compact('requests'));
    }

    public function updateServiceRequest(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate([
            'quote_price' => 'nullable|numeric|min:0',
            'status' => 'required|string',
            'admin_notes' => 'nullable|string',
        ]);

        $serviceRequest->update([
            'quote_price' => $request->input('quote_price'),
            'status' => ServiceRequestStatus::from($request->input('status')),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        return redirect()->back()->with('success', 'Custom service request updated successfully.');
    }

    public function products()
    {
        $products = Product::with(['category', 'salesChannels'])->get();
        $categories = ProductCategory::all();
        $channels = SalesChannel::where('is_active', true)->get();
        return view('admin.products', compact('products', 'categories', 'channels'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'demo_url' => 'nullable|url',
            'buy_url' => 'nullable|url',
            'docs_url' => 'nullable|string',
            'version' => 'required|string|max:50',
            'envato_item_id' => 'nullable|string|max:100',
            'is_active' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
            'image_url' => 'nullable|string|max:1000',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $product = Product::create($data);

        // Sync sales channels / marketplaces
        $channelsData = $request->input('channels', []);
        $syncData = [];
        foreach ($channelsData as $channelId => $channelParams) {
            if (!empty($channelParams['enabled'])) {
                $syncData[$channelId] = [
                    'purchase_url' => $channelParams['purchase_url'] ?? null,
                    'price' => $channelParams['price'] ?? null,
                    'priority' => $channelParams['priority'] ?? 0,
                    'status' => 'active',
                    'external_product_id' => $channelParams['external_product_id'] ?? null,
                ];
            }
        }
        $product->salesChannels()->sync($syncData);

        return redirect()->back()->with('success', 'Product added to catalog and channels mapped successfully.');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,' . $product->id . '|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'demo_url' => 'nullable|url',
            'buy_url' => 'nullable|url',
            'docs_url' => 'nullable|string',
            'version' => 'required|string|max:50',
            'envato_item_id' => 'nullable|string|max:100',
            'is_active' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
            'image_url' => 'nullable|string|max:1000',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $product->update($data);

        // Sync sales channels / marketplaces
        $channelsData = $request->input('channels', []);
        $syncData = [];
        foreach ($channelsData as $channelId => $channelParams) {
            if (!empty($channelParams['enabled'])) {
                $syncData[$channelId] = [
                    'purchase_url' => $channelParams['purchase_url'] ?? null,
                    'price' => $channelParams['price'] ?? null,
                    'priority' => $channelParams['priority'] ?? 0,
                    'status' => 'active',
                    'external_product_id' => $channelParams['external_product_id'] ?? null,
                ];
            }
        }
        $product->salesChannels()->sync($syncData);

        return redirect()->back()->with('success', 'Product specifications and channel mappings updated successfully.');
    }

    public function destroyProduct(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    public function cms()
    {
        $blogPostsCount = BlogPost::count();
        $docArticlesCount = DocumentationArticle::count();
        
        $blogPosts = BlogPost::latest()->get();
        $docArticles = DocumentationArticle::with('category.product')->latest()->get();
        $docCategories = DocumentationCategory::with('product')->get();

        return view('admin.cms', compact('blogPostsCount', 'docArticlesCount', 'blogPosts', 'docArticles', 'docCategories'));
    }

    public function storeBlogPost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:blog_posts,slug|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'is_published' => 'required|boolean',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        BlogPost::create($data);

        return redirect()->back()->with('success', 'Blog post created successfully.');
    }

    public function updateBlogPost(Request $request, BlogPost $blogPost)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:blog_posts,slug,' . $blogPost->id . '|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'is_published' => 'required|boolean',
        ]);

        $blogPost->update($request->all());

        return redirect()->back()->with('success', 'Blog post updated successfully.');
    }

    public function destroyBlogPost(BlogPost $blogPost)
    {
        $blogPost->delete();
        return redirect()->back()->with('success', 'Blog post deleted successfully.');
    }

    public function storeDocArticle(Request $request)
    {
        $request->validate([
            'documentation_category_id' => 'required|exists:documentation_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:documentation_articles,slug|max:255',
            'content' => 'required|string',
            'sort_order' => 'required|integer',
            'is_published' => 'required|boolean',
        ]);

        DocumentationArticle::create($request->all());

        return redirect()->back()->with('success', 'Doc article created successfully.');
    }

    public function updateDocArticle(Request $request, DocumentationArticle $docArticle)
    {
        $request->validate([
            'documentation_category_id' => 'required|exists:documentation_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:documentation_articles,slug,' . $docArticle->id . '|max:255',
            'content' => 'required|string',
            'sort_order' => 'required|integer',
            'is_published' => 'required|boolean',
        ]);

        $docArticle->update($request->all());

        return redirect()->back()->with('success', 'Doc article updated successfully.');
    }

    public function destroyDocArticle(DocumentationArticle $docArticle)
    {
        $docArticle->delete();
        return redirect()->back()->with('success', 'Doc article deleted successfully.');
    }
}
