<?php

namespace App\Http\Controllers;

use App\Models\SupervisorApplication;
use App\Models\SupervisorAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminSupervisionManagementController extends Controller
{
    public function index()
    {
        $adminId = Auth::id();

        $assignments = SupervisorAssignment::with(['student', 'supervisor'])
            ->where('assigned_by_admin_id', $adminId)
            ->orderByDesc('assigned_at')
            ->get();

        $students = User::query()
            ->where('role', 'user')
            ->orderBy('name')
            ->get();

        $approvedApps = SupervisorApplication::query()
            ->where('status', 'approved')
            ->get();

        $supervisors = User::query()
            ->where('role', 'staff')
            ->whereIn('id', $approvedApps->pluck('staff_id'))
            ->orderBy('name')
            ->get()
            ->map(function (User $u) use ($approvedApps) {
                $app = $approvedApps->firstWhere('staff_id', $u->id);
                $current = SupervisorAssignment::where('supervisor_id', $u->id)
                    ->where('active', true)
                    ->count();
                return [
                    'user' => $u,
                    'max_students' => $app?->max_students ?? 0,
                    'current_students' => $current,
                ];
            });

        return view('admin.assignments.manage', compact('assignments', 'students', 'supervisors'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $request->validate([
            'student_id' => 'required|exists:users,id',
            'supervisor_id' => 'required|exists:users,id',
        ]);

        $adminId = Auth::id();

        $student = User::where('role', 'user')->findOrFail($request->student_id);
        $supervisor = User::where('role', 'staff')->findOrFail($request->supervisor_id);

        $app = SupervisorApplication::where('staff_id', $supervisor->id)
            ->where('status', 'approved')
            ->first();
        abort_unless($app, 400, 'Supervisor not approved');

        $current = SupervisorAssignment::where('supervisor_id', $supervisor->id)->where('active', true)->count();
        if ($current >= $app->max_students) {
            return back()->with('status', 'Supervisor capacity reached.');
        }

        DB::transaction(function () use ($student, $supervisor, $adminId) {
            // Ensure student has only one active supervisor: deactivate old assignment if present
            SupervisorAssignment::where('student_id', $student->id)
                ->where('active', true)
                ->update(['active' => false]);

            SupervisorAssignment::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'supervisor_id' => $supervisor->id,
                    'assigned_by_admin_id' => $adminId,
                    'assigned_at' => now(),
                    'active' => true,
                ]
            );
        });

        return back()->with('success', 'Supervision assigned.');
    }

    public function destroy(SupervisorAssignment $assignment)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);
        abort_unless((int) $assignment->assigned_by_admin_id === (int) Auth::id(), 403);

        $assignment->update(['active' => false]);

        return back()->with('success', 'Supervision removed (deactivated).');
    }

    public function transfer(SupervisorAssignment $assignment, Request $request)
    {
        $this->authorize('transfer', $assignment);
        // Allow any admin to transfer assignments (not only the admin who originally assigned).
        // Some older rows may have active as NULL; treat as active.
        abort_unless($assignment->active || $assignment->active === null, 400, 'Only active assignments can be transferred.');

        $data = $request->validate([
            'new_supervisor_id' => 'required|exists:users,id',
        ]);

        $studentId = (int) $assignment->student_id;
        abort_unless($studentId > 0, 400, 'Invalid student on assignment');
        $student = User::findOrFail($studentId);
        abort_unless($student->role === 'user', 400, 'Selected student is not a student');

        $newSupervisorId = (int) $data['new_supervisor_id'];
        abort_unless($newSupervisorId > 0, 400, 'Invalid new supervisor');
        $newSupervisor = User::findOrFail($newSupervisorId);
        abort_unless($newSupervisor->role === 'staff', 400, 'Selected supervisor is not staff');

        $app = SupervisorApplication::where('staff_id', $newSupervisor->id)
            ->where('status', 'approved')
            ->first();
        abort_unless($app, 400, 'Supervisor not approved');

        $current = SupervisorAssignment::where('supervisor_id', $newSupervisor->id)->where('active', true)->count();
        abort_unless($current < ($app->max_students ?? 0), 400, 'Supervisor capacity reached');

        $adminId = Auth::id();

        DB::transaction(function () use ($assignment, $student, $newSupervisor, $adminId) {
            $oldSupervisorId = (int) $assignment->supervisor_id;

            \App\Models\SupervisionTransferLog::create([
                'student_id' => $student->id,
                'from_supervisor_id' => $oldSupervisorId,
                'to_supervisor_id' => $newSupervisor->id,
                'performed_by_admin_id' => $adminId,
                'transferred_at' => now(),
            ]);

            // Deactivate old
            $assignment->update(['active' => false]);

            // Create/replace active assignment
            SupervisorAssignment::where('student_id', $student->id)
                ->where('active', true)
                ->update(['active' => false]);

            SupervisorAssignment::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'supervisor_id' => $newSupervisor->id,
                    'assigned_by_admin_id' => $adminId,
                    'assigned_at' => now(),
                    'active' => true,
                ]
            );

            // Transfer student-specific tasks to the new supervisor so the new supervisor sees the same student data.
            // Only transfer individual tasks (assigned_student_id = student). Group tasks belong to a supervisor cohort.
            \App\Models\InternshipTask::withTrashed()
                ->where('supervisor_id', $oldSupervisorId)
                ->where('assigned_student_id', $student->id)
                ->update(['supervisor_id' => $newSupervisor->id]);
        });

        return back()->with('success', 'Supervision transferred.');
    }
}
