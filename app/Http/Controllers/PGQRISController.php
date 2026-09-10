<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PGQRISService;
use Illuminate\Support\Facades\Log;

class PGQRISController extends Controller
{
    protected PGQRISService $pgqris;

    public function __construct(PGQRISService $pgqris)
    {
        $this->pgqris = $pgqris;
    }

    /**
     * Endpoint untuk membuat invoice QRIS
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'username' => 'nullable|string|max:50'
        ]);

        $res = $this->pgqris->generateQris(
            $request->input('amount'),
            $request->input('username', 'user_app'),
            'Order #' . uniqid()
        );

        return response()->json($res);
    }

    /**
     * Endpoint Webhook Handler Laravel
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        Log::info('PGQRIS Webhook Received:', $payload);

        $status = strtolower($payload['status'] ?? '');
        $trxId = $payload['trx_id'] ?? $payload['check_id'] ?? '';

        if ($status === 'success') {
            // Update transaksi di database Anda
            // Order::where('invoice_id', $trxId)->update(['status' => 'PAID']);
            return response()->json(['status' => 'ok', 'message' => 'Transaction updated']);
        }

        return response()->json(['status' => 'received']);
    }
}
