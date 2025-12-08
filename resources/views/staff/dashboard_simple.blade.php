@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Email Verification Alert -->
    @unless (auth()->user()->hasVerifiedEmail())
        <div class="mb-8 p-6 bg-amber-50 border border-amber-200 rounded-xl shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-amber-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-amber-800">Email Verification Required</h3>
                    <p class="text-amber-700">Please verify your email address to access all staff features.</p>
                    <a href="{{ route('verification.resend') }}" class="text-blue-600 hover:text-blue-800 underline font-medium">Resend verification email</a>
                </div>
            </div>
        </div>
    @endunless

    <!-- Header Section -->
    <div class="bg-slate-800 text-white rounded-xl p-8 mb-8 shadow-xl">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold mb-1">Staff Dashboard</h1>
                <p class="text-blue-300 mb-2">User Support & Communication Center</p>
                <div class="flex items-center space-x-2">
                    <span class="bg-green-600/20 border border-green-500/30 px-3 py-1 rounded-full text-sm">
                        Staff Member
                    </span>
                    <span class="bg-emerald-600/20 border border-emerald-500/30 px-3 py-1 rounded-full text-sm">
                        <span class="inline-block w-2 h-2 bg-emerald-400 rounded-full mr-1"></span>
                        Active
                    </span>
                </div>
            </div>
        </div>
    </div>