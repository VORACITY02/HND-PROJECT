<?php

use App\Models\InternshipTask;
use App\Models\Profile;
use App\Models\SupervisorAssignment;
use App\Models\TaskSubmission;
use App\Models\User;
use App\Services\ProgressService;

it('shows group tasks (assigned_student_id null) to supervised students only', function () {
    $this->withoutExceptionHandling();
    $this->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    ]);

    $student = User::factory()->create(['role' => 'user']);
    Profile::create(['user_id' => $student->id, 'department' => 'CS', 'phone' => '123', 'address' => 'X']);

    $otherStudent = User::factory()->create(['role' => 'user']);
    Profile::create(['user_id' => $otherStudent->id, 'department' => 'CS', 'phone' => '123', 'address' => 'X']);

    $supervisor = User::factory()->create(['role' => 'staff']);
    $admin = User::factory()->create(['role' => 'admin']);

    SupervisorAssignment::create([
        'student_id' => $student->id,
        'supervisor_id' => $supervisor->id,
        'assigned_by_admin_id' => $admin->id,
        'assigned_at' => now(),
        'active' => true,
    ]);

    $groupTask = InternshipTask::create([
        'supervisor_id' => $supervisor->id,
        'assigned_student_id' => null,
        'title' => 'Group Task',
        'description' => 'For all',
        'due_at' => now()->addDay(),
        'max_score' => 100,
        'active' => true,
    ]);

    $this->actingAs($student)
        ->get(route('user.tasks.index'))
        ->assertOk()
        ->assertSee('Group Task');

    $this->actingAs($otherStudent)
        ->get(route('user.tasks.index'))
        ->assertOk()
        ->assertDontSee('Group Task');
});

it('negative grading reduces progress but overall stays between 0 and 100', function () {
    $student = User::factory()->create(['role' => 'user']);
    $supervisor = User::factory()->create(['role' => 'staff']);

    $task = InternshipTask::create([
        'supervisor_id' => $supervisor->id,
        'assigned_student_id' => $student->id,
        'title' => 'Task',
        'max_score' => 100,
        'active' => true,
    ]);

    TaskSubmission::create([
        'task_id' => $task->id,
        'student_id' => $student->id,
        'submitted_at' => now(),
        'status' => 'graded',
        'grade_score' => -20,
        'graded_at' => now(),
    ]);

    $progress = app(ProgressService::class)->computeStudentProgress($student->id, $supervisor->id);

    expect($progress)->toBeGreaterThanOrEqual(0);
    // with one task at -20%, clamped to 0 overall
    expect($progress)->toBe(0.0);
});
