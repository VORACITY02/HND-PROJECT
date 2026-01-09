@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">Apply to Become a Supervisor</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('status'))
        <div class="bg-slate-100 text-slate-700 p-3 rounded mb-4">
            {{ session('status') }}
        </div>
    @endif

    @php($personal = $hasPersonalDataTable ? auth()->user()->personalData : null)

    @if(isset($application) && $application && $application->status === 'pending')
        <div class="bg-slate-100 text-slate-700 p-3 rounded mb-4">
            Your application is pending. Please wait for admin approval.
        </div>
    @elseif(isset($application) && $application && $application->status === 'approved')
        <div class="bg-emerald-100 text-emerald-700 p-3 rounded mb-4">
            Your application has been approved. You can supervise up to {{ $application->max_students }} students.
        </div>
    @endif

    @if(!isset($application) || !$application || $application->status !== 'pending')
    <form method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 font-medium">Department <span class="text-red-600">*</span></label>
                <input type="text" name="department" value="{{ old('department', optional($personal)->department) }}" class="w-full border rounded p-2 mb-4" required>
            </div>
            <div>
                <label class="block mb-2 font-medium">Title</label>
                <input type="text" name="title" value="{{ old('title', optional($personal)->title) }}" class="w-full border rounded p-2 mb-4">
            </div>
            <div>
                <label class="block mb-2 font-medium">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', optional($personal)->phone) }}" class="w-full border rounded p-2 mb-4">
            </div>
            <div>
                <label class="block mb-2 font-medium">Address</label>
                <input type="text" name="address" value="{{ old('address', optional($personal)->address) }}" class="w-full border rounded p-2 mb-4">
            </div>
            <div class="md:col-span-2">
                <label class="block mb-2 font-medium">Short Bio</label>
                <textarea name="bio" class="w-full border rounded p-2 mb-4" rows="3">{{ old('bio', optional($personal)->bio) }}</textarea>
            </div>
        </div>

        <label class="block mb-2 font-medium">Maximum number of students you can supervise <span class="text-red-600">*</span></label>
        <input type="number" name="max_students" min="1" value="{{ old('max_students', optional($application)->max_students) }}" class="w-full border rounded p-2 mb-4" required>

        <button class="bg-sky-600 text-white px-4 py-2 rounded">
            @if(isset($application) && $application && $application->status === 'rejected')
                Re-Apply
            @else
                Submit Application
            @endif
        </button>
    </form>
    @endif
</div>
@endsection