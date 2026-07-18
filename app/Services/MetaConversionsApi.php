<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaConversionsApi
{
    /**
     * Send a Purchase event to the Conversions API.
     * Failures are logged but never prevent the order response.
     */
    public function sendPurchase(Request $request, Order $order, string $eventId): void
    {
        if (! config('services.meta.capi_enabled')) {
            return;
        }

        $pixelId     = config('services.meta.pixel_id');
        $accessToken = config('services.meta.capi_access_token');
        $apiVersion  = config('services.meta.api_version', 'v23.0');

        if (! $pixelId || ! $accessToken) {
            return;
        }

        // ── Build user_data with hashed PII ─────────────────
        $phone      = $this->normalizePhone($order->customer_phone);
        $nameParts  = $this->splitName($order->customer_name);

        $userData = array_filter([
            'client_ip_address' => $request->ip(),
            'client_user_agent' => $request->userAgent(),
            'ph'                => $phone      ? hash('sha256', $phone)              : null,
            'fn'                => $nameParts['fn'] ? hash('sha256', $nameParts['fn']) : null,
            'ln'                => $nameParts['ln'] ? hash('sha256', $nameParts['ln']) : null,
            'fbp'               => $request->cookie('_fbp') ?: null,
            'fbc'               => $request->cookie('_fbc') ?: null,
        ]);

        // ── Build contents array from order items ───────────
        $contents = $order->items->map(fn ($item) => [
            'id'         => $item->product_name,
            'quantity'   => $item->quantity,
            'item_price' => (float) $item->price,
        ])->toArray();

        // ── Build event payload ─────────────────────────────
        $eventPayload = [
            'event_name'       => 'Purchase',
            'event_time'       => time(),
            'event_id'         => $eventId,
            'action_source'    => 'website',
            'event_source_url' => $request->headers->get('referer') ?: url('/'),
            'user_data'        => $userData,
            'custom_data'      => [
                'currency'     => 'EGP',
                'value'        => (float) $order->total_amount,
                'order_id'     => $order->order_number,
                'contents'     => $contents,
                'content_type' => 'product',
            ],
        ];

        $body = [
            'data'         => [$eventPayload],
            'access_token' => $accessToken,
        ];

        // Attach test code when debugging in Events Manager
        $testCode = config('services.meta.test_event_code');
        if ($testCode) {
            $body['test_event_code'] = $testCode;
        }

        // ── Fire & forget ───────────────────────────────────
        $url = "https://graph.facebook.com/{$apiVersion}/{$pixelId}/events";

        try {
            $response = Http::timeout(5)
                ->asJson()
                ->post($url, $body);

            if ($response->failed()) {
                Log::warning('Meta CAPI request failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                    'order'  => $order->order_number,
                ]);
            } else {
                Log::info('Meta CAPI Purchase sent', [
                    'order'    => $order->order_number,
                    'event_id' => $eventId,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Meta CAPI exception', [
                'message' => $e->getMessage(),
                'order'   => $order->order_number,
            ]);
        }
    }

    // ─────────────────────────────────────────────────────────
    //  PageView
    // ─────────────────────────────────────────────────────────

    /**
     * Send a PageView event to the Conversions API.
     * Called server-side on every page load alongside the browser pixel.
     */
    public function sendPageView(Request $request, string $eventId): void
    {
        if (! config('services.meta.capi_enabled')) {
            return;
        }

        $pixelId     = config('services.meta.pixel_id');
        $accessToken = config('services.meta.capi_access_token');
        $apiVersion  = config('services.meta.api_version', 'v23.0');

        if (! $pixelId || ! $accessToken) {
            return;
        }

        $userData = array_filter([
            'client_ip_address' => $request->ip(),
            'client_user_agent' => $request->userAgent(),
            'fbp'               => $request->cookie('_fbp') ?: null,
            'fbc'               => $request->cookie('_fbc') ?: null,
        ]);

        $body = [
            'data' => [[
                'event_name'       => 'PageView',
                'event_time'       => time(),
                'event_id'         => $eventId,
                'action_source'    => 'website',
                'event_source_url' => $request->url(),
                'user_data'        => $userData,
            ]],
            'access_token' => $accessToken,
        ];

        $testCode = config('services.meta.test_event_code');
        if ($testCode) {
            $body['test_event_code'] = $testCode;
        }

        $url = "https://graph.facebook.com/{$apiVersion}/{$pixelId}/events";

        try {
            $response = Http::timeout(5)->asJson()->post($url, $body);

            if ($response->failed()) {
                Log::warning('Meta CAPI PageView failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            } else {
                Log::info('Meta CAPI PageView sent', ['event_id' => $eventId]);
            }
        } catch (\Throwable $e) {
            Log::error('Meta CAPI PageView exception', ['message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────────────────
    //  Generic Event (AddToCart, InitiateCheckout, etc.)
    // ─────────────────────────────────────────────────────────

    /**
     * Send any standard Meta event to the Conversions API.
     * Used for AddToCart, InitiateCheckout, ViewContent, etc.
     */
    public function sendEvent(Request $request, string $eventName, array $customData, string $eventId): void
    {
        if (! config('services.meta.capi_enabled')) {
            return;
        }

        $pixelId     = config('services.meta.pixel_id');
        $accessToken = config('services.meta.capi_access_token');
        $apiVersion  = config('services.meta.api_version', 'v23.0');

        if (! $pixelId || ! $accessToken) {
            return;
        }

        $userData = array_filter([
            'client_ip_address' => $request->ip(),
            'client_user_agent' => $request->userAgent(),
            'fbp'               => $request->cookie('_fbp') ?: null,
            'fbc'               => $request->cookie('_fbc') ?: null,
        ]);

        $body = [
            'data' => [[
                'event_name'       => $eventName,
                'event_time'       => time(),
                'event_id'         => $eventId,
                'action_source'    => 'website',
                'event_source_url' => $request->headers->get('referer') ?: url('/'),
                'user_data'        => $userData,
                'custom_data'      => $customData,
            ]],
            'access_token' => $accessToken,
        ];

        $testCode = config('services.meta.test_event_code');
        if ($testCode) {
            $body['test_event_code'] = $testCode;
        }

        $url = "https://graph.facebook.com/{$apiVersion}/{$pixelId}/events";

        try {
            $response = Http::timeout(5)->asJson()->post($url, $body);

            if ($response->failed()) {
                Log::warning("Meta CAPI {$eventName} failed", [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            } else {
                Log::info("Meta CAPI {$eventName} sent", ['event_id' => $eventId]);
            }
        } catch (\Throwable $e) {
            Log::error("Meta CAPI {$eventName} exception", ['message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────────────────
    //  Helpers
    // ─────────────────────────────────────────────────────────


    /**
     * Normalize Egyptian phone to international format without '+'.
     * Handles all common input formats:
     *   01112345678   → 201112345678  (local with leading 0)
     *   201112345678  → 201112345678  (already has country code)
     *   +201112345678 → 201112345678  (with + sign)
     *   1112345678    → 201112345678  (missing both 0 and country code)
     */
    private function normalizePhone(string $phone): string
    {
        // Strip everything except digits
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Already correct: 20 + 10 digits = 12 digits
        if (str_starts_with($phone, '20') && strlen($phone) === 12) {
            return $phone;
        }

        // Local Egyptian format: 0 + 10 digits = 11 digits
        if (str_starts_with($phone, '0') && strlen($phone) === 11) {
            return '20' . substr($phone, 1);
        }

        // Missing leading 0: 10 digits only
        if (strlen($phone) === 10) {
            return '20' . $phone;
        }

        return $phone;
    }

    /**
     * Split a full name into first / last name (lowercase, trimmed).
     * If only one word, last name is left empty.
     */
    private function splitName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName), 2);

        return [
            'fn' => mb_strtolower(trim($parts[0] ?? '')),
            'ln' => mb_strtolower(trim($parts[1] ?? '')),
        ];
    }
}
