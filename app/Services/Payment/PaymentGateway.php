<?php

namespace App\Services\Payment;

interface PaymentGateway
{
    /**
     * Create an external wallet/account and return its identifier.
     */
    public function createAccount(string $name, string $reference): string;

    /**
     * Get current balance for a wallet/account.
     *
     * @return array{balance_cents:int,currency:string}
     */
    public function getBalance(string $externalAccountId): array;

    /**
     * Transfer funds between wallets/accounts.
     *
     * @return array{transfer_id:string,status:string}
     */
    public function transfer(string $fromExternalAccountId, string $toExternalAccountId, int $amountMinor, string $currency, string $reference): array;
}
