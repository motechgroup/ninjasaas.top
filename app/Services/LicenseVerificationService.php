<?php

namespace App\Services;

use App\Services\Licensing\LicenseManager;
use App\Models\LicenseVerification;
use Carbon\Carbon;

class LicenseVerificationService
{
    protected LicenseManager $licenseManager;

    public function __construct(LicenseManager $licenseManager)
    {
        $this->licenseManager = $licenseManager;
    }

    /**
     * Verifies a license key (purchase code or direct key) locally or via the resolved provider.
     * Logs the attempt in the database.
     */
    public function verifyLicense(
        string $purchaseCode,
        ?string $productId = null,
        ?string $domain = null,
        ?string $ipAddress = null
    ): array {
        $purchaseCode = trim($purchaseCode);

        // Resolve driver dynamically
        $driver = $this->licenseManager->driver($purchaseCode);

        // Run verification
        $result = $driver->verify($purchaseCode, $productId);

        $isValid = $result['valid'];
        $errorMessage = $result['error'] ?? null;

        // Log the verification in database
        $legacyPurchaseId = $result['legacy_purchase_id'] ?? null;
        $licenseId = $result['license_id'] ?? null;

        // Save activation record if domain was passed and verification is valid
        if ($isValid && $domain) {
            $driver->activate($purchaseCode, $domain, $ipAddress);
        }

        LicenseVerification::create([
            'envato_purchase_id' => $legacyPurchaseId,
            'license_id' => $licenseId,
            'purchase_code' => $purchaseCode,
            'product_id' => $productId,
            'domain' => $domain,
            'ip_address' => $ipAddress,
            'is_valid' => $isValid,
            'error_message' => $errorMessage,
        ]);

        if ($isValid) {
            return [
                'valid' => true,
                'license_status' => 'active',
                'license_type' => $result['license_type'] ?? 'Regular License',
                'buyer' => $result['buyer'] ?? 'Client',
                'purchase_date' => $result['purchase_date'] ? Carbon::parse($result['purchase_date'])->toIso8601String() : null,
                'support_expiry' => $result['support_expiry'] ? Carbon::parse($result['support_expiry'])->toIso8601String() : null,
                'support_active' => $result['support_expiry'] ? Carbon::parse($result['support_expiry'])->isFuture() : false,
                'product' => [
                    'id' => $productId,
                    'name' => $result['item_name'] ?? ($productId ? 'SaaSNinja Product' : 'Product'),
                ]
            ];
        }

        return [
            'valid' => false,
            'error' => $errorMessage ?? 'License verification failed.'
        ];
    }
}
