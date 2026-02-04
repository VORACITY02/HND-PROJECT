<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;

class PaymentSimulatorClient

{
    public function __construct(private PaymentConfig $config)
    {
    }

    private function client()
    {
        $baseUrl = rtrim($this->config->simulatorBaseUrl(), '/');
        return Http::baseUrl($baseUrl)
            ->withHeaders([
                'Accept' => 'application/json',
                'X-API-Key' => $this->config->simulatorApiKey(),
            ])
            ->timeout(15);
    }

    /**
     * Generic contract:
     * POST /accounts { name, reference } => { account_id }
     */
    public function createAccount(string $name, string $reference): string
    {
        $resp = $this->client()->post('/accounts', [
            'name' => $name,
            'reference' => $reference,
        ])->throw();

        return (string) ($resp->json('account_id') ?? '');
    }

    /**
     * GET /accounts/{id}/balance => { balance_cents, currency }
     */
    public function getBalance(string $externalAccountId): array
    {
        $resp = $this->client()->get("/accounts/{$externalAccountId}/balance")->throw();

        return [
            'balance_cents' => (int) ($resp->json('balance_cents') ?? 0),
            'currency' => (string) ($resp->json('currency') ?? $this->config->currency()),
        ];
    }

    /**
     * POST /transfers { from_account_id, to_account_id, amount_cents, currency, reference } => { transfer_id, status }
     */
    public function transfer(string $fromExternalAccountId, string $toExternalAccountId, int $amountCents, string $currency, string $reference): array
    {
        $resp = $this->client()->post('/transfers', [
            'from_account_id' => $fromExternalAccountId,
            'to_account_id' => $toExternalAccountId,
            'amount_cents' => $amountCents,
            'currency' => $currency,
            'reference' => $reference,
        ])->throw();

        return [
            'transfer_id' => (string) ($resp->json('transfer_id') ?? ''),
            'status' => (string) ($resp->json('status') ?? ''),
        ];
    }
}
