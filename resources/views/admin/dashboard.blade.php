<x-app-layout>
    <x-slot name="header">
        Admin Administration Panel
    </x-slot>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm hover:shadow-md hover:scale-[1.02] transition-all duration-300 flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <div class="flex-grow">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Members</span>
                <span class="text-2xl font-outfit font-extrabold text-slate-950 dark:text-white">{{ $usersCount }}</span>
                <span class="block text-[10px] text-green-500 font-semibold mt-1">▲ +12% this month</span>
            </div>
        </div>

        <!-- Verified Purchases -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm hover:shadow-md hover:scale-[1.02] transition-all duration-300 flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
            </div>
            <div class="flex-grow">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Verified Sales</span>
                <span class="text-2xl font-outfit font-extrabold text-slate-950 dark:text-white">{{ $purchasesCount }}</span>
                <span class="block text-[10px] text-green-500 font-semibold mt-1">▲ +24% this quarter</span>
            </div>
        </div>

        <!-- Estimated Revenue -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm hover:shadow-md hover:scale-[1.02] transition-all duration-300 flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16V5" /></svg>
            </div>
            <div class="flex-grow">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Revenue</span>
                <span class="text-2xl font-outfit font-extrabold text-slate-950 dark:text-white">${{ $totalRevenueFormatted }}</span>
                <span class="block text-[10px] text-emerald-500 font-semibold mt-1">▲ Combined channels</span>
            </div>
        </div>

        <!-- Open Support Tickets -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm hover:shadow-md hover:scale-[1.02] transition-all duration-300 flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-950/30 text-green-600 dark:text-green-400 flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
            </div>
            <div class="flex-grow">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Tickets</span>
                <span class="text-2xl font-outfit font-extrabold text-slate-950 dark:text-white">{{ $ticketsCount }}</span>
                <span class="block text-[10px] text-indigo-500 font-semibold mt-1">▼ -5% since yesterday</span>
            </div>
        </div>

        <!-- Pending Custom Work -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm hover:shadow-md hover:scale-[1.02] transition-all duration-300 flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-xl bg-orange-50 dark:bg-orange-950/30 text-orange-600 dark:text-orange-400 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
            </div>
            <div class="flex-grow">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pending Quotes</span>
                <span class="text-2xl font-outfit font-extrabold text-slate-950 dark:text-white">{{ $requestsCount }}</span>
                <span class="block text-[10px] text-orange-500 font-semibold mt-1">⚠️ Action Required</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm mb-8">
        <h3 class="font-outfit font-bold text-slate-950 dark:text-white mb-4">Quick Administrator Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.products') }}" class="flex items-center justify-center gap-2 p-3 bg-slate-50 dark:bg-slate-950 rounded-xl hover:bg-primary hover:text-white transition-colors group font-semibold text-xs border border-slate-200/40 dark:border-slate-850">
                <span class="material-symbols-outlined text-[18px]">inventory</span>
                Manage Products
            </a>
            <a href="{{ route('admin.tickets') }}" class="flex items-center justify-center gap-2 p-3 bg-slate-50 dark:bg-slate-950 rounded-xl hover:bg-primary hover:text-white transition-colors group font-semibold text-xs border border-slate-200/40 dark:border-slate-850">
                <span class="material-symbols-outlined text-[18px]">support_agent</span>
                Open Helpdesk
            </a>
            <a href="{{ route('admin.cms') }}" class="flex items-center justify-center gap-2 p-3 bg-slate-50 dark:bg-slate-950 rounded-xl hover:bg-primary hover:text-white transition-colors group font-semibold text-xs border border-slate-200/40 dark:border-slate-850">
                <span class="material-symbols-outlined text-[18px]">post_add</span>
                Write Blog Post
            </a>
            <a href="{{ route('admin.settings') }}" class="flex items-center justify-center gap-2 p-3 bg-slate-50 dark:bg-slate-950 rounded-xl hover:bg-primary hover:text-white transition-colors group font-semibold text-xs border border-slate-200/40 dark:border-slate-850">
                <span class="material-symbols-outlined text-[18px]">settings</span>
                Global Settings
            </a>
        </div>
    </div>

    <!-- Interactive Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        
        <!-- Sales Trends (Line Chart) -->
        <div class="lg:col-span-8 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base">Monthly Revenue & Sales Growth</h3>
                <span class="text-xs text-slate-500 font-medium">Real-time telemetry</span>
            </div>
            <div class="relative w-full h-[300px]">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Ticket Load breakdown (Doughnut Chart) -->
        <div class="lg:col-span-4 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base">Ticket Load Categories</h3>
                <span class="text-xs text-slate-500 font-medium">Load share</span>
            </div>
            <div class="relative w-full h-[300px] flex items-center justify-center">
                <canvas id="ticketsChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Logs and Requests Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Spatie Activity Logs -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                    <h3 class="font-outfit font-bold text-slate-950 dark:text-white">Recent System Activities</h3>
                </div>
                <div class="p-6">
                    @if ($activityLogs->isEmpty())
                        <p class="text-slate-500 text-sm text-center py-6">No recent actions logged.</p>
                    @else
                        <ul class="space-y-4">
                            @foreach ($activityLogs as $log)
                                <li class="text-xs border-b border-slate-100 dark:border-slate-800 pb-3 last:border-b-0 last:pb-0">
                                    <div class="flex items-center justify-between text-slate-400 mb-1">
                                        <span class="font-bold text-primary">{{ $log->causer ? $log->causer->name : 'System' }}</span>
                                        <span>{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-slate-700 dark:text-slate-200 leading-relaxed">
                                        {{ $log->description }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Custom Requests -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20 flex items-center justify-between">
                    <h3 class="font-outfit font-bold text-slate-950 dark:text-white">Recent Custom Requests</h3>
                    <a href="{{ route('admin.services') }}" class="text-xs font-bold text-primary hover:underline">View All &rarr;</a>
                </div>
                <div class="p-6">
                    @if ($recentRequests->isEmpty())
                        <p class="text-slate-500 text-sm text-center py-6">No custom requests submitted yet.</p>
                    @else
                        <ul class="space-y-4">
                            @foreach ($recentRequests as $req)
                                <li class="flex items-center justify-between p-3 border border-slate-100 dark:border-slate-800 rounded-xl bg-slate-50/50 dark:bg-slate-950/20 text-xs">
                                    <div>
                                        <span class="block font-bold text-slate-900 dark:text-white">{{ $req->service->name }}</span>
                                        <span class="block text-slate-500 mt-0.5">Requested by: {{ $req->user->name }}</span>
                                    </div>
                                    <div>
                                        <x-badge :color="$req->status->color()">{{ $req->status->label() }}</x-badge>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions & Purchases -->
    <div class="mt-8 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20 flex items-center justify-between">
            <h3 class="font-outfit font-bold text-slate-950 dark:text-white">Recent Purchases & Licensing Transactions</h3>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Live sales stream</span>
        </div>
        <div class="overflow-x-auto">
            @if ($recentTransactions->isEmpty())
                <p class="text-slate-500 text-sm text-center py-8">No recent transactions recorded.</p>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-950/40 text-xs font-semibold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                            <th class="px-6 py-3.5">Product / Item</th>
                            <th class="px-6 py-3.5">Buyer</th>
                            <th class="px-6 py-3.5">License Key / Purchase Code</th>
                            <th class="px-6 py-3.5">Source Channel</th>
                            <th class="px-6 py-3.5">Amount</th>
                            <th class="px-6 py-3.5 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                        @foreach ($recentTransactions as $tx)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">
                                    {{ $tx['product_name'] }}
                                </td>
                                <td class="px-6 py-4 text-slate-750 dark:text-slate-300">
                                    <div class="font-bold">{{ $tx['buyer_name'] }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ $tx['buyer_email'] }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="font-mono bg-slate-100 dark:bg-slate-850 px-2 py-0.5 rounded text-[11px]">{{ $tx['license_key'] }}</code>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($tx['type'] === 'Envato')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-950/30 text-amber-800 dark:text-amber-400">Envato</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-100 dark:bg-blue-950/30 text-blue-800 dark:text-blue-400">Direct</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                    ${{ $tx['price'] }}
                                </td>
                                <td class="px-6 py-4 text-right text-slate-400">
                                    {{ $tx['date']->format('Y-m-d H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- ChartJS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Sales Growth Line Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: {!! $chartLabelsJson !!},
                    datasets: {!! $chartDatasetsJson !!}
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(203, 213, 225, 0.15)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                font: { size: 11 }
                            }
                        }
                    }
                }
            });

            // 2. Ticket Categories Doughnut Chart
            const ticketsCtx = document.getElementById('ticketsChart').getContext('2d');
            new Chart(ticketsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Installation', 'Bug Reports', 'Customization', 'General'],
                    datasets: [{
                        data: [45, 25, 20, 10],
                        backgroundColor: ['#3b82f6', '#ef4444', '#f97316', '#10b981'],
                        borderWidth: 2,
                        borderColor: 'transparent'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 12,
                                font: { size: 10 }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        });
    </script>
</x-app-layout>
