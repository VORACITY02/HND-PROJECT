<?php

namespace App\Console\Commands;

use App\Models\InternshipTask;
use App\Models\TaskSubmission;
use App\Models\SupervisorAssignment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GradeExpiredTasks extends Command
{
    protected $signature = 'tasks:grade-expired';
    protected $description = 'Create/mark graded 0 submissions for tasks whose due date passed without a submission.';

    public function handle(): int
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('task_submissions')) {
            $this->warn('task_submissions table not found.');
            return self::SUCCESS;
        }

        $now = now();

        $expiredTasks = InternshipTask::withTrashed()
            ->whereNotNull('due_at')
            ->where('due_at', '<', $now)
            ->get();

        $created = 0;

        foreach ($expiredTasks as $task) {
            // Determine target students for the task
            $studentIds = [];
            if ($task->assigned_student_id) {
                $studentIds = [(int) $task->assigned_student_id];
            } else {
                // group task: all active supervisees of this supervisor at the time of grading
                $studentIds = SupervisorAssignment::where('supervisor_id', $task->supervisor_id)
                    ->where('active', true)
                    ->pluck('student_id')
                    ->map(fn($v) => (int) $v)
                    ->all();
            }

            foreach ($studentIds as $studentId) {
                // If a submission exists (submitted or graded), do nothing.
                $existing = TaskSubmission::where('task_id', $task->id)
                    ->where('student_id', $studentId)
                    ->first();

                if ($existing) {
                    continue;
                }

                DB::transaction(function () use ($task, $studentId, &$created) {
                    TaskSubmission::create([
                        'task_id' => $task->id,
                        'student_id' => $studentId,
                        'submitted_at' => null,
                        'status' => 'graded',
                        'grade_score' => 0,
                        'graded_at' => now(),
                    ]);
                    $created++;
                });
            }
        }

        $this->info("Expired task zero-grades created: {$created}");

        return self::SUCCESS;
    }
}
