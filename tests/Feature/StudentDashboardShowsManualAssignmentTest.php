<?php

use App\Models\SupervisorAssignment;
use App\Models\User;

it('shows supervision assignment history on student dashboard even when no request exists', function () {
    $this->withoutMiddleware();

    $student = User::factory()->create(['role' => 'user', 'name' => 'Student A']);
    $supervisor = User::factory()->create(['role' => 'staff', 'name' => 'Supervisor B']);
    $admin = User::factory()->create(['role' => 'admin', 'name' => 'Admin C']);

    SupervisorAssignment::create([
        'student_id' => $student->id,
        'supervisor_id' => $supervisor->id,
        'assigned_by_admin_id' => $admin->id,
        'assigned_at' => now(),
        'active' => true,
    ]);

    $this->actingAs($student)
        ->get(route('user.dashboard'))
        ->assertOk()
        ->assertSee('Your Supervision Assignments')
        ->assertSee('Supervisor B')
        ->assertSee('Admin C')
        ->assertSee('Active');
});
