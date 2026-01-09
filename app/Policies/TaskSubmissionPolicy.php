<?php

namespace App\Policies;

use App\Models\TaskSubmission;
use App\Models\User;

class TaskSubmissionPolicy
{
    public function grade(User $user, TaskSubmission $submission): bool
    {
        return $user->role === 'staff'
            && $submission->task
            && (int) $submission->task->supervisor_id === (int) $user->id;
    }
}
