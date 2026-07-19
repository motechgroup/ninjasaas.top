@extends('layouts.public')

@section('title', 'Checkout - ' . $product->name)

@section('content')
<div class="py-16 bg-slate-50 dark:bg-slate-950 min-h-[calc(100vh-8rem)] flex items-center justify-center">
    <div class="max-w-4xl w-full mx-auto px-4 grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
        
        <!-- Left Pane: Checkout Form (7 cols) -->
        <div class="md:col-span-7 bg-white dark:bg-slate-900 border border-slate-205 dark:border-slate-800 rounded-3xl p-6 md:p-8 shadow-xl space-y-6">
            <div>
                <h2 class="font-outfit font-extrabold text-2xl text-slate-950 dark:text-white tracking-tight">Direct Checkout</h2>
                <p class="text-xs text-slate-500 mt-1">Complete your transaction securely. Licenses and downloads will be instantly available in your profile.</p>
            </div>

            <form action="{{ route('checkout.process', $product->slug) }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Cardholder Name -->
                <div>
                    <label for="card_name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Cardholder Name</label>
                    <input type="text" id="card_name" name="card_name" required value="{{ auth()->user()->name }}"
                           class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2.5 px-3 focus:ring-primary focus:border-primary">
                    @error('card_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Card Number -->
                <div>
                    <label for="card_number" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Card Number</label>
                    <div class="relative">
                        <input type="text" id="card_number" name="card_number" required placeholder="4111 2222 3333 4444" maxlength="19"
                               class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2.5 px-3 focus:ring-primary focus:border-primary">
                        <span class="absolute right-3 top-3 material-symbols-outlined text-slate-400">credit_card</span>
                    </div>
                    @error('card_number') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Expiry -->
                    <div>
                        <label for="card_expiry" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Expiry Date</label>
                        <input type="text" id="card_expiry" name="card_expiry" required placeholder="MM/YY" maxlength="5"
                               class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2.5 px-3 focus:ring-primary focus:border-primary">
                        @error('card_expiry') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- CVC -->
                    <div>
                        <label for="card_cvc" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">CVC / CVV</label>
                        <input type="text" id="card_cvc" name="card_cvc" required placeholder="123" maxlength="4"
                               class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2.5 px-3 focus:ring-primary focus:border-primary">
                        @error('card_cvc') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs text-slate-500">
                        <span class="material-symbols-outlined text-[16px] text-green-600">lock</span>
                        SSL Encrypted Payment
                    </div>
                    <button type="submit" class="px-6 py-3 bg-indigo-650 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/25 transition-all">
                        Pay ${{ number_format($price, 2) }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Pane: Order Summary (5 cols) -->
        <div class="md:col-span-5 space-y-6">
            <div class="bg-slate-900 text-white rounded-3xl p-6 shadow-xl space-y-6 relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-36 h-36 bg-indigo-600/25 rounded-full blur-2xl"></div>
                
                <div>
                    <h3 class="font-outfit font-bold text-lg text-white">Order Summary</h3>
                    <p class="text-xs text-slate-400">Direct Software Purchase</p>
                </div>

                <div class="flex items-center gap-3 py-4 border-y border-slate-800">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover border border-slate-800">
                    @else
                        <div class="w-12 h-12 rounded-lg bg-slate-850 flex items-center justify-center border border-slate-800 font-bold text-xs text-slate-400">
                            SN
                        </div>
                    @endif
                    <div>
                        <div class="font-bold text-sm text-white line-clamp-1">{{ $product->name }}</div>
                        <div class="text-[11px] text-indigo-400 font-bold mt-0.5">Version {{ $product->version }}</div>
                    </div>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Subtotal</span>
                        <span class="font-bold text-slate-200">${{ number_format($price, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tax</span>
                        <span class="font-bold text-slate-200">$0.00</span>
                    </div>
                    <div class="flex justify-between text-sm pt-2.5 border-t border-slate-800">
                        <span class="font-extrabold text-white">Total</span>
                        <span class="font-extrabold text-indigo-400">${{ number_format($price, 2) }}</span>
                    </div>
                </div>

                <div class="bg-slate-850 rounded-2xl p-4 text-xs text-slate-350 leading-relaxed border border-slate-800">
                    <span class="block font-bold text-white mb-1">What happens next?</span>
                    On successful checkout, a premium software license key is generated. You can verify it and access release file downloads in your customer portal.
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
