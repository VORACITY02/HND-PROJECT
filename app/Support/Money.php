<?php

namespace App\Support;

final class Money
{
    /**
     * Format an integer amount stored in the smallest unit for the given currency.
     *
     * Note: despite historical column naming ("*_cents"), amounts in this app are treated
     * as "minor units". For currencies like XOF (FCFA), minor units have 0 decimals.
     */
    public static function format(int $amountMinor, string $currency): string
    {
        $currency = strtoupper(trim($currency));
        $decimals = self::decimals($currency);

        if ($decimals === 0) {
            return number_format($amountMinor, 0, '.', ',');
        }

        $divisor = 10 ** $decimals;

        return number_format($amountMinor / $divisor, $decimals, '.', ',');
    }

    /**
     * Return the number of decimal places used by a currency.
     */
    public static function decimals(string $currency): int
    {
        return match (strtoupper(trim($currency))) {
            'XOF', 'XAF', 'JPY', 'KRW' => 0,
            default => 2,
        };
    }
}
