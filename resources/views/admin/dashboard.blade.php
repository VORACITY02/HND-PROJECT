@extends('layouts.app')

@section('content_header')
@endsection

@section('content')
<!-- Sinister-Professional Admin Dashboard -->
<div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 mb-2">
                Admin Dashboard
            </h1>
            <p class="text-slate-600">
                Access Level: <span class="font-semibold text-blue-600">Administrator</span> • 
                Welcome back, <span class="font-semibold text-slate-800">{{ auth()->user()->name }}</span>
            </p>
        </div>
        <div class="flex items-center space-x-4">
            <!-- User Profile Card -->
            <a href="{{ route('profile.edit') }}" class="group">
                <div class="bg-gradient-to-br from-green-950 via-green-900 to-green-950 text-white px-6 py-4 rounded-2xl shadow-2xl hover:shadow-lime-400/20 transition-all transform hover:scale-105 border border-lime-300/25">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-slate-700 rounded-full flex items-center justify-center font-bold text-xl border-2 border-blue-500 shadow-md">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            @if(auth()->user()->is_online)
                                <div class="absolute bottom-0 right-0 w-5 h-5 bg-green-500 rounded-full border-4 border-black animate-pulse"></div>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-slate-300">Administrator</p>
                            <p class="text-xs text-slate-400 mt-1">
                                @if(auth()->user()->email_verified_at)
                                    ✓ Verified
                                @else
                                    ⚠ Unverified
                                @endif
                            </p>
                        </div>
                        <svg class="w-6 h-6 text-lime-300 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Email Verification Alert -->
@if(!auth()->user()->email_verified_at)
<div class="mb-8 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-red-900 to-orange-900 opacity-20 animate-pulse"></div>
    <div class="relative bg-gradient-to-r from-red-950 to-orange-950 border-2 border-red-700 text-white p-6 rounded-2xl shadow-2xl">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-12 h-12 text-red-400 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-xl font-bold mb-2">⚠️ Email Verification Required</h3>
                <p class="mb-1 text-red-200">Your email address is <strong class="text-yellow-300">NOT VERIFIED</strong></p>
                <p class="text-sm text-red-300 mb-4">Email: <span class="font-mono bg-black/30 px-2 py-1 rounded">{{ auth()->user()->email }}</span></p>
                <div class="flex gap-3">
                    <form action="{{ route('verification.resend') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                            📧 Resend Verification Email
                        </button>
                    </form>
                    <a href="{{ route('profile.edit') }}" class="bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-xl font-bold transition-all">
                        Go to Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Statistics Cards - Dark Theme -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl shadow-lg hover:shadow-blue-900/30 transition-all border border-slate-700">
        <div class="absolute inset-0 bg-gradient-to-br from-lime-300/15 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-slate-400 text-sm font-medium mb-2">Total Users</p>
            <p class="text-4xl font-bold text-white mb-2">{{ $totalUsers }}</p>
            <p class="text-xs text-blue-400">Active accounts</p>
        </div>
    </div>

    <!-- Admins -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-red-950 to-red-900 rounded-2xl shadow-2xl hover:shadow-red-900/50 transition-all transform hover:scale-105 border border-red-700">
        <div class="absolute inset-0 bg-gradient-to-br from-red-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-red-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
            <p class="text-red-300 text-sm font-semibold mb-2 uppercase tracking-wide">Administrators</p>
            <p class="text-5xl font-black text-white mb-2">{{ $totalAdmins }}</p>
            <p class="text-xs text-red-400">Maximum clearance</p>
        </div>
    </div>

    <!-- Staff -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-emerald-950 to-emerald-900 rounded-2xl shadow-2xl hover:shadow-emerald-900/50 transition-all transform hover:scale-105 border border-emerald-700">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-emerald-300 text-sm font-semibold mb-2 uppercase tracking-wide">Staff Members</p>
            <p class="text-5xl font-black text-white mb-2">{{ $totalStaff }}</p>
            <p class="text-xs text-emerald-400">Active educators</p>
        </div>
    </div>

    <!-- Students -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-amber-950 to-amber-900 rounded-2xl shadow-2xl hover:shadow-amber-900/50 transition-all transform hover:scale-105 border border-amber-700">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <p class="text-amber-300 text-sm font-semibold mb-2 uppercase tracking-wide">Students</p>
            <p class="text-5xl font-black text-white mb-2">{{ $totalStudents }}</p>
            <p class="text-xs text-amber-400">Learning community</p>
        </div>
    </div>
</div>

