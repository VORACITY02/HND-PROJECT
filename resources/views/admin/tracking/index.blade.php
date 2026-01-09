@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
  <h1 class="text-2xl font-bold mb-4">Reports & Analytics: Your Assigned Students</h1>
  <table class="w-full border">
    <thead>
      <tr class="bg-slate-100">
        <th class="p-2 text-left">Student</th>
        <th class="p-2">Supervisor ID</th>
        <th class="p-2">Assigned At</th>
        <th class="p-2">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($assignments as $a)
      <tr class="border-t">
        <td class="p-2">{{ $a->student->name ?? 'Unknown' }} ({{ $a->student->email ?? '' }})</td>
        <td class="p-2 text-center">{{ $a->supervisor_id }}</td>
        <td class="p-2 text-center">{{ optional($a->assigned_at)->toDayDateTimeString() }}</td>
        <td class="p-2 text-center">
          <a class="inline-block bg-lime-400 text-green-950 px-3 py-1 rounded border border-lime-200" href="{{ route('admin.tracking.show', $a->student_id) }}">View Tracking</a>
        </td>
      </tr>
      @empty
      <tr><td colspan="4" class="p-4">No assigned students yet</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
