<?php
namespace App\Http\Controllers;

use App\Models\InternshipTask;
use App\Models\TaskSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StudentSubmissionController extends Controller
{
    public function store(InternshipTask $task, Request $request)
    {
        $request->validate([
            'content'=>'nullable|string',
            'file'=>'nullable|file|max:10240'
        ]);
        // Ensure student is supervised by the task's supervisor
        $assigned = \App\Models\SupervisorAssignment::where('student_id', Auth::id())
            ->where('supervisor_id', $task->supervisor_id)
            ->where('active', 1)
            ->exists();
        abort_unless($assigned, 403);

        // Authorization:
        // - Individual task: assigned_student_id must match
        // - Group task (assigned_student_id null): any supervised student may submit
        if ($task->assigned_student_id !== null) {
            abort_unless((int) $task->assigned_student_id === (int) Auth::id(), 403);
        }

        // Lock rules:
        // - If due date has passed: submission is expired (no create/update)
        // - If already graded: no changes allowed
        // - If rejected: student may resubmit (only before due date)
        if ($task->due_at && now()->gt($task->due_at)) {
            return back()->with('status', 'This task is expired (due date passed). You can no longer modify or submit.');
        }

        $existing = TaskSubmission::where('task_id', $task->id)
            ->where('student_id', Auth::id())
            ->first();

        if ($existing && $existing->status === 'graded') {
            return back()->with('status', 'This submission has already been graded and can no longer be modified.');
        }

        $filePath = $existing?->file_path;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        TaskSubmission::updateOrCreate(
            ['task_id' => $task->id, 'student_id' => Auth::id()],
            [
                'submitted_at' => now(),
                'file_path' => $filePath,
                'content' => $request->content,
                // Resubmitting moves it back to submitted, supervisor can re-grade
                'status' => 'submitted',
            ]
        );
        return back()->with('success','Submission uploaded');
    }
}
