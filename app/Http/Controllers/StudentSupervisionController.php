<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupervisionRequest;
use App\Models\SupervisorApplication;
use App\Models\SupervisorAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentSupervisionController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        $hasActiveAssignment = SupervisorAssignment::where('student_id', $user->id)
            ->where('active', true)
            ->exists();
        if ($hasActiveAssignment) {
            return redirect()->route('user.dashboard')
                ->with('status', 'You already have an active supervisor. You cannot request another supervisor.');
        }

        $profile = $user->profile;
        $department = $profile?->department ?? $user->personalData?->department;
        $phone = $profile?->phone ?? $user->personalData?->phone;
        $address = $profile?->address ?? $user->personalData?->address;
        if (!$department || !$phone || !$address) {
            return redirect()->route('profile.required', ['redirect_to' => route('user.supervision.request', [], false)])
                ->with('status', 'Please complete your required information before requesting supervision.');
        }
        // available supervisors: approved applications with capacity
        $approved = SupervisorApplication::query()->where('status','approved')->get();
        $availableSupervisors = $approved->filter(function($app){
            $current = SupervisorAssignment::where('supervisor_id',$app->staff_id)->count();
            return $current < $app->max_students;
        })->map(fn($app)=>User::find($app->staff_id));

        $admins = User::where('role','admin')->get();
        return view('user.request-supervision', compact('availableSupervisors','admins','user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $profile = $user->profile;
        $department = $profile?->department ?? $user->personalData?->department;
        $phone = $profile?->phone ?? $user->personalData?->phone;
        $address = $profile?->address ?? $user->personalData?->address;
        if (!$department || !$phone || !$address) {
            return redirect()->route('profile.required', ['redirect_to' => route('user.supervision.request', [], false)])
                ->with('status', 'Please complete your required information before requesting supervision.');
        }
        $request->validate([
            'requested_supervisor_id'=>'required|exists:users,id',
            'requested_admin_id'=>'required|exists:users,id',
            'note'=>'nullable|string'
        ]);
        // Block requests if already assigned to an active supervisor
        $hasActiveAssignment = SupervisorAssignment::where('student_id', $user->id)
            ->where('active', true)
            ->exists();
        if ($hasActiveAssignment) {
            return redirect()->route('user.dashboard')
                ->with('status', 'You already have an active supervisor. You cannot request another supervisor.');
        }

        DB::transaction(function () use ($user, $request) {
            // If an identical pending request already exists, do nothing (prevents spamming duplicates)
            $duplicate = SupervisionRequest::where('student_id', $user->id)
                ->where('requested_supervisor_id', $request->requested_supervisor_id)
                ->where('requested_admin_id', $request->requested_admin_id)
                ->where('status', 'pending')
                ->exists();
            if ($duplicate) {
                return;
            }

            // Only one pending at a time: cancel other pending requests (different target)
            SupervisionRequest::where('student_id', $user->id)
                ->where('status', 'pending')
                ->update(['status' => 'cancelled']);

            SupervisionRequest::create([
                'student_id' => $user->id,
                'requested_supervisor_id' => $request->requested_supervisor_id,
                'requested_admin_id' => $request->requested_admin_id,
                'status' => 'pending',
                'note' => $request->note,
            ]);
        });

        return redirect()->route('user.dashboard')->with('success','Supervision request sent.');
    }
}
