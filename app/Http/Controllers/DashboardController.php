<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SupervisorAssignment;
use App\Models\SupervisionRequest;
use App\Models\Message;

class DashboardController extends Controller
{
    public function adminWorkQueue()
    {
        $adminId = Auth::id();

        $pendingRequests = SupervisionRequest::with(['student','requestedSupervisor'])
            ->where('status', 'pending')
            ->where('requested_admin_id', $adminId)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $pendingRequestsCount = SupervisionRequest::where('status', 'pending')
            ->where('requested_admin_id', $adminId)
            ->count();

        $assignedStudentsCount = SupervisorAssignment::where('assigned_by_admin_id', $adminId)->count();

        return view('admin.work-queue', [
            'pendingRequests' => $pendingRequests,
            'pendingRequestsCount' => $pendingRequestsCount,
            'assignedStudentsCount' => $assignedStudentsCount,
            'totalUsers'    => User::count(),
            'totalStaff'    => User::where('role', 'staff')->count(),
            'totalStudents' => User::where('role', 'user')->count(),
        ]);
    }

    public function adminDashboard()
    {
        $adminId = Auth::id();

        $pendingRequests = SupervisionRequest::with(['student','requestedSupervisor'])
            ->where('status', 'pending')
            ->where('requested_admin_id', $adminId)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $pendingRequestsCount = SupervisionRequest::where('status', 'pending')
            ->where('requested_admin_id', $adminId)
            ->count();

        return view('admin.dashboard', [
            'totalUsers'    => User::count(),
            'totalAdmins'   => User::where('role', 'admin')->count(),
            'totalStaff'    => User::where('role', 'staff')->count(),
            'totalStudents' => User::where('role', 'user')->count(),
            'pendingRequests' => $pendingRequests,
            'pendingRequestsCount' => $pendingRequestsCount,
        ]);
    }

    public function staffDashboard()
    {
        $userId = auth()->id();
        $unreadCount = Message::where('receiver_id', $userId)->where('is_read', false)->count();
        $recentMessages = Message::where('receiver_id', $userId)->latest()->limit(5)->get();

        $assignments = SupervisorAssignment::with(['student', 'assignedBy'])
            ->where('supervisor_id', $userId)
            ->where('active', true)
            ->orderByDesc('assigned_at')
            ->get();

        $progressService = app(\App\Services\ProgressService::class);
        $assignmentRows = $assignments->map(function (SupervisorAssignment $a) use ($progressService, $userId) {
            return [
                'assignment' => $a,
                'student' => $a->student,
                'progress' => $progressService->computeStudentProgress($a->student_id, $userId),
            ];
        });

        return view('staff.dashboard', [
            'unreadCount' => $unreadCount,
            'recentMessages' => $recentMessages,
            'assignmentRows' => $assignmentRows,
        ]);
    }

    public function userDashboard()
    {
        $user = auth()->user();
        $progress = app(\App\Services\ProgressService::class)->computeStudentProgress($user->id);
        $assignment = SupervisorAssignment::with(['supervisor', 'assignedBy'])
            ->where('student_id', $user->id)
            ->where(function ($q) {
                // Some older rows may have active as NULL; treat as active.
                $q->where('active', true)->orWhereNull('active');
            })
            ->orderByDesc('assigned_at')
            ->orderByDesc('id')
            ->first();
        $requests = SupervisionRequest::with(['requestedSupervisor','requestedAdmin'])
            ->where('student_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Also show assignment history for cases where an admin assigns supervision without a student request.
        $assignmentHistory = SupervisorAssignment::with(['supervisor', 'assignedBy'])
            ->where('student_id', $user->id)
            ->orderByDesc('assigned_at')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('user.dashboard', compact('progress','assignment','requests','assignmentHistory'));
    }
}