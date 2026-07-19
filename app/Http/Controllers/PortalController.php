<?php

namespace App\Http\Controllers;

use App\Models\EnvatoPurchase;
use App\Models\SupportTicket;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function dashboard()
    {
        // If the user has administrative roles, redirect them to the Admin Dashboard instead!
        if (auth()->user()->hasAnyRole(['Super Admin', 'Support Staff', 'Content Manager'])) {
            return redirect()->route('admin.dashboard');
        }

        $userId = auth()->id();
        $purchasesCount = EnvatoPurchase::where('user_id', $userId)->count();
        $ticketsCount = SupportTicket::where('user_id', $userId)->where('status', '!=', 'closed')->count();
        $requestsCount = ServiceRequest::where('user_id', $userId)->count();

        $recentPurchases = EnvatoPurchase::where('user_id', $userId)->with('item')->latest()->take(3)->get();
        $recentTickets = SupportTicket::where('user_id', $userId)->latest()->take(3)->get();

        return view('dashboard', compact('purchasesCount', 'ticketsCount', 'requestsCount', 'recentPurchases', 'recentTickets'));
    }

    public function purchases()
    {
        return view('portal.purchases');
    }

    public function tickets()
    {
        return view('portal.tickets');
    }

    public function services()
    {
        $services = Service::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        
        $myRequests = ServiceRequest::where('user_id', auth()->id())
            ->with(['service', 'product'])
            ->latest()
            ->get();

        return view('portal.services', compact('services', 'products', 'myRequests'));
    }

    public function submitServiceRequest(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'product_id' => 'nullable|exists:products,id',
            'description' => 'required|string|min:20',
            'budget' => 'nullable|numeric|min:1',
        ]);

        ServiceRequest::create([
            'user_id' => auth()->id(),
            'service_id' => $request->input('service_id'),
            'product_id' => $request->input('product_id'),
            'description' => $request->input('description'),
            'budget' => $request->input('budget'),
            'status' => \App\Enums\ServiceRequestStatus::PENDING,
        ]);

        return redirect()->back()->with('success', 'Your custom service request has been submitted. A team member will review it and send a quote shortly.');
    }

    public function payServiceRequest(Request $request, ServiceRequest $serviceRequest)
    {
        // Security check
        if ($serviceRequest->user_id !== auth()->id()) {
            abort(403);
        }

        if ($serviceRequest->status->value !== 'quoted') {
            return redirect()->back()->with('error', 'This request is not in a payable state.');
        }

        $secretKey = \App\Models\Setting::get('stripe_secret_key');
        if (!$secretKey) {
            return redirect()->back()->with('error', 'Stripe payment gateway is not configured by the administrator.');
        }

        try {
            $response = \Illuminate\Support\Facades\Http::asForm()
                ->withBasicAuth($secretKey, '')
                ->post('https://api.stripe.com/v1/checkout/sessions', [
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'price_data' => [
                                'currency' => 'usd',
                                'product_data' => [
                                    'name' => $serviceRequest->service->name . ' (Request #' . $serviceRequest->id . ')',
                                    'description' => 'SaaSNinja Custom Service Customization Development',
                                ],
                                'unit_amount' => intval($serviceRequest->quote_price * 100), // in cents
                            ],
                            'quantity' => 1,
                        ]
                    ],
                    'mode' => 'payment',
                    'success_url' => route('portal.services.payment-success', $serviceRequest->id),
                    'cancel_url' => route('portal.services.payment-cancel', $serviceRequest->id),
                ]);

            if ($response->failed()) {
                return redirect()->back()->with('error', 'Stripe checkout error: ' . ($response->json()['error']['message'] ?? 'Unknown error'));
            }

            $session = $response->json();
            return redirect()->away($session['url']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment connection failed: ' . $e->getMessage());
        }
    }

    public function paymentSuccess(ServiceRequest $serviceRequest)
    {
        // Security check
        if ($serviceRequest->user_id !== auth()->id()) {
            abort(403);
        }

        $serviceRequest->update([
            'status' => \App\Enums\ServiceRequestStatus::ACTIVE,
        ]);

        return redirect()->route('portal.services')->with('success', 'Thank you! Your payment was processed successfully. Work on your service request has been initiated.');
    }

    public function paymentCancel(ServiceRequest $serviceRequest)
    {
        // Security check
        if ($serviceRequest->user_id !== auth()->id()) {
            abort(403);
        }

        return redirect()->route('portal.services')->with('error', 'Stripe checkout session was cancelled. You can retry payment when ready.');
    }
}
