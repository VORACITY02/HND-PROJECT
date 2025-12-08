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
        <h1 class="text-4xl font-bold text-slate-800 mb-2">Create New User</h1>
        <p class="text-slate-600 text-lg">Add a new user to the system</p>
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

    <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white rounded-xl shadow-lg p-8">
        @csrf

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
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>

                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>

                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Password *</label>
                    <input type="password" name="password" required
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>

                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-slate-700 font-semibold mb-2">Role *</label>
                    <select name="role" id="role" required
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                        <option value="">Select a role</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Student</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Role-Specific Fields -->
        <div id="admin-fields" class="role-fields hidden mb-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Admin Details
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Department</label>
                    <input type="text" name="department" value="{{ old('department') }}"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Position</label>
                    <input type="text" name="position" value="{{ old('position') }}"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
            </div>
        </div>

        <div id="staff-fields" class="role-fields hidden mb-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Staff Details
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Employee ID</label>
                    <input type="text" name="employee_id" value="{{ old('employee_id') }}"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Department</label>
                    <input type="text" name="department" value="{{ old('department') }}"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Position</label>
                    <input type="text" name="position" value="{{ old('position') }}"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-slate-700 font-semibold mb-2">Specialization</label>
                    <textarea name="specialization" rows="3"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">{{ old('specialization') }}</textarea>
                </div>
            </div>
        </div>

        <div id="student-fields" class="role-fields hidden mb-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Student Details
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Student ID</label>
                    <input type="text" name="student_id" value="{{ old('student_id') }}"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">University</label>
                    <input type="text" name="university" value="{{ old('university') }}"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Major</label>
                    <input type="text" name="major" value="{{ old('major') }}"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Year of Study</label>
                    <select name="year_of_study"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                        <option value="">Select year</option>
                        <option value="1st Year">1st Year</option>
                        <option value="2nd Year">2nd Year</option>
                        <option value="3rd Year">3rd Year</option>
                        <option value="4th Year">4th Year</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">GPA</label>
                    <input type="number" step="0.01" min="0" max="4" name="gpa" value="{{ old('gpa') }}"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-slate-700 font-semibold mb-2">Skills</label>
                    <textarea name="skills" rows="2" placeholder="e.g., PHP, Laravel, JavaScript"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">{{ old('skills') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-slate-700 font-semibold mb-2">Bio</label>
                    <textarea name="bio" rows="3"
                        class="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">{{ old('bio') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
            <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition font-semibold">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition font-semibold shadow-lg hover:shadow-xl">
                Create User
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('role').addEventListener('change', function() {
        // Hide all role fields
        document.querySelectorAll('.role-fields').forEach(function(el) {
            el.classList.add('hidden');
        });
        
        // Show selected role fields
        const role = this.value;
        if (role === 'admin') {
            document.getElementById('admin-fields').classList.remove('hidden');
        } else if (role === 'staff') {
            document.getElementById('staff-fields').classList.remove('hidden');
        } else if (role === 'user') {
            document.getElementById('student-fields').classList.remove('hidden');
        }
    });
    
    // Trigger on page load if role is pre-selected
    if (document.getElementById('role').value) {
        document.getElementById('role').dispatchEvent(new Event('change'));
    }
</script>
@endsection
