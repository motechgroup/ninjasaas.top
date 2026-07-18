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
}
