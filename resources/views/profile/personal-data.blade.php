@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">My Personal Data</h2>
    @if(!$hasPersonalDataTable)
        <div class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">Personal data storage is not set up yet. Please run migrations.</div>
    @endif
    @php($personal = $hasPersonalDataTable ? auth()->user()->personalData : null)

    @if($hasPersonalDataTable)
    <form method="POST" action="{{ route('profile.personal.update') }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-slate-500">Department</label>
                <input class="w-full border rounded p-2" name="department" value="{{ old('department', optional($personal)->department) }}">
            </div>
            <div>
                <label class="text-sm text-slate-500">Title</label>
                <input class="w-full border rounded p-2" name="title" value="{{ old('title', optional($personal)->title) }}">
            </div>
            <div>
                <label class="text-sm text-slate-500">Phone</label>
                <input class="w-full border rounded p-2" name="phone" value="{{ old('phone', optional($personal)->phone) }}">
            </div>
            <div>
                <label class="text-sm text-slate-500">Address</label>
                <input class="w-full border rounded p-2" name="address" value="{{ old('address', optional($personal)->address) }}">
            </div>
            <div class="md:col-span-2">
                <label class="text-sm text-slate-500">Bio</label>
                <textarea class="w-full border rounded p-2" rows="4" name="bio">{{ old('bio', optional($personal)->bio) }}</textarea>
            </div>
        </div>

        <div class="mt-4">
            <button class="bg-emerald-600 text-white px-4 py-2 rounded">Save</button>
        </div>
    </form>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-slate-500">Department</p>
            <p class="font-medium">{{ optional($personal)->department ?? '—' }}</p>
        </div>
        <div>
            <p class="text-sm text-slate-500">Title</p>
            <p class="font-medium">{{ optional($personal)->title ?? '—' }}</p>
        </div>
        <div>
            <p class="text-sm text-slate-500">Phone</p>
            <p class="font-medium">{{ optional($personal)->phone ?? '—' }}</p>
        </div>
        <div>
            <p class="text-sm text-slate-500">Address</p>
            <p class="font-medium">{{ optional($personal)->address ?? '—' }}</p>
        </div>
        <div class="md:col-span-2">
            <p class="text-sm text-slate-500">Bio</p>
            <p class="font-medium whitespace-pre-line">{{ optional($personal)->bio ?? '—' }}</p>
        </div>
    </div>
    @endif
</div>
@endsection