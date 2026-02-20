<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use App\Models\StaffPayout;
use App\Models\StudentPayment;
use App\Models\User;
use App\Services\Payment\PaymentConfig;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPaymentController extends Controller
{
    public function __construct(
        private PaymentService $payments,
        private PaymentConfig $config,
    ) {
    }

    public function dashboard()
    {
        $systemExternal = $this->config->systemExternalAccountId();

        $systemBalance = null;
        if ($systemExternal !== '') {
            try {
                $systemBalance = app(\App\Services\Payment\PaymentGateway::class)->getBalance($systemExternal);
            } catch (\Throwable $e) {
                $systemBalance = ['balance_cents' => 0, 'currency' => $this->config->currency(), 'error' => $e->getMessage()];
            }
        }

        return view('admin.payments.dashboard', [
            'currency' => $this->config->currency(),
            'studentFeeCents' => $this->config->studentFeeCents(),
            'systemExternalAccountId' => $systemExternal,
            'systemBalance' => $systemBalance,
            'totalStudentCollectedCents' => (int) StudentPayment::query()->where('status', 'completed')->sum('amount_cents'),
            'totalStaffPaidCents' => (int) StaffPayout::query()->where('status', 'paid')->sum('amount_cents'),
            'studentsWithAccounts' => User::query()->where('role', 'user')->whereHas('paymentAccount')->count(),
            'staffWithAccounts' => User::query()->where('role', 'staff')->whereHas('paymentAccount')->count(),
        ]);
    }

    public function studentsIndex(Request $request)
    {
        $feeCents = $this->config->studentFeeCents();

        $students = User::query()
            ->where('role', 'user')
            ->with('paymentAccount')
            ->orderBy('name')
            ->get()
            ->map(function (User $student) use ($feeCents) {
                $paid = (int) StudentPayment::query()
                    ->where('student_id', $student->id)
                    ->where('status', 'completed')
                    ->sum('amount_cents');

                return [
                    'student' => $student,
                    'paid_cents' => $paid,
                    'required_cents' => $feeCents,
                    'remaining_cents' => max(0, $feeCents - $paid),
                ];
            });

        return view('admin.payments.students', [
            'currency' => $this->config->currency(),
            'studentFeeCents' => $feeCents,
            'rows' => $students,
        ]);
    }

    public function chargeStudent(Request $request, User $student): RedirectResponse
    {
        if ($student->role !== 'user') {
            abort(404);
        }

        $request->validate([
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $feeCents = $this->config->studentFeeCents();
        $alreadyPaid = (int) StudentPayment::query()->where('student_id', $student->id)->where('status', 'completed')->sum('amount_cents');
        $remaining = max(0, $feeCents - $alreadyPaid);

        if ($remaining <= 0) {
            return back()->with('success', 'Student is already fully paid.');
        }

        try {
            $this->payments->chargeStudent($student, $remaining, null, $request->string('note')->toString());
        } catch (\Throwable $e) {
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Student payment collected successfully.');
    }

    public function staffIndex()
    {
        $staff = User::query()
            ->where('role', 'staff')
            ->with('paymentAccount')
            ->orderBy('name')
            ->get()
            ->map(function (User $s) {
                $superviseeCount = $this->payments->currentSuperviseeCount($s->id);
                $isSupervisor = $this->payments->isApprovedSupervisor($s->id);
                $calc = app(\App\Services\Payment\StaffPaymentCalculator::class)->compute($superviseeCount, $isSupervisor);

                return [
                    'staff' => $s,
                    'is_supervisor' => $isSupervisor,
                    'supervisee_count' => $superviseeCount,
                    'calc' => $calc,
                    'paid_total_cents' => (int) StaffPayout::query()->where('staff_id', $s->id)->where('status', 'paid')->sum('amount_cents'),
                ];
            });

        return view('admin.payments.staff', [
            'currency' => $this->config->currency(),
            'rows' => $staff,
        ]);
    }

    public function payStaff(Request $request, User $staff): RedirectResponse
    {
        if ($staff->role !== 'staff') {
            abort(404);
        }

        $request->validate([
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->payments->payStaff($staff, null, $request->string('note')->toString());
        } catch (\Throwable $e) {
            return back()->with('error', 'Payout failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Staff payout executed successfully.');
    }

    public function createPaymentAccount(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'force' => ['nullable', 'boolean'],
        ]);

        if ($user->paymentAccount && !$request->boolean('force')) {
            return back()->with('success', 'User already has a payment account.');
        }

        try {
            DB::transaction(function () use ($user, $request) {
                if ($request->boolean('force') && $user->paymentAccount) {
                    $user->paymentAccount()->delete();
                }

                $reference = 'user-' . $user->id;
                $externalId = app(\App\Services\Payment\PaymentGateway::class)->createAccount($user->name, $reference);

                if ($externalId === '') {
                    throw new \RuntimeException('Simulator did not return an account_id');
                }

                $user->paymentAccount()->create([
                    'external_account_id' => $externalId,
                ]);
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Account creation failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Payment account created successfully.');
    }

    public function settings()
    {
        return view('admin.payments.settings', [
            'currency' => $this->config->currency(),
            'student_fee_cents' => $this->config->studentFeeCents(),
            'staff_base_pay_cents' => $this->config->staffBasePayCents(),
            'supervisor_fixed_bonus_cents' => $this->config->supervisorFixedBonusCents(),
            'per_supervisee_bonus_cents' => $this->config->perSuperviseeBonusCents(),
            'system_external_account_id' => $this->config->systemExternalAccountId(),
            'simulator_base_url' => $this->config->simulatorBaseUrl(),
            // Intentionally do not show API key.
        ]);
    }

    public function createSystemAccount(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->role === 'admin', 403);

        // Simulator can create a dedicated platform wallet.
        // RabbitMaid wallets are pre-provisioned (mtn/orange) and configured via env.
        if ($this->config->paymentDriver() === 'rabbitmaid') {
            return back()->with('error', 'RabbitMaid does not support creating wallets via API. Configure RABBITMAID_STUDENT_SERVICE and RABBITMAID_STAFF_SERVICE in .env.');
        }

        try {
            $name = (string) config('app.name', 'System');
            $reference = 'system';
            $externalId = app(\App\Services\Payment\PaymentGateway::class)->createAccount($name . ' (System)', $reference);

            if ($externalId === '') {
                throw new \RuntimeException('Simulator did not return an account_id');
            }

            PaymentSetting::set('system_external_account_id', $externalId);
        } catch (\Throwable $e) {
            return back()->with('error', 'System account creation failed: ' . $e->getMessage());
        }

        return back()->with('success', 'System payment account created and saved.');
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'currency' => ['sometimes', 'string', 'max:10'],
            'student_fee_cents' => ['required', 'integer', 'min:0'],
            'staff_base_pay_cents' => ['required', 'integer', 'min:0'],
            'supervisor_fixed_bonus_cents' => ['required', 'integer', 'min:0'],
            'per_supervisee_bonus_cents' => ['required', 'integer', 'min:0'],
            'system_external_account_id' => ['nullable', 'string', 'max:255'],
        ]);

        $data['currency'] = 'XOF';

        foreach ($data as $key => $value) {
            PaymentSetting::set($key, (string) ($value ?? ''));
        }

        return back()->with('success', 'Payment settings updated.');
    }
}
