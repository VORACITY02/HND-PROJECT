<?php

use App\Models\InternshipTask;
use App\Models\Profile;
use App\Models\SupervisorAssignment;
use App\Models\TaskSubmission;
use App\Models\User;

it('blocks student submission updates after due date', function () {
    $this->withoutExceptionHandling();
    $this->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    ]);
    $student = User::factory()->create(['role' => 'user']);
    Profile::create(['user_id' => $student->id, 'department' => 'CS', 'phone' => '123', 'address' => 'X']);

    $supervisor = User::factory()->create(['role' => 'staff']);
    $admin = User::factory()->create(['role' => 'admin']);

    SupervisorAssignment::create([
        'student_id' => $student->id,
        'supervisor_id' => $supervisor->id,
        'assigned_by_admin_id' => $admin->id,
        'assigned_at' => now(),
        'active' => true,
    ]);

    $task = InternshipTask::create([
        'supervisor_id' => $supervisor->id,
        'assigned_student_id' => $student->id,
        'title' => 'Task 1',
        'description' => 'Desc',
        'due_at' => now()->subDay(),
        'max_score' => 100,
        'active' => true,
    ]);

    expect($task->assigned_student_id)->toBe($student->id);
    expect(\App\Models\SupervisorAssignment::where('student_id',$student->id)->where('supervisor_id',$supervisor->id)->where('active',true)->exists())->toBeTrue();

    $this->actingAs($student)
        ->post(route('user.tasks.submit', $task), ['content' => 'attempt'])
        ->assertRedirect();

    expect(TaskSubmission::count())->toBe(0);
});

it('blocks student changes if graded, allows if rejected (before due date)', function () {
    $this->withoutExceptionHandling();
    $this->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    ]);
    $student = User::factory()->create(['role' => 'user']);
    Profile::create(['user_id' => $student->id, 'department' => 'CS', 'phone' => '123', 'address' => 'X']);

    $supervisor = User::factory()->create(['role' => 'staff']);
    $admin = User::factory()->create(['role' => 'admin']);

    SupervisorAssignment::create([
        'student_id' => $student->id,
        'supervisor_id' => $supervisor->id,
        'assigned_by_admin_id' => $admin->id,
        'assigned_at' => now(),
        'active' => true,
    ]);

    $task = InternshipTask::create([
        'supervisor_id' => $supervisor->id,
        'assigned_student_id' => $student->id,
        'title' => 'Task 1',
        'description' => 'Desc',
        'due_at' => now()->addDay(),
        'max_score' => 100,
        'active' => true,
    ]);

    // graded
    TaskSubmission::create([
        'task_id' => $task->id,
        'student_id' => $student->id,
        'submitted_at' => now(),
        'content' => 'original',
        'status' => 'graded',
        'grade_score' => 50,
        'graded_at' => now(),
    ]);

    expect($task->assigned_student_id)->toBe($student->id);

    $this->actingAs($student)
        ->post(route('user.tasks.submit', $task), ['content' => 'new'])
        ->assertRedirect();

    expect(TaskSubmission::first()->content)->toBe('original');

    // rejected: allow resubmit
    TaskSubmission::query()->update(['status' => 'rejected', 'grade_score' => 0, 'graded_at' => now()]);

    $this->actingAs($student)
        ->post(route('user.tasks.submit', $task), ['content' => 'resubmitted'])
        ->assertRedirect();

    expect(TaskSubmission::first()->content)->toBe('resubmitted');
    expect(TaskSubmission::first()->status)->toBe('submitted');
});
