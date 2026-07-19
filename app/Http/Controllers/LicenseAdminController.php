<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\SalesChannel;
use App\Models\LicenseProvider;
use App\Models\License;
use App\Models\ProductSalesChannel;
use App\Models\EnvatoPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LicenseAdminController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $users = User::orderBy('name')->get();
        
        $channels = SalesChannel::withCount('products')->get();
        $providers = LicenseProvider::withCount('licenses')->get();
        $licenses = License::with(['user', 'product', 'provider'])->latest()->paginate(15);

        // Fetch distribution list
        $distributions = ProductSalesChannel::with(['product', 'channel'])->get();

        // Calculate statistics
        $stats = [
            'total_envato' => EnvatoPurchase::count(),
            'total_direct' => License::count(),
            'active_direct' => License::where('is_active', true)->count(),
        ];

        return view('admin.licensing.index', compact('products', 'users', 'channels', 'providers', 'licenses', 'distributions', 'stats'));
    }

    public function storeChannel(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:sales_channels,slug|max:255',
        ]);

        SalesChannel::create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Sales channel created successfully.');
    }

    public function storeProvider(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:license_providers,slug|max:255',
        ]);

        LicenseProvider::create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'License provider created successfully.');
    }

    public function assignProductChannel(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'sales_channel_id' => 'required|exists:sales_channels,id',
            'purchase_url' => 'nullable|url',
            'price' => 'nullable|numeric|min:0',
            'priority' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'external_product_id' => 'nullable|string',
        ]);

        // Check if assignment exists
        ProductSalesChannel::updateOrCreate(
            [
                'product_id' => $request->input('product_id'),
                'sales_channel_id' => $request->input('sales_channel_id'),
            ],
            [
                'purchase_url' => $request->input('purchase_url'),
                'price' => $request->input('price'),
                'priority' => $request->input('priority'),
                'status' => $request->input('status'),
                'external_product_id' => $request->input('external_product_id'),
            ]
        );

        return redirect()->back()->with('success', 'Product assigned to channel successfully.');
    }

    public function generateLicenseKey(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'license_provider_id' => 'required|exists:license_providers,id',
            'duration_months' => 'required|integer|min:1',
        ]);

        $key = 'SN-' . strtoupper(\Illuminate\Support\Str::random(4)) . '-' . strtoupper(\Illuminate\Support\Str::random(4)) . '-' . strtoupper(\Illuminate\Support\Str::random(4)) . '-' . strtoupper(\Illuminate\Support\Str::random(4));

        License::create([
            'user_id' => $request->input('user_id'),
            'product_id' => $request->input('product_id'),
            'license_provider_id' => $request->input('license_provider_id'),
            'license_key' => $key,
            'purchased_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonths($request->input('duration_months')),
            'support_expires_at' => Carbon::now()->addMonths($request->input('duration_months')),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', "License key generated successfully: {$key}");
    }

    public function toggleLicense(License $license)
    {
        $license->update([
            'is_active' => !$license->is_active
        ]);

        return redirect()->back()->with('success', 'License status updated successfully.');
    }
}
