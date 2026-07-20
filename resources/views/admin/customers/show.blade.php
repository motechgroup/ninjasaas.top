<x-app-layout>
    <x-slot name="header">
        Customer Detail - {{ $customer->name }}
    </x-slot>
<div class="space-y-6" x-data="{ showStatusModal: false, showDeleteModal: false }">
    <!-- Back Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-primary transition-colors">
            ← Back to Customer List
        </a>
        @if($customer->id !== auth()->id())
            <form method="POST" action="{{ route('admin.customers.impersonate', $customer) }}" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-colors shadow-sm">
                    Impersonate / Login As Customer
                </button>
            </form>
        @endif
    </div>

    <!-- Customer Profile Header Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-xl text-slate-700 dark:text-slate-300">
                @if($customer->envato_avatar)
                    <img src="{{ $customer->envato_avatar }}" class="w-16 h-16 rounded-full object-cover">
                @else
                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                @endif
            </div>
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-outfit">{{ $customer->name }}</h1>
                    @if($customer->status === 'blocked')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400">
                            ● Account Blocked
                        </span>
                    @elseif($customer->status === 'suspended')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400">
                            ● Account Suspended
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">
                            ● Account Active
                        </span>
                    @endif
                </div>
                <p class="text-sm text-slate-500 mt-1">{{ $customer->email }} • Joined {{ $customer->created_at->format('F d, Y') }}</p>
                @if($customer->envato_username)
                    <div class="mt-2 text-xs font-mono text-emerald-600 dark:text-emerald-400">
                        Envato Marketplace Username: {{ $customer->envato_username }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Danger / Account Status Actions -->
        @if($customer->id !== auth()->id())
            <div class="flex flex-wrap gap-3">
                <button type="button" @click="showStatusModal = true" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-lg transition-colors">
                    Change Account Status
                </button>

                <button type="button" @click="showDeleteModal = true" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                    Delete Account
                </button>
            </div>
        @endif
    </div>

    <!-- Status Change Modal -->
    <div x-show="showStatusModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="showStatusModal = false" class="bg-white dark:bg-slate-900 rounded-xl max-w-md w-full p-6 border border-slate-200/80 dark:border-slate-800 shadow-xl space-y-4">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white font-outfit">Update Account Status</h3>
            <form method="POST" action="{{ route('admin.customers.status', $customer) }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Account Status</label>
                    <select id="status" name="status" class="w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        <option value="active" {{ $customer->status === 'active' ? 'selected' : '' }}>Active (Full Access Granted)</option>
                        <option value="suspended" {{ $customer->status === 'suspended' ? 'selected' : '' }}>Suspended (Temporary Access Hold)</option>
                        <option value="blocked" {{ $customer->status === 'blocked' ? 'selected' : '' }}>Blocked (Permanent Revocation)</option>
                    </select>
                </div>

                <div>
                    <label for="suspended_days" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Suspension Duration (Days - Optional)</label>
                    <input type="number" id="suspended_days" name="suspended_days" min="1" max="365" placeholder="e.g. 7 or 30 days"
                           class="w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label for="status_reason" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Reason Message (Shown to Customer)</label>
                    <textarea id="status_reason" name="status_reason" rows="3" placeholder="Explain why the account status was changed..."
                              class="w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">{{ $customer->status_reason }}</textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showStatusModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-semibold rounded-lg hover:bg-slate-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-lg transition-colors">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="showDeleteModal = false" class="bg-white dark:bg-slate-900 rounded-xl max-w-md w-full p-6 border border-slate-200/80 dark:border-slate-800 shadow-xl space-y-4">
            <div class="w-12 h-12 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </div>
            <div class="text-center">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white font-outfit">Delete Customer Account</h3>
                <p class="text-xs text-slate-500 mt-2">Are you sure you want to permanently delete customer account <strong>{{ $customer->name }}</strong> ({{ $customer->email }})?</p>
                <p class="text-xs text-rose-500 font-semibold mt-1">This action cannot be undone.</p>
            </div>
            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" @click="showDeleteModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-semibold rounded-lg hover:bg-slate-200 transition-colors">
                    Cancel
                </button>
                <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                        Yes, Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Grid: Left Profile Edit, Right Tabs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Edit Profile Panel -->
        <div class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-slate-900 dark:text-white font-outfit">Edit Profile & Credentials</h3>

            <form method="POST" action="{{ route('admin.customers.profile', $customer) }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" required
                           class="w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}" required
                           class="w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label for="envato_username" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Envato Username</label>
                    <input type="text" id="envato_username" name="envato_username" value="{{ old('envato_username', $customer->envato_username) }}" placeholder="Envato Market Username"
                           class="w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label for="role" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Role Assignment</label>
                    <select id="role" name="role" class="w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        @foreach($roles as $r)
                            <option value="{{ $r }}" {{ $customer->hasRole($r) ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Reset Password (Optional)</label>
                    <input type="password" id="password" name="password" placeholder="Leave blank to keep current"
                           class="w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white text-sm font-bold py-2.5 px-4 rounded-lg transition-colors">
                    Save Profile Changes
                </button>
            </form>
        </div>

        <!-- Customer Activity & Relational Records -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Product Licenses Owned -->
            <div class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-slate-900 dark:text-white font-outfit">Active Licenses & Purchases ({{ $customer->licenses->count() }})</h3>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($customer->licenses as $lic)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $lic->product ? $lic->product->name : 'General Software License' }}</p>
                                <p class="text-xs font-mono text-primary mt-0.5">Key: {{ $lic->license_key }}</p>
                                <p class="text-[11px] text-slate-500">Domain: {{ $lic->domain_name ?: 'Unbound' }} • Sales Channel: {{ ucfirst($lic->sales_channel) }}</p>
                            </div>
                            <div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $lic->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $lic->is_active ? 'Active' : 'Revoked' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-2">No product licenses owned by this customer.</p>
                    @endforelse
                </div>
            </div>

            <!-- Support Tickets -->
            <div class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-slate-900 dark:text-white font-outfit">Support Tickets ({{ $customer->tickets->count() }})</h3>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($customer->tickets as $ticket)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <p class="font-bold text-slate-900 dark:text-white text-sm">#{{ $ticket->id }} - {{ $ticket->subject }}</p>
                                <p class="text-xs text-slate-500">Opened {{ $ticket->created_at->diffForHumans() }}</p>
                            </div>
                            <div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-2">No support tickets opened by this customer.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
