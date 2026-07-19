<?php

namespace App\Livewire\Portal;

use App\Services\LicenseVerificationService;
use App\Models\EnvatoPurchase;
use App\Models\License;
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

        // Check if this purchase code is already associated with someone else (Envato)
        $existingEnvato = EnvatoPurchase::where('purchase_code', $this->purchaseCode)->first();
        if ($existingEnvato && $existingEnvato->user_id !== null && $existingEnvato->user_id !== $user->id) {
            $this->errorMessage = 'This purchase code is already linked to another account.';
            return;
        }

        // Check if this license key is already associated with someone else (Direct)
        $existingDirect = License::where('license_key', $this->purchaseCode)->first();
        if ($existingDirect && $existingDirect->user_id !== null && $existingDirect->user_id !== $user->id) {
            $this->errorMessage = 'This license key is already linked to another account.';
            return;
        }

        $result = $verificationService->verifyLicense(
            $this->purchaseCode,
            null,
            null,
            request()->ip()
        );

        if (!$result['valid']) {
            $this->errorMessage = $result['error'] ?? 'Could not verify purchase code / license key.';
            return;
        }

        // 1. Link if it is Envato
        $purchase = EnvatoPurchase::where('purchase_code', $this->purchaseCode)->first();
        if ($purchase) {
            $purchase->update([
                'user_id' => $user->id
            ]);
            
            $this->successMessage = "Successfully verified and linked: {$purchase->item->name} (Envato)!";
            $this->purchaseCode = '';
            $this->dispatch('purchase-linked');
            return;
        }

        // 2. Link if it is SaaSNinja Direct License
        $license = License::where('license_key', $this->purchaseCode)->first();
        if ($license) {
            $license->update([
                'user_id' => $user->id
            ]);

            $this->successMessage = "Successfully verified and linked: {$license->product->name} (SaaSNinja Direct)!";
            $this->purchaseCode = '';
            $this->dispatch('purchase-linked');
            return;
        }

        $this->errorMessage = 'License verified but failed to link locally.';
    }

    public function render()
    {
        $linkedPurchases = EnvatoPurchase::where('user_id', auth()->id())
            ->with('item')
            ->get();

        $linkedLicenses = License::where('user_id', auth()->id())
            ->with('product')
            ->get();

        return view('livewire.portal.purchase-verification', [
            'linkedPurchases' => $linkedPurchases,
            'linkedLicenses' => $linkedLicenses
        ]);
    }
}
