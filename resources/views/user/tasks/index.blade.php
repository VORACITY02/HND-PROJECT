@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold">My Tasks</h1>
        <p class="text-slate-600 text-sm mt-1">Tasks assigned by your supervisor. You can submit text, upload a document, or both. After grading or after the due date, submissions become locked.</p>
    </div>

    <div class="bg-white p-6 rounded shadow">
        @forelse($tasks as $task)
            @php($submission = $submissions[$task->id] ?? null)
            @php($expired = $task->due_at && now()->gt($task->due_at))
            @php($locked = $expired || ($submission && in_array($submission->status, ['graded','submitted'], true)))
            @php($canResubmit = (!$expired) && ($submission && $submission->status === 'rejected'))
            <div class="border rounded p-4 mb-4 {{ $expired ? 'opacity-80' : '' }}">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-lg font-semibold">{{ $task->title }}</div>
                        <div class="text-sm text-slate-600">Supervisor: {{ $task->supervisor->name ?? 'N/A' }}</div>
                        @if($task->due_at)
                            <div class="text-sm">Due: {{ $task->due_at->toDayDateTimeString() }}</div>
                        @endif
                    </div>
                    <div class="text-right text-sm">
                        <div>
                            Status:
                            <span class="font-medium">
                                @if($expired)
                                    expired
                                @else
                                    {{ $submission?->status ?? 'not submitted' }}
                                @endif
                            </span>
                        </div>
                        @if($submission?->grade_score !== null)
                            <div>Grade: {{ $submission->grade_score }} / {{ $task->max_score }}</div>
                        @endif
                    </div>
                </div>

                @if($task->description)
                    <div class="mt-2 text-slate-700">{{ $task->description }}</div>
                @endif

                @if($locked && !$canResubmit )
                    <div class="mt-4 p-3 rounded border bg-yellow-50">
                        @if($expired)
                            <div class="font-semibold">Expired</div>
                            <div class="text-sm">Due date has passed. Submissions are closed.</div>
                        @else
                            <div class="font-semibold">Locked</div>
                            <div class="text-sm">This submission is graded and cannot be modified.</div>
                        @endif
                    </div>
                @endif

                <form method="POST" action="{{ route('user.tasks.submit', $task) }}" enctype="multipart/form-data" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <label class="block font-semibold">Write-up (optional)</label>
                        <textarea name="content" class="w-full border rounded p-2" rows="3" placeholder="Describe what you did..." {{ ($locked && !$canResubmit) ? 'disabled' : '' }}>{{ old('content', $submission?->content) }}</textarea>
                    </div>
                    <div>
                        <label class="block font-semibold">Upload file (optional)</label>
                        <input type="file" name="file" class="border rounded p-2 w-full" {{ ($locked && !$canResubmit) ? 'disabled' : '' }} />
                        @if($submission?->file_path)
                            <div class="text-xs mt-1">Existing file: <a class="text-blue-600 underline" target="_blank" href="{{ asset('storage/' . $submission->file_path) }}">Download</a></div>
                        @endif
                    </div>
                    <button class="bg-emerald-600 text-white px-4 py-2 rounded" {{ ($locked && !$canResubmit) ? 'disabled' : '' }}>Submit / Update</button>
                </form>
            </div>
        @empty
            <div class="text-slate-600">No tasks assigned yet.</div>
        @endforelse
    </div>
</div>
@endsection
