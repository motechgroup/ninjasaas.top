<?php

namespace App\Livewire\Admin;

use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use App\Models\MediaFile;
use App\Models\EnvatoPurchase;
use App\Enums\TicketStatus;
use App\Enums\Priority;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class TicketManager extends Component
{
    use WithFileUploads;

    public ?int $selectedTicketId = null;
    public string $filterStatus = 'all'; // Status filter: all, open, answered, pending, closed
    
    // Creation Form (on behalf of customer)
    public bool $isCreating = false;
    public ?int $createUserId = null;
    public ?int $createPurchaseId = null;
    public string $createSubject = '';
    public string $createCategory = 'Bug';
    public string $createPriority = 'medium';
    public string $createMessage = '';

    // Chat Form
    public string $replyMessage = '';
    public bool $isInternal = false; // Internal admin note toggle
    public $chatAttachments = [];

    // Assignment & Status Form
    public ?int $assignedTo = null;
    public string $status = '';
    public string $priority = '';

    public function selectTicket(int $id)
    {
        $this->selectedTicketId = $id;
        $this->isCreating = false;
        $ticket = SupportTicket::findOrFail($id);
        
        $this->assignedTo = $ticket->assigned_to;
        $this->status = $ticket->status->value;
        $this->priority = $ticket->priority->value;
        
        $this->replyMessage = '';
        $this->isInternal = false;
        $this->chatAttachments = [];
    }

    public function startCreation()
    {
        $this->isCreating = true;
        $this->selectedTicketId = null;
        $this->createUserId = null;
        $this->createPurchaseId = null;
        $this->createSubject = '';
        $this->createCategory = 'Bug';
        $this->createPriority = 'medium';
        $this->createMessage = '';
    }

    public function cancelCreation()
    {
        $this->isCreating = false;
    }

    public function createTicket()
    {
        $this->validate([
            'createUserId' => 'required|exists:users,id',
            'createPurchaseId' => 'nullable|exists:envato_purchases,id',
            'createSubject' => 'required|string|max:255',
            'createCategory' => 'required|string',
            'createPriority' => 'required|in:low,medium,high,urgent',
            'createMessage' => 'required|string|min:10',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => $this->createUserId,
            'envato_purchase_id' => $this->createPurchaseId,
            'subject' => $this->createSubject,
            'category' => $this->createCategory,
            'priority' => $this->createPriority,
            'status' => TicketStatus::OPEN,
            'assigned_to' => auth()->id(), // Auto assign to the creating staff
        ]);

        TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $this->createMessage,
            'is_internal' => false,
        ]);

        $this->selectedTicketId = $ticket->id;
        $this->isCreating = false;
        
        session()->flash('success', 'Ticket created on behalf of client successfully.');
    }

    public function updateTicketSettings()
    {
        $ticket = SupportTicket::findOrFail($this->selectedTicketId);
        
        $ticket->update([
            'assigned_to' => $this->assignedTo ?: null,
            'status' => TicketStatus::from($this->status),
            'priority' => Priority::from($this->priority),
        ]);

        session()->flash('success', 'Ticket settings updated successfully.');
    }

    public function sendReply()
    {
        $this->validate([
            'replyMessage' => 'required|string|min:2',
            'chatAttachments.*' => 'nullable|file|max:5120',
        ]);

        $ticket = SupportTicket::findOrFail($this->selectedTicketId);

        $reply = TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $this->replyMessage,
            'is_internal' => $this->isInternal,
        ]);

        // Auto transition status: if public reply, set status to Answered.
        if (!$this->isInternal) {
            $ticket->update(['status' => TicketStatus::ANSWERED]);
            $this->status = TicketStatus::ANSWERED->value;
        }

        // Process attachments
        if (!empty($this->chatAttachments)) {
            foreach ($this->chatAttachments as $file) {
                $path = $file->store('tickets/' . $ticket->id, 'public');
                MediaFile::create([
                    'user_id' => auth()->id(),
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'model_type' => TicketReply::class,
                    'model_id' => $reply->id,
                ]);
            }
        }

        $this->replyMessage = '';
        $this->chatAttachments = [];
        $this->isInternal = false;
    }

    public function render()
    {
        // Admin gets access to all tickets, ordered by latest updates
        $query = SupportTicket::with(['user', 'purchase.item'])
            ->orderByDesc('updated_at');

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        $tickets = $query->get();

        $selectedTicket = null;
        if ($this->selectedTicketId) {
            $selectedTicket = SupportTicket::where('id', $this->selectedTicketId)
                ->with(['replies.user', 'replies.mediaFiles', 'purchase.item', 'user'])
                ->first();
        }

        // Support staff members list for assignment
        $staff = User::role(['Super Admin', 'Support Staff'])->get();

        // Client list for creating ticket on their behalf
        $users = User::orderBy('name')->get();
        $userPurchases = [];
        if ($this->createUserId) {
            $userPurchases = EnvatoPurchase::where('user_id', $this->createUserId)->with('item')->get();
        }

        return view('livewire.admin.ticket-manager', [
            'tickets' => $tickets,
            'selectedTicket' => $selectedTicket,
            'staff' => $staff,
            'users' => $users,
            'userPurchases' => $userPurchases,
        ]);
    }
}
