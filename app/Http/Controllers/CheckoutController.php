<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\License;
use App\Models\LicenseProvider;
use App\Models\SalesChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function show(Product $product)
    {
        // Resolve price from SaaSNinja Direct channel
        $directChannel = SalesChannel::where('slug', 'saasninja')->first();
        $price = 49.00;
        if ($directChannel) {
            $mapping = $product->salesChannels()->where('sales_channel_id', $directChannel->id)->first();
            if ($mapping && $mapping->pivot->price) {
                $price = (float) $mapping->pivot->price;
            }
        }

        return view('checkout.show', compact('product', 'price'));
    }

    public function process(Request $request, Product $product)
    {
        $request->validate([
            'card_name' => 'required|string|max:255',
            'card_number' => 'required|string|min:16|max:19',
            'card_expiry' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/?([0-9]{2})$/'],
            'card_cvc' => 'required|string|min:3|max:4',
        ]);

        // Resolve price
        $directChannel = SalesChannel::where('slug', 'saasninja')->first();
        $price = 49.00;
        if ($directChannel) {
            $mapping = $product->salesChannels()->where('sales_channel_id', $directChannel->id)->first();
            if ($mapping && $mapping->pivot->price) {
                $price = (float) $mapping->pivot->price;
            }
        }

        // Generate license key
        $key = 'SN-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        
        $provider = LicenseProvider::where('slug', 'saasninja')->first();

        $license = License::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'license_provider_id' => $provider ? $provider->id : 1,
            'license_key' => $key,
            'purchased_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addYear(),
            'support_expires_at' => Carbon::now()->addYear(),
            'is_active' => true,
        ]);

        return redirect()->route('checkout.success', $license->id)
            ->with('success_payment', true);
    }

    public function success(License $license)
    {
        if ($license->user_id !== auth()->id()) {
            abort(403);
        }

        $product = $license->product;
        // Get download URL of latest version if exists
        $latestVersion = $product->versions()->orderByDesc('release_date')->first();

        return view('checkout.success', compact('license', 'product', 'latestVersion'));
    }
}
