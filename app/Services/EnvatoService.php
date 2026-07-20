<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EnvatoService
{
    protected string $token;
    protected string $clientId;
    protected string $clientSecret;
    protected string $redirectUri;

    public function __construct()
    {
        $this->token = \App\Models\Setting::get('envato_personal_token', config('envato.personal_token', ''));
        $this->clientId = \App\Models\Setting::get('envato_client_id', config('envato.client_id', ''));
        $this->clientSecret = \App\Models\Setting::get('envato_client_secret', config('envato.client_secret', ''));
        $this->redirectUri = \App\Models\Setting::get('envato_redirect_uri', config('envato.redirect_uri', url('/auth/envato/callback')));
        if (empty($this->redirectUri)) {
            $this->redirectUri = url('/auth/envato/callback');
        }
    }

    /**
     * Verify a purchase code using Envato API v3.
     * Includes simulated fallback for testing when no token is present.
     */
    public function verifyPurchase(string $purchaseCode): array
    {
        $purchaseCode = trim($purchaseCode);

        // Simulated Sandbox/Mock Mode if no token is configured
        if (empty($this->token)) {
            return $this->getMockPurchaseData($purchaseCode);
        }

        try {
            $response = Http::withToken($this->token)
                ->timeout(10)
                ->get("https://api.envato.com/v3/market/author/sale", [
                    'code' => $purchaseCode
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'purchase_code' => $purchaseCode,
                    'item_id' => $data['item']['id'] ?? null,
                    'item_name' => $data['item']['name'] ?? null,
                    'buyer' => $data['buyer'] ?? null,
                    'purchase_date' => isset($data['sold_at']) ? Carbon::parse($data['sold_at']) : null,
                    'support_expiry' => isset($data['supported_until']) ? Carbon::parse($data['supported_until']) : null,
                    'license_type' => $data['license'] ?? 'Regular License',
                    'raw_data' => $data
                ];
            }

            $errorMessage = $response->json('description') ?? 'Envato API Error: ' . $response->status();
            return [
                'success' => false,
                'error' => $errorMessage
            ];

        } catch (\Exception $e) {
            Log::error('Envato API verification exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Could not connect to Envato API. ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate Envato OAuth Login URL.
     */
    public function getOAuthUrl(string $state = ''): string
    {
        if (empty($this->clientId)) {
            return '#';
        }

        // If clientSecret is empty, use Envato Implicit Grant Flow (response_type=token)
        $responseType = empty($this->clientSecret) ? 'token' : 'code';

        $queries = http_build_query([
            'response_type' => $responseType,
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'state' => $state,
        ]);

        return "https://api.envato.com/authorization?" . $queries;
    }

    /**
     * Handle OAuth callback to exchange authorization code for access token.
     */
    public function exchangeCodeForToken(string $code): array
    {
        if (empty($this->clientId) || empty($this->clientSecret)) {
            return [
                'success' => false,
                'error' => 'Envato OAuth client credentials not configured.'
            ];
        }

        try {
            $response = Http::asForm()->post("https://api.envato.com/token", [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUri,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'access_token' => $response->json('access_token'),
                    'refresh_token' => $response->json('refresh_token'),
                    'expires_in' => $response->json('expires_in'),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('error_description') ?? 'Failed to exchange Envato OAuth code.'
            ];
        } catch (\Exception $e) {
            Log::error('Envato OAuth exchange exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'OAuth token exchange exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Retrieve OAuth user profile details.
     */
    public function getUserProfile(string $accessToken): array
    {
        try {
            $response = Http::withToken($accessToken)
                ->get("https://api.envato.com/v1/market/private/user/username.json");

            if ($response->successful()) {
                $username = $response->json('username');
                
                // Get additional user detail e.g., email or avatar from other private details
                $detailResponse = Http::withToken($accessToken)
                    ->get("https://api.envato.com/v1/market/private/user/account.json");

                $account = $detailResponse->successful() ? $detailResponse->json('account') : [];

                $email = $account['email'] ?? null;
                if (!$email) {
                    $emailResponse = Http::withToken($accessToken)
                        ->get("https://api.envato.com/v1/market/private/user/email.json");
                    if ($emailResponse->successful()) {
                        $email = $emailResponse->json('email');
                    }
                }

                $avatar = $account['image'] ?? ($account['avatar'] ?? null);

                return [
                    'success' => true,
                    'username' => $username,
                    'email' => $email,
                    'avatar' => $avatar,
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to retrieve Envato user profile.'
            ];
        } catch (\Exception $e) {
            Log::error('Envato Profile fetch exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Simulated purchase data generator for testing.
     */
    protected function getMockPurchaseData(string $purchaseCode): array
    {
        // Simple validation of the format: any 36-character string or standard test codes
        if (strlen($purchaseCode) < 10) {
            return [
                'success' => false,
                'error' => 'Invalid purchase code format. Code must be at least 10 characters.'
            ];
        }

        // Mock verification database
        // We will assume code starting with 'invalid' or containing '000000' is invalid
        if (str_contains(strtolower($purchaseCode), 'invalid') || str_contains($purchaseCode, '0000-0000')) {
            return [
                'success' => false,
                'error' => 'The purchase code was not found or is already associated with another account.'
            ];
        }

        // Simulated products matching Envato Item IDs
        $mockItems = [
            '12345678' => 'LexCore - Premium Multipurpose SaaS Landing Engine',
            '87654321' => 'SaaSNinja CRM - Enterprise Customer Portal',
            '55556666' => 'NinjaDoc - Developer API Documentation Platform',
        ];

        $itemKeys = array_keys($mockItems);
        // Deterministic mock selection based on purchase code length or contents
        $itemIndex = abs(crc32($purchaseCode)) % count($itemKeys);
        $itemId = $itemKeys[$itemIndex];
        $itemName = $mockItems[$itemId];

        return [
            'success' => true,
            'purchase_code' => $purchaseCode,
            'item_id' => $itemId,
            'item_name' => $itemName,
            'buyer' => 'envato_ninja_' . substr(md5($purchaseCode), 0, 6),
            'purchase_date' => Carbon::now()->subMonths(3),
            'support_expiry' => Carbon::now()->addMonths(3), // support active
            'license_type' => (crc32($purchaseCode) % 3 === 0) ? 'Extended License' : 'Regular License',
            'is_mock' => true,
            'raw_data' => [
                'sold_at' => Carbon::now()->subMonths(3)->toIso8601String(),
                'supported_until' => Carbon::now()->addMonths(3)->toIso8601String(),
                'license' => 'Regular License'
            ]
        ];
    }
}
