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
    <div class="group relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl shadow-lg hover:shadow-lime-400/20 transition-all border border-slate-700">
        <div class="absolute inset-0 bg-gradient-to-br from-lime-300/15 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-lime-400 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
            </div>
            <p class="text-slate-400 text-sm font-medium mb-2">Messages Sent</p>
            <p class="text-4xl font-bold text-white mb-2">{{ \App\Models\Message::where('sender_id', auth()->id())->count() }}</p>
            <p class="text-xs text-lime-300">Communications</p>
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

<!-- Notices / Instructions -->
<div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-6 rounded-xl shadow-lg border border-slate-700 mb-6">
    <h2 class="text-xl font-bold mb-1">Staff Dashboard</h2>
    <p class="text-slate-300 text-sm">Instructions: Apply to become a supervisor (if not yet approved), create internship tasks for assigned students, and grade their submissions. You’ll receive notifications when your supervisor application is approved or rejected.</p>
    @isset($unreadCount)
        @if($unreadCount > 0)
            <div class="mt-3 bg-amber-500/20 border border-amber-400/40 text-amber-200 px-3 py-2 rounded">
                You have {{ $unreadCount }} unread notification(s).
            </div>
        @endif
    @endisset
    @isset($recentMessages)
        @if($recentMessages->count())
        <div class="mt-3">
            <div class="font-semibold mb-1">Recent Notifications</div>
            <ul class="text-sm list-disc pl-5 text-slate-200">
                @foreach($recentMessages as $msg)
                    <li><span class="font-medium">{{ $msg->subject }}</span> — <span class="text-slate-300">{{ $msg->created_at->diffForHumans() }}</span></li>
                @endforeach
            </ul>
        </div>
        @endif
    @endisset
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

    @php($app = auth()->user()->SupervisorApplication)
    @if(!$app)
    <a href="{{ route('staff.supervisor.apply') }}" class="group relative overflow-hidden bg-gradient-to-br from-green-950 to-green-900 p-6 rounded-xl hover:from-green-900 hover:to-green-800 transition-all shadow-xl hover:shadow-2xl hover:shadow-lime-400/20 transform hover:scale-105 border border-lime-300/25">
        <div class="flex items-start">
            <div class="w-12 h-12 bg-lime-400 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-white text-lg mb-1">Apply to be Supervisor</p>
                <p class="text-sm text-lime-100/70">Submit application</p>
            </div>
        </div>
    </a>
    @elseif($app->status === 'pending')
    <div class="group relative overflow-hidden bg-gradient-to-br from-slate-800 to-slate-700 p-6 rounded-xl border border-slate-600">
        <div class="flex items-start">
            <div class="w-12 h-12 bg-slate-500 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 1"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-white text-lg mb-1">Application Pending</p>
                <p class="text-sm text-slate-300">Awaiting admin approval</p>
            </div>
        </div>
    </div>
    @elseif($app->status === 'rejected')
    <a href="{{ route('staff.supervisor.apply') }}" class="group relative overflow-hidden bg-gradient-to-br from-rose-900 to-rose-800 p-6 rounded-xl hover:from-rose-800 hover:to-rose-700 transition-all shadow-xl hover:shadow-2xl hover:shadow-rose-900/50 transform hover:scale-105 border border-rose-700">
        <div class="flex items-start">
            <div class="w-12 h-12 bg-rose-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-white text-lg mb-1">Application Rejected</p>
                <p class="text-sm text-rose-300">Click to re-apply</p>
            </div>
        </div>
    </a>
    @else
    <div class="group relative overflow-hidden bg-gradient-to-br from-emerald-900 to-emerald-800 p-6 rounded-xl border border-emerald-700">
        <div class="flex items-start">
            <div class="w-12 h-12 bg-emerald-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-white text-lg mb-1">Supervisor Approved</p>
                <p class="text-sm text-emerald-300">You can now take students</p>
            </div>
        </div>
    </div>
    @endif

    <a href="{{ route('profile.edit') }}" class="group relative overflow-hidden bg-gradient-to-br from-green-950 to-green-900 p-6 rounded-xl hover:from-green-900 hover:to-green-800 transition-all shadow-xl hover:shadow-2xl hover:shadow-lime-400/20 transform hover:scale-105 border border-lime-300/25">
        <div class="flex items-start">
            <div class="w-12 h-12 bg-lime-400 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-white text-lg mb-1">My Profile</p>
                <p class="text-sm text-lime-100/70">Settings</p>
            </div>
        </div>
    </a>
</div>

<!-- My Supervised Students -->
<div class="bg-white p-6 rounded-xl shadow border border-slate-200 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-slate-800">My Supervised Students</h2>
        <a href="{{ route('staff.tasks.index') }}" class="inline-block bg-slate-900 text-white px-4 py-2 rounded">Manage Tasks & Submissions</a>
    </div>

    @if(isset($assignmentRows) && count($assignmentRows))
        <div class="overflow-x-auto">
            <table class="w-full text-sm border">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="p-2 text-left">Student</th>
                        <th class="p-2 text-left">Email</th>
                        <th class="p-2 text-center">Progress</th>
                        <th class="p-2 text-left">Approved By (Admin)</th>
                        <th class="p-2 text-center">Assigned At</th>
                        <th class="p-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignmentRows as $row)
                        @php($a = $row['assignment'])
                        @php($student = $row['student'])
                        <tr class="border-t">
                            <td class="p-2 font-medium">{{ $student?->name ?? 'Unknown' }} (ID: {{ $a->student_id }})</td>
                            <td class="p-2">{{ $student?->email ?? '-' }}</td>
                            <td class="p-2 text-center">
                                <span class="inline-block px-2 py-1 rounded bg-emerald-50 border border-emerald-200 text-emerald-800">
                                    {{ $row['progress'] }}%
                                </span>
                            </td>
                            <td class="p-2">{{ $a->assignedBy?->name ?? 'N/A' }} (ID: {{ $a->assigned_by_admin_id }})</td>
                            <td class="p-2 text-center">{{ optional($a->assigned_at)->toDayDateTimeString() }}</td>
                            <td class="p-2 text-center">
                                <a href="{{ route('staff.tasks.index') }}" class="inline-block bg-lime-400 text-green-950 px-3 py-1 rounded border border-lime-200">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="text-xs text-slate-500 mt-3">Note: Progress is calculated from graded task submissions for your tasks.</p>
    @else
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-900 p-4 rounded">
            No students have been assigned to you for supervision yet.
        </div>
    @endif
</div>

@endsection