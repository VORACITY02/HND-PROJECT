<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;

/**
 * RabbitMaid gateway.
 *
 * RabbitMaid exposes a single application-wallet transaction endpoint.
 * Auth is performed via HTTP headers: application-key, access-key, secret-key.
 *
 * Note: RabbitMaid wallets are per-application + per-service (mtn/orange).
 * This app therefore treats `system_external_account_id` as the service name.
 */
class RabbitMaidPaymentGateway implements PaymentGateway
{
    public function __construct(private PaymentConfig $config)
    {
    }

    private function client()
    {
        return Http::acceptJson()->timeout(20);
    }

    public function createAccount(string $name, string $reference): string
    {
        // RabbitMaid has no per-user wallet creation endpoint in the public API.
        // We store a placeholder local "account id" so the UI can proceed.
        return 'rabbitmaid';
    }

    public function getBalance(string $externalAccountId): array
    {
        // RabbitMaid does not expose a balance read endpoint in the provided public API.
        // We return the last known balance captured from a successful transaction.
        $service = $this->config->rabbitMaidService();
        $key = 'rabbitmaid_last_balance_' . $service;

        return [
            'balance_cents' => (int) (\App\Models\PaymentSetting::get($key, '0') ?? '0'),
            'currency' => $this->config->currency(),
        ];
    }

    public function transfer(string $fromExternalAccountId, string $toExternalAccountId, int $amountMinor, string $currency, string $reference): array
    {
        // Internally we work in XOF; RabbitMaid documentation states the wallet currency is XAF.
        // Both have 0 decimals and are treated as whole CFA units in this app.
        if (strtoupper($currency) !== 'XOF') {
            throw new \InvalidArgumentException('Currency must be XOF');
        }
        if ($amountMinor <= 0) {
            throw new \InvalidArgumentException('Amount must be a positive integer');
        }

        // Determine which RabbitMaid application wallet to operate on.
        // RabbitMaid requires `service` to be mtn/orange.
        $service = null;
        if (in_array(strtolower($fromExternalAccountId), ['mtn', 'orange'], true)) {
            $service = strtolower($fromExternalAccountId);
        } elseif (in_array(strtolower($toExternalAccountId), ['mtn', 'orange'], true)) {
            $service = strtolower($toExternalAccountId);
        } else {
            // Fallback to configured default.
            $service = strtolower(trim($this->config->rabbitMaidService()));
        }

        if (!in_array($service, ['mtn', 'orange'], true)) {
            throw new \InvalidArgumentException('RabbitMaid service must be mtn or orange');
        }

        // Mapping:
        // - student payment: student -> system(service) => credit
        // - staff payout: system(service) -> staff => debit
        $type = (strtolower($fromExternalAccountId) === $service) ? 'debit' : 'credit';

        $endpoint = rtrim($this->config->rabbitMaidEndpoint(), '/');
        if ($endpoint === '') {
            throw new \RuntimeException('RabbitMaid endpoint is not configured');
        }

        $resp = $this->client()
            ->withHeaders([
                'application-key' => $this->config->rabbitMaidApplicationKey(),
                'access-key' => $this->config->rabbitMaidAccessKey(),
                'secret-key' => $this->config->rabbitMaidSecretKey(),
                'Content-Type' => 'application/json',
            ])
            ->post($endpoint, [
                'service' => $service,
                'type' => $type,
                'amount' => $amountMinor,
            ])
            ->throw();

        $balance = (int) ($resp->json('balance') ?? 0);
        \App\Models\PaymentSetting::set('rabbitmaid_last_balance_' . $service, (string) $balance);

        return [
            'transfer_id' => (string) ($resp->json('reference') ?? ''),
            'status' => 'ok',
        ];
    }
}
