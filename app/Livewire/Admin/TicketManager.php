<?php

namespace App\Livewire\Admin;

use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use App\Models\MediaFile;
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
        $ticket = SupportTicket::findOrFail($id);
        
        $this->assignedTo = $ticket->assigned_to;
        $this->status = $ticket->status->value;
        $this->priority = $ticket->priority->value;
        
        $this->replyMessage = '';
        $this->isInternal = false;
        $this->chatAttachments = [];
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

        return view('livewire.admin.ticket-manager', [
            'tickets' => $tickets,
            'selectedTicket' => $selectedTicket,
            'staff' => $staff,
        ]);
    }
}
