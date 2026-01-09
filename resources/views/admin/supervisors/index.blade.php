@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-6">Supervisor Applications</h2>

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2">Name</th>
                <th>Department</th>
                <th>Max Students</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $app)
            <tr class="border-t">
                <td class="p-2">{{ $app->staff->name }}</td>
                <td>{{ optional($app->staff->personalData)->department ?? '—' }}</td>
                <td>{{ $app->max_students }}</td>
                <td>{{ ucfirst($app->status) }}</td>
                <td>
                    @if($app->status === 'pending')
                        <form method="POST" action="/admin/supervisors/{{ $app->id }}/approve" class="inline">
                            @csrf
                            <button class="text-green-600">Approve</button>
                        </form>
                        <form method="POST" action="/admin/supervisors/{{ $app->id }}/reject" class="inline ml-2">
                            @csrf
                            <button class="text-red-600">Reject</button>
                        </form>
                    @elseif($app->status === 'approved')
                        <form method="POST" action="{{ route('admin.supervisors.revoke', $app) }}" class="inline" onsubmit="return confirm('Revoke supervisor privileges for this staff?')">
                            @csrf
                            <button class="text-red-700">Revoke</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection