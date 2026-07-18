<x-app-layout>
    <x-slot name="header">
        Users & Roles Categorization
    </x-slot>

    <!-- Tabbed Layout Container -->
    <div x-data="{ tab: 'staff' }" class="space-y-6">
        
        <!-- Tab Bar Header -->
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
            <div class="flex gap-2">
                <button @click="tab = 'staff'" :class="tab === 'staff' ? 'bg-primary text-white font-bold' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800'" class="px-4 py-2 rounded-xl text-xs font-semibold shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">support_agent</span>
                    Staff & Team ({{ $staff->count() }})
                </button>
                <button @click="tab = 'customers'" :class="tab === 'customers' ? 'bg-primary text-white font-bold' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800'" class="px-4 py-2 rounded-xl text-xs font-semibold shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                    Customers & Buyers ({{ $customers->total() }})
                </button>
            </div>
            <span class="text-xs text-slate-500 font-medium">Categorized by system permission level</span>
        </div>

        <!-- Tab 1: Staff and Team Table -->
        <div x-show="tab === 'staff'" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden" style="display: none;">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base">Authorized Staff Directory</h3>
                <p class="text-xs text-slate-500 mt-0.5">Users with administrative, support engineer, or content editor clearance levels.</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-semibold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                            <th class="px-6 py-3.5">Name</th>
                            <th class="px-6 py-3.5">Email</th>
                            <th class="px-6 py-3.5">Assigned Role</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                        @forelse ($staff as $user)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-850/40 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    @foreach($user->roles as $role)
                                        <x-badge color="indigo">{{ $role->name }}</x-badge>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <select name="role" required class="rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-1 px-2.5 focus:ring-primary focus:border-primary">
                                            <option value="Super Admin" {{ $user->hasRole('Super Admin') ? 'selected' : '' }}>Super Admin</option>
                                            <option value="Support Staff" {{ $user->hasRole('Support Staff') ? 'selected' : '' }}>Support Staff</option>
                                            <option value="Content Manager" {{ $user->hasRole('Content Manager') ? 'selected' : '' }}>Content Manager</option>
                                            <option value="Customer">Demote to Customer</option>
                                        </select>
                                        
                                        <button type="submit" class="px-3 py-1 bg-slate-900 dark:bg-primary hover:opacity-90 text-white text-xs font-bold rounded-lg transition-all shadow-sm">
                                            Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500">No staff members found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 2: Customers Table -->
        <div x-show="tab === 'customers'" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden" style="display: none;">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base">Verified Envato Customers</h3>
                <p class="text-xs text-slate-500 mt-0.5">Customers who register or link their Envato profiles to download products and open support tickets.</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-semibold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                            <th class="px-6 py-3.5">Name</th>
                            <th class="px-6 py-3.5">Email</th>
                            <th class="px-6 py-3.5">Envato Username</th>
                            <th class="px-6 py-3.5 text-center">Verified Purchases</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                        @forelse ($customers as $user)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-850/40 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs">
                                    @if($user->envato_username)
                                        <span class="inline-flex items-center gap-1 text-teal-600 dark:text-teal-400 font-semibold bg-teal-50 dark:bg-teal-950/30 px-2 py-1 rounded-md">
                                            <span class="material-symbols-outlined text-[14px]">verified</span>
                                            {{ $user->envato_username }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic">Not Linked</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($user->purchases_count > 0)
                                        <span class="inline-flex items-center justify-center bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 font-extrabold w-7 h-7 rounded-full text-xs">
                                            {{ $user->purchases_count }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">0</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <select name="role" required class="rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-1 px-2.5 focus:ring-primary focus:border-primary">
                                            <option value="Customer" selected>Customer</option>
                                            <option value="Super Admin">Promote to Super Admin</option>
                                            <option value="Support Staff">Promote to Support Staff</option>
                                            <option value="Content Manager">Promote to Content Manager</option>
                                        </select>
                                        
                                        <button type="submit" class="px-3 py-1 bg-slate-900 dark:bg-primary hover:opacity-90 text-white text-xs font-bold rounded-lg transition-all shadow-sm">
                                            Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">No registered customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($customers->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
