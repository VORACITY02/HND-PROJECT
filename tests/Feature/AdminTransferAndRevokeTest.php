<?php

use App\Models\InternshipTask;
use App\Models\SupervisorApplication;
use App\Models\SupervisorAssignment;
use App\Models\User;

it('admin can transfer a student to a new supervisor and student-specific tasks move', function () {
    $this->withoutMiddleware();
    $this->withoutExceptionHandling();
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

    $task = InternshipTask::create([
        'supervisor_id' => $sup1->id,
        'assigned_student_id' => $student->id,
        'title' => 'Task A',
        'max_score' => 100,
        'active' => true,
        'is_special' => false,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.supervision.manage.transfer', $assign), ['new_supervisor_id' => $sup2->id])
        ->assertRedirect();

    expect(SupervisorAssignment::where('student_id', $student->id)->where('active', true)->first()->supervisor_id)
        ->toBe($sup2->id);

    expect($task->fresh()->supervisor_id)->toBe($sup2->id);
});

it('admin can revoke supervisor privileges and deactivate assignments', function () {
    $this->withoutMiddleware();
    $this->withoutExceptionHandling();
    $admin = User::factory()->create(['role' => 'admin']);
    $staff = User::factory()->create(['role' => 'staff']);
    $student = User::factory()->create(['role' => 'user']);

    $app = SupervisorApplication::create(['staff_id' => $staff->id, 'max_students' => 10, 'status' => 'approved']);

    SupervisorAssignment::create([
        'student_id' => $student->id,
        'supervisor_id' => $staff->id,
        'assigned_by_admin_id' => $admin->id,
        'assigned_at' => now(),
        'active' => true,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.supervisors.revoke', $app))
        ->assertRedirect();

    expect($app->fresh()->status)->toBe('rejected');
    expect(SupervisorAssignment::where('supervisor_id', $staff->id)->where('active', true)->count())->toBe(0);
});
