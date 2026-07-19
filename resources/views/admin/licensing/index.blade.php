@extends('layouts.app')

@section('header')
    Licensing & Sales Channels
@endsection

@section('content')
<div class="space-y-8" x-data="{ tab: 'statistics' }">
    
    <!-- Navigation Tabs -->
    <div class="flex border-b border-slate-200 dark:border-slate-800 gap-6">
        <button @click="tab = 'statistics'" :class="tab === 'statistics' ? 'border-indigo-600 text-indigo-600 font-bold border-b-2 pb-3.5 text-sm transition-all' : 'text-slate-500 hover:text-slate-800 dark:hover:text-white pb-3.5 text-sm transition-all'">Statistics</button>
        <button @click="tab = 'channels'" :class="tab === 'channels' ? 'border-indigo-600 text-indigo-600 font-bold border-b-2 pb-3.5 text-sm transition-all' : 'text-slate-500 hover:text-slate-800 dark:hover:text-white pb-3.5 text-sm transition-all'">Sales Channels</button>
        <button @click="tab = 'distribution'" :class="tab === 'distribution' ? 'border-indigo-600 text-indigo-600 font-bold border-b-2 pb-3.5 text-sm transition-all' : 'text-slate-500 hover:text-slate-800 dark:hover:text-white pb-3.5 text-sm transition-all'">Product Distribution</button>
        <button @click="tab = 'generator'" :class="tab === 'generator' ? 'border-indigo-600 text-indigo-600 font-bold border-b-2 pb-3.5 text-sm transition-all' : 'text-slate-500 hover:text-slate-800 dark:hover:text-white pb-3.5 text-sm transition-all'">Key Generator</button>
        <button @click="tab = 'licenses'" :class="tab === 'licenses' ? 'border-indigo-600 text-indigo-600 font-bold border-b-2 pb-3.5 text-sm transition-all' : 'text-slate-500 hover:text-slate-800 dark:hover:text-white pb-3.5 text-sm transition-all'">Active Licenses</button>
    </div>

    <!-- Tab 1: Statistics -->
    <div x-show="tab === 'statistics'" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Envato Legacy Purchases</span>
                <span class="text-3xl font-outfit font-extrabold text-slate-900 dark:text-white mt-2 block">{{ $stats['total_envato'] }}</span>
            </div>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Direct Licenses</span>
                <span class="text-3xl font-outfit font-extrabold text-slate-900 dark:text-white mt-2 block">{{ $stats['total_direct'] }}</span>
            </div>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Active Direct Licenses</span>
                <span class="text-3xl font-outfit font-extrabold text-green-600 dark:text-green-400 mt-2 block">{{ $stats['active_direct'] }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
            <h3 class="font-outfit font-bold text-lg text-slate-900 dark:text-white mb-4">Architecture Summary</h3>
            <p class="text-sm text-slate-500 leading-relaxed">
                SaaSNinja supports unified software delivery through a pluggable License Provider structure. 
                New purchases verify dynamically using Envato API (legacy) or SaaSNinja Direct Key validation. 
                Adding new channels requires declaring them in the Sales Channels module, and registering a corresponding driver class implementing <code>LicenseProviderInterface</code>.
            </p>
        </div>
    </div>

    <!-- Tab 2: Channels -->
    <div x-show="tab === 'channels'" class="grid grid-cols-1 lg:grid-cols-3 gap-8" style="display: none;">
        <!-- Left: Listing (2 cols) -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Configured Sales Channels</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-semibold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                            <th class="px-6 py-3">Channel Name</th>
                            <th class="px-6 py-3">Slug</th>
                            <th class="px-6 py-3">Assigned Products</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                        @foreach ($channels as $channel)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">{{ $channel->name }}</td>
                                <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $channel->slug }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-950 dark:text-white">{{ $channel->products_count }} products</td>
                                <td class="px-6 py-4">
                                    <x-badge color="{{ $channel->is_active ? 'green' : 'red' }}">{{ $channel->is_active ? 'Active' : 'Disabled' }}</x-badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Create Forms -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white mb-4">Add Sales Channel</h3>
                <form action="{{ route('admin.licensing.channel.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Channel Name</label>
                        <input type="text" name="name" required placeholder="e.g. SaaSNinja Direct" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Slug</label>
                        <input type="text" name="slug" required placeholder="e.g. saasninja" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                    </div>
                    <button type="submit" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-lg transition-colors">Create Channel</button>
                </form>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white mb-4">Add License Provider</h3>
                <form action="{{ route('admin.licensing.provider.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Provider Name</label>
                        <input type="text" name="name" required placeholder="e.g. SaaSNinja API" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Slug</label>
                        <input type="text" name="slug" required placeholder="e.g. saasninja" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                    </div>
                    <button type="submit" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-lg transition-colors">Create Provider</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tab 3: Distribution -->
    <div x-show="tab === 'distribution'" class="grid grid-cols-1 lg:grid-cols-3 gap-8" style="display: none;">
        <!-- Left: List distribution mappings (2 cols) -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Active Product Distributions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-semibold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                            <th class="px-6 py-3">Product</th>
                            <th class="px-6 py-3">Channel</th>
                            <th class="px-6 py-3">External ID</th>
                            <th class="px-6 py-3">Priority</th>
                            <th class="px-6 py-3">Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                        @foreach ($distributions as $dist)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">{{ $dist->product->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $dist->channel->slug === 'envato' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $dist->channel->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $dist->external_product_id ?: 'N/A' }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-500">{{ $dist->priority }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-850 dark:text-white">${{ number_format($dist->price ?: 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Assign Product Form -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
            <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white mb-4">Assign Product Channel</h3>
            <form action="{{ route('admin.licensing.assign') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Select Product</label>
                    <select name="product_id" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                        @foreach ($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Select Sales Channel</label>
                    <select name="sales_channel_id" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                        @foreach ($channels as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Purchase URL (Optional)</label>
                    <input type="url" name="purchase_url" placeholder="https://..." class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Price</label>
                        <input type="number" name="price" step="0.01" min="0" placeholder="59.00" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Priority</label>
                        <input type="number" name="priority" value="0" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">External Product ID (Optional)</label>
                    <input type="text" name="external_product_id" placeholder="e.g. Envato Item ID" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-lg transition-colors">Assign Channel</button>
            </form>
        </div>
    </div>

    <!-- Tab 4: Generator -->
    <div x-show="tab === 'generator'" class="max-w-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-8 shadow-sm" style="display: none;">
        <h3 class="font-outfit font-bold text-lg text-slate-950 dark:text-white mb-2">Direct License Key Generator</h3>
        <p class="text-sm text-slate-500 mb-6">Generate and assign a unique SaaSNinja Software License Key directly to a client user account.</p>

        <form action="{{ route('admin.licensing.generate') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Select Client User</label>
                <select name="user_id" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Target Product</label>
                    <select name="product_id" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                        @foreach ($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">License Provider</label>
                    <select name="license_provider_id" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                        @foreach ($providers as $pr)
                            <option value="{{ $pr->id }}">{{ $pr->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Support & Expiry Duration</label>
                <select name="duration_months" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                    <option value="6">6 Months</option>
                    <option value="12" selected>12 Months</option>
                    <option value="24">24 Months</option>
                    <option value="36">36 Months</option>
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors duration-150">
                    Generate License Key
                </button>
            </div>
        </form>
    </div>

    <!-- Tab 5: Active Licenses -->
    <div x-show="tab === 'licenses'" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden" style="display: none;">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
            <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Direct Licenses Registry</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-semibold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                        <th class="px-6 py-3">Client</th>
                        <th class="px-6 py-3">Product</th>
                        <th class="px-6 py-3">License Key</th>
                        <th class="px-6 py-3">Provider</th>
                        <th class="px-6 py-3">Support Expiry</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @if($licenses->isEmpty())
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">No direct licenses created yet.</td>
                        </tr>
                    @else
                        @foreach ($licenses as $lic)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/45 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900 dark:text-white">{{ $lic->user ? $lic->user->name : 'N/A' }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $lic->user ? $lic->user->email : '' }}</div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">{{ $lic->product->name }}</td>
                                <td class="px-6 py-4">
                                    <code class="text-xs font-mono bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">{{ $lic->license_key }}</code>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/30 text-indigo-700 dark:text-indigo-400">
                                        {{ $lic->provider->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold {{ $lic->hasActiveSupport() ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $lic->support_expires_at ? $lic->support_expires_at->format('Y-m-d') : 'No expiry' }}
                                </td>
                                <td class="px-6 py-4">
                                    <x-badge color="{{ $lic->is_active ? 'green' : 'red' }}">{{ $lic->is_active ? 'Active' : 'Revoked' }}</x-badge>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.licensing.toggle', $lic->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold {{ $lic->is_active ? 'text-red-600 hover:text-red-500' : 'text-green-600 hover:text-green-500' }}">
                                            {{ $lic->is_active ? 'Revoke' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        @if($licenses->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                {{ $licenses->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
