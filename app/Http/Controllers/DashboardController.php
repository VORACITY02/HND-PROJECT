<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        return view('admin.dashboard_simple', [
            'totalUsers'    => User::count(),
            'totalAdmins'   => User::where('role', 'admin')->count(),
            'totalStaff'    => User::where('role', 'staff')->count(),
            'totalStudents' => User::where('role', 'user')->count(),
        ]);
    }

    public function staffDashboard()
    {
        return view('staff.dashboard_simple');
    }

    public function userDashboard()
    {
        return view('user.dashboard_simple');
    }
}