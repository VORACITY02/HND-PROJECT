@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4">Request Supervision</h1>
    <form method="POST" action="{{ route('user.supervision.request.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold">Supervisor</label>
            <select name="requested_supervisor_id" class="w-full border p-2 rounded" required>
                <option value="">Select supervisor</option>
                @foreach($availableSupervisors as $sup)
                    <option value="{{ $sup->id }}">{{ $sup->name }} ({{ $sup->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold">Admin to validate</label>
            <select name="requested_admin_id" class="w-full border p-2 rounded" required>
                <option value="">Select admin</option>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold">Note (optional)</label>
            <textarea name="note" class="w-full border p-2 rounded" rows="3"></textarea>
        </div>
        <button class="bg-lime-400 text-green-950 px-4 py-2 rounded border border-lime-200">Send Request</button>
    </form>
</div>
@endsection
