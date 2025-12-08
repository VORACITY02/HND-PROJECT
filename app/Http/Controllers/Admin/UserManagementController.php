<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with(['admin', 'staff', 'student'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,staff,user',
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'email_verified_at' => now(),
            ]);

            // Create role-specific profile
            $this->createRoleProfile($user, $request);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create user: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Prevent admin from editing another admin
        if ($user->role === 'admin' && $user->id !== auth()->id()) {
            return back()->withErrors(['error' => 'You cannot edit another admin account! For security reasons, only database administrators can modify admin accounts.']);
        }

        $user->load(['admin', 'staff', 'student']);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        // Prevent admin from editing another admin
        if ($user->role === 'admin' && $user->id !== auth()->id()) {
            return back()->withErrors(['error' => 'You cannot edit another admin account! For security reasons, only database administrators can modify admin accounts.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,staff,user',
            'password' => 'nullable|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $oldRole = $user->role;
            
            // Update user basic info
            $user->name = $request->name;
            $user->email = $request->email;
            
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Check if role changed
            if ($oldRole !== $request->role) {
                // Delete old role profile
                $this->deleteRoleProfile($user, $oldRole);
                
                // Update role
                $user->role = $request->role;
                $user->save();
                
                // Create new role profile
                $this->createRoleProfile($user, $request);
            } else {
                $user->save();
                // Update existing role profile
                $this->updateRoleProfile($user, $request);
            }

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update user: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account!']);
        }

        // Prevent admin from deleting other admins
        if ($user->role === 'admin') {
            return back()->withErrors(['error' => 'You cannot delete another admin account! Only admins can be demoted from the database or by another admin through role change.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Create role-specific profile.
     */
    private function createRoleProfile(User $user, Request $request)
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

    /**
     * Update role-specific profile.
     */
    private function updateRoleProfile(User $user, Request $request)
    {
        switch ($user->role) {
            case 'admin':
                // Minimal fields for now
                // Future: Add more fields when needed
                break;
            
            case 'staff':
                // Minimal fields for now
                // Future: Add more fields when needed
                break;
            
            case 'user':
                // Minimal fields for now
                // Future: Add more fields when profile editing is implemented
                break;
        }
    }

    /**
     * Delete role-specific profile.
     */
    private function deleteRoleProfile(User $user, string $role)
    {
        switch ($role) {
            case 'admin':
                $user->admin()->delete();
                break;
            case 'staff':
                $user->staff()->delete();
                break;
            case 'user':
                $user->student()->delete();
                break;
        }
    }
}
