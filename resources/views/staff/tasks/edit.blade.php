@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4">Edit Task</h1>

    <form method="POST" action="{{ route('staff.tasks.update', $task) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <p class="text-sm text-slate-600">Type: <span class="font-semibold">{{ $task->is_special ? 'Special' : 'Normal' }}</span></p>
        </div>

        <div class="mb-3">
            <label class="block font-semibold">Title</label>
            <input type="text" name="title" value="{{ old('title', $task->title) }}" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-3">
            <label class="block font-semibold">Description</label>
            <textarea name="description" class="w-full border p-2 rounded" rows="3">{{ old('description', $task->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="block font-semibold">Due At</label>
            <input type="datetime-local" name="due_at" value="{{ old('due_at', $task->due_at?->format('Y-m-d\TH:i')) }}" class="w-full border p-2 rounded">
        </div>
        <div class="mb-3">
            <label class="block font-semibold">Max Score</label>
            <input type="number" name="max_score" value="{{ old('max_score', $task->max_score ?? 100) }}" min="1" class="w-full border p-2 rounded">
        </div>
        <div class="mb-3">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="active" value="1" {{ old('active', $task->active) ? 'checked' : '' }}>
                <span>Active</span>
            </label>
        </div>

        <button class="bg-emerald-600 text-white px-4 py-2 rounded">Save</button>
        <a class="ml-3 text-slate-700 underline" href="{{ route('staff.tasks.index') }}">Cancel</a>
    </form>
</div>
@endsection
