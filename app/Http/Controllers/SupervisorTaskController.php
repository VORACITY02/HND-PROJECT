<?php
namespace App\Http\Controllers;

use App\Models\InternshipTask;
use App\Models\TaskSubmission;
use App\Models\SupervisorAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SupervisorTaskController extends Controller
{
    public function index()
    {
        $tasks = InternshipTask::withTrashed()->with('assignedStudent')
            ->where('supervisor_id', Auth::id())
            ->latest()
            ->get();

        $submissions = TaskSubmission::whereHas('task', function ($q) {
                $q->where('supervisor_id', Auth::id());
            })
            // Integrity:
            // - individual tasks: submission student must match assigned_student_id
            // - group tasks: assigned_student_id is null, allow any supervised student
            ->where(function ($q) {
                $q->whereHas('task', fn($t) => $t->whereColumn('assigned_student_id', 'task_submissions.student_id'))
                  ->orWhereHas('task', fn($t) => $t->whereNull('assigned_student_id'));
            })
            ->with(['task', 'student'])
            ->latest()
            ->get();

        return view('staff.tasks.index', compact('tasks','submissions'));
    }

    public function create()
    {
        $students = SupervisorAssignment::with('student')
            ->where('supervisor_id', Auth::id())
            ->where('active', true)
            ->orderByDesc('assigned_at')
            ->get()
            ->map(fn($a) => $a->student)
            ->filter();

        return view('staff.tasks.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_type' => 'required|in:normal,special',
            'assign_to' => 'required|in:individual,all',
            'assigned_student_id' => 'nullable|required_if:assign_to,individual|exists:users,id',
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'due_at'=>'nullable|date',
            'max_score'=>'nullable|integer|min:1'
        ]);

        $assignedStudentId = null;

        if ($request->assign_to === 'individual') {
            // Ensure this student is actually assigned to the supervisor
            $isSupervising = SupervisorAssignment::where('supervisor_id', Auth::id())
                ->where('student_id', $request->assigned_student_id)
                ->where('active', true)
                ->exists();
            abort_unless($isSupervising, 403);

            $assignedStudentId = (int) $request->assigned_student_id;

            // Special task eligibility: student must already have at least one negative graded submission
            if ($request->task_type === 'special') {
                $hasNegative = TaskSubmission::where('student_id', $assignedStudentId)
                    ->whereNotNull('grade_score')
                    ->where('grade_score', '<', 0)
                    ->exists();
                abort_unless($hasNegative, 400, 'Special tasks can only be assigned to students who have at least one negative grade.');
            }
        } else {
            // assign_to === 'all' => group task
            // Special tasks are only meaningful as a targeted recovery mechanism; disallow for group.
            abort_unless($request->task_type === 'normal', 400, 'Special tasks must be assigned to an individual student.');

            $hasAny = SupervisorAssignment::where('supervisor_id', Auth::id())
                ->where('active', true)
                ->exists();
            abort_unless($hasAny, 403);
        }

        InternshipTask::create([
            'supervisor_id'=>Auth::id(),
            'assigned_student_id'=>$assignedStudentId,
            'title'=>$request->title,
            'description'=>$request->description,
            'due_at'=>$request->due_at,
            'max_score'=>$request->max_score ?? 100,
            'active'=>true,
            'is_special'=>($request->task_type === 'special'),
        ]);

        return redirect()->route('staff.tasks.index')->with('success','Task created');
    }

    public function edit(InternshipTask $task)
    {
        $this->authorize('update', $task);
        return view('staff.tasks.edit', compact('task'));
    }

    public function update(InternshipTask $task, Request $request)
    {
        $this->authorize('update', $task);

        $data = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'due_at'=>'nullable|date',
            'max_score'=>'nullable|integer|min:1',
            'active'=>'nullable|boolean',
        ]);

        $task->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'due_at' => $data['due_at'] ?? null,
            'max_score' => $data['max_score'] ?? $task->max_score,
            'active' => (bool) ($request->input('active', false)),
        ]);

        return redirect()->route('staff.tasks.index')->with('success','Task updated');
    }

    public function destroy(InternshipTask $task)
    {
        $this->authorize('delete', $task);
        // Soft-delete: keep submissions and historical grading.
        $task->delete();
        return redirect()->route('staff.tasks.index')->with('success','Task archived');
    }

    public function restore(int $task)
    {
        $taskModel = InternshipTask::withTrashed()->findOrFail($task);
        $this->authorize('restore', $taskModel);
        $taskModel->restore();
        return redirect()->route('staff.tasks.index')->with('success','Task restored');
    }

    public function grade(TaskSubmission $submission, Request $request)
    {
        $this->authorize('grade-submission', $submission);
        if ($submission->task->assigned_student_id !== null) {
            abort_unless((int) $submission->task->assigned_student_id === (int) $submission->student_id, 403);
        } else {
            // group task: ensure this student is supervised by the supervisor
            $isSupervised = SupervisorAssignment::where('supervisor_id', Auth::id())
                ->where('student_id', $submission->student_id)
                ->where('active', true)
                ->exists();
            abort_unless($isSupervised, 403);
        }
        $request->validate([
            'grade_score' => 'required|integer|min:-1000',
            'status' => 'nullable|in:graded,rejected'
        ]);
        $status = $request->status ?? 'graded';

        $submission->update([
            'grade_score' => $request->grade_score,
            'graded_at' => now(),
            'status' => $status,
        ]);

        // Notify student
        \App\Models\Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $submission->student_id,
            'recipient_type' => 'user',
            'subject' => 'Task submission ' . $status,
            'message' => "Your submission for task '{$submission->task->title}' has been {$status}. Score: {$submission->grade_score}/{$submission->task->max_score}.",
            'is_read' => false,
            'is_broadcast' => false,
        ]);

        return back()->with('success', 'Submission ' . $status . ' and student notified.');
    }
}
