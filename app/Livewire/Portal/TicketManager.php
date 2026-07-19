<?php

namespace App\Livewire\Portal;

use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\EnvatoPurchase;
use App\Models\License;
use App\Models\MediaFile;
use App\Enums\TicketStatus;
use App\Enums\Priority;
use Livewire\Component;
use Livewire\WithFileUploads;

class TicketManager extends Component
{
    use WithFileUploads;

    // View State
    public ?int $selectedTicketId = null;
    public bool $isCreating = false;

    // Creation Form
    public string $subject = '';
    public string $category = 'Bug';
    public ?string $purchaseId = null; // Stored as string e.g. "envato_1" or "direct_1"
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
            'purchaseId' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'createMessage' => 'required|string|min:10',
            'attachments.*' => 'nullable|file|max:5120', // 5MB max
        ]);

        $purchaseIdValue = $this->purchaseId;
        $type = 'envato';
        $id = null;

        if (str_starts_with($purchaseIdValue, 'envato_')) {
            $type = 'envato';
            $id = (int) str_replace('envato_', '', $purchaseIdValue);
        } elseif (str_starts_with($purchaseIdValue, 'direct_')) {
            $type = 'direct';
            $id = (int) str_replace('direct_', '', $purchaseIdValue);
        } else {
            $type = 'envato';
            $id = (int) $purchaseIdValue;
        }

        $envatoPurchaseId = null;
        $licenseId = null;

        if ($type === 'envato') {
            $purchase = EnvatoPurchase::where('id', $id)->where('user_id', auth()->id())->first();
            if (!$purchase) {
                $this->addError('purchaseId', 'Invalid purchase code selected.');
                return;
            }
            if (!$purchase->hasActiveSupport()) {
                $this->addError('purchaseId', 'Your support for this Envato product has expired.');
                return;
            }
            $envatoPurchaseId = $purchase->id;
        } else {
            $license = License::where('id', $id)->where('user_id', auth()->id())->first();
            if (!$license) {
                $this->addError('purchaseId', 'Invalid license key selected.');
                return;
            }
            if (!$license->hasActiveSupport()) {
                $this->addError('purchaseId', 'Your support for this license has expired.');
                return;
            }
            $licenseId = $license->id;
        }

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'envato_purchase_id' => $envatoPurchaseId,
            'license_id' => $licenseId,
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

        $licenses = License::where('user_id', auth()->id())
            ->with('product')
            ->get();

        $purchaseOptions = [];
        foreach ($purchases as $p) {
            $purchaseOptions[] = [
                'value' => 'envato_' . $p->id,
                'label' => $p->item->name . ' (Envato - ' . ($p->hasActiveSupport() ? 'Support Active' : 'Support Expired') . ')',
                'has_support' => $p->hasActiveSupport(),
            ];
        }
        foreach ($licenses as $l) {
            $purchaseOptions[] = [
                'value' => 'direct_' . $l->id,
                'label' => $l->product->name . ' (Direct - ' . ($l->hasActiveSupport() ? 'Support Active' : 'Support Expired') . ')',
                'has_support' => $l->hasActiveSupport(),
            ];
        }

        $selectedTicket = null;
        if ($this->selectedTicketId) {
            $selectedTicket = SupportTicket::where('id', $this->selectedTicketId)
                ->where('user_id', auth()->id())
                ->with(['replies.user', 'replies.mediaFiles', 'purchase.item', 'license.product'])
                ->first();
        }

        return view('livewire.portal.ticket-manager', [
            'tickets' => $tickets,
            'purchaseOptions' => $purchaseOptions,
            'selectedTicket' => $selectedTicket,
        ]);
    }
}
