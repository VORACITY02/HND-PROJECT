<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use App\Models\SupervisorApplication;
use Illuminate\Http\Request;

class SupervisorApplicationController extends Controller
{
    public function create(){
        $user = auth()->user();

        $profile = $user->profile;
        $department = $profile?->department ?? $user->personalData?->department;
        $phone = $profile?->phone ?? $user->personalData?->phone;
        $address = $profile?->address ?? $user->personalData?->address;
        if (!$department || !$phone || !$address) {
            return redirect()->route('profile.required', ['redirect_to' => route('staff.supervisor.apply', [], false)])
                ->with('status', 'Please complete your required information before applying.');
        }

        $application = $user->SupervisorApplication;
        $hasPersonalDataTable = \Illuminate\Support\Facades\Schema::hasTable('personal_data');
        return view('staff.supervisor.apply', compact('application', 'user', 'hasPersonalDataTable'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'max_students' => 'required|integer|min:1',
            'department' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);

        $user = auth()->user();

        $profile = $user->profile;
        $department = $profile?->department ?? $user->personalData?->department;
        $phone = $profile?->phone ?? $user->personalData?->phone;
        $address = $profile?->address ?? $user->personalData?->address;
        if (!$department || !$phone || !$address) {
            return redirect()->route('profile.required', ['redirect_to' => route('staff.supervisor.apply', [], false)])
                ->with('status', 'Please complete your required information before applying.');
        }

        // Upsert personal data (only if table exists)
        if (\Illuminate\Support\Facades\Schema::hasTable('personal_data')) {
            \App\Models\PersonalData::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'department' => $request->department,
                    'title' => $request->title,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'bio' => $request->bio,
                ]
            );
        } else {
            return back()->withErrors(['personal_data' => 'Personal data storage is not set up. Please run migrations.']);
        }

        $existing = SupervisorApplication::where('staff_id', $user->id)->first();
        if ($existing && in_array($existing->status, ['pending', 'approved'], true)) {
            return redirect()->route('staff.supervisor.apply')
                ->with('status', 'You already have a supervisor application that is ' . $existing->status . '. You cannot apply twice.');
        }

        // Create new application, or allow re-apply if previously rejected
        SupervisorApplication::updateOrCreate(
            ['staff_id' => $user->id],
            [
                'max_students' => $request->max_students,
                'status' => 'pending',
            ]
        );

        return redirect()->route('staff.dashboard')->with('success','Application submitted and awaiting admin approval.');
    }
}
