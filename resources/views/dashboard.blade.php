<x-app-layout>
    <x-slot name="header">
        Customer Dashboard
    </x-slot>

    <!-- Stat cards grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <!-- Linked licenses -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
            </div>
            <div>
                <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Linked Licenses</span>
                <span class="text-2xl font-outfit font-extrabold text-slate-950 dark:text-white">{{ $purchasesCount }}</span>
            </div>
        </div>

        <!-- Open support tickets -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-950/30 text-green-600 dark:text-green-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
            </div>
            <div>
                <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Open Tickets</span>
                <span class="text-2xl font-outfit font-extrabold text-slate-950 dark:text-white">{{ $ticketsCount }}</span>
            </div>
        </div>

        <!-- Custom development requests -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 dark:bg-orange-950/30 text-orange-600 dark:text-orange-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Service Quotes</span>
                <span class="text-2xl font-outfit font-extrabold text-slate-950 dark:text-white">{{ $requestsCount }}</span>
            </div>
        </div>
    </div>

    <!-- Details Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Linked licenses -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 class="font-outfit font-bold text-slate-950 dark:text-white">Recent Verified Products</h3>
                <a href="{{ route('portal.purchases') }}" class="text-xs font-bold text-indigo-650 dark:text-indigo-400 hover:underline">Link New &rarr;</a>
            </div>
            
            <div class="p-6">
                @if($recentPurchases->isEmpty())
                    <p class="text-slate-500 text-sm text-center py-6">No purchases linked to this account yet.</p>
                @else
                    <ul class="space-y-4">
                        @foreach($recentPurchases as $p)
                            <li class="flex items-center justify-between p-3 border border-slate-100 dark:border-slate-800 rounded-xl bg-slate-50/50 dark:bg-slate-950/20">
                                <div>
                                    <span class="block text-sm font-bold text-slate-900 dark:text-white">{{ $p->item->name }}</span>
                                    <span class="block text-xs text-slate-400 font-mono mt-0.5">Code: {{ $p->purchase_code }}</span>
                                </div>
                                <div class="text-right">
                                    @if($p->hasActiveSupport())
                                        <x-badge color="green">Support Active</x-badge>
                                    @else
                                        <x-badge color="red">Support Expired</x-badge>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Recent tickets -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 class="font-outfit font-bold text-slate-950 dark:text-white">Recent Support Tickets</h3>
                <a href="{{ route('portal.tickets') }}" class="text-xs font-bold text-indigo-650 dark:text-indigo-400 hover:underline">View All &rarr;</a>
            </div>
            
            <div class="p-6">
                @if($recentTickets->isEmpty())
                    <p class="text-slate-500 text-sm text-center py-6">No support tickets opened yet.</p>
                @else
                    <ul class="space-y-4">
                        @foreach($recentTickets as $t)
                            <li class="flex items-center justify-between p-3 border border-slate-100 dark:border-slate-800 rounded-xl bg-slate-50/50 dark:bg-slate-950/20">
                                <div>
                                    <span class="block text-sm font-bold text-slate-900 dark:text-white">{{ $t->subject }}</span>
                                    <span class="block text-xs text-slate-400 mt-0.5">Category: {{ $t->category }}</span>
                                </div>
                                <div>
                                    <x-badge :color="$t->status->color()">{{ $t->status->label() }}</x-badge>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
