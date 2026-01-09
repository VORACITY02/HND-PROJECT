@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
  <div class="flex items-start justify-between gap-4 flex-wrap mb-6">
    <div>
      <h1 class="text-2xl font-bold">Admin Work Queue</h1>
      <p class="text-slate-600">Handle supervision requests and jump into student tracking.</p>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('admin.assignments.index') }}" class="bg-emerald-600 text-white px-4 py-2 rounded">Open Requests</a>
      <a href="{{ route('admin.tracking.index') }}" class="bg-lime-400 text-green-950 px-4 py-2 rounded border border-lime-200">Open Tracking</a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold">Pending Supervision Requests</h2>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-amber-100 text-amber-800">
          {{ $pendingRequestsCount }} pending
        </span>
      </div>

      <div class="mt-4">
        @if($pendingRequestsCount > 0)
          <ul class="divide-y">
            @foreach($pendingRequests as $r)
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
      </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-xl font-bold">Tracking</h2>
      <p class="text-slate-600 mt-2">You currently have <span class="font-semibold">{{ $assignedStudentsCount }}</span> assigned students to track.</p>

      <div class="mt-4">
        <a href="{{ route('admin.tracking.index') }}" class="inline-block bg-lime-400 text-green-950 px-4 py-2 rounded border border-lime-200">View Assigned Students</a>
      </div>

      <div class="mt-6 border-t pt-4">
        <h3 class="font-semibold text-slate-800">Quick stats</h3>
        <ul class="text-slate-600 mt-2 space-y-1">
          <li>Total users: {{ $totalUsers }}</li>
          <li>Total staff: {{ $totalStaff }}</li>
          <li>Total students: {{ $totalStudents }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
