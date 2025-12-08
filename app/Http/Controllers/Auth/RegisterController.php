<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','confirmed', Password::min(8)],
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email'=> $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role' => $request->input('role', 'user'),
            ]);

            // Create role-specific profile
            $this->createRoleProfile($user);

            DB::commit();

            // Log the user in
            Auth::login($user);

            // regenerate session for security
            $request->session()->regenerate();

            // redirect based on role
            return $this->redirectByRole($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to register: ' . $e->getMessage()])
                ->withInput();
        }
    }

    protected function createRoleProfile(User $user)
    {
        switch ($user->role) {
            case 'admin':
                Admin::create([
                    'user_id' => $user->id,
                    'appointed_date' => now(),
                ]);
                break;
            
            case 'staff':
                Staff::create([
                    'user_id' => $user->id,
                    'joined_date' => now(),
                ]);
                break;
            
            case 'user':
                Student::create([
                    'user_id' => $user->id,
                    'enrollment_date' => now(),
                ]);
                break;
        }
    }

    protected function redirectByRole(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'staff') {
            return redirect()->route('staff.dashboard');
        }
        
        return redirect()->route('user.dashboard');
    }
}