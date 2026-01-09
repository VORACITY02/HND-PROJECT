<?php

use App\Models\InternshipTask;
use App\Models\TaskSubmission;
use App\Models\User;
use App\Services\ProgressService;

it('counts expired tasks with no submission as automatic zero (reducing progress)', function () {
    $student = User::factory()->create(['role' => 'user']);
    $supervisor = User::factory()->create(['role' => 'staff']);

    // One expired task with no submission
    InternshipTask::create([
        'supervisor_id' => $supervisor->id,
        'assigned_student_id' => $student->id,
        'title' => 'Expired',
        'due_at' => now()->subDay(),
        'max_score' => 100,
        'active' => true,
    ]);

    // One graded task 100/100
    $t2 = InternshipTask::create([
        'supervisor_id' => $supervisor->id,
        'assigned_student_id' => $student->id,
        'title' => 'Graded',
        'due_at' => now()->addDay(),
        'max_score' => 100,
        'active' => true,
    ]);

    TaskSubmission::create([
        'task_id' => $t2->id,
        'student_id' => $student->id,
        'submitted_at' => now(),
        'status' => 'graded',
        'grade_score' => 100,
        'graded_at' => now(),
    ]);

    $progress = app(ProgressService::class)->computeStudentProgress($student->id, $supervisor->id);

    // avg of [0, 100] = 50
    expect($progress)->toBe(50.0);
});

it('adds +5 points to the score when a graded submission is strictly before due date', function () {
    $student = User::factory()->create(['role' => 'user']);
    $supervisor = User::factory()->create(['role' => 'staff']);

    $task = InternshipTask::create([
        'supervisor_id' => $supervisor->id,
        'assigned_student_id' => $student->id,
        'title' => 'Early',
        'due_at' => now()->addDay(),
        'max_score' => 100,
        'active' => true,
    ]);

    TaskSubmission::create([
        'task_id' => $task->id,
        'student_id' => $student->id,
        'submitted_at' => now(),
        'status' => 'graded',
        'grade_score' => 50,
        'graded_at' => now(),
    ]);

    $progress = app(ProgressService::class)->computeStudentProgress($student->id, $supervisor->id);

    // 50 + 5 = 55
    expect($progress)->toBe(55.0);
});
