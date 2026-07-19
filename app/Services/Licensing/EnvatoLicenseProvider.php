<?php

namespace App\Services\Licensing;

use App\Services\EnvatoService;
use App\Models\EnvatoPurchase;
use App\Models\EnvatoItem;
use App\Models\LicenseVerification;
use Carbon\Carbon;

class EnvatoLicenseProvider implements LicenseProviderInterface
{
    protected EnvatoService $envatoService;

    public function __construct(EnvatoService $envatoService)
    {
        $this->envatoService = $envatoService;
    }

    public function verify(string $key, ?string $productId = null): array
    {
        $purchase = EnvatoPurchase::where('purchase_code', $key)->first();
        $isValid = false;
        $errorMessage = null;

        if (!$purchase) {
            $envatoResult = $this->envatoService->verifyPurchase($key);

            if ($envatoResult['success']) {
                $envatoItem = EnvatoItem::firstOrCreate(
                    ['item_id' => $envatoResult['item_id']],
                    [
                        'name' => $envatoResult['item_name'],
                        'url' => 'https://codecanyon.net/item/' . $envatoResult['item_id']
                    ]
                );

                $purchase = EnvatoPurchase::create([
                    'envato_item_id' => $envatoItem->id,
                    'purchase_code' => $key,
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
            if (!$purchase->is_active) {
                $errorMessage = 'This license has been deactivated by the author.';
            } else {
                $isValid = true;
            }
        }

        if ($isValid && $purchase && $productId) {
            $item = $purchase->item;
            if ($item && $item->item_id !== $productId) {
                $isValid = false;
                $errorMessage = "License matches product '{$item->name}' (ID: {$item->item_id}) but not requested product (ID: {$productId}).";
            }
        }

        return [
            'valid' => $isValid,
            'purchase_code' => $key,
            'buyer' => $purchase ? $purchase->envato_username : null,
            'purchase_date' => $purchase ? $purchase->purchase_date : null,
            'support_expiry' => $purchase ? $purchase->support_expiry : null,
            'license_type' => $purchase ? $purchase->license_type : null,
            'error' => $errorMessage,
            'legacy_purchase_id' => $purchase ? $purchase->id : null,
        ];
    }

    public function activate(string $key, string $domain, ?string $ipAddress = null): bool
    {
        $verifyResult = $this->verify($key);
        if (!$verifyResult['valid']) {
            return false;
        }

        LicenseVerification::create([
            'envato_purchase_id' => $verifyResult['legacy_purchase_id'],
            'purchase_code' => $key,
            'domain' => $domain,
            'ip_address' => $ipAddress,
            'is_valid' => true,
        ]);

        return true;
    }

    public function deactivate(string $key, string $domain): bool
    {
        return true;
    }

    public function validate(string $key, string $domain): bool
    {
        $verifyResult = $this->verify($key);
        return $verifyResult['valid'];
    }

    public function checkSupport(string $key): array
    {
        $verifyResult = $this->verify($key);
        if (!$verifyResult['valid']) {
            return ['active' => false, 'expires_at' => null];
        }

        $expiresAt = $verifyResult['support_expiry'];
        $active = $expiresAt ? Carbon::parse($expiresAt)->isFuture() : false;

        return [
            'active' => $active,
            'expires_at' => $expiresAt,
        ];
    }
}
