<?php

use App\Models\PaymentSetting;
use App\Models\StudentPayment;
use App\Models\User;
use Illuminate\Support\Facades\Http;

it('student can create a payment account and pay remaining fee', function () {
    $student = User::factory()->create(['role' => 'user']);

    $this->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ]);

    PaymentSetting::set('currency', 'XOF');
    PaymentSetting::set('student_fee_cents', '1000');
    PaymentSetting::set('system_external_account_id', 'sys_1');

    // Create account
    Http::fake([
        '*' => Http::response(['account_id' => 'acct_student'], 200),
    ]);

    $this->actingAs($student)
        ->post(route('user.payments.account.create'))
        ->assertRedirect();

    expect($student->fresh()->paymentAccount)->not->toBeNull();

    // Pay fee
    Http::fake([
        '*' => Http::response(['transfer_id' => 'tr_1', 'status' => 'ok'], 200),
    ]);

    $this->actingAs($student)
        ->post(route('user.payments.pay'))
        ->assertRedirect();

    expect(StudentPayment::count())->toBe(1);
    $p = StudentPayment::first();
    expect($p->status)->toBe('completed');
    expect((int) $p->amount_cents)->toBe(1000);
});

it('student payments page loads', function () {
    $student = User::factory()->create(['role' => 'user']);

    PaymentSetting::set('currency', 'XOF');
    PaymentSetting::set('student_fee_cents', '0');

    $this->actingAs($student)
        ->get(route('user.payments.index'))
        ->assertOk();
});
