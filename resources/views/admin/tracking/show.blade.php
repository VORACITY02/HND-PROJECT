@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto space-y-6">
  <div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold">Tracking: {{ $student->name }} ({{ $student->email }})</h1>
    <p class="mt-2">Supervisor ID: {{ $assignment->supervisor_id }}</p>
    <p class="text-sm text-slate-600 mt-1">Admin Authorizer ID: {{ $assignment->assigned_by_admin_id }}</p>
    <div class="mt-4 p-4 bg-sky-50 border border-sky-200 rounded">
      @if(isset($chart))
        @php($total = max(1, $chart['total'] ?? 1))
        <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
          <div class="p-3 rounded border bg-white">
            <div class="font-semibold">Graded On Time</div>
            <div>{{ $chart['on_time'] ?? 0 }}</div>
            <div class="mt-2 h-2 bg-sky-100 rounded overflow-hidden"><div class="h-2 bg-sky-500" style="width: {{ (($chart['on_time'] ?? 0) / $total) * 100 }}%"></div></div>
          </div>
          <div class="p-3 rounded border bg-white">
            <div class="font-semibold">Graded Late</div>
            <div>{{ $chart['late'] ?? 0 }}</div>
            <div class="mt-2 h-2 bg-sky-100 rounded overflow-hidden"><div class="h-2 bg-amber-400" style="width: {{ (($chart['late'] ?? 0) / $total) * 100 }}%"></div></div>
          </div>
          <div class="p-3 rounded border bg-white">
            <div class="font-semibold">Negative Grades</div>
            <div>{{ $chart['negative'] ?? 0 }}</div>
            <div class="mt-2 h-2 bg-sky-100 rounded overflow-hidden"><div class="h-2 bg-rose-400" style="width: {{ (($chart['negative'] ?? 0) / $total) * 100 }}%"></div></div>
          </div>
        </div>
      @endif
      <div class="font-semibold">Overall Progress</div>
      <div class="text-3xl font-bold">{{ $overall }}%</div>
      <div class="mt-3 h-3 bg-sky-100 rounded overflow-hidden">
        <div class="h-3 bg-sky-500" style="width: {{ max(0, min(100, $overall)) }}%"></div>
      </div>
    </div>
  </div>

  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Task Details</h2>
    <table class="w-full border">
      <thead>
        <tr class="bg-slate-100">
          <th class="p-2 text-left">Task</th>
          <th class="p-2">Type</th>
          <th class="p-2">Score</th>
          <th class="p-2">Grade %</th>
          <th class="p-2">Submitted</th>
          <th class="p-2">Graded</th>
          <th class="p-2">On Time</th>
          <th class="p-2">Late (days)</th>
          <th class="p-2">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($details as $d)
        <tr class="border-t">
          <td class="p-2">{{ $d['task_title'] }}</td>
          <td class="p-2">{{ $d['task_type'] }}</td>
          <td class="p-2">{{ $d['raw_score'] === null ? '-' : ($d['raw_score'] . ' / ' . ($d['max_score'] ?? '-')) }}</td>
          <td class="p-2">{{ $d['grade_pct'] === null ? '-' : number_format($d['grade_pct'],2) . '%' }}</td>
          <td class="p-2">{{ optional($d['submitted_at'])->toDayDateTimeString() }}</td>
          <td class="p-2">{{ optional($d['graded_at'])->toDayDateTimeString() }}</td>
          <td class="p-2">{{ $d['on_time'] === null ? '-' : ($d['on_time'] ? 'Yes' : 'No') }}</td>
          <td class="p-2">{{ $d['late_by_days'] === null ? '-' : $d['late_by_days'] }}</td>
          <td class="p-2">{{ $d['status'] }}</td>
        </tr>
        @empty
        <tr><td class="p-3" colspan="9">No submissions yet</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
