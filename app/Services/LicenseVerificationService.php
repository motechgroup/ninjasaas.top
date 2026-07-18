<?php

namespace App\Services;

use App\Models\EnvatoItem;
use App\Models\EnvatoPurchase;
use App\Models\LicenseVerification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LicenseVerificationService
{
    protected EnvatoService $envatoService;

    public function __construct(EnvatoService $envatoService)
    {
        $this->envatoService = $envatoService;
    }

    /**
     * Verifies a license key (purchase code) locally or via Envato API.
     * Logs the attempt in the database.
     */
    public function verifyLicense(
        string $purchaseCode,
        ?string $productId = null,
        ?string $domain = null,
        ?string $ipAddress = null
    ): array {
        $purchaseCode = trim($purchaseCode);
        $isValid = false;
        $errorMessage = null;
        $purchase = null;

        // 1. Look up in our local database first
        $purchase = EnvatoPurchase::where('purchase_code', $purchaseCode)->first();

        // 2. If not found locally, query Envato directly
        if (!$purchase) {
            $envatoResult = $this->envatoService->verifyPurchase($purchaseCode);

            if ($envatoResult['success']) {
                // Ensure EnvatoItem exists in our database
                $envatoItem = EnvatoItem::firstOrCreate(
                    ['item_id' => $envatoResult['item_id']],
                    [
                        'name' => $envatoResult['item_name'],
                        'url' => 'https://codecanyon.net/item/' . $envatoResult['item_id']
                    ]
                );

                // Create local purchase record
                $purchase = EnvatoPurchase::create([
                    'envato_item_id' => $envatoItem->id,
                    'purchase_code' => $purchaseCode,
                    'envato_username' => $envatoResult['buyer'],
                    'purchase_date' => $envatoResult['purchase_date'],
                    'support_expiry' => $envatoResult['support_expiry'],
                    'license_type' => $envatoResult['license_type'],
                    'is_active' => true,
                ]);

                $isValid = true;
            } else {
                $errorMessage = $envatoResult['error'] ?? 'Invalid purchase code.';
            }
        } else {
            // 3. Local purchase found, validate status
            if (!$purchase->is_active) {
                $errorMessage = 'This license has been deactivated by the author.';
            } else {
                $isValid = true;
            }
        }

        // 4. Validate product ID matching if provided
        if ($isValid && $purchase && $productId) {
            $item = $purchase->item;
            if ($item && $item->item_id !== $productId) {
                $isValid = false;
                $errorMessage = "License matches product '{$item->name}' (ID: {$item->item_id}) but not requested product (ID: {$productId}).";
            }
        }

        // 5. Create verification log in database
        LicenseVerification::create([
            'envato_purchase_id' => $purchase ? $purchase->id : null,
            'purchase_code' => $purchaseCode,
            'product_id' => $productId,
            'domain' => $domain,
            'ip_address' => $ipAddress,
            'is_valid' => $isValid,
            'error_message' => $errorMessage,
        ]);

        if ($isValid && $purchase) {
            return [
                'valid' => true,
                'license_status' => 'active',
                'license_type' => $purchase->license_type,
                'buyer' => $purchase->envato_username,
                'purchase_date' => $purchase->purchase_date->toIso8601String(),
                'support_expiry' => $purchase->support_expiry ? $purchase->support_expiry->toIso8601String() : null,
                'support_active' => $purchase->hasActiveSupport(),
                'product' => [
                    'id' => $purchase->item->item_id,
                    'name' => $purchase->item->name,
                ]
            ];
        }

        return [
            'valid' => false,
            'error' => $errorMessage ?? 'License verification failed.'
        ];
    }
}
