<x-app-layout>
    <x-slot name="header">
        Verified Envato Purchases
    </x-slot>

    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
            <h3 class="font-outfit font-bold text-slate-950 dark:text-white">Envato Purchase Codes Scanned</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-semibold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                        <th class="px-6 py-3.5">Product</th>
                        <th class="px-6 py-3.5">Buyer</th>
                        <th class="px-6 py-3.5">Purchase Code</th>
                        <th class="px-6 py-3.5">License</th>
                        <th class="px-6 py-3.5">Support Expiration</th>
                        <th class="px-6 py-3.5">Purchased Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                    @foreach ($purchases as $p)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/40 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                {{ $p->item->name }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300">
                                {{ $p->user ? $p->user->name : 'Unlinked (' . $p->envato_username . ')' }}
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-xs font-mono bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">{{ $p->purchase_code }}</code>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-650 dark:text-slate-400">
                                {{ $p->license_type ?? 'Regular License' }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($p->hasActiveSupport())
                                    <x-badge color="green">Active (expires {{ $p->support_expiry->format('Y-m-d') }})</x-badge>
                                @else
                                    <x-badge color="red">Expired ({{ $p->support_expiry ? $p->support_expiry->format('Y-m-d') : 'No expiry' }})</x-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs">
                                {{ $p->purchase_date->format('Y-m-d') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($purchases->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
