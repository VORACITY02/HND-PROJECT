<?php

namespace App\Services\Payment;

/**
 * Adapter over the existing in-project payment simulator client.
 */
class SimulatorPaymentGateway implements PaymentGateway
{
    public function __construct(private PaymentSimulatorClient $client)
    {
    }

    public function createAccount(string $name, string $reference): string
    {
        return $this->client->createAccount($name, $reference);
    }

    public function getBalance(string $externalAccountId): array
    {
        return $this->client->getBalance($externalAccountId);
    }

    public function transfer(string $fromExternalAccountId, string $toExternalAccountId, int $amountMinor, string $currency, string $reference): array
    {
        return $this->client->transfer($fromExternalAccountId, $toExternalAccountId, $amountMinor, $currency, $reference);
    }
}
