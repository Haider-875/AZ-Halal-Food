<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected string $mode;

    protected string $clientId;

    protected string $clientSecret;

    protected string $currency;

    public function __construct()
    {
        $this->mode = config('services.paypal.mode', 'sandbox');
        $this->clientId = config('services.paypal.client_id', 'sb');
        $this->clientSecret = config('services.paypal.client_secret', '');
        $this->currency = config('services.paypal.currency', 'USD');
    }

    public function getBaseUrl(): string
    {
        return $this->mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function isConfigured(): bool
    {
        return ! empty($this->clientId) && ! empty($this->clientSecret) && $this->clientId !== 'sb';
    }

    /**
     * Get OAuth2 Access Token from PayPal with caching
     */
    public function getAccessToken(): ?string
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $cacheKey = 'paypal_access_token_'.md5($this->clientId.$this->mode);

        return Cache::remember($cacheKey, 3000, function () {
            try {
                $response = Http::asForm()
                    ->withBasicAuth($this->clientId, $this->clientSecret)
                    ->post($this->getBaseUrl().'/v1/oauth2/token', [
                        'grant_type' => 'client_credentials',
                    ]);

                if ($response->successful()) {
                    return $response->json('access_token');
                }

                Log::error('PayPal getAccessToken failed: '.$response->body());

                return null;
            } catch (\Throwable $e) {
                Log::error('PayPal OAuth exception: '.$e->getMessage());

                return null;
            }
        });
    }

    /**
     * Create an order via PayPal Orders API v2
     */
    public function createOrder(float $amount, string $currency = 'USD', ?string $referenceId = null, array $items = []): ?array
    {
        $token = $this->getAccessToken();
        if (! $token) {
            return null;
        }

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $referenceId ?: ('AZ-'.uniqid()),
                    'description' => 'AZ Halal Marts Order',
                    'amount' => [
                        'currency_code' => $currency ?: $this->currency,
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                ],
            ],
            'application_context' => [
                'brand_name' => 'AZ Halal Marts',
                'landing_page' => 'NO_PREFERENCE',
                'user_action' => 'PAY_NOW',
                'return_url' => route('checkout.success'),
                'cancel_url' => route('checkout'),
            ],
        ];

        try {
            $response = Http::withToken($token)
                ->post($this->getBaseUrl().'/v2/checkout/orders', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayPal createOrder failed: '.$response->body());

            return null;
        } catch (\Throwable $e) {
            Log::error('PayPal createOrder exception: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Capture payment for an order via PayPal Orders API v2
     */
    public function captureOrder(string $paypalOrderId): ?array
    {
        $token = $this->getAccessToken();
        if (! $token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Prefer' => 'return=representation',
                ])
                ->post($this->getBaseUrl()."/v2/checkout/orders/{$paypalOrderId}/capture", new \stdClass);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("PayPal captureOrder failed for {$paypalOrderId}: ".$response->body());

            return null;
        } catch (\Throwable $e) {
            Log::error("PayPal captureOrder exception for {$paypalOrderId}: ".$e->getMessage());

            return null;
        }
    }

    /**
     * Retrieve order details from PayPal Orders API v2
     */
    public function getOrder(string $paypalOrderId): ?array
    {
        $token = $this->getAccessToken();
        if (! $token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->get($this->getBaseUrl()."/v2/checkout/orders/{$paypalOrderId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("PayPal getOrder failed for {$paypalOrderId}: ".$response->body());

            return null;
        } catch (\Throwable $e) {
            Log::error("PayPal getOrder exception for {$paypalOrderId}: ".$e->getMessage());

            return null;
        }
    }
}
