<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Profile;
use App\Models\PersonalData;

class ProfileController extends Controller
{
    /**
     * Show profile edit form.
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function personalData()
    {
        // some views expect this variable
        $hasPersonalDataTable = \Illuminate\Support\Facades\Schema::hasTable('personal_data');
        return view('profile.personal-data', compact('hasPersonalDataTable'));
    }

    public function updatePersonalData(Request $request)
    {
        abort_unless(\Illuminate\Support\Facades\Schema::hasTable('personal_data'), 400, 'Personal data storage is not set up yet.');

        $data = $request->validate([
            'department' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ]);

        PersonalData::updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );

        return back()->with('status', 'Personal data updated.');
    }

    public function requiredInfo(Request $request)
    {
        $user = Auth::user();

        $profile = $user->profile;
        $personal = $user->personalData;

        $department = old('department') ?? ($profile->department ?? $personal->department ?? '');
        $phone = old('phone') ?? ($profile->phone ?? $personal->phone ?? '');
        $address = old('address') ?? ($profile->address ?? $personal->address ?? '');

        return view('profile.required-info', [
            'department' => $department,
            'phone' => $phone,
            'address' => $address,
            'redirectTo' => $request->query('redirect_to'),
        ]);
    }

    public function updateRequiredInfo(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'department' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:255'],
            'redirect_to' => ['nullable', 'string'],
        ]);

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'department' => $data['department'],
                'phone' => $data['phone'],
                'address' => $data['address'],
            ]
        );

        if (\Illuminate\Support\Facades\Schema::hasTable('personal_data')) {
            PersonalData::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'department' => $data['department'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                ]
            );
        }

        $redirectTo = $data['redirect_to'] ?? null;
        if ($redirectTo && str_starts_with($redirectTo, '/')) {
            return redirect($redirectTo)->with('status', 'Required information saved.');
        }

        return redirect()->route('profile.edit')->with('status', 'Required information saved.');
    }

    /**
     * Update profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        // Check current password if trying to update password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('status', 'Profile updated successfully!');
    }

    /**
     * Delete account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password is incorrect']);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Account deleted successfully');
    }
}
