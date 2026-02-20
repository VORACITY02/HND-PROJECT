<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupervisorApplication;

class AdminSupervisorController extends Controller
{
    public function index()
    {
        $query = SupervisorApplication::query();
        if (\Illuminate\Support\Facades\Schema::hasTable('personal_data')) {
            $query->with('staff.personalData');
        } else {
            $query->with('staff');
        }
        $applications = $query->get();
        return view('admin.supervisors.index', compact('applications'));
    }

    public function approve(SupervisorApplication $application)
    {
        $application->update(['status' => 'approved']);

        \\App\\Models\\Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $application->staff_id,
            'subject' => 'Supervisor Application Approved',
            'message' => 'Congratulations! Your application to become a supervisor has been approved. You can now supervise up to ' . $application->max_students . ' students.',
            'is_read' => false,
            'is_broadcast' => false,
        ]);

        return back()->with('success', 'Application approved and staff notified.');
    }

    public function reject(SupervisorApplication $application)
    {
        $application->update(['status' => 'rejected']);

        \\App\\Models\\Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $application->staff_id,
            'subject' => 'Supervisor Application Rejected',
            'message' => 'We are sorry to inform you that your application to become a supervisor has been rejected. You may re-apply with updated information.',
            'is_read' => false,
            'is_broadcast' => false,
        ]);

        return back()->with('success', 'Application rejected and staff notified.');
    }

    public function revoke(SupervisorApplication $application)
    {
        if (!$application->exists) {
            $param = request()->route('application');
            $id = is_object($param) ? ($param->id ?? null) : $param;
            $application = SupervisorApplication::query()->findOrFail($id);
        }

        abort_unless(auth()->user()?->role === 'admin', 403);

        $application->update(['status' => 'rejected']);

        \\App\\Models\\SupervisorAssignment::where('supervisor_id', $application->staff_id)
            ->where('active', true)
            ->update(['active' => false]);

        \\App\\Models\\InternshipTask::where('supervisor_id', $application->staff_id)
            ->whereNull('assigned_student_id')
            ->delete();

        \App\Models\Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $application->staff_id,
            'subject' => 'Supervisor Privileges Revoked',
            'message' => 'Your supervisor privileges have been revoked by an administrator.',
            'is_read' => false,
            'is_broadcast' => false,
        ]);

        return back()->with('success', 'Supervisor privileges revoked.');
    }
}