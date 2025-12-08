<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - Internship Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen">

    <!-- Top Navigation -->
    <nav class="bg-slate-800 text-white shadow-xl border-b-2 border-blue-600/30">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <!-- Logo -->
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-lg">IM</span>
                    </div>
                    <div>
                        <a href="@if(auth()->user()->role === 'admin'){{ route('admin.dashboard') }}@elseif(auth()->user()->role === 'staff'){{ route('staff.dashboard') }}@else{{ route('user.dashboard') }}@endif" class="text-xl font-bold hover:text-blue-300 transition-colors">
                            Internship Management
                        </a>
                        <p class="text-blue-300 text-xs">Professional Platform</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- User Info -->
                    <div class="flex items-center space-x-2 bg-slate-700/50 px-4 py-2 rounded-lg border border-slate-600">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <div class="font-medium text-white text-sm">{{ auth()->user()->name }}</div>
                            <div class="text-blue-300 text-xs">{{ ucfirst(auth()->user()->role) }}</div>
                        </div>
                    </div>
                    
                    <!-- Online Users Counter -->
                    @php
                        $onlineUsers = \App\Models\User::online()->count();
                    @endphp
                    <div class="bg-emerald-600/20 border border-emerald-500/30 px-3 py-2 rounded-lg">
                        <span class="text-emerald-400 text-sm font-medium">
                            <span class="inline-block w-2 h-2 bg-emerald-400 rounded-full mr-1"></span>
                            {{ $onlineUsers }} Online
                        </span>
                    </div>
                    
                    <!-- Message Center Button -->
                    <a href="{{ route('messages.index') }}" class="relative bg-blue-600/80 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition-all shadow-lg">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Messages
                        @php
                            $user = auth()->user();
                            $unreadCount = \App\Models\Message::where(function($query) use ($user) {
                                    // Individual messages to this user that are unread
                                    $query->where('receiver_id', $user->id)
                                          ->where('is_read', false);
                                })
                                ->orWhere(function($query) use ($user) {
                                    // Broadcast messages for this user's role that they haven't read
                                    $query->where('is_broadcast', true)
                                          ->where(function($subQ) use ($user) {
                                              if ($user->role === 'user') {
                                                  $subQ->where('recipient_type', 'all_users');
                                              } elseif ($user->role === 'staff') {
                                                  $subQ->where('recipient_type', 'all_staff');
                                              } elseif ($user->role === 'admin') {
                                                  $subQ->where('recipient_type', 'all_admins');
                                              }
                                          })
                                          ->whereDoesntHave('userReads', function($subQ) use ($user) {
                                              $subQ->where('user_id', $user->id);
                                          });
                                })
                                ->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold animate-pulse">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="{{ route('profile.edit') }}" class="bg-slate-600/80 hover:bg-slate-600 px-4 py-2 rounded-lg font-medium transition-all">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profile
                    </a>
                    
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="bg-red-600/80 hover:bg-red-600 px-4 py-2 rounded-lg font-medium transition-all">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 rounded">
                <p class="text-green-700 font-medium">✓ {{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-300 rounded">
                <p class="text-red-700 font-medium">✗ {{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-300 rounded">
                <ul class="list-disc list-inside text-red-700">
                    @foreach ($errors->all() as $error)
                        <li class="font-medium">✗ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-slate-800 text-slate-300 py-6 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Internship Management System. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>