@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.users.index') }}" class="text-primary-600 hover:text-primary-700 font-semibold inline-flex items-center mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Users
        </a>
        <h1 class="text-4xl font-bold text-slate-800 mb-2">Edit User: {{ $user->name }}</h1>
        <p class="text-slate-600 text-lg">Update user information and role</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-xl shadow">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-rose-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-semibold text-rose-700 mb-2">Please fix the following errors:</p>
                    <ul class="list-disc list-inside text-rose-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white rounded-xl shadow-lg p-8">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Basic Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>

                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>

                <div>
                    <label class="block text-slate-700 font-semibold mb-2">New Password (leave blank to keep current)</label>
                    <input type="password" name="password"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>

                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-slate-700 font-semibold mb-2">Role * 
                        @if($user->role !== old('role', $user->role))
                            <span class="text-amber-600 text-sm">(Changing role will transfer data to appropriate table)</span>
                        @endif
                    </label>
                    <select name="role" id="role" required
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                        <option value="">Select a role</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Role-Specific Fields -->
        @php
            $adminData = $user->admin;
            $staffData = $user->staff;
            $studentData = $user->student;
        @endphp

        <div id="admin-fields" class="role-fields hidden mb-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Admin Details
            </h2>
            <p class="text-slate-600 text-sm">No additional information required for admin role.</p>
        </div>

        <div id="staff-fields" class="role-fields hidden mb-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Staff Details
            </h2>
            <p class="text-slate-600 text-sm">No additional information required for staff role.</p>
        </div>

        <div id="student-fields" class="role-fields hidden mb-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                User Details
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $studentData->phone ?? '') }}"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200 mt-8">
            <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition font-semibold">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-lime-400 text-green-950 rounded-xl hover:bg-lime-300 transition font-semibold shadow-lg hover:shadow-xl border border-lime-200">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Update User
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('role').addEventListener('change', function() {
        document.querySelectorAll('.role-fields').forEach(function(el) {
            el.classList.add('hidden');
        });
        
        const role = this.value;
        if (role === 'admin') {
            document.getElementById('admin-fields').classList.remove('hidden');
        } else if (role === 'staff') {
            document.getElementById('staff-fields').classList.remove('hidden');
        } else if (role === 'user') {
            document.getElementById('student-fields').classList.remove('hidden');
        }
    });
    
    // Trigger on page load
    document.getElementById('role').dispatchEvent(new Event('change'));
</script>
@endsection
