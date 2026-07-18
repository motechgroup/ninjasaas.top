<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EnvatoPurchase;
use App\Models\SupportTicket;
use App\Models\ServiceRequest;
use App\Models\Product;
use App\Models\Setting;
use App\Models\BlogPost;
use App\Models\DocumentationArticle;
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
        $users = User::with('roles')->paginate(15);
        return view('admin.users', compact('users'));
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
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except('_token');
        
        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Global system settings updated.');
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
        $products = Product::with(['category'])->get();
        return view('admin.products', compact('products'));
    }

    public function cms()
    {
        $blogPostsCount = BlogPost::count();
        $docArticlesCount = DocumentationArticle::count();
        
        $blogPosts = BlogPost::latest()->take(5)->get();
        $docArticles = DocumentationArticle::with('category.product')->latest()->take(5)->get();

        return view('admin.cms', compact('blogPostsCount', 'docArticlesCount', 'blogPosts', 'docArticles'));
    }
}
