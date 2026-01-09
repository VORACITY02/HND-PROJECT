@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4">Create Task</h1>
    <form method="POST" action="{{ route('staff.tasks.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-2">Task Type</label>
            <div class="flex gap-4">
                <label class="inline-flex items-center gap-2">
                    <input type="radio" name="task_type" value="normal" checked>
                    <span>Normal</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="radio" name="task_type" value="special">
                    <span>Special (recovery)</span>
                </label>
            </div>
            <p class="text-xs text-slate-500 mt-1">Special tasks can only be assigned to students who have at least one negative graded submission.</p>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-2">Assign Task To</label>
            <div class="flex gap-4">
                <label class="inline-flex items-center gap-2">
                    <input type="radio" name="assign_to" value="individual" checked>
                    <span>One student</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="radio" name="assign_to" value="all">
                    <span>All my supervisees</span>
                </label>
            </div>
            <p class="text-xs text-slate-500 mt-1">Choose whether this is an individual task or a group task for your full supervision group.</p>
        </div>

        <div class="mb-3">
            <label class="block font-semibold">Select Student (only for individual)</label>
            <select name="assigned_student_id" class="w-full border p-2 rounded">
                <option value="">Select student</option>
                @foreach(($students ?? []) as $s)
                    <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->email }})</option>
                @endforeach
            </select>
            <p class="text-xs text-slate-500 mt-1">Ignored if you select “All my supervisees”.</p>
        </div>

        <div class="mb-3">
            <label class="block font-semibold">Title</label>
            <input type="text" name="title" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-3">
            <label class="block font-semibold">Description</label>
            <textarea name="description" class="w-full border p-2 rounded" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="block font-semibold">Due At</label>
            <input type="datetime-local" name="due_at" class="w-full border p-2 rounded">
        </div>
        <div class="mb-3">
            <label class="block font-semibold">Max Score</label>
            <input type="number" name="max_score" value="100" min="1" class="w-full border p-2 rounded">
        </div>
        <button class="bg-lime-400 text-green-950 px-4 py-2 rounded border border-lime-200">Create</button>
    </form>
</div>
@endsection
