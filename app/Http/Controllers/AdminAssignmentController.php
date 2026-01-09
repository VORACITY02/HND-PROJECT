<?php
namespace App\Http\Controllers;

use App\Models\SupervisionRequest;
use App\Models\SupervisorAssignment;
use App\Models\SupervisorApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminAssignmentController extends Controller
{
    public function index()
    {
        $requests = SupervisionRequest::with(['student','requestedSupervisor','requestedAdmin'])
            ->where('status','pending')
            ->where('requested_admin_id', Auth::id())
            ->get();
        return view('admin.assignments.index', compact('requests'));
    }

    public function approve(SupervisionRequest $request)
    {
        $this->authorizeAdmin($request);
        DB::transaction(function() use ($request){
            // capacity check
            $app = SupervisorApplication::where('staff_id',$request->requested_supervisor_id)->where('status','approved')->first();
            if (!$app) abort(400,'Supervisor not approved');
            $current = SupervisorAssignment::where('supervisor_id', $request->requested_supervisor_id)
                ->where('active', true)
                ->count();
            if ($current >= $app->max_students) abort(400,'Supervisor capacity reached');

            // enforce one student-one supervisor
            SupervisorAssignment::updateOrCreate(
                ['student_id'=>$request->student_id],
                [
                    'supervisor_id'=>$request->requested_supervisor_id,
                    'assigned_by_admin_id'=>Auth::id(),
                    'assigned_at'=>now(),
                    'active'=>true,
                ]
            );
            $request->update(['status'=>'approved']);
        });
        return back()->with('success','Assignment created');
    }

    public function reject(SupervisionRequest $request)
    {
        $this->authorizeAdmin($request);
        $request->update(['status'=>'rejected']);
        return back()->with('success','Request rejected');
    }

    protected function authorizeAdmin(SupervisionRequest $req)
    {
        abort_unless(Auth::user()?->role === 'admin' && Auth::id() === $req->requested_admin_id, 403);
    }
}
