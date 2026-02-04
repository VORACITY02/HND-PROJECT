<?php

use App\Models\PaymentSetting;
use App\Models\StaffPayout;
use App\Models\SupervisorApplication;
use App\Models\SupervisorAssignment;
use App\Models\User;
use Illuminate\Support\Facades\Http;

it('staff can create a payment account and request payout', function () {
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

    // Mark as approved supervisor + one supervisee
    SupervisorApplication::create([
        'staff_id' => $staff->id,
        'status' => 'approved',
        'max_students' => 10,
        'admin_note' => null,
    ]);

    $s1 = User::factory()->create(['role' => 'user']);
    SupervisorAssignment::create([
        'student_id' => $s1->id,
        'supervisor_id' => $staff->id,
        'assigned_by_admin_id' => $admin->id,
        'assigned_at' => now(),
        'active' => true,
    ]);

    // Create staff account
    Http::fake([
        '*' => Http::response(['account_id' => 'acct_staff'], 200),
    ]);

    $this->actingAs($staff)
        ->post(route('staff.payments.account.create'))
        ->assertRedirect();

    expect($staff->fresh()->paymentAccount)->not->toBeNull();

    // Request payout
    Http::fake([
        '*' => Http::response(['transfer_id' => 'tr_payout', 'status' => 'ok'], 200),
    ]);

    $this->actingAs($staff)
        ->post(route('staff.payments.request'))
        ->assertRedirect();

    expect(StaffPayout::count())->toBe(1);
    $p = StaffPayout::first();
    expect($p->status)->toBe('paid');
});

it('staff payouts page loads', function () {
    $staff = User::factory()->create(['role' => 'staff']);

    PaymentSetting::set('currency', 'XOF');

    $this->actingAs($staff)
        ->get(route('staff.payments.index'))
        ->assertOk();
});
