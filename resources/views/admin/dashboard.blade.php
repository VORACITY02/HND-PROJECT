@extends('layouts.app')

@section('content_header')
@endsection

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-8 rounded-2xl shadow-2xl mb-8 border border-slate-700">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black mb-2">
                Welcome back, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-slate-300 text-lg">
                Admin Dashboard - Manage users and system operations
            </p>
        </div>
        <div class="text-right">
            <p class="text-slate-400 text-sm">{{ now()->format('l, F j, Y') }}</p>
            <p class="text-slate-500 text-xs">{{ now()->format('g:i A') }}</p>
        </div>
    </div>
</div>

<!-- Email Verification Alert -->
@if(!auth()->user()->email_verified_at)
<div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4">
    <div class="flex items-start gap-3">
        <svg class="w-6 h-6 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div class="flex-1">
            <h3 class="font-bold text-slate-900 mb-1">Email Verification Required</h3>
            <p class="text-sm text-slate-700 mb-3">Your email address is not verified. Email: <span class="font-mono">{{ auth()->user()->email }}</span></p>
            <div class="flex gap-2">
                <form action="{{ route('verification.resend') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-all text-sm">
                        Resend Verification Email
                    </button>
                </form>
                <a href="{{ route('profile.edit') }}" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2 rounded-lg font-semibold transition-all text-sm">
                    Go to Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white/90 backdrop-blur border border-slate-200 rounded-xl p-6 shadow hover:shadow-lg transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-slate-600 text-sm font-medium mb-1">Total Users</p>
        <p class="text-3xl font-bold text-slate-900 mb-1">{{ $totalUsers }}</p>
        <p class="text-xs text-slate-500">Active accounts</p>
    </div>

    <!-- Admins -->
    <div class="bg-white/90 backdrop-blur border border-slate-200 rounded-xl p-6 shadow hover:shadow-lg transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
        </div>
        <p class="text-slate-600 text-sm font-medium mb-1">Administrators</p>
        <p class="text-3xl font-bold text-slate-900 mb-1">{{ $totalAdmins }}</p>
        <p class="text-xs text-slate-500">Maximum clearance</p>
    </div>

    <!-- Staff -->
    <div class="bg-white/90 backdrop-blur border border-slate-200 rounded-xl p-6 shadow hover:shadow-lg transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-slate-600 text-sm font-medium mb-1">Staff Members</p>
        <p class="text-3xl font-bold text-slate-900 mb-1">{{ $totalStaff }}</p>
        <p class="text-xs text-slate-500">Active educators</p>
    </div>

    <!-- Students -->
    <div class="bg-white/90 backdrop-blur border border-slate-200 rounded-xl p-6 shadow hover:shadow-lg transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>
        <p class="text-slate-600 text-sm font-medium mb-1">Students</p>
        <p class="text-3xl font-bold text-slate-900 mb-1">{{ $totalStudents }}</p>
        <p class="text-xs text-slate-500">Learning community</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <a href="{{ route('admin.users.create') }}" class="bg-white/90 backdrop-blur border border-slate-200 rounded-xl p-6 shadow hover:shadow-lg transition-all group">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
        </div>
        <p class="font-bold text-slate-900 mb-1">Add New User</p>
        <p class="text-sm text-slate-600">Create new account</p>
    </a>

    <a href="{{ route('admin.users.index') }}" class="bg-white/90 backdrop-blur border border-slate-200 rounded-xl p-6 shadow hover:shadow-lg transition-all group">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
        <p class="font-bold text-slate-900 mb-1">Manage Users</p>
        <p class="text-sm text-slate-600">View and edit all users</p>
    </a>

    <a href="{{ route('admin.supervisors.index') }}" class="bg-white/90 backdrop-blur border border-slate-200 rounded-xl p-6 shadow hover:shadow-lg transition-all group">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="font-bold text-slate-900 mb-1">Supervisor Apps</p>
        <p class="text-sm text-slate-600">Approve or reject</p>
    </a>

    <a href="{{ route('admin.work-queue') }}" class="bg-white/90 backdrop-blur border border-slate-200 rounded-xl p-6 shadow hover:shadow-lg transition-all group">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center group-hover:bg-sky-200 transition-colors">
                <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
        </div>
        <p class="font-bold text-slate-900 mb-1">Work Queue</p>
        <p class="text-sm text-slate-600">Tasks & tracking</p>
    </a>
</div>

<!-- Notices / Instructions -->
<div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-6 rounded-xl shadow-lg border border-slate-700 mb-6">
    <h2 class="text-xl font-bold mb-1">Admin Dashboard</h2>
    <p class="text-slate-300 text-sm">Manage system users, approve supervisor applications, and oversee internship operations. Monitor pending requests and track overall system activity.</p>
</div>

<!-- Admin Work Queue -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
  <div class="bg-white/90 backdrop-blur p-6 rounded-xl shadow border border-slate-200">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl font-bold text-slate-900">Pending Supervision Requests</h2>
      <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-amber-100 text-amber-800">
        {{ $pendingRequestsCount ?? 0 }} pending
      </span>
    </div>

    <div class="mt-4">
      @if(($pendingRequestsCount ?? 0) > 0)
        <ul class="divide-y divide-slate-200">
          @foreach(($pendingRequests ?? []) as $r)
            <li class="py-3 flex items-center justify-between gap-4">
              <div class="min-w-0">
                <p class="font-semibold text-slate-900 truncate">{{ $r->student?->name }} → {{ $r->requestedSupervisor?->name }}</p>
                <p class="text-sm text-slate-600 truncate">{{ $r->note }}</p>
              </div>
              <a class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm font-semibold whitespace-nowrap" href="{{ route('admin.assignments.index') }}">
                Review
              </a>
            </li>
          @endforeach
        </ul>
      @else
        <p class="text-slate-600">No pending requests.</p>
      @endif

      <div class="mt-4">
        <a href="{{ route('admin.assignments.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">Open Requests</a>
      </div>
    </div>
  </div>

  <div class="bg-white/90 backdrop-blur p-6 rounded-xl shadow border border-slate-200">
    <h2 class="text-xl font-bold text-slate-900 mb-3">Reports & Analytics</h2>
    <p class="text-slate-600 mb-4">Track individual student progress and review submissions.</p>
    <a href="{{ route('admin.tracking.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">Open Tracking</a>
  </div>
</div>

<!-- Recent Users -->
<div class="bg-white/90 backdrop-blur rounded-xl shadow p-6 border border-slate-200">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-900">Recent Users</h2>
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center transition-colors group">
            View All
            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </a>
    </div>
    
    @php
        $recentUsers = \App\Models\User::with(['admin', 'staff', 'student'])->latest()->take(5)->get();
    @endphp
    
    @if($recentUsers->count() > 0)
        <div class="space-y-3">
            @foreach($recentUsers as $recentUser)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-all border border-slate-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-bold">{{ substr($recentUser->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $recentUser->name }}</p>
                            <p class="text-xs text-slate-600">{{ $recentUser->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($recentUser->role === 'admin')
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">Admin</span>
                        @elseif($recentUser->role === 'staff')
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">Staff</span>
                        @else
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700">User</span>
                        @endif
                        <span class="text-xs text-slate-500">{{ $recentUser->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-20 h-20 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-slate-500 text-lg">No users found</p>
        </div>
    @endif
</div>
@endsection