<!-- Quick Actions - Dark Theme -->
<div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl shadow-2xl p-8 mb-8 border border-slate-700">
    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
        <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        Quick Actions
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.users.create') }}" class="group relative overflow-hidden bg-gradient-to-br from-blue-900 to-blue-800 p-5 rounded-lg hover:from-blue-800 hover:to-blue-700 transition-all shadow-md hover:shadow-lg border border-blue-700">
            <div class="flex items-start">
                <div class="w-12 h-12 bg-lime-400 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-white text-lg mb-1">Add New User</p>
                    <p class="text-sm text-lime-100/70">Create new account</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.users.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-emerald-900 to-emerald-800 p-6 rounded-xl hover:from-emerald-800 hover:to-emerald-700 transition-all shadow-xl hover:shadow-2xl hover:shadow-emerald-900/50 transform hover:scale-105 border border-emerald-700">
            <div class="flex items-start">
                <div class="w-12 h-12 bg-emerald-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-white text-lg mb-1">Manage Users</p>
                    <p class="text-sm text-emerald-300">View and edit all users</p>
                </div>
            </div>
        </a>

       <a href="{{ route('admin.supervisors.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-amber-900 to-amber-800 p-6 rounded-xl hover:from-amber-800 hover:to-amber-700 transition-all shadow-xl hover:shadow-2xl hover:shadow-amber-900/50 transform hover:scale-105 border border-amber-700">
           <div class="flex items-start">
               <div class="w-12 h-12 bg-amber-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                   <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                   </svg>
               </div>
               <div>
                   <p class="font-bold text-white text-lg mb-1">Manage Supervisor Applications</p>
                   <p class="text-sm text-amber-300">Approve or reject</p>
               </div>
           </div>
       </a>

        <a href="{{ route('admin.work-queue') }}" class="group relative overflow-hidden bg-gradient-to-br from-blue-900 to-blue-800 p-6 rounded-xl hover:from-blue-800 hover:to-blue-700 transition-all shadow-xl hover:shadow-2xl hover:shadow-blue-900/50 transform hover:scale-105 border border-blue-700">
            <div class="flex items-start">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-white text-lg mb-1">Work Queue</p>
                    <p class="text-sm text-blue-300">Requests, confirmations & tracking</p>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Admin Work Queue -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
  <div class="bg-white p-6 rounded-lg shadow">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-bold">Pending Supervision Requests</h2>
      <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-amber-100 text-amber-800">
        {{ $pendingRequestsCount ?? 0 }} pending
      </span>
    </div>

    <div class="mt-4">
      @if(($pendingRequestsCount ?? 0) > 0)
        <ul class="divide-y">
          @foreach(($pendingRequests ?? []) as $r)
            <li class="py-3 flex items-center justify-between gap-4">
              <div class="min-w-0">
                <p class="font-semibold text-slate-800 truncate">{{ $r->student?->name }} → {{ $r->requestedSupervisor?->name }}</p>
                <p class="text-sm text-slate-500 truncate">{{ $r->note }}</p>
              </div>
              <a class="text-green-900 font-semibold text-sm whitespace-nowrap" href="{{ route('admin.assignments.index') }}">
                Review
              </a>
            </li>
          @endforeach
        </ul>
      @else
        <p class="text-slate-600">No pending requests assigned to you.</p>
      @endif

      <div class="mt-4">
        <a href="{{ route('admin.assignments.index') }}" class="inline-block bg-emerald-600 text-white px-4 py-2 rounded">Open Requests</a>
      </div>
    </div>
  </div>

  <div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-3">Reports & Analytics</h2>
    <p class="text-slate-600 mb-4">Track individual student progress and review submissions.</p>
    <a href="{{ route('admin.tracking.index') }}" class="inline-block bg-lime-400 text-green-950 px-4 py-2 rounded border border-lime-200">Open Tracking</a>
  </div>
</div>

<!-- Recent Users - Dark Theme -->
<div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl shadow-2xl p-8 border border-slate-700">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-white flex items-center">
            <svg class="w-8 h-8 mr-3 text-lime-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Recent Users
        </h2>
        <a href="{{ route('admin.users.index') }}" class="text-lime-300 hover:text-lime-200 font-semibold flex items-center transition-colors group">
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
                <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-xl hover:bg-slate-700/50 transition-all border border-slate-700 hover:border-lime-400/40">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-lime-400 to-yellow-300 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold">{{ substr($recentUser->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-bold text-white">{{ $recentUser->name }}</p>
                            <p class="text-xs text-slate-400">{{ $recentUser->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($recentUser->role === 'admin')
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-900 text-red-200">Admin</span>
                        @elseif($recentUser->role === 'staff')
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-emerald-900 text-emerald-200">Staff</span>
                        @else
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-amber-900 text-amber-200">User</span>
                        @endif
                        <span class="text-xs text-slate-500">{{ $recentUser->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-20 h-20 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-slate-500 text-lg">No users found</p>
        </div>
    @endif
</div>
@endsection
