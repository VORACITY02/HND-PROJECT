@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Pending Supervision Requests</h1>
    <table class="w-full border">
        <thead>
            <tr class="bg-slate-100">
                <th class="p-2 text-left">Student</th>
                <th class="p-2 text-left">Supervisor</th>
                <th class="p-2 text-left">Note</th>
                <th class="p-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $r)
                <tr class="border-t">
                    <td class="p-2">{{ $r->student->name }} ({{ $r->student->email }})</td>
                    <td class="p-2">{{ $r->requestedSupervisor->name }} ({{ $r->requestedSupervisor->email }})</td>
                    <td class="p-2">{{ $r->note }}</td>
                    <td class="p-2 text-nowrap">
                        <form method="POST" action="{{ route('admin.assignments.approve', $r) }}" class="inline">
                            @csrf
                            <button class="bg-emerald-600 text-white px-3 py-1 rounded">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('admin.assignments.reject', $r) }}" class="inline ml-2">
                            @csrf
                            <button class="bg-rose-600 text-white px-3 py-1 rounded">Reject</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="p-3" colspan="4">No pending requests</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
