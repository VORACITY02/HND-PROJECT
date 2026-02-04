<?php

namespace App\Http\Controllers;

use App\Models\StudentPayment;
use App\Models\User;
use App\Services\Payment\PaymentConfig;
use App\Services\Payment\PaymentService;
use App\Services\Payment\PaymentGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StudentPaymentController extends Controller
{
    public function __construct(
        private PaymentService $payments,
        private PaymentConfig $config,
        private PaymentGateway $client,
    ) {
    }

    public function index(Request $request)
    {
        /** @var User $student */
        $student = $request->user();
        abort_unless($student && $student->role === 'user', 403);

        $currency = $this->config->currency();
        $feeMinor = $this->config->studentFeeCents();

        $paidMinor = (int) StudentPayment::query()
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->sum('amount_cents');

        $remainingMinor = max(0, $feeMinor - $paidMinor);

        $balance = null;
        if ($student->paymentAccount?->external_account_id) {
            try {
                $balance = $this->client->getBalance($student->paymentAccount->external_account_id);
            } catch (\Throwable $e) {
                $balance = ['balance_cents' => 0, 'currency' => $currency, 'error' => $e->getMessage()];
            }
        }

        $payments = StudentPayment::query()
            ->where('student_id', $student->id)
            ->latest('id')
            ->limit(20)
            ->get();

        return view('user.payments.index', [
            'currency' => $currency,
            'feeMinor' => $feeMinor,
            'paidMinor' => $paidMinor,
            'remainingMinor' => $remainingMinor,
            'hasAccount' => (bool) $student->paymentAccount,
            'externalAccountId' => $student->paymentAccount?->external_account_id,
            'balance' => $balance,
            'payments' => $payments,
        ]);
    }

    public function createAccount(Request $request): RedirectResponse
    {
        /** @var User $student */
        $student = $request->user();
        abort_unless($student && $student->role === 'user', 403);

        if ($student->paymentAccount) {
            return back()->with('success', 'You already have a payment account.');
        }

        try {
            if ($this->config->paymentDriver() === 'rabbitmaid') {
                // RabbitMaid wallets are application-scoped (no per-user wallet creation).
                // Store a constant marker so the app treats the user as "wallet-ready".
                $externalId = 'rabbitmaid';
            } else {
                // Simulator (default): create a dedicated external account.
                $reference = 'user-' . $student->id;
                $externalId = $this->client->createAccount($student->name, $reference);

                if ($externalId === '') {
                    throw new \RuntimeException('Simulator did not return an account_id');
                }
            }

            $student->paymentAccount()->create([
                'external_account_id' => $externalId,
            ]);
        } catch (\Throwable $e) {
            return back()->with('error', 'Account creation failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Payment account created successfully.');
    }

    public function pay(Request $request): RedirectResponse
    {
        /** @var User $student */
        $student = $request->user();
        abort_unless($student && $student->role === 'user', 403);

        $request->validate([
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $feeMinor = $this->config->studentFeeCents();
        $alreadyPaid = (int) StudentPayment::query()
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->sum('amount_cents');

        $remaining = max(0, $feeMinor - $alreadyPaid);

        if ($remaining <= 0) {
            return back()->with('success', 'You are already fully paid.');
        }

        try {
            $this->payments->chargeStudent($student, $remaining, null, $request->string('note')->toString());
        } catch (\Throwable $e) {
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Payment submitted successfully.');
    }
}
