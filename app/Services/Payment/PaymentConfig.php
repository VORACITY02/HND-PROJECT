<?php

namespace App\Services\Payment;

use App\Models\PaymentSetting;

class PaymentConfig
{
    public function currency(): string
    {
        // This application is configured to work only in West African CFA franc.
        // Amounts are stored/sent as whole XOF units (no decimals).
        return 'XOF';
    }

    public function studentFeeCents(): int
    {
        return (int) (PaymentSetting::get('student_fee_cents', (string) env('PAYMENT_STUDENT_FEE_CENTS', '0')) ?? '0');
    }

    public function staffBasePayCents(): int
    {
        return (int) (PaymentSetting::get('staff_base_pay_cents', (string) env('PAYMENT_STAFF_BASE_PAY_CENTS', '0')) ?? '0');
    }

    public function supervisorFixedBonusCents(): int
    {
        return (int) (PaymentSetting::get('supervisor_fixed_bonus_cents', (string) env('PAYMENT_SUPERVISOR_FIXED_BONUS_CENTS', '0')) ?? '0');
    }

    public function perSuperviseeBonusCents(): int
    {
        return (int) (PaymentSetting::get('per_supervisee_bonus_cents', (string) env('PAYMENT_PER_SUPERVISEE_BONUS_CENTS', '0')) ?? '0');
    }

    public function systemExternalAccountId(): string
    {
        return PaymentSetting::get('system_external_account_id', (string) env('PAYMENT_SYSTEM_ACCOUNT_ID', '')) ?? '';
    }

    /**
     * RabbitMaid supports two application wallets (mtn/orange).
     * We keep them backend-configured and do not expose the choice to end users.
     */
    public function rabbitMaidStudentService(): string
    {
        return (string) env('RABBITMAID_STUDENT_SERVICE', 'mtn');
    }

    public function rabbitMaidStaffService(): string
    {
        return (string) env('RABBITMAID_STAFF_SERVICE', 'orange');
    }

    public function systemExternalAccountIdForStudentPayments(): string
    {
        // Simulator keeps a single system wallet id; RabbitMaid uses service names.
        if ($this->paymentDriver() === 'rabbitmaid') {
            return strtolower(trim($this->rabbitMaidStudentService()));
        }

        return $this->systemExternalAccountId();
    }

    public function systemExternalAccountIdForStaffPayouts(): string
    {
        if ($this->paymentDriver() === 'rabbitmaid') {
            return strtolower(trim($this->rabbitMaidStaffService()));
        }

        return $this->systemExternalAccountId();
    }

    public function simulatorBaseUrl(): string
    {
        return (string) config('services.payment_simulator.base_url');
    }

    public function simulatorApiKey(): string
    {
        return (string) config('services.payment_simulator.api_key');
    }

    public function paymentDriver(): string
    {
        return (string) env('PAYMENT_DRIVER', 'simulator');
    }

    public function rabbitMaidEndpoint(): string
    {
        return (string) config('services.rabbitmaid.endpoint');
    }

    public function rabbitMaidApplicationKey(): string
    {
        return (string) config('services.rabbitmaid.application_key');
    }

    public function rabbitMaidAccessKey(): string
    {
        return (string) config('services.rabbitmaid.access_key');
    }

    public function rabbitMaidSecretKey(): string
    {
        return (string) config('services.rabbitmaid.secret_key');
    }

    public function rabbitMaidService(): string
    {
        return (string) config('services.rabbitmaid.service', env('RABBITMAID_SERVICE', 'mtn'));
    }
}
