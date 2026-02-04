<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - Internship Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-900">

   @php
        $navBtn = 'bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 px-4 py-2 rounded-lg font-semibold transition-all';
        $navBtnPrimary = 'bg-blue-600 hover:bg-blue-700 text-white border border-blue-700/20 px-4 py-2 rounded-lg font-semibold transition-all shadow-sm';

        $dashboardRoute = auth()->user()->role === 'admin'
            ? route('admin.dashboard')
            : (auth()->user()->role === 'staff' ? route('staff.dashboard') : route('user.dashboard'));

        $brandHref = $dashboardRoute;

        $centerLinks = [];
        $role = auth()->user()->role;
        if ($role === 'user') {
            $centerLinks[] = ['label' => 'Dashboard', 'href' => route('user.dashboard')];
            $centerLinks[] = ['label' => 'Tasks', 'href' => route('user.tasks.index')];
            $centerLinks[] = ['label' => 'Payments', 'href' => route('user.payments.index'), 'primary' => true];
        } elseif ($role === 'staff') {
            $centerLinks[] = ['label' => 'Dashboard', 'href' => route('staff.dashboard')];
            $centerLinks[] = ['label' => 'Tasks', 'href' => route('staff.tasks.index')];
            $centerLinks[] = ['label' => 'Payouts', 'href' => route('staff.payments.index'), 'primary' => true];
        } else {
            $centerLinks[] = ['label' => 'Dashboard', 'href' => route('admin.dashboard')];
            $centerLinks[] = ['label' => 'Users', 'href' => route('admin.users.index')];
            $centerLinks[] = ['label' => 'Supervisor Apps', 'href' => route('admin.supervisors.index'), 'primary' => true];
            $centerLinks[] = ['label' => 'Supervision', 'href' => route('admin.supervision.manage.index')];
            $centerLinks[] = ['label' => 'Payments', 'href' => route('admin.payments.dashboard')];
        }

        if ($hasPersonalDataTable) {
            $centerLinks[] = ['label' => 'Personal Data', 'href' => route('profile.personal')];
        }
   @endphp

    <!-- Top Navigation (symmetrical 3-column) -->
    <nav class="bg-white/80 backdrop-blur border-b border-blue-200 text-slate-900 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 items-center gap-3 py-3 md:py-4">
                <!-- Left: Brand -->
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center shadow-sm border border-blue-200 shrink-0">
                        <span class="text-white font-bold text-sm">IM</span>
                    </div>
                    <div class="min-w-0">
                        <a href="{{ $brandHref }}" class="block truncate text-lg font-bold tracking-wide hover:text-blue-700">
                            Internship Management
                        </a>
                        <p class="text-xs text-blue-700/70 truncate">Fast • Secure • Simple</p>
                    </div>
                </div>

                <!-- Center: Primary Nav (always centered) -->
                <div class="flex justify-center md:justify-center">
                    <div class="flex flex-nowrap md:flex-wrap justify-start md:justify-center gap-2 overflow-x-auto md:overflow-visible max-w-full pb-1 md:pb-0">
                        @foreach($centerLinks as $l)
                            <a href="{{ $l['href'] }}" class="{{ !empty($l['primary']) ? $navBtnPrimary : $navBtn }}">
                                {{ $l['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Right: User + Actions -->
                <div class="flex flex-wrap items-center justify-end gap-2 min-w-0">
                    @php
                        $onlineUsers = \App\Models\User::online()->count();
                        $user = auth()->user();
                        $unreadCount = \App\Models\Message::where(function($query) use ($user) {
                                $query->where('receiver_id', $user->id)
                                      ->where('is_read', false);
                            })
                            ->orWhere(function($query) use ($user) {
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

                    <a href="{{ route('messages.index') }}" class="relative {{ $navBtn }}">
                        Messages
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold border border-blue-200">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <button type="button" data-theme-toggle class="{{ $navBtn }}" title="Toggle theme">
                        <span data-theme-label>Theme</span>
                    </button>

                    <a href="{{ route('profile.edit') }}" class="{{ $navBtn }}">Profile</a>

                    <div class="hidden lg:flex items-center gap-2 border border-blue-200 bg-blue-50 rounded-lg px-3 py-2">
                        <div class="w-7 h-7 bg-blue-100 border border-blue-200 rounded-full flex items-center justify-center">
                            <span class="text-blue-800 font-bold text-xs">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-semibold text-slate-900 truncate max-w-[10rem]">{{ auth()->user()->name }}</div>
                            <div class="text-[10px] text-blue-800/70">{{ ucfirst(auth()->user()->role) }} • {{ $onlineUsers }} online</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-all border border-blue-700/20 shadow-sm">
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
                <a href="{{ $dashboardRoute }}" class="inline-flex items-center gap-2 bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-800 px-4 py-2 rounded-lg font-semibold shadow-sm transition-all">
                    <span aria-hidden="true">←</span>
                    <span>Back to Dashboard</span>
                </a>
            </div>
        @endif
        @if (session('success'))
            <div class="mb-6 p-4 bg-blue-50 border border-blue-300 rounded">
                <p class="text-slate-900 font-medium">✓ {{ session('success') }}</p>
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
    <footer class="bg-white/80 text-blue-900/80 py-6 border-t border-blue-200">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Internship Management System. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>