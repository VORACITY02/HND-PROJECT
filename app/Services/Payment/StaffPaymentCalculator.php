<?php

namespace App\Services\Payment;

class StaffPaymentCalculator
{
    public function __construct(private PaymentConfig $config)
    {
    }

    /**
     * Rule (as requested):
     * - All staff get base pay.
     * - If staff is an approved supervisor:
     *   - add fixed supervisor bonus
     *   - add per supervisee bonus * current supervisee count
     * - If not a supervisor: supervisor bonus = 0
     * - If supervisee count is 0, the per supervisee part is 0.
     */
    public function compute(int $superviseeCount, bool $isSupervisor): array
    {
        $base = max(0, $this->config->staffBasePayCents());
        $fixed = $isSupervisor ? max(0, $this->config->supervisorFixedBonusCents()) : 0;
        $per = ($isSupervisor && $superviseeCount > 0) ? max(0, $this->config->perSuperviseeBonusCents()) : 0;

        $total = $base + $fixed + ($per * max(0, $superviseeCount));

        return [
            'amount_cents' => $total,
            'currency' => $this->config->currency(),
            'base_pay_cents' => $base,
            'supervisor_fixed_bonus_cents' => $fixed,
            'per_supervisee_bonus_cents' => $per,
        ];
    }
}
