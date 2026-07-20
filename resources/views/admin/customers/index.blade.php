<x-app-layout>
    <x-slot name="header">
        Customer Management
    </x-slot>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-outfit">Customer Management</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage, inspect, suspend, block, and support registered customers & Envato buyers.</p>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Accounts</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1 font-outfit">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-emerald-500/20 shadow-sm">
            <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Active</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1 font-outfit">{{ number_format($stats['active']) }}</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-amber-500/20 shadow-sm">
            <p class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Suspended</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1 font-outfit">{{ number_format($stats['suspended']) }}</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-rose-500/20 shadow-sm">
            <p class="text-xs font-semibold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Blocked</p>
            <p class="text-2xl font-bold text-rose-600 dark:text-rose-400 mt-1 font-outfit">{{ number_format($stats['blocked']) }}</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-primary/20 shadow-sm">
            <p class="text-xs font-semibold text-primary uppercase tracking-wider">Envato Buyers</p>
            <p class="text-2xl font-bold text-primary mt-1 font-outfit">{{ number_format($stats['envato']) }}</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Search Customer</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Name, email, or Envato username..."
                       class="w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label for="status" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Status</label>
                <select id="status" name="status" class="w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>
            <div>
                <label for="role" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Role</label>
                <select id="role" name="role" class="w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                    <option value="">All Roles</option>
                    @foreach($roles as $r)
                        <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white text-sm font-semibold py-2 px-4 rounded-lg transition-colors">
                    Filter Results
                </button>
                @if(request()->hasAny(['search', 'status', 'role']))
                    <a href="{{ route('admin.customers.index') }}" class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-sm font-semibold py-2 px-3 rounded-lg hover:bg-slate-200 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white text-xs uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-4 font-bold">Customer</th>
                        <th class="px-6 py-4 font-bold">Role</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 font-bold">Licenses / Tickets</th>
                        <th class="px-6 py-4 font-bold">Joined</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($customers as $c)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-slate-700 dark:text-slate-300">
                                        @if($c->envato_avatar)
                                            <img src="{{ $c->envato_avatar }}" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            {{ strtoupper(substr($c->name, 0, 2)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.customers.show', $c) }}" class="font-bold text-slate-900 dark:text-white hover:text-primary transition-colors">
                                            {{ $c->name }}
                                        </a>
                                        <div class="text-xs text-slate-500">{{ $c->email }}</div>
                                        @if($c->envato_username)
                                            <div class="inline-flex items-center space-x-1 text-[11px] text-emerald-600 dark:text-emerald-400 font-mono mt-0.5">
                                                <span>Envato: {{ $c->envato_username }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @foreach($c->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                @if($c->status === 'blocked')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900">
                                        ● Blocked
                                    </span>
                                @elseif($c->status === 'suspended')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900" title="{{ $c->status_reason }}">
                                        ● Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900">
                                        ● Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-medium">
                                <div><span class="font-bold text-slate-900 dark:text-white">{{ $c->licenses->count() }}</span> Licenses</div>
                                <div><span class="font-bold text-slate-900 dark:text-white">{{ $c->tickets->count() }}</span> Support Tickets</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $c->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.customers.show', $c) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-semibold transition-colors">
                                    Manage
                                </a>

                                @if($c->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.customers.impersonate', $c) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-semibold transition-colors" title="Log in as customer for troubleshooting">
                                            Login As
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                No customer accounts found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
</x-app-layout>
