@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-6 rounded-xl shadow-lg border border-slate-700 mb-6">
        <h1 class="text-2xl font-bold">Welcome, {{ auth()->user()->name }}</h1>
        <p class="text-slate-300">This is your student dashboard.</p>
        <p class="text-slate-400 text-sm mt-1">Instructions: Complete your profile, request a supervisor, then submit tasks assigned to you. Track your overall progress below.</p>
        @isset($progress)
        <div class="mt-4 p-5 rounded-xl bg-white/10 border border-sky-400/20">
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-semibold">Internship Progress</div>
                    <div class="text-3xl font-bold">{{ $progress }}%</div>
                    <div class="text-xs text-slate-300 mt-1">Progress updates after your supervisor grades tasks (late submissions reduce score).</div>
                </div>
                <div class="w-20 h-20 rounded-full border-4 border-sky-300/30 flex items-center justify-center">
                    <div class="text-lg font-bold text-sky-200">{{ $progress }}%</div>
                </div>
            </div>
            <div class="mt-4 h-3 bg-slate-800/60 rounded overflow-hidden">
                <div class="h-3 bg-sky-400" style="width: {{ max(0, min(100, $progress)) }}%"></div>
            </div>
        </div>
        @endisset
    </div>

    @if(!$assignment)
    <div class="bg-amber-50 border border-amber-200 rounded p-4 mb-6">
        <div class="font-semibold mb-2">No active supervisor assignment</div>
        <a href="{{ route('user.supervision.request') }}" class="inline-block bg-sky-500 text-white px-4 py-2 rounded border border-sky-200">Request Supervision</a>
    </div>
    @else
    <div class="bg-sky-50 border border-sky-200 rounded p-4 mb-6">
        <div class="font-semibold">Assigned Supervisor</div>
        <div class="mt-1">{{ $assignment->supervisor->name ?? 'Unknown' }} (ID: {{ $assignment->supervisor_id }})</div>
        <div class="text-sm text-slate-600 mt-2">
            Assigned at: {{ optional($assignment->assigned_at)->toDayDateTimeString() ?? 'N/A' }}
            @if($assignment->assignedBy)
                <span class="ml-2">| Authorised by: {{ $assignment->assignedBy->name }} (ID: {{ $assignment->assigned_by_admin_id }})</span>
            @endif
        </div>
    </div>
    @endif

    {{-- Show ONLY ONE table: if there is any assignment history show assignments, otherwise show requests --}}
    @if(($assignmentHistory ?? collect())->isNotEmpty())
    <div class="bg-white/90 backdrop-blur border border-slate-200 p-4 rounded-xl shadow mb-6">
        <div class="font-semibold mb-3">Your Supervision Assignments</div>
        <table class="w-full text-sm">
            <thead class="bg-slate-100">
                <tr>
                    <th class="text-left p-2">Supervisor</th>
                    <th class="text-left p-2">Admin</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Assigned At</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($assignmentHistory ?? collect()) as $a)
                <tr class="border-t">
                    <td class="p-2">{{ $a->supervisor->name ?? 'N/A' }}</td>
                    <td class="p-2">{{ $a->assignedBy->name ?? 'N/A' }}</td>
                    <td class="p-2">{{ ($a->active ?? true) ? 'Active' : 'Inactive' }}</td>
                    <td class="p-2">{{ optional($a->assigned_at)->toDayDateTimeString() ?? 'N/A' }}</td>
                </tr>
                @empty
                <tr><td class="p-3" colspan="4">No assignments yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @else
    <div class="bg-white/90 backdrop-blur border border-slate-200 p-4 rounded-xl shadow mb-6">
        <div class="font-semibold mb-3">Your Supervision Requests</div>
        <table class="w-full text-sm">
            <thead class="bg-slate-100">
                <tr>
                    <th class="text-left p-2">Supervisor</th>
                    <th class="text-left p-2">Admin</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $r)
                <tr class="border-t">
                    <td class="p-2">{{ $r->requestedSupervisor->name ?? 'N/A' }}</td>
                    <td class="p-2">{{ $r->requestedAdmin->name ?? 'N/A' }}</td>
                    <td class="p-2">{{ ucfirst($r->status) }}</td>
                    <td class="p-2">{{ $r->created_at->toDayDateTimeString() }}</td>
                </tr>
                @empty
                <tr><td class="p-3" colspan="4">No requests yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('messages.index') }}" class="group block bg-white/90 backdrop-blur p-6 rounded-xl shadow hover:shadow-lg border border-slate-200 hover:border-sky-300 transition">
            <p class="font-semibold text-slate-900 group-hover:text-sky-900">Messages</p>
            <p class="text-sm text-slate-500">Read and send messages</p>
        </a>
        <a href="{{ route('profile.edit') }}" class="group block bg-white/90 backdrop-blur p-6 rounded-xl shadow hover:shadow-lg border border-slate-200 hover:border-sky-300 transition">
            <p class="font-semibold text-slate-900 group-hover:text-sky-900">Profile</p>
            <p class="text-sm text-slate-500">Update your profile</p>
        </a>
        <a href="{{ route('user.tasks.index') }}" class="group block bg-white/90 backdrop-blur p-6 rounded-xl shadow hover:shadow-lg border border-slate-200 hover:border-sky-300 transition">
            <p class="font-semibold text-slate-900 group-hover:text-sky-900">My Tasks</p>
            <p class="text-sm text-slate-500">View and submit assigned tasks</p>
        </a>
        <a href="{{ route('profile.personal') }}" class="group block bg-white/90 backdrop-blur p-6 rounded-xl shadow hover:shadow-lg border border-slate-200 hover:border-sky-300 transition">
            <p class="font-semibold text-slate-900 group-hover:text-sky-900">Personal Data</p>
            <p class="text-sm text-slate-500">View saved personal data</p>
        </a>
    </div>
</div>
@endsection