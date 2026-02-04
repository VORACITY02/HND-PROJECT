<?php

namespace App\Http\Controllers;

use App\Models\StaffPayout;
use App\Models\User;
use App\Services\Payment\PaymentConfig;
use App\Services\Payment\PaymentService;
use App\Services\Payment\StaffPaymentCalculator;
use App\Services\Payment\PaymentGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StaffPaymentController extends Controller
{
    public function __construct(
        private PaymentService $payments,
        private PaymentConfig $config,
        private StaffPaymentCalculator $calculator,
        private PaymentGateway $client,
    ) {
    }

    public function index(Request $request)
    {
        /** @var User $staff */
        $staff = $request->user();
        abort_unless($staff && $staff->role === 'staff', 403);

        $currency = $this->config->currency();

        $superviseeCount = $this->payments->currentSuperviseeCount($staff->id);
        $isSupervisor = $this->payments->isApprovedSupervisor($staff->id);
        $calc = $this->calculator->compute($superviseeCount, $isSupervisor);

        $paidTotalMinor = (int) StaffPayout::query()
            ->where('staff_id', $staff->id)
            ->where('status', 'paid')
            ->sum('amount_cents');

        // "Remaining" in this simplified model is computed payout - total paid so far.
        // (If you later move to accrual by month, this should become time-based.)
        $remainingMinor = max(0, (int)($calc['amount_cents'] ?? 0) - $paidTotalMinor);

        $balance = null;
        if ($staff->paymentAccount?->external_account_id) {
            try {
                $balance = $this->client->getBalance($staff->paymentAccount->external_account_id);
            } catch (\Throwable $e) {
                $balance = ['balance_cents' => 0, 'currency' => $currency, 'error' => $e->getMessage()];
            }
        }

        $payouts = StaffPayout::query()
            ->where('staff_id', $staff->id)
            ->latest('id')
            ->limit(20)
            ->get();

        return view('staff.payments.index', [
            'currency' => $currency,
            'calc' => $calc,
            'isSupervisor' => $isSupervisor,
            'superviseeCount' => $superviseeCount,
            'paidTotalMinor' => $paidTotalMinor,
            'remainingMinor' => $remainingMinor,
            'hasAccount' => (bool) $staff->paymentAccount,
            'externalAccountId' => $staff->paymentAccount?->external_account_id,
            'balance' => $balance,
            'payouts' => $payouts,
        ]);
    }

    public function createAccount(Request $request): RedirectResponse
    {
        /** @var User $staff */
        $staff = $request->user();
        abort_unless($staff && $staff->role === 'staff', 403);

        if ($staff->paymentAccount) {
            return back()->with('success', 'You already have a payment account.');
        }

        try {
            if ($this->config->paymentDriver() === 'rabbitmaid') {
                $externalId = 'rabbitmaid';
            } else {
                $reference = 'user-' . $staff->id;
                $externalId = $this->client->createAccount($staff->name, $reference);

                if ($externalId === '') {
                    throw new \RuntimeException('Simulator did not return an account_id');
                }
            }

            $staff->paymentAccount()->create([
                'external_account_id' => $externalId,
            ]);
        } catch (\Throwable $e) {
            return back()->with('error', 'Account creation failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Payment account created successfully.');
    }

    public function requestPayout(Request $request): RedirectResponse
    {
        /** @var User $staff */
        $staff = $request->user();
        abort_unless($staff && $staff->role === 'staff', 403);

        $request->validate([
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->payments->payStaff($staff, null, $request->string('note')->toString());
        } catch (\Throwable $e) {
            return back()->with('error', 'Payout failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Payout request submitted successfully.');
    }
}
