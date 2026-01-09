<?php

namespace App\Http\Controllers;

use App\Models\InternshipTask;
use App\Models\TaskSubmission;
use Illuminate\Support\Facades\Auth;

class StudentTaskController extends Controller
{
    public function index()
    {
        $studentId = Auth::id();

        // Tasks can be individual (assigned_student_id = student) or group tasks (assigned_student_id = null)
        // Group tasks are visible only if the student is actively assigned to that supervisor.
        $tasks = InternshipTask::query()
            ->with('supervisor')
            ->where('active', true)
            ->where(function ($q) use ($studentId) {
                $q->where('assigned_student_id', $studentId)
                  ->orWhereNull('assigned_student_id');
            })
            ->where(function ($q) use ($studentId) {
                $q->where('assigned_student_id', $studentId)
                  ->orWhereExists(function ($sub) use ($studentId) {
                      $sub->selectRaw(1)
                          ->from('supervisor_assignments')
                          ->whereColumn('supervisor_assignments.supervisor_id', 'internship_tasks.supervisor_id')
                          ->where('supervisor_assignments.student_id', $studentId)
                          ->where('supervisor_assignments.active', true);
                  });
            })
            ->orderByDesc('created_at')
            ->get();

        $submissions = TaskSubmission::query()
            ->where('student_id', $studentId)
            ->get()
            ->keyBy('task_id');

        return view('user.tasks.index', [
            'tasks' => $tasks,
            'submissions' => $submissions,
        ]);
    }
}
