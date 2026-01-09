<?php

use App\Models\SupervisorApplication;
use App\Models\SupervisorAssignment;
use App\Models\SupervisionRequest;
use App\Models\User;

it('prevents a student with an active assignment from requesting supervision', function () {
    $this->withoutMiddleware();
    $student = User::factory()->create(['role' => 'user']);
    \App\Models\Profile::create(['user_id' => $student->id, 'department' => 'CS', 'phone' => '123', 'address' => 'Somewhere']);
    $supervisor = User::factory()->create(['role' => 'staff']);
    $admin = User::factory()->create(['role' => 'admin']);

    SupervisorAssignment::create([
        'student_id' => $student->id,
        'supervisor_id' => $supervisor->id,
        'assigned_by_admin_id' => $admin->id,
        'assigned_at' => now(),
        'active' => true,
    ]);

    $this->actingAs($student)
        ->post(route('user.supervision.request.store'), [
            'requested_supervisor_id' => $supervisor->id,
            'requested_admin_id' => $admin->id,
        ])
        ->assertRedirect(route('user.dashboard'));

    expect(SupervisionRequest::count())->toBe(0);
});

it('prevents staff from applying to be supervisor twice when pending', function () {
    $this->withoutMiddleware();
    $staff = User::factory()->create(['role' => 'staff']);
    \App\Models\Profile::create(['user_id' => $staff->id, 'department' => 'CS', 'phone' => '123', 'address' => 'Somewhere']);

    SupervisorApplication::create([
        'staff_id' => $staff->id,
        'max_students' => 3,
        'status' => 'pending',
    ]);

    $this->actingAs($staff)
        ->post(route('staff.supervisor.apply.store'), [
            'max_students' => 5,
            'department' => 'CS',
            'title' => 'Dr',
            'phone' => '123',
            'address' => 'Somewhere',
            'bio' => 'Bio',
        ])
        ->assertRedirect(route('staff.supervisor.apply'));

    expect(SupervisorApplication::where('staff_id', $staff->id)->count())->toBe(1);
});
