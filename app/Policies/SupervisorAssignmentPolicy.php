<?php

namespace App\Policies;

use App\Models\SupervisorAssignment;
use App\Models\User;

class SupervisorAssignmentPolicy
{
    public function manage(User $user, SupervisorAssignment $assignment): bool
    {
        // Any admin can manage supervision assignments.
        return $user->role === 'admin';
    }

    public function transfer(User $user, SupervisorAssignment $assignment): bool
    {
        return $this->manage($user, $assignment);
    }
}
