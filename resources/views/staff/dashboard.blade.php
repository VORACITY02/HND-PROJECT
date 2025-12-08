@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-8 rounded-2xl shadow-2xl mb-8 border border-slate-700">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black mb-2">
                Welcome back, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-slate-300 text-lg">
                Staff Dashboard - Manage your students and programs
            </p>
        </div>
        <div class="text-right">
            <p class="text-slate-400 text-sm">{{ now()->format('l, F j, Y') }}</p>
            <p class="text-slate-500 text-xs">{{ now()->format('g:i A') }}</p>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Students -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl shadow-lg hover:shadow-blue-900/30 transition-all border border-slate-700">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-slate-400 text-sm font-medium mb-2">Total Students</p>
            <p class="text-4xl font-bold text-white mb-2">{{ \App\Models\User::where('role', 'user')->count() }}</p>
            <p class="text-xs text-blue-400">Registered users</p>
        </div>
    </div>

    <!-- Staff Team -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl shadow-lg hover:shadow-emerald-900/30 transition-all border border-slate-700">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-slate-400 text-sm font-medium mb-2">Staff Team</p>
            <p class="text-4xl font-bold text-white mb-2">{{ \App\Models\User::where('role', 'staff')->count() }}</p>
            <p class="text-xs text-emerald-400">Colleagues</p>
        </div>
    </div>

    <!-- Messages Sent -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl shadow-lg hover:shadow-purple-900/30 transition-all border border-slate-700">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
            </div>
            <p class="text-slate-400 text-sm font-medium mb-2">Messages Sent</p>
            <p class="text-4xl font-bold text-white mb-2">{{ \App\Models\Message::where('sender_id', auth()->id())->count() }}</p>
            <p class="text-xs text-purple-400">Communications</p>
        </div>
    </div>

    <!-- Unread Messages -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl shadow-lg hover:shadow-amber-900/30 transition-all border border-slate-700">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-slate-400 text-sm font-medium mb-2">Unread Messages</p>
            @php
                $user = auth()->user();
                $unreadCount = \App\Models\Message::where(function($query) use ($user) {
                        $query->where('receiver_id', $user->id)
                              ->where('is_read', false);
                    })
                    ->orWhere(function($query) use ($user) {
                        $query->where('is_broadcast', true)
                              ->where('recipient_type', 'all_staff')
                              ->whereDoesntHave('userReads', function($subQ) use ($user) {
                                  $subQ->where('user_id', $user->id);
                              });
                    })
                    ->count();
            @endphp
            <p class="text-4xl font-bold text-white mb-2">{{ $unreadCount }}</p>
            <p class="text-xs text-amber-400">Pending</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <a href="{{ route('messages.create') }}" class="group relative overflow-hidden bg-gradient-to-br from-emerald-900 to-emerald-800 p-6 rounded-xl hover:from-emerald-800 hover:to-emerald-700 transition-all shadow-xl hover:shadow-2xl hover:shadow-emerald-900/50 transform hover:scale-105 border border-emerald-700">
        <div class="flex items-start">
            <div class="w-12 h-12 bg-emerald-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-white text-lg mb-1">Send Message</p>
                <p class="text-sm text-emerald-300">Contact students</p>
            </div>
        </div>
    </a>

    <a href="{{ route('messages.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-blue-900 to-blue-800 p-6 rounded-xl hover:from-blue-800 hover:to-blue-700 transition-all shadow-xl hover:shadow-2xl hover:shadow-blue-900/50 transform hover:scale-105 border border-blue-700">
        <div class="flex items-start">
            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-white text-lg mb-1">View Messages</p>
                <p class="text-sm text-blue-300">Inbox</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.users.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-amber-900 to-amber-800 p-6 rounded-xl hover:from-amber-800 hover:to-amber-700 transition-all shadow-xl hover:shadow-2xl hover:shadow-amber-900/50 transform hover:scale-105 border border-amber-700">
        <div class="flex items-start">
            <div class="w-12 h-12 bg-amber-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-white text-lg mb-1">Manage Students</p>
                <p class="text-sm text-amber-300">User management</p>
            </div>
        </div>
    </a>

    <a href="{{ route('profile.edit') }}" class="group relative overflow-hidden bg-gradient-to-br from-purple-900 to-purple-800 p-6 rounded-xl hover:from-purple-800 hover:to-purple-700 transition-all shadow-xl hover:shadow-2xl hover:shadow-purple-900/50 transform hover:scale-105 border border-purple-700">
        <div class="flex items-start">
            <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-white text-lg mb-1">My Profile</p>
                <p class="text-sm text-purple-300">Settings</p>
            </div>
        </div>
    </a>
</div>

<!-- Recent Students -->
<div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl shadow-2xl p-8 border border-slate-700">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-white flex items-center">
            <svg class="w-8 h-8 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Recent Students
        </h2>
        <a href="{{ route('admin.users.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-all shadow-lg text-sm">
            View All
        </a>
    </div>
    
    @php
        $recentUsers = \App\Models\User::where('role', 'user')->orderBy('created_at', 'desc')->take(5)->get();
    @endphp

    @if($recentUsers->count() > 0)
        <div class="space-y-3">
            @foreach($recentUsers as $recentUser)
                <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-xl hover:bg-slate-700/50 transition-all border border-slate-700 hover:border-purple-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-pink-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold">{{ substr($recentUser->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-bold text-white">{{ $recentUser->name }}</p>
                            <p class="text-xs text-slate-400">{{ $recentUser->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-amber-900 text-amber-200">User</span>
                        <span class="text-xs text-slate-500">{{ $recentUser->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <p class="text-slate-400 text-lg mb-4">No students registered yet</p>
            <p class="text-slate-500 text-sm">Students will appear here when they join the platform!</p>
        </div>
    @endif
</div>
@endsection