<x-app-layout>
    <x-slot name="header">
        Custom Development & Server Services
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Side: Request Form (1 col) -->
        <div class="lg:col-span-1 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm h-fit">
            <h3 class="font-outfit font-bold text-lg text-slate-950 dark:text-white mb-4">Request Service</h3>
            
            <form action="{{ route('portal.services.submit') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="service_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Service Type</label>
                    <select id="service_id" name="service_id" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-2 px-3 focus:ring-indigo-500">
                        <option value="">-- Select Service --</option>
                        @foreach ($services as $srv)
                            <option value="{{ $srv->id }}">{{ $srv->name }} (Starts at ${{ number_format($srv->price, 0) }})</option>
                        @endforeach
                    </select>
                    @error('service_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="product_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Related Product (Optional)</label>
                    <select id="product_id" name="product_id" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-2 px-3 focus:ring-indigo-500">
                        <option value="">-- Select Product --</option>
                        @foreach ($products as $prd)
                            <option value="{{ $prd->id }}">{{ $prd->name }}</option>
                        @endforeach
                    </select>
                    @error('product_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="budget" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Estimated Budget (USD, Optional)</label>
                    <input type="number" id="budget" name="budget" min="1" placeholder="e.g. 500" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-2 px-3 focus:ring-indigo-500">
                    @error('budget') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Description & Specifications</label>
                    <textarea id="description" name="description" rows="5" required placeholder="Describe your server configuration or customized requirements in detail..."
                              class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-2 px-3 focus:ring-indigo-500"></textarea>
                    @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-lg shadow-sm transition-colors">
                        Request Quote
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Side: Requests History (2 cols) -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden flex flex-col h-full">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                <h3 class="font-outfit font-bold text-slate-950 dark:text-white">Your Custom Requests</h3>
            </div>

            <div class="flex-grow overflow-y-auto divide-y divide-slate-150 dark:divide-slate-800">
                @if($myRequests->isEmpty())
                    <p class="text-slate-500 text-sm text-center py-12">You haven't requested any custom work yet.</p>
                @else
                    @foreach($myRequests as $req)
                        <div class="p-6 space-y-4 hover:bg-slate-50/50 dark:hover:bg-slate-950/10 transition-colors">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div>
                                    <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Request #{{ $req->id }}</span>
                                    <h4 class="font-outfit font-extrabold text-base text-slate-900 dark:text-white mt-0.5">{{ $req->service->name }}</h4>
                                </div>
                                <div class="flex items-center gap-2">
                                    <x-badge :color="$req->status->color()">{{ $req->status->label() }}</x-badge>
                                </div>
                            </div>
                            
                            <p class="text-xs text-slate-600 dark:text-slate-400 whitespace-pre-wrap leading-relaxed bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-100 dark:border-slate-850">{{ $req->description }}</p>
                            
                            <div class="flex flex-wrap items-center justify-between text-xs pt-2 border-t border-slate-100 dark:border-slate-800 text-slate-500 gap-4">
                                <div class="flex items-center gap-4">
                                    <span>Budget: <strong>{{ $req->budget ? '$' . number_format($req->budget, 2) : 'Open Budget' }}</strong></span>
                                    @if ($req->quote_price)
                                        <span class="text-indigo-600 dark:text-indigo-400 font-bold">Quote: ${{ number_format($req->quote_price, 2) }}</span>
                                    @endif
                                </div>
                                <span>Requested {{ $req->created_at->diffForHumans() }}</span>
                            </div>

                            @if ($req->admin_notes)
                                <div class="p-3.5 bg-indigo-50/40 dark:bg-indigo-950/10 border border-indigo-100 dark:border-indigo-900/30 rounded-xl text-xs text-slate-700 dark:text-slate-300">
                                    <span class="font-bold text-indigo-650 dark:text-indigo-400 block mb-1">Response from Engineer:</span>
                                    {{ $req->admin_notes }}
                                </div>
                            @endif

                            @if ($req->status->value === 'quoted')
                                <div class="flex justify-end pt-1">
                                    <form action="{{ route('portal.services.pay', $req->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg shadow-sm transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">credit_card</span>
                                            Pay Quote with Stripe (${{ number_format($req->quote_price, 2) }})
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
