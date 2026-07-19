<?php

namespace App\Services\Licensing;

use App\Models\License;
use Illuminate\Support\Facades\App;

class LicenseManager
{
    /**
     * Resolve the license provider for a given key.
     */
    public function driver(string $key): LicenseProviderInterface
    {
        $key = trim($key);

        // Check if the key exists in our licenses table
        $license = License::where('license_key', $key)->with('provider')->first();

        if ($license && $license->provider) {
            $slug = $license->provider->slug;
            if ($slug === 'saasninja') {
                return App::make(SaaSNinjaLicenseProvider::class);
            }
        }

        // Default fallback is Envato provider
        return App::make(EnvatoLicenseProvider::class);
    }
}
