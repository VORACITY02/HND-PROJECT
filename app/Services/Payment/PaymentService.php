<?php

namespace App\Services\Payment;

use App\Models\PaymentAccount;
use App\Models\StaffPayout;
use App\Models\StudentPayment;
use App\Models\SupervisorApplication;
use App\Models\SupervisorAssignment;
use App\Models\User;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        private PaymentConfig $config,
        private PaymentGateway $client,
        private StaffPaymentCalculator $calculator,
    ) {
    }

    public function currentSuperviseeCount(int $staffId): int
    {
        return SupervisorAssignment::query()
            ->where('supervisor_id', $staffId)
            ->where(function ($q) {
                $q->where('active', true)->orWhereNull('active');
            })
            ->count();
    }

    public function isApprovedSupervisor(int $staffId): bool
    {
        return SupervisorApplication::query()
            ->where('staff_id', $staffId)
            ->where('status', 'approved')
            ->exists();
    }

    /**
     * Executes a student -> system transfer and records it.
     */
    public function chargeStudent(User $student, ?int $amountCents = null, ?string $reference = null, ?string $note = null): StudentPayment
    {
        // In some flows (tests, auth session), the User model instance may have cached relations.
        // Force reload to ensure newly-created accounts are visible.
        $student->load('paymentAccount');
        $amountCents ??= $this->config->studentFeeCents();
        $currency = $this->config->currency();

        $systemAccount = $this->config->systemExternalAccountIdForStudentPayments();
        if ($systemAccount === '') {
            throw new \RuntimeException('System payment account is not configured');
        }

        $studentAccountId = $student->paymentAccount?->external_account_id;
        if (!$studentAccountId) {
            throw new \RuntimeException('Student has no payment account');
        }

        $reference ??= 'student-fee-' . Str::uuid();

        $payment = StudentPayment::create([
            'student_id' => $student->id,
            'amount_cents' => $amountCents,
            'currency' => $currency,
            'status' => 'pending',
            'reference' => $reference,
            'note' => $note,
        ]);

        try {
            $result = $this->client->transfer($studentAccountId, $systemAccount, $amountCents, $currency, $reference);
            $payment->update([
                'status' => 'completed',
                'external_transfer_id' => $result['transfer_id'] ?: null,
                'paid_at' => now(),
            ]);
        } catch (\Throwable $e) {
            $payment->update(['status' => 'failed']);
            throw $e;
        }

        return $payment;
    }

    /**
     * Computes and executes a system -> staff payout.
     */
    public function payStaff(User $staff, ?string $reference = null, ?string $note = null): StaffPayout
    {
        // In some flows (tests, auth session), the User model instance may have cached relations.
        // Force reload to ensure newly-created accounts are visible.
        $staff->load('paymentAccount');
        $systemAccount = $this->config->systemExternalAccountIdForStaffPayouts();
        if ($systemAccount === '') {
            throw new \RuntimeException('System payment account is not configured');
        }

        $staffAccountId = $staff->paymentAccount?->external_account_id;
        if (!$staffAccountId) {
            throw new \RuntimeException('Staff has no payment account');
        }

        $superviseeCount = $this->currentSuperviseeCount($staff->id);
        $isSupervisor = $this->isApprovedSupervisor($staff->id);

        $calc = $this->calculator->compute($superviseeCount, $isSupervisor);
        $amountCents = $calc['amount_cents'];
        $currency = $calc['currency'];

        $reference ??= 'staff-payout-' . Str::uuid();

        $payout = StaffPayout::create([
            'staff_id' => $staff->id,
            'amount_cents' => $amountCents,
            'currency' => $currency,
            'supervisee_count' => $superviseeCount,
            'base_pay_cents' => $calc['base_pay_cents'],
            'supervisor_fixed_bonus_cents' => $calc['supervisor_fixed_bonus_cents'],
            'per_supervisee_bonus_cents' => $calc['per_supervisee_bonus_cents'],
            'status' => 'pending',
            'reference' => $reference,
            'note' => $note,
        ]);

        try {
            $result = $this->client->transfer($systemAccount, $staffAccountId, $amountCents, $currency, $reference);
            $payout->update([
                'status' => 'paid',
                'external_transfer_id' => $result['transfer_id'] ?: null,
                'paid_at' => now(),
            ]);
        } catch (\Throwable $e) {
            $payout->update(['status' => 'failed']);
            throw $e;
        }

        return $payout;
    }
}
