<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PGQRISService
{
    protected string $storeKey;
    protected string $baseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->storeKey = config('pgqris.store_key');
        $this->baseUrl = rtrim(config('pgqris.base_url', 'https://rest.pgqris.com'), '/');
        $this->timeout = config('pgqris.timeout', 30);
    }

    /**
     * Buat Transaksi QRIS
     */
    public function generateQris(int|float $amount, string $playerUsername = '', string $notes = ''): array
    {
        return $this->sendRequest('/payment/api/generate', [
            'key' => $this->storeKey,
            'channel' => 'QRIS',
            'amount' => (int)$amount,
            'player_username' => $playerUsername,
            'notes' => $notes
        ]);
    }

    /**
     * Buat Transaksi DANA
     */
    public function generateDana(int|float $amount, string $playerUsername = '', string $notes = ''): array
    {
        return $this->sendRequest('/payment/api/generate', [
            'key' => $this->storeKey,
            'channel' => 'DANA',
            'amount' => (int)$amount,
            'player_username' => $playerUsername,
            'notes' => $notes
        ]);
    }

    /**
     * Cek Status Transaksi
     */
    public function checkStatus(string $checkId): array
    {
        return $this->sendRequest('/payment/api/check', [
            'key' => $this->storeKey,
            'check_id' => $checkId
        ]);
    }

    /**
     * Cek Saldo Toko
     */
    public function checkBalance(): array
    {
        return $this->sendRequest('/payment/api/merchantbalance', [
            'key' => $this->storeKey
        ]);
    }

    /**
     * Internal HTTP Client via Laravel Http Facade
     */
    protected function sendRequest(string $endpoint, array $data): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . $endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PGQRIS API Error: ' . $response->body());
            return [
                'success' => false,
                'message' => 'Server error code: ' . $response->status(),
                'data' => $response->json()
            ];
        } catch (Exception $e) {
            Log::error('PGQRIS Connection Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
