<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KuickPayService
{
    private string $baseUrl;
    private string $username;
    private string $password;
    private string $bankMnemonic;

    public function __construct()
    {
        $this->baseUrl      = config('services.kuickpay.base_url', 'https://api.kuickpay.com');
        $this->username     = config('services.kuickpay.username', 'Test');
        $this->password     = config('services.kuickpay.password', '@bcd');
        $this->bankMnemonic = config('services.kuickpay.bank_mnemonic', 'KPY');
    }

    // ── Bill Inquiry ─────────────────────────────────
    public function billInquiry(string $consumerNumber): array
    {
        try {
            $response = Http::withHeaders([
                'username' => $this->username,
                'password' => $this->password,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/api/v1/BillInquiry', [
                'consumer_number' => $consumerNumber,
                'bank_mnemonic'   => $this->bankMnemonic,
                'reserved'        => 'BazaarHub Order Inquiry',
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data'    => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Inquiry failed. Status: ' . $response->status(),
                'data'    => $response->json(),
            ];

        } catch (\Exception $e) {
            Log::error('KuickPay Inquiry Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ];
        }
    }

    // ── Bill Payment ──────────────────────────────────
    public function billPayment(
        string $consumerNumber,
        string $tranAuthId,
        float  $amount,
        string $tranDate,
        string $tranTime
    ): array {
        try {
            $response = Http::withHeaders([
                'username' => $this->username,
                'password' => $this->password,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/api/v1/BillPayment', [
                'consumer_number'    => $consumerNumber,
                'tran_auth_id'       => $tranAuthId,
                'transaction_amount' => (string) $amount,
                'tran_date'          => $tranDate,
                'tran_time'          => $tranTime,
                'bank_mnemonic'      => $this->bankMnemonic,
                'reserved'           => 'BazaarHub Payment',
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data'    => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Payment failed. Status: ' . $response->status(),
                'data'    => $response->json(),
            ];

        } catch (\Exception $e) {
            Log::error('KuickPay Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ];
        }
    }

    // ── Format Response Code ──────────────────────────
    public function getResponseMessage(string $code, string $type = 'inquiry'): string
    {
        $inquiryCodes = [
            '00' => 'Successful Bill Inquiry',
            '01' => 'Voucher Number does not exist',
            '02' => 'Voucher Blocked or Inactive',
            '03' => 'Unknown Error/Null Transaction',
            '04' => 'Invalid Data',
            '05' => 'Service Fail',
        ];

        $paymentCodes = [
            '00' => 'Successful Bill Payment',
            '01' => 'Voucher Number does not exist',
            '02' => 'Voucher Blocked or Inactive Transaction',
            '03' => 'Duplicate/Null Transaction',
            '04' => 'Invalid Data',
            '05' => 'Service Fail',
        ];

        $codes = $type === 'payment' ? $paymentCodes : $inquiryCodes;
        return $codes[$code] ?? 'Unknown Response Code: ' . $code;
    }
}
