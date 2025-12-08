@extends('layouts.app')

@section('content')
<!-- Sinister-Professional Student Dashboard -->
<div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-900 via-purple-900 to-blue-900 bg-clip-text text-transparent mb-3 tracking-tight">
                STUDENT PORTAL
            </h1>
            <p class="text-slate-600 text-lg">
                Access Level: <span class="font-bold text-blue-600">LEARNER</span> • 
                Welcome, <span class="font-semibold text-purple-800">{{ auth()->user()->name }}</span>
            </p>
        </div>
        <div class="flex items-center space-x-4">
            <!-- User Profile Card -->
            <a href="{{ route('profile.edit') }}" class="group">
                <div class="bg-gradient-to-br from-blue-950 via-purple-900 to-black text-white px-6 py-4 rounded-2xl shadow-2xl hover:shadow-blue-900/50 transition-all transform hover:scale-105 border border-blue-700">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center font-bold text-2xl border-4 border-blue-400 shadow-lg">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            @if(auth()->user()->is_online)
                                <div class="absolute bottom-0 right-0 w-5 h-5 bg-green-500 rounded-full border-4 border-black animate-pulse"></div>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-lg">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-blue-300">🎓 Student</p>
                            <p class="text-xs text-slate-400 mt-1">
                                @if(auth()->user()->email_verified_at)
                                    ✓ Verified
                                @else
                                    ⚠ Unverified
                                @endif
                            </p>
                        </div>
                        <svg class="w-6 h-6 text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Email Verification Alert -->
@if(!auth()->user()->email_verified_at)
<div class="mb-8 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-red-900 to-orange-900 opacity-20 animate-pulse"></div>
    <div class="relative bg-gradient-to-r from-red-950 to-orange-950 border-2 border-red-700 text-white p-6 rounded-2xl shadow-2xl">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-12 h-12 text-red-400 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-2xl font-bold mb-2">⚠️ SECURITY ALERT</h3>
                <p class="mb-1 text-red-200">Your email address is <strong class="text-yellow-300">NOT VERIFIED</strong></p>
                <p class="text-sm text-red-300 mb-4">Email: <span class="font-mono bg-black/30 px-2 py-1 rounded">{{ auth()->user()->email }}</span></p>
                <div class="flex gap-3">
                    <form action="{{ route('verification.resend') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                            📧 Resend Verification Email
                        </button>
                    </form>
                    <a href="{{ route('profile.edit') }}" class="bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-xl font-bold transition-all">
                        Go to Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- My Applications -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-blue-950 to-blue-900 rounded-2xl shadow-2xl hover:shadow-blue-900/50 transition-all transform hover:scale-105 border border-blue-700">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-blue-300 text-sm font-semibold mb-2 uppercase tracking-wide">My Applications</p>
            <p class="text-5xl font-black text-white mb-2">0</p>
            <p class="text-xs text-blue-400">Submitted requests</p>
        </div>
    </div>

    <!-- Active Internships -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-emerald-950 to-emerald-900 rounded-2xl shadow-2xl hover:shadow-emerald-900/50 transition-all transform hover:scale-105 border border-emerald-700">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
            </div>
            <p class="text-emerald-300 text-sm font-semibold mb-2 uppercase tracking-wide">Active Internships</p>
            <p class="text-5xl font-black text-white mb-2">0</p>
            <p class="text-xs text-emerald-400">Currently enrolled</p>
        </div>
    </div>

    <!-- Completed -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-amber-950 to-amber-900 rounded-2xl shadow-2xl hover:shadow-amber-900/50 transition-all transform hover:scale-105 border border-amber-700">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
            </div>
            <p class="text-amber-300 text-sm font-semibold mb-2 uppercase tracking-wide">Completed</p>
            <p class="text-5xl font-black text-white mb-2">0</p>
            <p class="text-xs text-amber-400">Finished programs</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl shadow-2xl p-8 mb-8 border border-slate-700">
    <h2 class="text-3xl font-bold text-white mb-6 flex items-center">
        <svg class="w-8 h-8 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        Quick Actions
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('messages.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-blue-900 to-blue-800 p-6 rounded-xl hover:from-blue-800 hover:to-blue-700 transition-all shadow-xl hover:shadow-2xl hover:shadow-blue-900/50 transform hover:scale-105 border border-blue-700">
            <div class="flex items-start">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-white text-lg mb-1">Browse Internships</p>
                    <p class="text-sm text-blue-300">Find opportunities</p>
                </div>
            </div>
        </a>

        <a href="{{ route('profile.edit') }}" class="group relative overflow-hidden bg-gradient-to-br from-purple-900 to-purple-800 p-6 rounded-xl hover:from-purple-800 hover:to-purple-700 transition-all shadow-xl hover:shadow-2xl hover:shadow-purple-900/50 transform hover:scale-105 border border-purple-700">
            <div class="flex items-start">
                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-white text-lg mb-1">My Profile</p>
                    <p class="text-sm text-purple-300">Update information</p>
                </div>
            </div>
        </a>

        <a href="{{ route('messages.create') }}" class="group relative overflow-hidden bg-gradient-to-br from-emerald-900 to-emerald-800 p-6 rounded-xl hover:from-emerald-800 hover:to-emerald-700 transition-all shadow-xl hover:shadow-2xl hover:shadow-emerald-900/50 transform hover:scale-105 border border-emerald-700">
            <div class="flex items-start">
                <div class="w-12 h-12 bg-emerald-600 rounded-lg flex items-center justify-center mr-4 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-white text-lg mb-1">My Applications</p>
                    <p class="text-sm text-emerald-300">Track status</p>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Available Internships -->
<div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl shadow-2xl p-8 border border-slate-700">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-white flex items-center">
            <svg class="w-8 h-8 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Available Internships
        </h2>
    </div>
    
    <div class="text-center py-12">
        <svg class="w-20 h-20 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <p class="text-slate-400 text-lg mb-4">No internships available at the moment</p>
        <p class="text-slate-500 text-sm">Check back later for new opportunities!</p>
    </div>
</div>
@endsection
