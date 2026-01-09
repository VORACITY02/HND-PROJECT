<?php
namespace App\Http\Controllers;

use App\Models\TaskSubmission;
use App\Models\InternshipTask;
use App\Models\SupervisorAssignment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminTrackingController extends Controller
{
    public function index()
    {
        $assignments = \App\Models\SupervisorAssignment::with('student')
            ->where('assigned_by_admin_id', auth()->id())
            ->latest()
            ->get();
        return view('admin.tracking.index', compact('assignments'));
    }

    public function show(User $student)
    {
        // Only allow admins who assigned this student
        $assignment = SupervisorAssignment::where('student_id',$student->id)
            ->where('assigned_by_admin_id', Auth::id())
            ->firstOrFail();

        $subs = TaskSubmission::with('task')
            ->where('student_id', $student->id)
            ->whereHas('task', function ($q) use ($student, $assignment) {
                // include individual + group tasks
                $q->where('supervisor_id', $assignment->supervisor_id)
                  ->where(function ($t) use ($student) {
                      $t->where('assigned_student_id', $student->id)
                        ->orWhereNull('assigned_student_id');
                  });
            })
            ->orderByDesc('created_at')
            ->get();

        $service = app(\App\Services\ProgressService::class);
        $overall = $service->computeStudentProgress($student->id);

        // Build per-task percentages for detailed view
        $details = $subs->map(function($s) use ($assignment){
            $max = max(1, $s->task?->max_score ?? 100);
            $gradePct = $s->grade_score !== null ? (($s->grade_score / $max) * 100) : null;
            if ($gradePct !== null) {
                $gradePct = max(-100, min(100, $gradePct));
            }
            $due = $s->task?->due_at;
            $onTime = null;
            $lateByDays = null;
            if ($due) {
                $onTime = $s->submitted_at ? $s->submitted_at->lte($due) : null;
                if ($s->submitted_at && $s->submitted_at->gt($due)) {
                    $lateByDays = $s->submitted_at->diffInDays($due);
                }
            }
            $isGroup = $s->task?->assigned_student_id === null;
            return [
                'task_title' => $s->task?->title,
                'task_type' => $isGroup ? 'group' : 'individual',
                'max_score' => $s->task?->max_score,
                'raw_score' => $s->grade_score,
                'submitted_at' => $s->submitted_at,
                'graded_at' => $s->graded_at,
                'grade_pct' => $gradePct,
                'on_time' => $onTime,
                'late_by_days' => $lateByDays,
                'status' => $s->status,
            ];
        });

        $graded = $details->filter(fn($d) => $d['status'] === 'graded' && $d['grade_pct'] !== null);
        $chart = [
            'on_time' => $graded->where('on_time', true)->count(),
            'late' => $graded->where('on_time', false)->count(),
            'negative' => $graded->filter(fn($d) => ($d['raw_score'] ?? 0) < 0)->count(),
            'total' => $graded->count(),
        ];

        return view('admin.tracking.show', [
            'student'=>$student,
            'assignment'=>$assignment,
            'overall'=>$overall,
            'details'=>$details,
            'chart' => $chart,
        ]);
    }
}
