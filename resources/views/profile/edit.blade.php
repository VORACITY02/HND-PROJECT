@extends('layouts.app')

@section('content')
<!-- Back to Dashboard Button -->
<div class="mb-8">
    <a href="@if(auth()->user()->role === 'admin'){{ route('admin.dashboard') }}@elseif(auth()->user()->role === 'staff'){{ route('staff.dashboard') }}@else{{ route('user.dashboard') }}@endif" 
       class="inline-flex items-center text-green-900 hover:text-green-950 font-medium transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Dashboard
    </a>
</div>

<!-- Header Section -->
<div class="bg-slate-800 text-white rounded-xl p-8 mb-8 shadow-xl">
    <div class="flex items-center space-x-4">
        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center shadow-lg">
            <span class="text-white font-bold text-2xl">{{ substr(auth()->user()->name, 0, 1) }}</span>
        </div>
        <div>
            <h1 class="text-3xl font-bold mb-1">Profile Settings</h1>
            <p class="text-blue-300">Manage your account information and preferences</p>
            <div class="flex items-center space-x-2 mt-2">
                <span class="bg-blue-600/20 border border-blue-500/30 px-3 py-1 rounded-full text-sm">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
                <span class="bg-emerald-600/20 border border-emerald-500/30 px-3 py-1 rounded-full text-sm">
                    <span class="inline-block w-2 h-2 bg-emerald-400 rounded-full mr-1"></span>
                    Online
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Profile Update Form -->
<div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8 mb-8">
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-10 h-10 bg-lime-300/15 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Profile Information</h2>
            <p class="text-slate-600">Update your account details and personal information</p>
        </div>
    </div>
    
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-slate-700 font-medium mb-2">Full Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                       class="w-full p-4 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 transition-all"
                       required>
            </div>

            <div>
                <label class="block text-slate-700 font-medium mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                       class="w-full p-4 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 transition-all"
                       required>
            </div>
        </div>

        <div class="mt-8 flex items-center space-x-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-all shadow-lg">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Update Profile
            </button>
            <div class="text-sm text-slate-500">
                Last updated: {{ auth()->user()->updated_at->diffForHumans() }}
            </div>
        </div>
    </form>
</div>

<!-- Email Verification Section -->
@unless (auth()->user()->hasVerifiedEmail())
<div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-8">
    <div class="flex items-center space-x-3 mb-4">
        <div class="w-10 h-10 bg-amber-500/10 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-bold text-amber-800">Email Verification Required</h3>
            <p class="text-amber-700">Please verify your email address to access all features.</p>
        </div>
    </div>
    
    <div class="bg-white/70 rounded-lg p-4 border border-amber-200 mb-4">
        <p class="text-amber-700 text-sm mb-3">
            <strong>Note:</strong> Email verification is currently set to development mode. In a real environment, 
            you would receive an actual email with a verification link.
        </p>
        <p class="text-amber-700 text-sm">
            For testing purposes, you can manually verify your email or check the application logs for the verification link.
        </p>
    </div>
    
    <div class="flex space-x-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg font-medium transition-all">
                Generate Verification Link
            </button>
        </form>
        
        <!-- Manual verification for testing -->
        <form method="POST" action="{{ route('verification.verify.manual') }}">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-all">
                Mark as Verified (Testing)
            </button>
        </form>
    </div>
</div>
@else
<div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8">
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-bold text-green-800">Email Verified</h3>
            <p class="text-green-700">Your email address has been verified successfully.</p>
        </div>
    </div>
</div>
@endunless

<!-- Account Security -->
<div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8 mb-8">
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-10 h-10 bg-red-600/10 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Security Settings</h2>
            <p class="text-slate-600">Update your password and security preferences</p>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="list-disc list-inside text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Change Password Form -->
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="grid md:grid-cols-1 gap-6">
            <div>
                <label class="block text-slate-700 font-medium mb-2">Current Password</label>
                <input type="password" name="current_password"
                       class="w-full p-4 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 transition-all"
                       required>
            </div>

            <div>
                <label class="block text-slate-700 font-medium mb-2">New Password</label>
                <input type="password" name="password"
                       class="w-full p-4 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 transition-all"
                       required>
            </div>

            <div>
                <label class="block text-slate-700 font-medium mb-2">Confirm New Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full p-4 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 transition-all"
                       required>
            </div>
        </div>

        <div class="mt-8">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg font-medium transition-all shadow-lg">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Update Password
            </button>
        </div>
    </form>
</div>

<!-- Account Information -->
<div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-10 h-10 bg-slate-600/10 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Account Information</h2>
            <p class="text-slate-600">View your account details and status</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
        <div class="space-y-4">
            <div class="flex justify-between items-center p-4 bg-slate-50 rounded-lg">
                <span class="font-medium text-slate-700">Account Created</span>
                <span class="text-slate-600">{{ auth()->user()->created_at->format('M d, Y') }}</span>
            </div>
            
            <div class="flex justify-between items-center p-4 bg-slate-50 rounded-lg">
                <span class="font-medium text-slate-700">Last Login</span>
                <span class="text-slate-600">{{ auth()->user()->last_seen_at ? auth()->user()->last_seen_at->diffForHumans() : 'Unknown' }}</span>
            </div>
            
            <div class="flex justify-between items-center p-4 bg-slate-50 rounded-lg">
                <span class="font-medium text-slate-700">Email Verified</span>
                <span class="text-slate-600">
                    @if(auth()->user()->hasVerifiedEmail())
                        <span class="text-green-600 font-medium">✓ Verified</span>
                    @else
                        <span class="text-amber-600 font-medium">⚠ Not Verified</span>
                    @endif
                </span>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex justify-between items-center p-4 bg-slate-50 rounded-lg">
                <span class="font-medium text-slate-700">User Role</span>
                <span class="bg-blue-600/20 border border-blue-500/30 px-3 py-1 rounded-full text-sm text-blue-800 font-medium">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
            
            <div class="flex justify-between items-center p-4 bg-slate-50 rounded-lg">
                <span class="font-medium text-slate-700">Account Status</span>
                <span class="bg-emerald-600/20 border border-emerald-500/30 px-3 py-1 rounded-full text-sm text-emerald-800 font-medium">
                    <span class="inline-block w-2 h-2 bg-emerald-400 rounded-full mr-1"></span>
                    Active
                </span>
            </div>
            
            <div class="flex justify-between items-center p-4 bg-slate-50 rounded-lg">
                <span class="font-medium text-slate-700">Profile Completed</span>
                <span class="text-slate-600">
                    @if(auth()->user()->name && auth()->user()->email)
                        <span class="text-green-600 font-medium">✓ Complete</span>
                    @else
                        <span class="text-amber-600 font-medium">⚠ Incomplete</span>
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>

@endsection