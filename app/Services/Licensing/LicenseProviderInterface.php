<?php

namespace App\Services\Licensing;

interface LicenseProviderInterface
{
    /**
     * Verify a license key or purchase code.
     * Should return verification details or throw exceptions.
     */
    public function verify(string $key, ?string $productId = null): array;

    /**
     * Activate a license key for a domain.
     */
    public function activate(string $key, string $domain, ?string $ipAddress = null): bool;

    /**
     * Deactivate a license key for a domain.
     */
    public function deactivate(string $key, string $domain): bool;

    /**
     * Validate an already activated license for a domain.
     */
    public function validate(string $key, string $domain): bool;

    /**
     * Check support status.
     */
    public function checkSupport(string $key): array;
}
