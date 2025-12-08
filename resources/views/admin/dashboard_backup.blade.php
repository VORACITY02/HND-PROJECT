@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-slate-800 mb-2">Admin Dashboard</h1>
    <p class="text-slate-600 text-lg">Welcome back, {{ auth()->user()->name }}! Here's your system overview.</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-primary-500 hover:shadow-xl transition">
        <p class="text-slate-500 text-sm font-semibold mb-2">Total Users</p>
        <p class="text-4xl font-bold text-slate-800">{{ $totalUsers }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-secondary-500 hover:shadow-xl transition">
        <p class="text-slate-500 text-sm font-semibold mb-2">Admins</p>
        <p class="text-4xl font-bold text-slate-800">{{ $totalAdmins }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-emerald-500 hover:shadow-xl transition">
        <p class="text-slate-500 text-sm font-semibold mb-2">Staff Members</p>
        <p class="text-4xl font-bold text-slate-800">{{ $totalStaff }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-amber-500 hover:shadow-xl transition">
        <p class="text-slate-500 text-sm font-semibold mb-2">Students</p>
        <p class="text-4xl font-bold text-slate-800">{{ $totalStudents }}</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <h2 class="text-xl font-semibold text-slate-800 mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.users.create') }}" class="p-4 border-2 border-slate-200 rounded-xl hover:bg-primary-50 hover:border-primary-300 transition shadow hover:shadow-md">
            <p class="font-semibold text-slate-800 mb-1">Add New User</p>
            <p class="text-sm text-slate-600">Create a new user account</p>
        </a>

        <a href="{{ route('admin.users.index') }}" class="p-4 border-2 border-slate-200 rounded-xl hover:bg-primary-50 hover:border-primary-300 transition shadow hover:shadow-md">
            <p class="font-semibold text-slate-800 mb-1">Manage Users</p>
            <p class="text-sm text-slate-600">View and edit all users</p>
        </a>

        <a href="#" class="p-4 border-2 border-slate-200 rounded-xl hover:bg-primary-50 hover:border-primary-300 transition shadow hover:shadow-md">
            <p class="font-semibold text-slate-800 mb-1">System Reports</p>
            <p class="text-sm text-slate-600">View analytics and reports</p>
        </a>
    </div>
</div>

<!-- Recent Users -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-slate-800">Recent Users</h2>
        <a href="{{ route('admin.users.index') }}" class="text-primary-600 hover:text-primary-700 font-semibold">View All →</a>
    </div>
    
    @php
        $recentUsers = \App\Models\User::with(['admin', 'staff', 'student'])->latest()->take(5)->get();
    @endphp
    
    @if($recentUsers->count() > 0)
        <div class="space-y-3">
            @foreach($recentUsers as $recentUser)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                            <span class="text-primary-600 font-bold">{{ substr($recentUser->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">{{ $recentUser->name }}</p>
                            <p class="text-xs text-slate-500">{{ $recentUser->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($recentUser->role === 'admin')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-secondary-100 text-secondary-800">Admin</span>
                        @elseif($recentUser->role === 'staff')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Staff</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Student</span>
                        @endif
                        <span class="text-xs text-slate-500">{{ $recentUser->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-slate-600">No users found.</p>
    @endif
</div>
@endsection