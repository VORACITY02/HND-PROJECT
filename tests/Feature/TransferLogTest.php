<?php

use App\Models\SupervisorApplication;
use App\Models\SupervisorAssignment;
use App\Models\SupervisionTransferLog;
use App\Models\User;

it('creates a supervision transfer log when an admin transfers a student', function () {
    $this->withoutMiddleware();

    $admin = User::factory()->create(['role' => 'admin']);
    $student = User::factory()->create(['role' => 'user']);
    $sup1 = User::factory()->create(['role' => 'staff']);
    $sup2 = User::factory()->create(['role' => 'staff']);

    SupervisorApplication::create(['staff_id' => $sup1->id, 'max_students' => 10, 'status' => 'approved']);
    SupervisorApplication::create(['staff_id' => $sup2->id, 'max_students' => 10, 'status' => 'approved']);

    $assign = SupervisorAssignment::create([
        'student_id' => $student->id,
        'supervisor_id' => $sup1->id,
        'assigned_by_admin_id' => $admin->id,
        'assigned_at' => now(),
        'active' => true,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.supervision.manage.transfer', $assign), ['new_supervisor_id' => $sup2->id])
        ->assertRedirect();

    expect(SupervisionTransferLog::count())->toBe(1);
    $log = SupervisionTransferLog::first();
    expect((int)$log->student_id)->toBe($student->id);
    expect((int)$log->from_supervisor_id)->toBe($sup1->id);
    expect((int)$log->to_supervisor_id)->toBe($sup2->id);
    expect((int)$log->performed_by_admin_id)->toBe($admin->id);
});
