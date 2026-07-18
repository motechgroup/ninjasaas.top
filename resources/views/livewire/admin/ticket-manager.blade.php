<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 h-[calc(100vh-12rem)] min-h-[500px]">
    
    <!-- Left Pane: Ticket List (4 cols) -->
    <div class="lg:col-span-4 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl flex flex-col overflow-hidden h-full">
        <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
            <h3 class="font-outfit font-bold text-slate-900 dark:text-white">All Helpdesk Tickets</h3>
        </div>

        <!-- List Items -->
        <div class="flex-grow overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800">
            @if ($tickets->isEmpty())
                <div class="p-8 text-center text-slate-500">
                    <p class="text-sm">No support tickets found in the queue.</p>
                </div>
            @else
                @foreach ($tickets as $ticket)
                    <button wire:click="selectTicket({{ $ticket->id }})" 
                            class="w-full text-left p-4 hover:bg-slate-50 dark:hover:bg-slate-850/40 transition-colors flex flex-col gap-2 {{ $selectedTicketId === $ticket->id ? 'bg-indigo-50/50 dark:bg-indigo-950/15 border-l-4 border-indigo-600' : '' }}">
                        <div class="flex items-center justify-between w-full">
                            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider">#{{ $ticket->id }} &bull; {{ $ticket->category }}</span>
                            <span class="text-xs font-semibold text-slate-400">{{ $ticket->updated_at->diffForHumans() }}</span>
                        </div>
                        <span class="font-bold text-sm text-slate-900 dark:text-white line-clamp-1">{{ $ticket->subject }}</span>
                        <div class="text-xs text-slate-500 truncate">From: <strong class="text-slate-700 dark:text-slate-300">{{ $ticket->user->name }}</strong></div>
                        <div class="flex items-center gap-2 mt-1">
                            <x-badge :color="$ticket->status->color()">{{ $ticket->status->label() }}</x-badge>
                            <x-badge :color="$ticket->priority->color()">{{ $ticket->priority->label() }}</x-badge>
                        </div>
                    </button>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Right Pane: Ticket Work Area (8 cols) -->
    <div class="lg:col-span-8 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl overflow-hidden h-full flex flex-col">
        
        @if ($selectedTicket)
            <!-- Ticket Work Header -->
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-xs text-slate-500 font-semibold mb-1">
                        <span>Ticket #{{ $selectedTicket->id }}</span>
                        <span>&bull;</span>
                        <span>Opened by: <strong class="text-slate-700 dark:text-slate-300">{{ $selectedTicket->user->name }}</strong></span>
                    </div>
                    <h3 class="font-outfit font-extrabold text-lg text-slate-950 dark:text-white">{{ $selectedTicket->subject }}</h3>
                </div>
            </div>

            <!-- Double-column inside the work area: Chat Stream (65%) & Metadata Sidebar (35%) -->
            <div class="flex-grow flex flex-col md:flex-row overflow-hidden">
                
                <!-- Chat stream column -->
                <div class="flex-grow flex flex-col h-full md:w-2/3 border-r border-slate-200 dark:border-slate-800 overflow-hidden">
                    
                    <!-- Scrollable Chat Stream -->
                    <div class="flex-grow overflow-y-auto p-6 space-y-4 bg-slate-50/30 dark:bg-slate-950/10">
                        @foreach ($selectedTicket->replies as $reply)
                            <div class="flex {{ $reply->is_internal ? 'justify-center my-2' : ($reply->user_id === auth()->id() ? 'justify-end' : 'justify-start') }}">
                                @if($reply->is_internal)
                                    <!-- Internal note representation -->
                                    <div class="w-full bg-yellow-50 dark:bg-yellow-950/20 border border-yellow-200 dark:border-yellow-900/50 rounded-xl p-4 text-slate-800 dark:text-yellow-300">
                                        <div class="flex items-center justify-between gap-8 mb-1 text-[10px] font-bold uppercase tracking-wider text-yellow-600 dark:text-yellow-400">
                                            <span>Staff Private Note &bull; {{ $reply->user->name }}</span>
                                            <span>{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm whitespace-pre-wrap leading-relaxed">{{ $reply->message }}</p>
                                    </div>
                                @else
                                    <!-- Client/Staff Reply representation -->
                                    <div class="max-w-[85%] rounded-2xl p-4 shadow-sm {{ $reply->user_id === auth()->id() ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white dark:bg-slate-850 text-slate-900 dark:text-slate-100 rounded-bl-none border border-slate-200/50 dark:border-slate-800/50' }}">
                                        <div class="flex items-center justify-between gap-8 mb-1.5 text-[10px] font-semibold opacity-75">
                                            <span>{{ $reply->user->name }} ({{ $reply->user->hasAnyRole(['Super Admin', 'Support Staff']) ? 'Staff' : 'Customer' }})</span>
                                            <span>{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm whitespace-pre-wrap leading-relaxed">{{ $reply->message }}</p>

                                        <!-- Attachments -->
                                        @if ($reply->mediaFiles->isNotEmpty())
                                            <div class="mt-3 pt-2.5 border-t {{ $reply->user_id === auth()->id() ? 'border-white/20' : 'border-slate-100 dark:border-slate-800' }}">
                                                <span class="text-[10px] font-bold uppercase tracking-wider block opacity-75 mb-1.5">Attachments:</span>
                                                <div class="space-y-1.5">
                                                    @foreach ($reply->mediaFiles as $media)
                                                        <a href="{{ Storage::url($media->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold underline hover:opacity-80">
                                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                                            {{ $media->file_name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Reply/Note Form -->
                    <form wire:submit.prevent="sendReply" class="p-4 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-3">
                        <div class="relative">
                            <textarea wire:model.defer="replyMessage" rows="2" placeholder="Write your reply or private note..."
                                      class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            @error('replyMessage') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-between flex-wrap gap-2.5">
                            <!-- Toggle / Attachment -->
                            <div class="flex items-center gap-4">
                                <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer select-none">
                                    <input type="checkbox" wire:model="isInternal" class="rounded border-slate-300 text-yellow-600 focus:ring-yellow-500">
                                    <span>Staff Private Note</span>
                                </label>
                                
                                <input type="file" wire:model="chatAttachments" id="admin-chat-file" class="hidden" multiple>
                                <label for="admin-chat-file" class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-lg cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                    Attach
                                </label>
                            </div>

                            <button type="submit" wire:loading.attr="disabled"
                                    class="inline-flex items-center justify-center px-4 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-lg shadow-sm transition-colors duration-150">
                                <span wire:loading.remove>Submit Reply</span>
                                <span wire:loading>Submitting...</span>
                            </button>
                        </div>
                    </form>

                </div>

                <!-- Ticket Sidebar Column (35%) -->
                <div class="w-full md:w-1/3 bg-slate-50/50 dark:bg-slate-900/40 p-5 overflow-y-auto space-y-6 flex flex-col h-full">
                    
                    <!-- Customer Details -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Customer Profile</h4>
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $selectedTicket->user->name }}</div>
                        <div class="text-xs text-slate-500">{{ $selectedTicket->user->email }}</div>
                    </div>

                    <!-- Envato Purchase Details -->
                    @if ($selectedTicket->purchase)
                        <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2.5">Envato License</h4>
                            <div class="space-y-2 text-xs">
                                <div>
                                    <span class="text-slate-400">Product:</span>
                                    <div class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">{{ $selectedTicket->purchase->item->name }}</div>
                                </div>
                                <div>
                                    <span class="text-slate-400">Purchase Code:</span>
                                    <div class="font-mono bg-slate-100 dark:bg-slate-850 px-1.5 py-0.5 rounded text-slate-800 dark:text-slate-200 truncate mt-0.5">{{ $selectedTicket->purchase->purchase_code }}</div>
                                </div>
                                <div>
                                    <span class="text-slate-400">License:</span>
                                    <div class="font-semibold text-slate-850 dark:text-slate-200 mt-0.5">{{ $selectedTicket->purchase->license_type ?? 'Regular' }}</div>
                                </div>
                                <div>
                                    <span class="text-slate-400">Support Status:</span>
                                    <div class="mt-1">
                                        @if ($selectedTicket->purchase->hasActiveSupport())
                                            <x-badge color="green">Active (ends {{ $selectedTicket->purchase->support_expiry->format('Y-m-d') }})</x-badge>
                                        @else
                                            <x-badge color="red">Expired ({{ $selectedTicket->purchase->support_expiry ? $selectedTicket->purchase->support_expiry->format('Y-m-d') : 'No expiry' }})</x-badge>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="pt-4 border-t border-slate-200 dark:border-slate-800 text-xs text-red-500 font-semibold">
                            No verified purchase linked to this support ticket.
                        </div>
                    @endif

                    <!-- Ticket Actions Form -->
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-4 flex-grow">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Helpdesk Parameters</h4>

                        <div class="space-y-3">
                            <div>
                                <label for="assignedTo" class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Assignee</label>
                                <select id="assignedTo" wire:model="assignedTo" wire:change="updateTicketSettings" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-1.5 px-2">
                                    <option value="">Unassigned</option>
                                    @foreach($staff as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="adminStatus" class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Status</label>
                                <select id="adminStatus" wire:model="status" wire:change="updateTicketSettings" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-1.5 px-2">
                                    <option value="open">Open</option>
                                    <option value="answered">Answered</option>
                                    <option value="pending">Pending</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>

                            <div>
                                <label for="adminPriority" class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Priority</label>
                                <select id="adminPriority" wire:model="priority" wire:change="updateTicketSettings" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-1.5 px-2">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        @else
            <!-- Placeholder -->
            <div class="flex-grow flex flex-col items-center justify-center p-8 text-center text-slate-500">
                <svg class="w-16 h-16 text-slate-300 dark:text-slate-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h4 class="font-outfit font-bold text-slate-900 dark:text-white mb-1">Queue Empty or Idle</h4>
                <p class="text-sm max-w-sm">Select a ticket from the sidebar queue to start replying, assigning staff, or analyzing Envato purchase telemetry.</p>
            </div>
        @endif
    </div>
</div>
