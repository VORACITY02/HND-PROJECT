@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-800 mb-2">Student Dashboard</h1>
    <p class="text-slate-600">Welcome back, {{ auth()->user()->name }}! Explore internship opportunities here.</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-primary-500 hover:shadow-xl transition">
        <p class="text-slate-500 text-sm font-semibold mb-2">My Applications</p>
        <p class="text-4xl font-bold text-slate-800">0</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-emerald-500 hover:shadow-xl transition">
        <p class="text-slate-500 text-sm font-semibold mb-2">Active Internships</p>
        <p class="text-4xl font-bold text-slate-800">0</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-amber-500 hover:shadow-xl transition">
        <p class="text-slate-500 text-sm font-semibold mb-2">Completed</p>
        <p class="text-4xl font-bold text-slate-800">0</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <h2 class="text-xl font-semibold text-slate-800 mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="#" class="p-4 border-2 border-slate-200 rounded-xl hover:bg-primary-50 hover:border-primary-300 transition shadow hover:shadow-md">
            <p class="font-semibold text-slate-800 mb-1">Browse Internships</p>
            <p class="text-sm text-slate-600">Find opportunities</p>
        </a>

        <a href="#" class="p-4 border-2 border-slate-200 rounded-xl hover:bg-primary-50 hover:border-primary-300 transition shadow hover:shadow-md">
            <p class="font-semibold text-slate-800 mb-1">My Profile</p>
            <p class="text-sm text-slate-600">Update your information</p>
        </a>

        <a href="#" class="p-4 border-2 border-slate-200 rounded-xl hover:bg-primary-50 hover:border-primary-300 transition shadow hover:shadow-md">
            <p class="font-semibold text-slate-800 mb-1">My Applications</p>
            <p class="text-sm text-slate-600">Track application status</p>
        </a>
    </div>
</div>

<!-- Available Internships -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-xl font-semibold text-slate-800 mb-4">Available Internships</h2>
    <p class="text-slate-600">No internships available at the moment. Check back later!</p>
</div>
@endsection