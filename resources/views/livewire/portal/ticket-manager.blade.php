<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 h-[calc(100vh-12rem)] min-h-[500px]">
    
    <!-- Left Pane: Ticket List (4 cols) -->
    <div class="lg:col-span-4 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl flex flex-col overflow-hidden h-full">
        <!-- List Header -->
        <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-950/20">
            <h3 class="font-outfit font-bold text-slate-900 dark:text-white">My Support Tickets</h3>
            <button wire:click="startCreation" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-lg shadow-sm transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                New Ticket
            </button>
        </div>

        <!-- List Items -->
        <div class="flex-grow overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800">
            @if ($tickets->isEmpty())
                <div class="p-8 text-center text-slate-500">
                    <p class="text-sm">No support tickets found.</p>
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
                        <div class="flex items-center gap-2 mt-1">
                            <x-badge :color="$ticket->status->color()">{{ $ticket->status->label() }}</x-badge>
                            <x-badge :color="$ticket->priority->color()">{{ $ticket->priority->label() }}</x-badge>
                        </div>
                    </button>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Right Pane: Ticket Chat or Create Form (8 cols) -->
    <div class="lg:col-span-8 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl overflow-hidden h-full flex flex-col">
        
        @if ($isCreating)
            <!-- Ticket Creation Form -->
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20 flex items-center justify-between">
                <h3 class="font-outfit font-bold text-slate-900 dark:text-white">Create New Support Ticket</h3>
                <button wire:click="cancelCreation" class="text-xs font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-white">Cancel</button>
            </div>

            <form wire:submit.prevent="createTicket" class="p-6 space-y-4 overflow-y-auto flex-grow">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="purchaseId" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Verified Product</label>
                        <select id="purchaseId" wire:model.defer="purchaseId" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-indigo-500">
                            <option value="">-- Select Product --</option>
                            @foreach ($purchaseOptions as $option)
                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                        @error('purchaseId') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        @if(empty($purchaseOptions))
                            <span class="text-xs text-indigo-500 mt-1.5 block">You must first <a href="{{ route('portal.purchases') }}" class="underline font-semibold">link a purchase code or license key</a> to open a ticket.</span>
                        @endif
                    </div>

                    <div>
                        <label for="category" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Category</label>
                        <select id="category" wire:model.defer="category" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-indigo-500">
                            <option value="Bug">Bug / Issue</option>
                            <option value="Installation">Installation Assistance</option>
                            <option value="Customization">Customization Request</option>
                            <option value="General">General Inquiry</option>
                        </select>
                        @error('category') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="priority" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Priority</label>
                        <select id="priority" wire:model.defer="priority" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-indigo-500">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                        @error('priority') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Subject</label>
                        <input type="text" id="subject" wire:model.defer="subject" placeholder="Summarize your issue"
                               class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-indigo-500">
                        @error('subject') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="createMessage" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Detailed Description</label>
                    <textarea id="createMessage" wire:model.defer="createMessage" rows="5" placeholder="Explain your request, including steps to reproduce or custom details..."
                              class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-indigo-500"></textarea>
                    @error('createMessage') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Attachment Upload -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Attachments (Optional)</label>
                    <input type="file" wire:model="attachments" multiple class="block text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-slate-800 dark:file:text-slate-300">
                    <div class="text-slate-500 text-xs mt-1">Allowed file types: images, PDFs, ZIP files. Max 5MB per file.</div>
                    @error('attachments.*') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                    <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors duration-150">
                        <span wire:loading.remove>Open Ticket</span>
                        <span wire:loading>Submitting...</span>
                    </button>
                </div>
            </form>

        @elseif ($selectedTicket)
            <!-- Ticket Header -->
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-xs text-slate-500 font-semibold mb-1">
                        <span>Ticket #{{ $selectedTicket->id }}</span>
                        <span>&bull;</span>
                        <span>Product: <strong class="text-slate-700 dark:text-slate-300">
                            @if ($selectedTicket->purchase)
                                {{ $selectedTicket->purchase->item->name }}
                            @elseif ($selectedTicket->license)
                                {{ $selectedTicket->license->product->name }}
                            @else
                                General Support
                            @endif
                        </strong></span>
                    </div>
                    <h3 class="font-outfit font-extrabold text-lg text-slate-950 dark:text-white">{{ $selectedTicket->subject }}</h3>
                </div>
                <div class="flex items-center gap-2">
                    <x-badge :color="$selectedTicket->status->color()">{{ $selectedTicket->status->label() }}</x-badge>
                    @if ($selectedTicket->status !== App\Enums\TicketStatus::CLOSED)
                        <button wire:click="closeTicket" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300 rounded-lg transition-colors">
                            Close Ticket
                        </button>
                    @endif
                </div>
            </div>

            <!-- Chat Message Stream -->
            <div class="flex-grow overflow-y-auto p-6 space-y-4 bg-slate-50/30 dark:bg-slate-950/10">
                @foreach ($selectedTicket->replies as $reply)
                    @if(!$reply->is_internal)
                        <div class="flex {{ $reply->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[75%] rounded-2xl p-4 shadow-sm {{ $reply->user_id === auth()->id() ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white dark:bg-slate-850 text-slate-900 dark:text-slate-100 rounded-bl-none border border-slate-200/50 dark:border-slate-800/50' }}">
                                <div class="flex items-center justify-between gap-8 mb-1.5 text-[10px] font-semibold opacity-75">
                                    <span>{{ $reply->user->name }}</span>
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
                                                    {{ $media->file_name }} ({{ number_format($media->file_size / 1024, 1) }} KB)
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Chat Input (if not closed) -->
            @if ($selectedTicket->status !== App\Enums\TicketStatus::CLOSED)
                <form wire:submit.prevent="sendReply" class="p-4 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-3">
                    <div class="relative">
                        <textarea wire:model.defer="replyMessage" rows="2" placeholder="Write your reply..."
                                  class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        @error('replyMessage') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-between flex-wrap gap-2.5">
                        <!-- Attachment Upload -->
                        <div class="flex items-center gap-2">
                            <input type="file" wire:model="chatAttachments" id="chat-file" class="hidden" multiple>
                            <label for="chat-file" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-lg cursor-pointer transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                Attach Files
                            </label>
                            @if (!empty($chatAttachments))
                                <span class="text-xs text-indigo-500 font-semibold">{{ count($chatAttachments) }} file(s) selected</span>
                            @endif
                        </div>

                        <button type="submit" wire:loading.attr="disabled"
                                class="inline-flex items-center justify-center px-4 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-lg shadow-sm transition-colors duration-150">
                            <span wire:loading.remove>Send Reply</span>
                            <span wire:loading>Sending...</span>
                        </button>
                    </div>
                    @error('chatAttachments.*') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </form>
            @else
                <div class="p-4 bg-slate-50 dark:bg-slate-950 text-slate-500 text-center text-xs font-semibold uppercase tracking-wider border-t border-slate-200 dark:border-slate-800">
                    This support ticket has been closed.
                </div>
            @endif
        @else
            <!-- Default Placeholder -->
            <div class="flex-grow flex flex-col items-center justify-center p-8 text-center text-slate-500">
                <svg class="w-16 h-16 text-slate-300 dark:text-slate-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h4 class="font-outfit font-bold text-slate-900 dark:text-white mb-1">No Ticket Selected</h4>
                <p class="text-sm max-w-sm mb-4">Select an existing ticket from the sidebar, or open a new support request.</p>
                <button wire:click="startCreation" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    Open a Support Ticket &rarr;
                </button>
            </div>
        @endif
    </div>
</div>
