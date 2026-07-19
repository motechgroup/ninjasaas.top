<div class="space-y-8">
    
    <!-- Verification Form -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl p-6 shadow-sm relative overflow-hidden">
        <!-- Background Gradient glow -->
        <div class="absolute -right-24 -top-24 w-48 h-48 rounded-full bg-indigo-500/5 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-24 -bottom-24 w-48 h-48 rounded-full bg-emerald-500/5 blur-3xl pointer-events-none"></div>

        <div class="flex items-start gap-4 mb-6">
            <div class="p-3 bg-indigo-550/10 dark:bg-indigo-500/10 text-primary rounded-2xl flex-shrink-0">
                <span class="material-symbols-outlined text-[28px] block">vpn_key</span>
            </div>
            <div>
                <h2 class="font-outfit font-extrabold text-xl text-slate-950 dark:text-white mb-1.5">Link Product License or Purchase Code</h2>
                <p class="text-sm text-slate-500 leading-relaxed max-w-2xl">
                    Enter your Envato Purchase Code or SaaSNinja Direct License Key below to register the product with your customer profile. This unlocks developer file downloads and activates support coverage.
                </p>
            </div>
        </div>

        <form wire:submit.prevent="verify" class="space-y-4 max-w-3xl">
            <div class="space-y-2">
                <label for="purchaseCode" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Purchase Code / License Key</label>
                <div class="relative rounded-xl shadow-sm flex items-center">
                    <span class="material-symbols-outlined text-[20px] text-slate-400 absolute left-4 pointer-events-none">key</span>
                    <input type="text" id="purchaseCode" wire:model.defer="purchaseCode" placeholder="e.g. 11111111-2222-3333-4444-555555555555"
                           class="block w-full rounded-xl border border-slate-200 dark:border-slate-800 dark:bg-slate-950/80 text-slate-900 dark:text-white placeholder-slate-400 focus:border-primary focus:ring-primary text-sm py-3 pl-12 pr-4 transition-all">
                </div>
                @error('purchaseCode') <span class="text-xs text-red-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
            </div>

            @if ($errorMessage)
                <div class="p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800/50 rounded-xl text-sm text-rose-800 dark:text-rose-300 flex items-center gap-3">
                    <span class="material-symbols-outlined text-rose-500 flex-shrink-0 text-[20px]">error</span>
                    <span class="font-semibold">{{ $errorMessage }}</span>
                </div>
            @endif

            @if ($successMessage)
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/50 rounded-xl text-sm text-emerald-800 dark:text-emerald-350 flex items-center gap-3">
                    <span class="material-symbols-outlined text-emerald-500 flex-shrink-0 text-[20px]">check_circle</span>
                    <span class="font-semibold">{{ $successMessage }}</span>
                </div>
            @endif

            <button type="submit" wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center px-5 py-3 bg-gradient-to-r from-primary to-indigo-650 hover:opacity-95 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/15 transition-all duration-200 hover:scale-[1.01] active:scale-[0.99]">
                <span wire:loading.remove class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">verified_user</span>
                    Verify & Link License
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Verifying Credentials...
                </span>
            </button>
        </form>
    </div>

    <!-- Purchases List -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20 flex items-center justify-between">
            <div>
                <h3 class="font-outfit font-extrabold text-lg text-slate-950 dark:text-white">Your Linked Licenses</h3>
                <p class="text-xs text-slate-500 mt-1">Manage active software deployments and support lifecycles.</p>
            </div>
            <span class="text-xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-full">
                {{ $linkedPurchases->count() + $linkedLicenses->count() }} active licenses
            </span>
        </div>

        @if ($linkedPurchases->isEmpty() && $linkedLicenses->isEmpty())
            <div class="p-12 text-center text-slate-500">
                <div class="w-16 h-16 bg-slate-50 dark:bg-slate-950/50 border border-slate-100 dark:border-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-slate-300 dark:text-slate-700 text-[32px] block">folder_open</span>
                </div>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">No licenses linked yet</p>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Use the link form above to register your purchases and access download files.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/40 dark:bg-slate-950/40 text-xs font-semibold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                            <th class="px-6 py-4">Product Specifications</th>
                            <th class="px-6 py-4">Source</th>
                            <th class="px-6 py-4">License Key / Purchase Code</th>
                            <th class="px-6 py-4">License Type</th>
                            <th class="px-6 py-4">Support Status</th>
                            <th class="px-6 py-4">Purchased Date</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                        <!-- Envato Purchases -->
                        @foreach ($linkedPurchases as $purchase)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">
                                    <div class="font-bold text-sm text-slate-900 dark:text-white">{{ $purchase->item->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-normal mt-0.5">Linked to purchase account</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-950/30 text-amber-800 dark:text-amber-400">Envato</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div x-data="{ copied: false }" class="relative flex items-center gap-1.5">
                                        <code class="text-xs font-mono bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800/85 px-2.5 py-1 rounded-lg text-slate-800 dark:text-slate-200">{{ $purchase->purchase_code }}</code>
                                        <button @click="navigator.clipboard.writeText('{{ $purchase->purchase_code }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                                                title="Copy to Clipboard">
                                            <span class="material-symbols-outlined text-[16px] block" x-show="!copied">content_copy</span>
                                            <span class="material-symbols-outlined text-[16px] text-emerald-500 block" x-show="copied" style="display: none;">check</span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-600 dark:text-slate-300">
                                    {{ $purchase->license_type }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($purchase->hasActiveSupport())
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-455 border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Active (expires {{ $purchase->support_expiry->format('Y-m-d') }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-455 border border-rose-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Expired ({{ $purchase->support_expiry ? $purchase->support_expiry->format('Y-m-d') : 'No expiry' }})
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $purchase->purchase_date->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @php $prod = $purchase->getProduct(); @endphp
                                    @if ($prod && $prod->getLatestDownloadUrl())
                                        <a href="{{ $prod->getLatestDownloadUrl() }}" target="_blank" 
                                           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-emerald-600 to-teal-500 hover:opacity-95 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-500/10 transition-all hover:scale-[1.03]">
                                            <span class="material-symbols-outlined text-[15px]">download</span>
                                            Download v{{ $prod->getLatestVersionNumber() }}
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 font-semibold italic">No Releases</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        <!-- Direct Licenses -->
                        @foreach ($linkedLicenses as $lic)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">
                                    <div class="font-bold text-sm text-slate-900 dark:text-white">{{ $lic->product->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-normal mt-0.5">Direct retail catalog license</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-indigo-100 dark:bg-indigo-950/30 text-indigo-850 dark:text-indigo-400">Direct</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div x-data="{ copied: false }" class="relative flex items-center gap-1.5">
                                        <code class="text-xs font-mono bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800/85 px-2.5 py-1 rounded-lg text-slate-800 dark:text-slate-200">{{ $lic->license_key }}</code>
                                        <button @click="navigator.clipboard.writeText('{{ $lic->license_key }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                                                title="Copy to Clipboard">
                                            <span class="material-symbols-outlined text-[16px] block" x-show="!copied">content_copy</span>
                                            <span class="material-symbols-outlined text-[16px] text-emerald-500 block" x-show="copied" style="display: none;">check</span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-600 dark:text-slate-300">
                                    Direct Sale
                                </td>
                                <td class="px-6 py-4">
                                    @if ($lic->hasActiveSupport())
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-455 border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Active (expires {{ $lic->support_expires_at->format('Y-m-d') }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-455 border border-rose-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Expired ({{ $lic->support_expires_at ? $lic->support_expires_at->format('Y-m-d') : 'No expiry' }})
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $lic->purchased_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if ($lic->product && $lic->product->getLatestDownloadUrl())
                                        <a href="{{ $lic->product->getLatestDownloadUrl() }}" target="_blank" 
                                           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-emerald-600 to-teal-500 hover:opacity-95 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-500/10 transition-all hover:scale-[1.03]">
                                            <span class="material-symbols-outlined text-[15px]">download</span>
                                            Download v{{ $lic->product->getLatestVersionNumber() }}
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 font-semibold italic">No Releases</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
