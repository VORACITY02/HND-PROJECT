<?php

use App\Models\PaymentSetting;
use App\Models\StaffPayout;
use App\Models\StudentPayment;
use App\Models\SupervisorApplication;
use App\Models\SupervisorAssignment;
use App\Models\User;
use Illuminate\Support\Facades\Http;

it('admin can create a system payment account via simulator and store it in settings', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ]);

    Http::fake([
        '*' => Http::response(['account_id' => 'sys_acct_1'], 200),
    ]);

    $this->actingAs($admin)
        ->post(route('admin.payments.settings.system-account'))
        ->assertRedirect();

    expect(PaymentSetting::get('system_external_account_id'))->toBe('sys_acct_1');
});

it('admin can update payment settings', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.payments.settings.update'), [
            'currency' => 'XOF',
            'student_fee_cents' => 15000,
            'staff_base_pay_cents' => 1000,
            'supervisor_fixed_bonus_cents' => 500,
            'per_supervisee_bonus_cents' => 100,
            'system_external_account_id' => 'sys_1',
        ])
        ->assertRedirect();

    expect(PaymentSetting::get('student_fee_cents'))->toBe('15000');
    expect(PaymentSetting::get('system_external_account_id'))->toBe('sys_1');
});

it('admin can create a payment account for a student and charge remaining one-time fee', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $student = User::factory()->create(['role' => 'user']);

    $this->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ]);

    PaymentSetting::set('currency', 'XOF');
    PaymentSetting::set('student_fee_cents', '1000');
    PaymentSetting::set('system_external_account_id', 'sys_1');

    Http::fake([
        '*' => Http::response(['account_id' => 'acct_student'], 200),
    ]);

    $this->actingAs($admin)
        ->post(route('admin.payments.users.account.create', $student))
        ->assertRedirect();

    expect($student->fresh()->paymentAccount)->not->toBeNull();

    // Now fake transfer
    Http::fake([
        '*' => Http::response(['transfer_id' => 'tr_1', 'status' => 'ok'], 200),
    ]);

    $this->actingAs($admin)
        ->post(route('admin.payments.students.charge', $student))
        ->assertRedirect();

    expect(StudentPayment::count())->toBe(1);
    $p = StudentPayment::first();
    expect($p->status)->toBe('completed');
    expect((int)$p->amount_cents)->toBe(1000);
});

it('admin can create a payment account for staff and pay computed payout with supervisor bonuses', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $staff = User::factory()->create(['role' => 'staff']);

    $this->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ]);

    PaymentSetting::set('currency', 'XOF');
    PaymentSetting::set('system_external_account_id', 'sys_1');
    PaymentSetting::set('staff_base_pay_cents', '1000');
    PaymentSetting::set('supervisor_fixed_bonus_cents', '500');
    PaymentSetting::set('per_supervisee_bonus_cents', '100');

    // Mark as approved supervisor
    SupervisorApplication::create([
        'staff_id' => $staff->id,
        'status' => 'approved',
        'max_students' => 10,
        'admin_note' => null,
    ]);

    // Two supervisees
    $s1 = User::factory()->create(['role' => 'user']);
    $s2 = User::factory()->create(['role' => 'user']);
    SupervisorAssignment::create([
        'student_id' => $s1->id,
        'supervisor_id' => $staff->id,
        'assigned_by_admin_id' => $admin->id,
        'assigned_at' => now(),
        'active' => true,
    ]);
    SupervisorAssignment::create([
        'student_id' => $s2->id,
        'supervisor_id' => $staff->id,
        'assigned_by_admin_id' => $admin->id,
        'assigned_at' => now(),
        'active' => true,
    ]);

    Http::fake([
        '*' => Http::response(['account_id' => 'acct_staff'], 200),
    ]);

    $this->actingAs($admin)
        ->post(route('admin.payments.users.account.create', $staff))
        ->assertRedirect();

    Http::fake([
        '*' => Http::response(['transfer_id' => 'tr_payout', 'status' => 'ok'], 200),
    ]);

    $this->actingAs($admin)
        ->post(route('admin.payments.staff.pay', $staff))
        ->assertRedirect();

    expect(StaffPayout::count())->toBe(1);
    $p = StaffPayout::first();

    // base 1000 + fixed 500 + per 100*2 = 1700
    expect((int)$p->amount_cents)->toBe(1700);
    expect($p->status)->toBe('paid');
});
