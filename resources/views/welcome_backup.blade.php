<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Internship Management System</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#faf5ff',
                            100: '#f3e8ff',
                            200: '#e9d5ff',
                            300: '#d8b4fe',
                            400: '#c084fc',
                            500: '#a855f7',
                            600: '#9333ea',
                            700: '#7e22ce',
                            800: '#6b21a8',
                            900: '#581c87',
                        },
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-purple-50 to-slate-100 text-slate-800">

    <!-- Top Navigation -->
    <nav class="w-full bg-white/80 backdrop-blur-lg shadow-lg fixed top-0 left-0 z-50 border-b border-purple-100">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-5">
            <div class="flex items-center space-x-3">
                <!-- Logo Placeholder -->
                <div class="w-12 h-12 bg-gradient-to-br from-primary-600 to-primary-700 rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-sm">LOGO</span>
                </div>
                <h1 class="font-bold text-2xl bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">
                    Internship Manager
                </h1>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="hidden md:block text-slate-700 hover:text-primary-600 font-semibold transition">
                    Login
                </a>
                <a href="{{ route('register') }}" class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl hover:shadow-xl hover:scale-105 transition font-semibold">
                    Get Started
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-24 px-6 min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-block mb-4 px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold">
                        ✨ Modern Internship Management
                    </div>
                    <h1 class="text-6xl font-bold mb-6 leading-tight">
                        <span class="bg-gradient-to-r from-primary-600 via-primary-700 to-purple-800 bg-clip-text text-transparent">
                            Streamline Your
                        </span>
                        <br/>
                        <span class="text-slate-800">Internship Journey</span>
                    </h1>
                    <p class="text-xl text-slate-600 mb-8 leading-relaxed">
                        A powerful platform connecting students, staff, and administrators. Manage internships, track progress, and collaborate seamlessly—all in one place.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-lg rounded-xl shadow-xl hover:shadow-2xl hover:scale-105 transition font-semibold">
                            Create Account
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-white text-primary-700 text-lg rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition font-semibold border-2 border-primary-200">
                            Login
                        </a>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-400 to-purple-500 rounded-3xl blur-3xl opacity-30"></div>
                        <div class="relative bg-white rounded-3xl shadow-2xl p-8 border border-purple-100">
                            <div class="space-y-4">
                                <div class="p-4 bg-gradient-to-r from-primary-50 to-purple-50 rounded-xl">
                                    <div class="font-semibold text-slate-800 mb-1">Track Progress</div>
                                    <div class="text-sm text-slate-500">Monitor internship milestones</div>
                                </div>
                                <div class="p-4 bg-gradient-to-r from-purple-50 to-primary-50 rounded-xl">
                                    <div class="font-semibold text-slate-800 mb-1">Collaborate</div>
                                    <div class="text-sm text-slate-500">Connect with mentors</div>
                                </div>
                                <div class="p-4 bg-gradient-to-r from-primary-50 to-purple-50 rounded-xl">
                                    <div class="font-semibold text-slate-800 mb-1">Analytics</div>
                                    <div class="text-sm text-slate-500">View detailed reports</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-slate-800 mb-4">Join Our Growing Community</h2>
                <p class="text-slate-600 text-lg">Start managing internships efficiently today</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $stats = [
                        ['count' => \App\Models\User::where('role', 'user')->count(), 'label' => 'Active Students', 'color' => 'from-purple-500 to-purple-600'],
                        ['count' => \App\Models\User::where('role', 'staff')->count(), 'label' => 'Staff Members', 'color' => 'from-pink-500 to-purple-500'],
                        ['count' => \App\Models\User::where('role', 'admin')->count(), 'label' => 'System Admins', 'color' => 'from-primary-500 to-primary-600']
                    ];
                @endphp
                @foreach($stats as $stat)
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-r {{ $stat['color'] }} rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition"></div>
                        <div class="relative bg-white rounded-2xl shadow-lg p-8 border border-purple-100 hover:shadow-xl transition text-center">
                            <h3 class="text-5xl font-bold bg-gradient-to-r {{ $stat['color'] }} bg-clip-text text-transparent mb-2">{{ $stat['count'] }}</h3>
                            <p class="text-slate-600 font-semibold">{{ $stat['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-20 px-6 bg-gradient-to-br from-purple-50 to-slate-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-slate-800 mb-4">Powerful Features for Everyone</h2>
                <p class="text-slate-600 text-lg">Built with all user roles in mind</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition border border-purple-100 hover:scale-105">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <span class="text-white font-bold text-xl">A</span>
                    </div>
                    <h3 class="font-bold text-2xl text-slate-800 mb-3">For Administrators</h3>
                    <p class="text-slate-600 leading-relaxed">Complete control over user management, system settings, and internship program configuration.</p>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition border border-purple-100 hover:scale-105">
                    <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-purple-500 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <span class="text-white font-bold text-xl">S</span>
                    </div>
                    <h3 class="font-bold text-2xl text-slate-800 mb-3">For Staff</h3>
                    <p class="text-slate-600 leading-relaxed">Monitor student progress, review submissions, and provide guidance throughout the internship journey.</p>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition border border-purple-100 hover:scale-105">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <span class="text-white font-bold text-xl">U</span>
                    </div>
                    <h3 class="font-bold text-2xl text-slate-800 mb-3">For Students</h3>
                    <p class="text-slate-600 leading-relaxed">Track your internship progress, submit reports, receive feedback, and manage your profile effortlessly.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 px-6 bg-gradient-to-br from-slate-800 to-slate-900 text-slate-300">
        <div class="max-w-7xl mx-auto text-center">
            <div class="flex items-center justify-center space-x-3 mb-4">
                <!-- Logo Placeholder -->
                <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-primary-700 rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-sm">LOGO</span>
                </div>
                <span class="font-bold text-xl text-white">Internship Manager</span>
            </div>
            <p class="text-slate-400 mb-6">Empowering students, staff, and administrators to collaborate effectively</p>
            <div class="border-t border-slate-700 pt-6">
                <p>&copy; {{ date('Y') }} Internship Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>