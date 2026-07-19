<div class="space-y-8">
    
    <!-- Verification Form -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl p-6 shadow-sm">
        <h2 class="font-outfit font-bold text-lg text-slate-950 dark:text-white mb-2">Link Envato Purchase</h2>
        <p class="text-sm text-slate-500 mb-6">Enter your Envato Purchase Code (e.g. <code>11111111-2222-3333-4444-555555555555</code>) to activate support and download files.</p>

        <form wire:submit.prevent="verify" class="space-y-4">
            <div>
                <label for="purchaseCode" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Purchase Code</label>
                <div class="relative rounded-lg shadow-sm">
                    <input type="text" id="purchaseCode" wire:model.defer="purchaseCode" placeholder="xxxx-xxxx-xxxx-xxxx"
                           class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3">
                </div>
                @error('purchaseCode') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            @if ($errorMessage)
                <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/50 rounded-xl text-sm text-red-800 dark:text-red-300 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span>{{ $errorMessage }}</span>
                </div>
            @endif

            @if ($successMessage)
                <div class="p-4 bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-800/50 rounded-xl text-sm text-green-800 dark:text-green-300 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ $successMessage }}</span>
                </div>
            @endif

            <button type="submit" wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors duration-150">
                <span wire:loading.remove>Verify & Link License</span>
                <span wire:loading>Verifying with Envato...</span>
            </button>
        </form>
    </div>

    <!-- Purchases List -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">
            <h3 class="font-outfit font-bold text-lg text-slate-950 dark:text-white">Your Linked Licenses</h3>
            <p class="text-xs text-slate-500 mt-1">Products currently linked to your SaaSNinja account.</p>
        </div>

        @if ($linkedPurchases->isEmpty() && $linkedLicenses->isEmpty())
            <div class="p-8 text-center text-slate-500">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm">You haven't linked any purchase codes or license keys yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-semibold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                            <th class="px-6 py-3.5">Product</th>
                            <th class="px-6 py-3.5">Source</th>
                            <th class="px-6 py-3.5">License Key / Purchase Code</th>
                            <th class="px-6 py-3.5">License Type</th>
                            <th class="px-6 py-3.5">Support Status</th>
                            <th class="px-6 py-3.5">Purchased Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                        @foreach ($linkedPurchases as $purchase)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">
                                    {{ $purchase->item->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 dark:bg-amber-950/30 text-amber-800 dark:text-amber-400">Envato</span>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="text-xs font-mono bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">{{ $purchase->purchase_code }}</code>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold">
                                    {{ $purchase->license_type }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($purchase->hasActiveSupport())
                                        <x-badge color="green">Active (expires {{ $purchase->support_expiry->format('Y-m-d') }})</x-badge>
                                    @else
                                        <x-badge color="red">Expired ({{ $purchase->support_expiry ? $purchase->support_expiry->format('Y-m-d') : 'No expiry' }})</x-badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    {{ $purchase->purchase_date->format('Y-m-d') }}
                                </td>
                            </tr>
                        @endforeach

                        @foreach ($linkedLicenses as $lic)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">
                                    {{ $lic->product->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 dark:bg-blue-950/30 text-blue-800 dark:text-blue-400">Direct</span>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="text-xs font-mono bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">{{ $lic->license_key }}</code>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold">
                                    Direct Sale
                                </td>
                                <td class="px-6 py-4">
                                    @if ($lic->hasActiveSupport())
                                        <x-badge color="green">Active (expires {{ $lic->support_expires_at->format('Y-m-d') }})</x-badge>
                                    @else
                                        <x-badge color="red">Expired ({{ $lic->support_expires_at ? $lic->support_expires_at->format('Y-m-d') : 'No expiry' }})</x-badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    {{ $lic->purchased_at->format('Y-m-d') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
