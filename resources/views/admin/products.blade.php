<x-app-layout>
    <x-slot name="header">
        Product Catalog Manager
    </x-slot>

    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20 flex items-center justify-between">
            <h3 class="font-outfit font-bold text-slate-950 dark:text-white">Active Catalog Products</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-semibold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                        <th class="px-6 py-3.5">Product Name</th>
                        <th class="px-6 py-3.5">Category</th>
                        <th class="px-6 py-3.5">Envato Item ID</th>
                        <th class="px-6 py-3.5">Current version</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                    @forelse ($products as $p)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/40 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                {{ $p->name }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $p->category->name }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                {{ $p->envato_item_id ?: 'No Envato Binding' }}
                            </td>
                            <td class="px-6 py-4 font-semibold">
                                v{{ $p->version }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($p->is_active)
                                    <x-badge color="green">Active</x-badge>
                                @else
                                    <x-badge color="gray">Inactive</x-badge>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-500">
                                No products cataloged. Run database seeders.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
