<?php

namespace App\Livewire\Portal;

use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\EnvatoPurchase;
use App\Models\MediaFile;
use App\Enums\TicketStatus;
use App\Enums\Priority;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class TicketManager extends Component
{
    use WithFileUploads;

    // View State
    public ?int $selectedTicketId = null;
    public bool $isCreating = false;

    // Creation Form
    public string $subject = '';
    public string $category = 'Bug';
    public ?int $purchaseId = null;
    public string $priority = 'medium';
    public string $createMessage = '';
    public $attachments = []; // For new ticket attachments

    // Chat Form
    public string $replyMessage = '';
    public $chatAttachments = []; // For reply attachments

    protected $listeners = ['purchase-linked' => '$refresh'];

    public function selectTicket(int $id)
    {
        $this->selectedTicketId = $id;
        $this->isCreating = false;
        $this->replyMessage = '';
        $this->chatAttachments = [];
    }

    public function startCreation()
    {
        $this->isCreating = true;
        $this->selectedTicketId = null;
        $this->resetCreationForm();
    }

    public function cancelCreation()
    {
        $this->isCreating = false;
        $this->resetCreationForm();
    }

    protected function resetCreationForm()
    {
        $this->subject = '';
        $this->category = 'Bug';
        $this->purchaseId = null;
        $this->priority = 'medium';
        $this->createMessage = '';
        $this->attachments = [];
    }

    public function createTicket()
    {
        $this->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            'purchaseId' => 'required|exists:envato_purchases,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'createMessage' => 'required|string|min:10',
            'attachments.*' => 'nullable|file|max:5120', // 5MB max
        ]);

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'envato_purchase_id' => $this->purchaseId,
            'subject' => $this->subject,
            'category' => $this->category,
            'priority' => $this->priority,
            'status' => TicketStatus::OPEN,
        ]);

        $reply = TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $this->createMessage,
        ]);

        // Process attachments
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $file) {
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

        $this->selectedTicketId = $ticket->id;
        $this->isCreating = false;
        $this->resetCreationForm();
        session()->flash('success', 'Support ticket opened successfully!');
    }

    public function sendReply()
    {
        $this->validate([
            'replyMessage' => 'required|string|min:2',
            'chatAttachments.*' => 'nullable|file|max:5120', // 5MB max
        ]);

        $ticket = SupportTicket::where('id', $this->selectedTicketId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $reply = TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $this->replyMessage,
            'is_internal' => false,
        ]);

        // Set ticket status back to open when customer replies
        $ticket->update(['status' => TicketStatus::OPEN]);

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
    }

    public function closeTicket()
    {
        if ($this->selectedTicketId) {
            $ticket = SupportTicket::where('id', $this->selectedTicketId)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $ticket->update(['status' => TicketStatus::CLOSED]);
            session()->flash('success', 'Ticket closed successfully.');
        }
    }

    public function render()
    {
        $tickets = SupportTicket::where('user_id', auth()->id())
            ->orderByDesc('updated_at')
            ->get();

        $purchases = EnvatoPurchase::where('user_id', auth()->id())
            ->with('item')
            ->get();

        $selectedTicket = null;
        if ($this->selectedTicketId) {
            $selectedTicket = SupportTicket::where('id', $this->selectedTicketId)
                ->where('user_id', auth()->id())
                ->with(['replies.user', 'replies.mediaFiles', 'purchase.item'])
                ->first();
        }

        return view('livewire.portal.ticket-manager', [
            'tickets' => $tickets,
            'purchases' => $purchases,
            'selectedTicket' => $selectedTicket,
        ]);
    }
}
