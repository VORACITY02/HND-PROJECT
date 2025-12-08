@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Email Verification Alert -->
    @unless (auth()->user()->hasVerifiedEmail())
        <div class="mb-8 p-6 bg-amber-50 border border-amber-200 rounded-xl shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-amber-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-amber-800">Email Verification Required</h3>
                    <p class="text-amber-700">Please verify your email address to access all administrative features.</p>
                    <a href="{{ route('verification.resend') }}" class="text-blue-600 hover:text-blue-800 underline font-medium">Resend verification email</a>
                </div>
            </div>
        </div>
    @endunless

    <!-- Header Section -->
    <div class="bg-slate-800 text-white rounded-xl p-8 mb-8 shadow-xl">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0121 12a11.955 11.955 0 01-1.382 5.016m0 0A11.955 11.955 0 0112 21a11.955 11.955 0 01-7.618-2.984m15.236 0A11.955 11.955 0 0112 3c-3.314 0-6.248 1.344-8.382 3.016m0 0A11.955 11.955 0 003 12a11.955 11.955 0 001.382 5.016"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold mb-1">Administrator Dashboard</h1>
                <p class="text-blue-300 mb-2">System Control & User Management Center</p>
                <div class="flex items-center space-x-2">
                    <span class="bg-red-600/20 border border-red-500/30 px-3 py-1 rounded-full text-sm">
                        Administrator
                    </span>
                    <span class="bg-emerald-600/20 border border-emerald-500/30 px-3 py-1 rounded-full text-sm">
                        <span class="inline-block w-2 h-2 bg-emerald-400 rounded-full mr-1"></span>
                        Full Access
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-blue-600/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-800">{{ $totalUsers ?? 0 }}</div>
                    <div class="text-sm text-slate-600">Total Users</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-red-600/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0121 12a11.955 11.955 0 01-1.382 5.016m0 0A11.955 11.955 0 0112 21a11.955 11.955 0 01-7.618-2.984m15.236 0A11.955 11.955 0 0112 3c-3.314 0-6.248 1.344-8.382 3.016m0 0A11.955 11.955 0 003 12a11.955 11.955 0 001.382 5.016"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-800">{{ $totalAdmins ?? 0 }}</div>
                    <div class="text-sm text-slate-600">Administrators</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-green-600/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-800">{{ $totalStaff ?? 0 }}</div>
                    <div class="text-sm text-slate-600">Staff Members</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-purple-600/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-800">{{ $totalStudents ?? 0 }}</div>
                    <div class="text-sm text-slate-600">Regular Users</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8 mb-8">
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-10 h-10 bg-blue-600/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Administrative Actions</h2>
                <p class="text-slate-600">Core administrative functions and tools</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('admin.users.index') }}" class="group bg-blue-600/10 hover:bg-blue-600 border border-blue-600/20 hover:border-blue-600 rounded-xl p-6 transition-all text-center">
                <div class="w-12 h-12 bg-blue-600 group-hover:bg-white rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-white group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-blue-600 group-hover:text-white mb-2">Manage Users</h3>
                <p class="text-blue-600/80 group-hover:text-white/80 text-sm">View, edit, and promote users</p>
            </a>

            <a href="{{ route('admin.users.create') }}" class="group bg-green-600/10 hover:bg-green-600 border border-green-600/20 hover:border-green-600 rounded-xl p-6 transition-all text-center">
                <div class="w-12 h-12 bg-green-600 group-hover:bg-white rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-white group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-green-600 group-hover:text-white mb-2">Add New User</h3>
                <p class="text-green-600/80 group-hover:text-white/80 text-sm">Create new user accounts</p>
            </a>

            <a href="{{ route('messages.create') }}" class="group bg-purple-600/10 hover:bg-purple-600 border border-purple-600/20 hover:border-purple-600 rounded-xl p-6 transition-all text-center">
                <div class="w-12 h-12 bg-purple-600 group-hover:bg-white rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-white group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-purple-600 group-hover:text-white mb-2">Send Broadcast</h3>
                <p class="text-purple-600/80 group-hover:text-white/80 text-sm">Message all users, staff, or admins</p>
            </a>
        </div>
    </div>

    <!-- Online Users -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-10 h-10 bg-emerald-600/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Currently Online Users</h2>
                <p class="text-slate-600">Monitor active users across all roles</p>
            </div>
        </div>
        
        @php
            $onlineUsers = \App\Models\User::online()->with(['admin', 'staff', 'student'])->get();
        @endphp
        
        @if($onlineUsers->count() > 0)
            <!-- Online Stats -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                @php
                    $onlineAdmins = $onlineUsers->where('role', 'admin')->count();
                    $onlineStaff = $onlineUsers->where('role', 'staff')->count();  
                    $onlineUsersCount = $onlineUsers->where('role', 'user')->count();
                @endphp
                
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                    <div class="text-lg font-bold text-red-600">{{ $onlineAdmins }}</div>
                    <div class="text-sm text-red-700">Admins Online</div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                    <div class="text-lg font-bold text-green-600">{{ $onlineStaff }}</div>
                    <div class="text-sm text-green-700">Staff Online</div>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <div class="text-lg font-bold text-blue-600">{{ $onlineUsersCount }}</div>
                    <div class="text-sm text-blue-700">Users Online</div>
                </div>
            </div>

            <!-- Online Users List -->
            <div class="space-y-3">
                @foreach($onlineUsers as $onlineUser)
                    <div class="flex items-center space-x-4 p-4 bg-slate-50 rounded-lg border border-slate-200">
                        <div class="w-12 h-12 bg-{{ $onlineUser->role === 'admin' ? 'red' : ($onlineUser->role === 'staff' ? 'green' : 'blue') }}-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold">{{ substr($onlineUser->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-slate-800">{{ $onlineUser->name }}</div>
                            <div class="text-sm text-slate-600">{{ ucfirst($onlineUser->role) }} • {{ $onlineUser->email }}</div>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                                <span class="text-sm font-medium text-emerald-600">Online</span>
                            </div>
                            <div class="text-xs text-slate-500">
                                Last seen: {{ $onlineUser->last_seen_at ? $onlineUser->last_seen_at->diffForHumans() : 'Now' }}
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.users.edit', $onlineUser) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition-all">
                                Edit
                            </a>
                            <a href="{{ route('messages.create', ['user_id' => $onlineUser->id]) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium transition-all">
                                Message
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-800 mb-2">No Users Currently Online</h3>
                <p class="text-slate-600">All users are currently offline. Online users will appear here with real-time status.</p>
            </div>
        @endif
    </div>

</div>
@endsection