<x-app-layout>
    <x-slot name="header">
        Custom Development & Server Setup requests
    </x-slot>

    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
            <h3 class="font-outfit font-bold text-slate-950 dark:text-white">Customer Requests Queue</h3>
        </div>

        <div class="divide-y divide-slate-150 dark:divide-slate-800">
            @forelse($requests as $req)
                <div class="p-6 space-y-4 hover:bg-slate-50/50 dark:hover:bg-slate-950/10 transition-colors">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Request #{{ $req->id }} &bull; Customer: {{ $req->user->name }} ({{ $req->user->email }})</span>
                            <h4 class="font-outfit font-extrabold text-base text-slate-900 dark:text-white mt-0.5">{{ $req->service->name }}</h4>
                            @if($req->product)
                                <span class="block text-xs text-slate-400 mt-0.5">Product: {{ $req->product->name }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <x-badge :color="$req->status->color()">{{ $req->status->label() }}</x-badge>
                        </div>
                    </div>

                    <p class="text-xs text-slate-650 dark:text-slate-400 whitespace-pre-wrap leading-relaxed bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-100 dark:border-slate-850">{{ $req->description }}</p>
                    
                    <div class="flex flex-wrap items-center justify-between text-xs pt-2 text-slate-500 gap-4">
                        <div>Estimated Client Budget: <strong>{{ $req->budget ? '$' . number_format($req->budget, 2) : 'Open Budget' }}</strong></div>
                        <span>Submitted {{ $req->created_at->diffForHumans() }}</span>
                    </div>

                    <!-- Actions Form to Update Quote and Status -->
                    <form action="{{ route('admin.services.update', $req->id) }}" method="POST" class="p-4 bg-indigo-50/20 dark:bg-indigo-950/10 border border-indigo-100 dark:border-indigo-900/30 rounded-xl space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="quote_price_{{ $req->id }}" class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Quote Price (USD)</label>
                                <input type="number" step="0.01" min="0" name="quote_price" id="quote_price_{{ $req->id }}" value="{{ $req->quote_price }}" placeholder="0.00"
                                       class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-1.5 px-2">
                            </div>
                            
                            <div>
                                <label for="status_{{ $req->id }}" class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Status</label>
                                <select name="status" id="status_{{ $req->id }}" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-1.5 px-2">
                                    <option value="pending" {{ $req->status === App\Enums\ServiceRequestStatus::PENDING ? 'selected' : '' }}>Pending Review</option>
                                    <option value="quoted" {{ $req->status === App\Enums\ServiceRequestStatus::QUOTED ? 'selected' : '' }}>Quote Sent</option>
                                    <option value="active" {{ $req->status === App\Enums\ServiceRequestStatus::ACTIVE ? 'selected' : '' }}>Work In Progress</option>
                                    <option value="completed" {{ $req->status === App\Enums\ServiceRequestStatus::COMPLETED ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $req->status === App\Enums\ServiceRequestStatus::CANCELLED ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="admin_notes_{{ $req->id }}" class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Engineer Response / Quote Details</label>
                            <textarea name="admin_notes" id="admin_notes_{{ $req->id }}" rows="3" placeholder="Explain the quotation breakdown, estimated delivery times, and server configurations..."
                                      class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-1.5 px-2">{{ $req->admin_notes }}</textarea>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-1.5 bg-indigo-650 hover:bg-indigo-600 text-white text-xs font-bold rounded-lg transition-colors">
                                Update Request Details
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500">
                    No custom service requests in the queue.
                </div>
            @endforelse
        </div>

        @if($requests->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
