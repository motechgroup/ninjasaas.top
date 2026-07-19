<?php

namespace App\Services\Licensing;

use App\Models\License;
use App\Models\LicenseActivation;
use Carbon\Carbon;

class SaaSNinjaLicenseProvider implements LicenseProviderInterface
{
    public function verify(string $key, ?string $productId = null): array
    {
        $license = License::where('license_key', $key)->first();

        if (!$license) {
            return [
                'valid' => false,
                'error' => 'License key not found.',
            ];
        }

        if (!$license->is_active) {
            return [
                'valid' => false,
                'error' => 'This license key has been deactivated.',
            ];
        }

        if ($license->expires_at && $license->expires_at->isPast()) {
            return [
                'valid' => false,
                'error' => 'This license key has expired.',
            ];
        }

        if ($productId && $license->product_id != $productId && $license->product->slug !== $productId) {
            return [
                'valid' => false,
                'error' => "License belongs to product '{$license->product->name}' but not requested product.",
            ];
        }

        return [
            'valid' => true,
            'purchase_code' => $key,
            'buyer' => $license->user ? $license->user->name : 'SaaSNinja Client',
            'purchase_date' => $license->purchased_at,
            'support_expiry' => $license->support_expires_at,
            'license_type' => 'Direct License',
            'error' => null,
            'license_id' => $license->id,
        ];
    }

    public function activate(string $key, string $domain, ?string $ipAddress = null): bool
    {
        $verifyResult = $this->verify($key);
        if (!$verifyResult['valid']) {
            return false;
        }

        $licenseId = $verifyResult['license_id'];

        $exists = LicenseActivation::where('license_id', $licenseId)
            ->where('domain', $domain)
            ->exists();

        if (!$exists) {
            LicenseActivation::create([
                'license_id' => $licenseId,
                'domain' => $domain,
                'ip_address' => $ipAddress,
                'activated_at' => Carbon::now(),
            ]);
        }

        return true;
    }

    public function deactivate(string $key, string $domain): bool
    {
        $license = License::where('license_key', $key)->first();
        if (!$license) {
            return false;
        }

        LicenseActivation::where('license_id', $license->id)
            ->where('domain', $domain)
            ->delete();

        return true;
    }

    public function validate(string $key, string $domain): bool
    {
        $verifyResult = $this->verify($key);
        if (!$verifyResult['valid']) {
            return false;
        }

        return LicenseActivation::where('license_id', $verifyResult['license_id'])
            ->where('domain', $domain)
            ->exists();
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
