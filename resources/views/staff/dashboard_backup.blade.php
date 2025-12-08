@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-800 mb-2">Staff Dashboard</h1>
    <p class="text-slate-600">Welcome back, {{ auth()->user()->name }}! Manage your internship programs here.</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-primary-500 hover:shadow-xl transition">
        <p class="text-slate-500 text-sm font-semibold mb-2">My Internships</p>
        <p class="text-4xl font-bold text-slate-800">0</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-emerald-500 hover:shadow-xl transition">
        <p class="text-slate-500 text-sm font-semibold mb-2">Active Students</p>
        <p class="text-4xl font-bold text-slate-800">0</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-amber-500 hover:shadow-xl transition">
        <p class="text-slate-500 text-sm font-semibold mb-2">Pending Reviews</p>
        <p class="text-4xl font-bold text-slate-800">0</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <h2 class="text-xl font-semibold text-slate-800 mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="#" class="p-4 border-2 border-slate-200 rounded-xl hover:bg-primary-50 hover:border-primary-300 transition shadow hover:shadow-md">
            <p class="font-semibold text-slate-800 mb-1">Create Internship</p>
            <p class="text-sm text-slate-600">Post a new internship</p>
        </a>

        <a href="#" class="p-4 border-2 border-slate-200 rounded-xl hover:bg-primary-50 hover:border-primary-300 transition shadow hover:shadow-md">
            <p class="font-semibold text-slate-800 mb-1">View Applications</p>
            <p class="text-sm text-slate-600">Review student applications</p>
        </a>

        <a href="#" class="p-4 border-2 border-slate-200 rounded-xl hover:bg-primary-50 hover:border-primary-300 transition shadow hover:shadow-md">
            <p class="font-semibold text-slate-800 mb-1">Reports</p>
            <p class="text-sm text-slate-600">View performance reports</p>
        </a>
    </div>
</div>

<!-- My Internships -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-xl font-semibold text-slate-800 mb-4">My Internships</h2>
    <p class="text-slate-600">No internships posted yet. Create your first internship opportunity!</p>
</div>
@endsection