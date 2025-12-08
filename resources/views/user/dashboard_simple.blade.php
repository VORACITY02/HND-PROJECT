@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Email Verification Alert -->
    @unless (auth()->user()->hasVerifiedEmail())
        <div class="mb-8 p-6 bg-amber-50 border border-amber-200 rounded-xl shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-amber-500/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-amber-800">Email Verification Required</h3>
                        <p class="text-amber-700">Please verify your email address to access all platform features.</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <form method="POST" action="{{ route('verification.verify.manual') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm">
                            Verify Now (Testing)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endunless

    <!-- Header Section -->
    <div class="bg-slate-800 text-white rounded-xl p-8 mb-8 shadow-xl">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center shadow-lg">
                <span class="text-white font-bold text-2xl">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <div>
                <h1 class="text-3xl font-bold mb-1">Welcome, {{ auth()->user()->name }}!</h1>
                <p class="text-blue-300 mb-2">User Dashboard - Professional Learning Environment</p>
                <div class="flex items-center space-x-2">
                    <span class="bg-blue-600/20 border border-blue-500/30 px-3 py-1 rounded-full text-sm">
                        User Role
                    </span>
                    <span class="bg-emerald-600/20 border border-emerald-500/30 px-3 py-1 rounded-full text-sm">
                        <span class="inline-block w-2 h-2 bg-emerald-400 rounded-full mr-1"></span>
                        Active Status
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Status & Progress -->
    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <!-- Account Status -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-blue-600/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Account Status</h3>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Role</span>
                    <span class="bg-blue-600/20 border border-blue-500/30 px-2 py-1 rounded text-sm text-blue-800 font-medium">User</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Status</span>
                    <span class="bg-emerald-600/20 border border-emerald-500/30 px-2 py-1 rounded text-sm text-emerald-800 font-medium">Active</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Member Since</span>
                    <span class="text-slate-800 font-medium">{{ auth()->user()->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Message Stats -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-purple-600/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Messages</h3>
            </div>
            @php
                $totalMessages = \App\Models\Message::where('receiver_id', auth()->id())->count();
                $unreadMessages = \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count();
            @endphp
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Total Received</span>
                    <span class="text-slate-800 font-bold text-lg">{{ $totalMessages }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Unread</span>
                    <span class="text-red-600 font-bold text-lg">{{ $unreadMessages }}</span>
                </div>
            </div>
        </div>

        <!-- Activity Status -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-emerald-600/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Activity</h3>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Last Login</span>
                    <span class="text-slate-800 font-medium">{{ auth()->user()->last_seen_at ? auth()->user()->last_seen_at->diffForHumans() : 'Now' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Current Status</span>
                    <div class="flex items-center space-x-1">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                        <span class="text-emerald-600 font-medium text-sm">Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Information -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-8 mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <div class="w-12 h-12 bg-blue-600/20 rounded-lg flex items-center justify-center">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-blue-800 mb-1">Getting Started</h2>
                <p class="text-blue-700">Learn about your role and available features</p>
            </div>
        </div>
        
        <div class="bg-white/70 rounded-lg p-6 border border-blue-200">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-bold text-blue-800 mb-2">Your Current Role: User</h4>
                    <ul class="text-blue-700 space-y-1 text-sm">
                        <li>• Send messages to any staff member or admin</li>
                        <li>• Receive broadcasts and individual messages</li>
                        <li>• Access your profile and account settings</li>
                        <li>• View online users and their status</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-blue-800 mb-2">Role Promotion</h4>
                    <p class="text-blue-700 text-sm mb-3">
                        Administrators can promote you to staff or admin roles for additional privileges like broadcasting messages and user management.
                    </p>
                    <div class="text-xs text-blue-600 bg-blue-100 p-2 rounded border border-blue-300">
                        <strong>Next Steps:</strong> Complete your profile and start engaging with the platform to be considered for promotion.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8 mb-8">
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-10 h-10 bg-green-600/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Quick Actions</h2>
                <p class="text-slate-600">Essential tools and features at your fingertips</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('messages.index') }}" class="group bg-blue-600/10 hover:bg-blue-600 border border-blue-600/20 hover:border-blue-600 rounded-xl p-6 transition-all text-center">
                <div class="w-12 h-12 bg-blue-600 group-hover:bg-white rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-white group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-blue-600 group-hover:text-white mb-2">View Messages</h3>
                <p class="text-blue-600/80 group-hover:text-white/80 text-sm">Check your inbox and read all messages</p>
                @if($unreadMessages > 0)
                    <div class="mt-2">
                        <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">{{ $unreadMessages }} New</span>
                    </div>
                @endif
            </a>

            <a href="{{ route('messages.create') }}" class="group bg-green-600/10 hover:bg-green-600 border border-green-600/20 hover:border-green-600 rounded-xl p-6 transition-all text-center">
                <div class="w-12 h-12 bg-green-600 group-hover:bg-white rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-white group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-green-600 group-hover:text-white mb-2">Send Message</h3>
                <p class="text-green-600/80 group-hover:text-white/80 text-sm">Compose and send new messages</p>
            </a>

            <a href="{{ route('profile.edit') }}" class="group bg-purple-600/10 hover:bg-purple-600 border border-purple-600/20 hover:border-purple-600 rounded-xl p-6 transition-all text-center">
                <div class="w-12 h-12 bg-purple-600 group-hover:bg-white rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-white group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-purple-600 group-hover:text-white mb-2">Edit Profile</h3>
                <p class="text-purple-600/80 group-hover:text-white/80 text-sm">Update your account information</p>
            </a>
        </div>
    </div>

    <!-- Recent Messages & Activity -->
    <div class="grid md:grid-cols-2 gap-8">
        <!-- Recent Messages -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-slate-600/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Recent Messages</h2>
                    <p class="text-slate-600">Your latest communications</p>
                </div>
            </div>
            
            @php
                $recentMessages = \App\Models\Message::where(function($query) {
                    $user = auth()->user();
                    $query->where('receiver_id', $user->id)
                          ->orWhere(function($q) use ($user) {
                              $q->where('is_broadcast', true)
                                ->where('recipient_type', 'all_users');
                          });
                })->with('sender')->latest()->take(5)->get();
            @endphp
            
            @if($recentMessages->count() > 0)
                <div class="space-y-4">
                    @foreach($recentMessages as $message)
                        <div class="flex items-start space-x-3 p-4 bg-slate-50 rounded-lg border border-slate-200 hover:shadow-sm transition-shadow">
                            <!-- Sender Avatar -->
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white font-bold text-sm">{{ substr($message->sender->name, 0, 1) }}</span>
                            </div>
                            
                            <!-- Message Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    <p class="font-medium text-slate-800 truncate">{{ $message->subject }}</p>
                                    @if($message->is_broadcast)
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium border border-purple-200">Broadcast</span>
                                    @endif
                                    @if(!$message->is_read)
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium border border-red-200">New</span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-600">From: {{ $message->sender->name }} ({{ ucfirst($message->sender->role) }})</p>
                                <p class="text-xs text-slate-500">{{ $message->created_at->diffForHumans() }}</p>
                            </div>
                            
                            <!-- Action -->
                            <a href="{{ route('messages.show', $message) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex-shrink-0">
                                View →
                            </a>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('messages.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        View All Messages →
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <p class="text-slate-600 mb-4">No messages yet</p>
                    <a href="{{ route('messages.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-all">
                        Send Your First Message
                    </a>
                </div>
            @endif
        </div>

        <!-- Online Users & Platform Activity -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-emerald-600/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Platform Activity</h2>
                    <p class="text-slate-600">Current online users and stats</p>
                </div>
            </div>
            
            @php
                $onlineUsers = \App\Models\User::online()->where('id', '!=', auth()->id())->get();
                $totalUsers = \App\Models\User::count();
                $onlineCount = \App\Models\User::online()->count();
            @endphp
            
            <!-- Activity Stats -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-emerald-600">{{ $onlineCount }}</div>
                    <div class="text-sm text-emerald-700">Online Now</div>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $totalUsers }}</div>
                    <div class="text-sm text-blue-700">Total Users</div>
                </div>
            </div>
            
            <!-- Online Users List -->
            @if($onlineUsers->count() > 0)
                <div class="space-y-3">
                    <h4 class="font-medium text-slate-800 mb-3">Currently Online</h4>
                    @foreach($onlineUsers->take(5) as $onlineUser)
                        <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-lg">
                            <div class="w-8 h-8 bg-slate-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-xs">{{ substr($onlineUser->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-slate-800 text-sm">{{ $onlineUser->name }}</div>
                                <div class="text-xs text-slate-600">{{ ucfirst($onlineUser->role) }}</div>
                            </div>
                            <div class="flex items-center space-x-1">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                <span class="text-xs text-slate-500">Online</span>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($onlineUsers->count() > 5)
                        <p class="text-sm text-slate-500 text-center">
                            And {{ $onlineUsers->count() - 5 }} more users online
                        </p>
                    @endif
                </div>
            @else
                <div class="text-center py-6">
                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <p class="text-slate-600 text-sm">You are the only one online</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection