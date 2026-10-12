<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MidtransService
{
    protected string $merchantId;
    protected string $serverKey;
    protected string $clientKey;
    protected bool $isProduction;
    protected bool $simulatorEnabled;
    protected string $apiUrl;

    public function __construct()
    {
        $this->merchantId = trim((string) SiteSetting::get('midtrans_merchant_id', (string) config('midtrans.merchant_id', '')));
        $this->serverKey  = trim((string) SiteSetting::get('midtrans_server_key', (string) config('midtrans.server_key', '')));
        $this->clientKey  = trim((string) SiteSetting::get('midtrans_client_key', (string) config('midtrans.client_key', '')));

        $isProdSetting = SiteSetting::get('midtrans_is_production', null);
        if ($isProdSetting !== null) {
            $this->isProduction = filter_var($isProdSetting, FILTER_VALIDATE_BOOLEAN);
        } else {
            $this->isProduction = (bool) config('midtrans.is_production', false);
        }

        $simSetting = SiteSetting::get('midtrans_enable_simulator', null);
        if ($simSetting !== null) {
            $this->simulatorEnabled = filter_var($simSetting, FILTER_VALIDATE_BOOLEAN);
        } else {
            // Simulator hanya aktif otomatis pada lingkungan local.
            $this->simulatorEnabled = app()->environment('local') && ! $this->isProduction;
        }

        $this->apiUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1'
            : 'https://app.sandbox.midtrans.com/snap/v1';
    }

    /**
     * Uji koneksi ke Midtrans API menggunakan Server Key
     */
    public function testConnection(?string $serverKey = null, ?bool $isProduction = null): array
    {
        $key = $serverKey !== null ? trim($serverKey) : $this->serverKey;
        $isProd = $isProduction !== null ? $isProduction : $this->isProduction;

        if (empty($key)) {
            return [
                'success'   => false,
                'status'    => 'empty',
                'message'   => 'Server Key belum diisi. Masukkan Server Key dari dashboard Midtrans.',
                'http_code' => 0,
            ];
        }

        $targetUrl = $isProd
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $payload = [
            'transaction_details' => [
                'order_id'     => 'PING-TEST-' . strtoupper(Str::random(6)),
                'gross_amount' => 10000,
            ],
        ];

        try {
            $http = Http::withBasicAuth($key, '')
                ->acceptJson()
                ->timeout(12);

            if (! $isProd && app()->environment('local')) {
                $http = $http->withoutVerifying();
            }

            $response = $http->post($targetUrl, $payload);
            $statusCode = $response->status();

            if ($response->successful()) {
                return [
                    'success'   => true,
                    'status'    => 'connected',
                    'message'   => 'Koneksi ke Midtrans ' . ($isProd ? 'Production' : 'Sandbox') . ' aktif & valid! Snap Token berhasil dibuat.',
                    'http_code' => $statusCode,
                ];
            }

            if ($statusCode === 401) {
                return [
                    'success'   => false,
                    'status'    => 'unauthorized',
                    'message'   => 'Kunci Server ditolak Midtrans (401 Unauthorized). Pastikan Server Key cocok dengan akun ' . ($isProd ? 'Production' : 'Sandbox') . ' Anda.',
                    'http_code' => $statusCode,
                ];
            }

            $json = $response->json();
            $errorMessage = $json['error_messages'][0] ?? $json['message'] ?? 'Respons Midtrans: ' . $response->body();

            return [
                'success'   => false,
                'status'    => 'failed',
                'message'   => 'Midtrans mengembalikan status ' . $statusCode . ': ' . $errorMessage,
                'http_code' => $statusCode,
            ];
        } catch (\Throwable $e) {
            return [
                'success'   => false,
                'status'    => 'exception',
                'message'   => 'Tidak dapat menghubungi server Midtrans: ' . $e->getMessage(),
                'http_code' => 0,
            ];
        }
    }

    /**
     * Buat Snap Token untuk pesanan
     */
    public function createSnapToken(Order $order): ?array
    {
        $order->loadMissing(['items', 'user', 'address']);

        // Jika kredensial belum ada sama sekali dan simulator aktif
        if (empty($this->serverKey)) {
            Log::info('Midtrans Server Key kosong, menggunakan fallback simulasi');
            if ($this->isSimulatorEnabled()) {
                return $this->generateSimulatedResponse($order, 'Server Key belum diisi di pengaturan/env');
            }
            return null;
        }

        $midtransOrderId = $order->order_number . '-' . Str::lower(Str::random(8));

        $itemDetails = $order->items->map(function ($item) {
            return [
                'id'       => (string) ($item->product_id ?? $item->id),
                'price'    => (int) round((float) $item->price),
                'quantity' => (int) $item->quantity,
                'name'     => mb_substr((string) ($item->product_name ?: 'Produk'), 0, 50),
            ];
        })->values()->toArray();

        $shipping = (int) round((float) $order->shipping_cost);
        if ($shipping > 0) {
            $itemDetails[] = [
                'id'       => 'SHIPPING',
                'price'    => $shipping,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim',
            ];
        }

        // Midtrans wajib: sum(item) == gross_amount
        $grossAmount = 0;
        foreach ($itemDetails as $row) {
            $grossAmount += $row['price'] * $row['quantity'];
        }

        $payload = [
            'transaction_details' => [
                'order_id'     => $midtransOrderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details'     => $itemDetails,
            'customer_details' => [
                'first_name' => $order->user->name ?? 'Customer',
                'email'      => $order->user->email ?? 'customer@example.com',
                'phone'      => $order->address?->phone
                    ?? $order->user->phone
                    ?? '081234567890',
            ],
            'callbacks' => [
                'finish' => url('/pesanan/' . $order->id),
            ],
        ];

        try {
            $http = Http::withBasicAuth($this->serverKey, '')
                ->acceptJson()
                ->timeout(20);

            if (! $this->isProduction && app()->environment('local')) {
                $http = $http->withoutVerifying();
            }

            $response = $http->post(rtrim($this->apiUrl, '/') . '/transactions', $payload);

            if ($response->successful()) {
                $data  = $response->json();
                $token = $data['token'] ?? null;

                if ($token) {
                    $update = [];
                    if (Schema::hasColumn('orders', 'snap_token')) {
                        $update['snap_token'] = $token;
                    }
                    if (Schema::hasColumn('orders', 'midtrans_order_id')) {
                        $update['midtrans_order_id'] = $midtransOrderId;
                    }
                    if ($update) {
                        try {
                            $order->update($update);
                        } catch (\Throwable $e) {
                            Log::warning('Gagal simpan snap fields: ' . $e->getMessage());
                        }
                    }
                }

                return [
                    'token'             => $token,
                    'redirect_url'      => $data['redirect_url'] ?? null,
                    'is_simulated'      => false,
                    'simulation_reason' => null,
                ];
            }

            // Gagal dari Midtrans API (misal 401 Unauthorized / keys invalid)
            $errorInfo = $response->json();
            $errorMsg = $errorInfo['error_messages'][0] ?? $errorInfo['status_message'] ?? 'HTTP ' . $response->status();

            Log::error('Midtrans Snap Token Error', [
                'status'   => $response->status(),
                'response' => $errorInfo ?? $response->body(),
                'api_url'  => $this->apiUrl,
            ]);

            // Jika simulator aktif, alihkan ke simulasi agar pengguna tidak terblokir
            if ($this->isSimulatorEnabled()) {
                return $this->generateSimulatedResponse($order, 'Midtrans API error: ' . $errorMsg);
            }

            return null;
        } catch (\Throwable $e) {
            Log::error('Midtrans Exception: ' . $e->getMessage());

            if ($this->isSimulatorEnabled()) {
                return $this->generateSimulatedResponse($order, 'Midtrans exception: ' . $e->getMessage());
            }

            return null;
        }
    }

    /**
     * Buat response token simulasi untuk pengujian sandbox/lokal
     */
    protected function generateSimulatedResponse(Order $order, string $reason): array
    {
        $simToken = 'SIM-' . strtoupper(Str::random(24));
        $simOrderId = $order->order_number . '-sim-' . Str::lower(Str::random(6));

        $update = [];
        if (Schema::hasColumn('orders', 'snap_token')) {
            $update['snap_token'] = $simToken;
        }
        if (Schema::hasColumn('orders', 'midtrans_order_id')) {
            $update['midtrans_order_id'] = $simOrderId;
        }
        if ($update) {
            try {
                $order->update($update);
            } catch (\Throwable $e) {
                // Ignore
            }
        }

        return [
            'token'             => $simToken,
            'redirect_url'      => null,
            'is_simulated'      => true,
            'simulation_reason' => $reason,
        ];
    }

    public function verifySignature(array $payload): bool
    {
        if ($this->serverKey === '') {
            return false;
        }

        $orderId     = $payload['order_id'] ?? '';
        $statusCode  = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signature   = $payload['signature_key'] ?? '';

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        return hash_equals($expected, $signature);
    }

    public function mapTransactionStatus(string $transactionStatus, ?string $fraudStatus = null): string
    {
        if ($fraudStatus === 'challenge') {
            return 'pending';
        }

        return match ($transactionStatus) {
            'capture', 'settlement' => 'paid',
            'pending'               => 'pending',
            'deny', 'cancel', 'expire', 'failure' => 'cancelled',
            default                 => 'pending',
        };
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    public function getServerKey(): string
    {
        return $this->serverKey;
    }

    public function isProduction(): bool
    {
        return $this->isProduction;
    }

    public function isSimulatorEnabled(): bool
    {
        return $this->simulatorEnabled && ! $this->isProduction;
    }

    public function getSnapJsUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    public function isConfigured(): bool
    {
        return $this->serverKey !== '' && $this->clientKey !== '';
    }
}
