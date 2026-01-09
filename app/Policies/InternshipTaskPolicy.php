<?php

namespace App\Policies;

use App\Models\InternshipTask;
use App\Models\User;

class InternshipTaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'staff';
    }

    public function view(User $user, InternshipTask $task): bool
    {
        return $user->role === 'staff' && (int) $task->supervisor_id === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'staff';
    }

    public function update(User $user, InternshipTask $task): bool
    {
        return $this->view($user, $task);
    }

    public function delete(User $user, InternshipTask $task): bool
    {
        return $this->view($user, $task);
    }

    public function restore(User $user, InternshipTask $task): bool
    {
        return $this->view($user, $task);
    }
}
