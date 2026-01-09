@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white p-4 rounded shadow">
        <a href="{{ route('staff.tasks.create') }}" class="bg-lime-400 text-green-950 px-4 py-2 rounded border border-lime-200">Create Task</a>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h2 class="font-bold mb-2">Your Tasks</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="p-2 text-left">Title</th>
                        <th class="p-2 text-left">Student</th>
                        <th class="p-2">Due</th>
                        <th class="p-2">Active</th>
                        <th class="p-2">Type</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $t)
                        <tr class="border-t">
                            <td class="p-2">{{ $t->title }}</td>
                            <td class="p-2">
                                @if($t->assigned_student_id)
                                    {{ $t->assignedStudent?->name ?? ('ID: ' . $t->assigned_student_id) }}
                                @else
                                    <span class="text-slate-600">All supervisees</span>
                                @endif
                            </td>
                            <td class="p-2 text-center">{{ $t->due_at ? $t->due_at->toDayDateTimeString() : '-' }}</td>
                            <td class="p-2 text-center">{{ $t->active ? 'Yes' : 'No' }}</td>
                            <td class="p-2 text-center">{{ $t->is_special ? 'Special' : 'Normal' }}</td>
                            <td class="p-2 text-center whitespace-nowrap">
                                <a class="text-blue-700 underline mr-2" href="{{ route('staff.tasks.edit', $t) }}">Edit</a>

                                @if($t->deleted_at)
                                    <form class="inline" method="POST" action="{{ route('staff.tasks.restore', $t->id) }}" onsubmit="return confirm('Restore this task?')">
                                        @csrf
                                        <button class="text-emerald-700 underline">Restore</button>
                                    </form>
                                @else
                                    <form class="inline" method="POST" action="{{ route('staff.tasks.destroy', $t) }}" onsubmit="return confirm('Archive this task? Already graded submissions will be kept.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-700 underline">Archive</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td class="p-3" colspan="4">No tasks created yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h2 class="font-bold mb-2">Submissions</h2>
        @foreach($submissions as $s)
            <div class="border rounded p-3 mb-2">
                <div class="font-semibold">Task: {{ $s->task->title }}</div>
                <div>Student: {{ $s->student?->name ?? 'ID: ' . $s->student_id }} ({{ $s->student?->email ?? '' }})</div>
                <div>Status: {{ $s->status }}</div>
                @if($s->file_path)
                    <div>File: <a class="text-blue-600 underline" target="_blank" href="{{ asset('storage/' . $s->file_path) }}">Download</a></div>
                @endif
                @if($s->content)
                    <div class="mt-2 text-slate-700 text-sm whitespace-pre-line">{{ $s->content }}</div>
                @endif
                <form method="POST" action="{{ route('staff.submissions.grade', $s) }}" class="mt-2">
                    @csrf
                    <input type="number" name="grade_score" class="border p-1 rounded" placeholder="Score" required>
                    <select name="status" class="border p-1 rounded">
                        <option value="graded">Graded</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <button class="bg-emerald-600 text-white px-3 py-1 rounded">Save</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
