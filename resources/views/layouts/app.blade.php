<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - Internship Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-gradient-to-br from-sky-50 via-sky-100 to-slate-100">

   @php
       // Unified button styling
       $navBtn = 'bg-sky-900/35 hover:bg-sky-900/55 border border-sky-300/15 px-4 py-2 rounded-lg font-medium transition-all';
       $navBtnPrimary = 'bg-sky-400/20 hover:bg-sky-400/30 border border-sky-300/30 px-4 py-2 rounded-lg font-medium transition-all shadow-lg';
       $dashboardRoute = auth()->user()->role === 'admin'
           ? route('admin.dashboard')
           : (auth()->user()->role === 'staff' ? route('staff.dashboard') : route('user.dashboard'));
   @endphp

    <!-- Top Navigation -->
    <nav class="bg-gradient-to-r from-slate-900 via-sky-900 to-slate-900 text-white shadow-xl border-b-4 border-sky-400/40">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <!-- Logo -->
                    <div class="w-12 h-12 bg-gradient-to-br from-sky-400 to-cyan-300 rounded-lg flex items-center justify-center shadow-lg border border-sky-200/50">
                        <span class="text-white font-bold text-lg">IM</span>
                    </div>
                    <div>
                        <a href="@if(auth()->user()->role === 'admin'){{ route('admin.dashboard') }}@elseif(auth()->user()->role === 'staff'){{ route('staff.dashboard') }}@else{{ route('user.dashboard') }}@endif" class="text-xl font-bold hover:text-sky-300 transition-colors">
                            Internship Management
                        </a>
                        <p class="text-sky-200 text-xs">Professional Platform</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- User Info -->
                    <div class="flex items-center space-x-2 bg-sky-900/40 px-4 py-2 rounded-lg border border-sky-400/25">
                        <div class="w-8 h-8 bg-sky-400/20 border border-sky-300/30 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <div class="font-medium text-white text-sm">{{ auth()->user()->name }}</div>
                            <div class="text-sky-200 text-xs">{{ ucfirst(auth()->user()->role) }}</div>
                        </div>
                    </div>
                    
                    <!-- Online Users Counter -->
                    @php
                        $onlineUsers = \App\Models\User::online()->count();
                    @endphp
                    <div class="bg-sky-400/10 border border-sky-300/20 px-3 py-2 rounded-lg">
                        <span class="text-sky-200 text-sm font-medium">
                            <span class="inline-block w-2 h-2 bg-sky-300 rounded-full mr-1"></span>
                            {{ $onlineUsers }} Online
                        </span>
                    </div>
                    
                    <!-- Message Center Button -->
                    <a href="{{ route('messages.index') }}" class="relative {{ $navBtnPrimary }}">
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
                            <span class="absolute -top-1 -right-1 bg-amber-300 text-slate-900 text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold animate-pulse border border-amber-200">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.supervisors.index') }}" class="{{ $navBtnPrimary }}">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Supervisor Apps
                        </a>
                        <a href="{{ route('admin.supervision.manage.index') }}" class="{{ $navBtn }}">
                            Supervision
                        </a>
                    @endif

                    @if($hasPersonalDataTable)
                    <a href="{{ route('profile.personal') }}" class="{{ $navBtn }}">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Personal Data
                    </a>
                    @endif
                    
                    <a href="{{ route('profile.edit') }}" class="{{ $navBtn }}">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profile
                    </a>
                    
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="bg-amber-300/90 hover:bg-amber-300 text-slate-900 px-4 py-2 rounded-lg font-medium transition-all border border-amber-200">
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
    <main class="flex-1">
    <div class="container mx-auto px-4 py-8">
        @php
            $routeName = optional(request()->route())->getName();
            $isWelcome = request()->is('/');
            $isDashboard = in_array($routeName, ['admin.dashboard','staff.dashboard','user.dashboard'], true);
        @endphp

        @if(!$isWelcome && !$isDashboard)
            <div class="mb-5">
                <a href="{{ $dashboardRoute }}" class="inline-flex items-center gap-2 bg-white/80 hover:bg-white border border-slate-200 text-slate-900 px-4 py-2 rounded-lg font-semibold shadow-sm transition-all">
                    <span aria-hidden="true">←</span>
                    <span>Back to Dashboard</span>
                </a>
            </div>
        @endif
        @if (session('success'))
            <div class="mb-6 p-4 bg-sky-50 border border-sky-300 rounded">
                <p class="text-sky-900 font-medium">✓ {{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-300 rounded">
                <p class="text-slate-900 font-medium">✗ {{ session('error') }}</p>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-300 rounded">
                <ul class="list-disc list-inside text-slate-900">
                    @foreach ($errors->all() as $error)
                        <li class="font-medium">✗ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 text-sky-100 py-6 border-t-4 border-sky-400/30">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Internship Management System. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>