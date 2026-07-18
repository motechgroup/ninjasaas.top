<?php

namespace App\Livewire\Portal;

use App\Services\LicenseVerificationService;
use App\Models\EnvatoPurchase;
use Livewire\Component;

class PurchaseVerification extends Component
{
    public string $purchaseCode = '';
    public string $errorMessage = '';
    public string $successMessage = '';

    protected array $rules = [
        'purchaseCode' => 'required|string|min:10',
    ];

    public function verify(LicenseVerificationService $verificationService)
    {
        $this->validate();
        $this->errorMessage = '';
        $this->successMessage = '';

        $user = auth()->user();

        // Check if this purchase code is already associated with someone else
        $existing = EnvatoPurchase::where('purchase_code', $this->purchaseCode)->first();
        if ($existing && $existing->user_id !== null && $existing->user_id !== $user->id) {
            $this->errorMessage = 'This purchase code is already linked to another account.';
            return;
        }

        $result = $verificationService->verifyLicense(
            $this->purchaseCode,
            null,
            null,
            request()->ip()
        );

        if (!$result['valid']) {
            $this->errorMessage = $result['error'] ?? 'Could not verify purchase code with Envato.';
            return;
        }

        // The service created/updated the purchase record, now associate it with the logged in user!
        $purchase = EnvatoPurchase::where('purchase_code', $this->purchaseCode)->first();
        if ($purchase) {
            $purchase->update([
                'user_id' => $user->id
            ]);
            
            $this->successMessage = "Successfully verified and linked: {$purchase->item->name}!";
            $this->purchaseCode = '';
            $this->dispatch('purchase-linked');
        } else {
            $this->errorMessage = 'Purchase verified but failed to link locally.';
        }
    }

    public function render()
    {
        $linkedPurchases = EnvatoPurchase::where('user_id', auth()->id())
            ->with('item')
            ->get();

        return view('livewire.portal.purchase-verification', [
            'linkedPurchases' => $linkedPurchases
        ]);
    }
}
