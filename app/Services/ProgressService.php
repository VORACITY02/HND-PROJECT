<?php
namespace App\Services;

use App\Models\TaskSubmission;
use App\Models\InternshipTask;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

class ProgressService
{
    /**
     * Compute progress percentage for a student under a supervisor.
     * Basic formula: average( grade_ratio * 100 adjusted by timeliness ), bounded 0..100.
     */
    public function computeStudentProgress(int $studentId, ?int $supervisorId = null): float
    {
        // If the table isn't migrated yet, return 0 gracefully
        if (!Schema::hasTable('task_submissions')) {
            return 0.0;
        }

        try {
            // Progress is computed over tasks that are due (or already graded), including:
            // - individual tasks assigned directly to the student
            // - group tasks (assigned_student_id is null)
            // Include soft-deleted tasks so that already-graded work continues to count in progress
            // even if the supervisor later deletes/archives the task.
            $tasksQuery = InternshipTask::withTrashed()->where(function ($q) use ($studentId) {
                    $q->where('assigned_student_id', $studentId)
                      ->orWhereNull('assigned_student_id');
                });

            if ($supervisorId) {
                $tasksQuery->where('supervisor_id', $supervisorId);
            }

            $tasks = $tasksQuery->get();

            if ($tasks->isEmpty()) {
                return 0.0;
            }

            $subsByTask = TaskSubmission::query()
                ->where('student_id', $studentId)
                ->whereIn('task_id', $tasks->pluck('id'))
                ->get()
                ->keyBy('task_id');
        } catch (QueryException $e) {
            // In case of race conditions during first boot (before migration), default to 0
            return 0.0;
        }

        // Only count tasks that are either:
        // - graded (grade_score not null)
        // - expired (due_at passed) => automatic 0 if no graded submission
        $now = now();
        $counted = $tasks->filter(function (InternshipTask $t) use ($subsByTask, $now, $studentId) {
            $sub = $subsByTask->get($t->id);
            if ($sub && $sub->grade_score !== null) {
                return true;
            }
            // No graded submission: if due date passed, it expires and counts as 0.
            return $t->due_at && $now->gt($t->due_at);
        });

        if ($counted->isEmpty()) {
            return 0.0;
        }

        // Split normal vs special tasks. Special tasks are recovery-only: they may offset negative impact
        // but must NOT inflate progress beyond the baseline that would exist if negatives were 0.
        $normalTasks = $counted->filter(fn(InternshipTask $t) => !$t->is_special);
        $specialTasks = $counted->filter(fn(InternshipTask $t) => (bool) $t->is_special);

        $scoreForTask = function (InternshipTask $t) use ($subsByTask): float {
            $sub = $subsByTask->get($t->id);
            $max = max(1, $t->max_score ?? 100);

            // If expired with no graded submission => automatic zero.
            $rawScore = ($sub && $sub->grade_score !== null) ? (int) $sub->grade_score : 0;

            // Early submission bonus:
            if ($t->due_at && $sub && $sub->submitted_at && $sub->submitted_at->lt($t->due_at)) {
                $rawScore += 5;
            }

            $gradePct = ($rawScore / $max) * 100;
            return max(-100, min(100, $gradePct));
        };

        $normalScores = $normalTasks->map(fn($t) => $scoreForTask($t));
        $specialScores = $specialTasks->map(fn($t) => max(0, $scoreForTask($t))); // special tasks only help

        // Base progress uses normal tasks (including negatives), clamped.
        $base = $normalScores->isEmpty() ? 0.0 : (float) $normalScores->avg();
        $base = max(0, min(100, $base));

        // Ceiling progress is what progress would be if negative normal scores were treated as 0.
        $ceiling = $normalScores->isEmpty()
            ? 0.0
            : (float) $normalScores->map(fn($v) => max(0, $v))->avg();
        $ceiling = max(0, min(100, $ceiling));

        // Special tasks can recover up to the ceiling, never beyond.
        $recovery = $specialScores->isEmpty() ? 0.0 : (float) $specialScores->avg();

        $final = min($ceiling, $base + $recovery);

        return round(max(0, min(100, $final)), 2);
    }
}
