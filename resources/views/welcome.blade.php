<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Internship Management System</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 text-slate-900 antialiased">
        <!-- Blue-first Background -->
        <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-blue-100">
            <!-- Header -->
            <header class="bg-white/80 backdrop-blur border-b border-blue-200">
                <div class="container mx-auto px-6 py-4">
                    <div class="flex items-center justify-between">
                        <!-- Logo Section -->
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center shadow-sm border border-blue-200">
                                <span class="text-white font-bold text-xl">IM</span>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-slate-900">Internship Management</h1>
                                <p class="text-blue-800/70 text-xs">Professional Learning Platform</p>
                            </div>
                        </div>
                        
                        <!-- Navigation -->
                        @if (Route::has('login'))
                            <nav class="flex space-x-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors shadow-sm border border-blue-700/20">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="bg-blue-50 hover:bg-blue-100 text-blue-800 px-6 py-2 rounded-lg font-semibold transition-colors border border-blue-200">
                                        Login
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors shadow-sm border border-blue-700/20">
                                            Get Started
                                        </a>
                                    @endif
                                @endauth
                            </nav>
                        @endif
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="container mx-auto px-6 py-16">
                
                <!-- Hero Section -->
                <div class="text-center max-w-4xl mx-auto mb-16">
                    <h2 class="text-5xl font-bold text-slate-900 mb-6">
                        Manage Internships with
                        <span class="text-blue-700">Professional Excellence</span>
                    </h2>
                    <p class="text-xl text-blue-900/70 mb-8 leading-relaxed">
                        A comprehensive platform designed to streamline internship management, 
                        facilitate communication, and track progress with professional-grade tools.
                    </p>
                    
                    @guest
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('register') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg shadow-lg transition-all transform hover:scale-105 border border-blue-700/20">
                                Start Your Journey
                            </a>
                            <a href="{{ route('login') }}" 
                               class="bg-white/60 border-2 border-blue-200 hover:bg-blue-50 px-8 py-4 rounded-lg font-semibold text-lg transition-all text-blue-800">
                                Sign In
                            </a>
                        </div>
                    @endguest
                </div>

                <!-- Features Grid -->
                <div class="grid md:grid-cols-3 gap-8 mb-16">
                    
                    <!-- Feature 1: User Management -->
                    <div class="bg-white/80 backdrop-blur border border-blue-200 rounded-xl p-8 text-center shadow-sm">
                        <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-blue-200">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">User Management</h3>
                        <p class="text-blue-900/70 leading-relaxed">
                            Comprehensive role-based access control with admin, staff, and user privileges. 
                            Secure user promotion and management capabilities.
                        </p>
                    </div>

                    <!-- Feature 2: Real-time Communication -->
                    <div class="bg-white/80 backdrop-blur border border-blue-200 rounded-xl p-8 text-center shadow-sm">
                        <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-blue-200">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Smart Messaging</h3>
                        <p class="text-blue-900/70 leading-relaxed">
                            Advanced messaging system with role-based broadcasting, individual communication, 
                            and real-time notification tracking.
                        </p>
                    </div>

                    <!-- Feature 3: Online Status -->
                    <div class="bg-white/80 backdrop-blur border border-blue-200 rounded-xl p-8 text-center shadow-sm">
                        <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-blue-200">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Live Activity</h3>
                        <p class="text-blue-900/70 leading-relaxed">
                            Real-time user activity tracking, online status monitoring, 
                            and interactive dashboards for better collaboration.
                        </p>
                    </div>
                </div>

                <!-- Stats Section -->
                <div class="bg-white/80 backdrop-blur border border-blue-200 rounded-2xl p-12 text-center shadow-sm">
                    <h3 class="text-3xl font-bold text-slate-900 mb-8">Professional Platform Features</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        <div class="space-y-2">
                            <div class="text-3xl font-bold text-blue-700">3</div>
                            <div class="text-blue-900/70 font-medium">User Roles</div>
                            <div class="text-sm text-blue-900/60">Admin, Staff, User</div>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="text-3xl font-bold text-blue-700">∞</div>
                            <div class="text-blue-900/70 font-medium">Messages</div>
                            <div class="text-sm text-blue-900/60">Individual & Broadcast</div>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="text-3xl font-bold text-blue-700">24/7</div>
                            <div class="text-blue-900/70 font-medium">Monitoring</div>
                            <div class="text-sm text-blue-900/60">Real-time Tracking</div>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="text-3xl font-bold text-blue-700">100%</div>
                            <div class="text-blue-900/70 font-medium">Secure</div>
                            <div class="text-sm text-blue-900/60">Protected Access</div>
                        </div>
                    </div>
                </div>

            </main>

            <!-- Footer -->
            <footer class="bg-white/80 backdrop-blur border-t border-blue-200 mt-20">
                <div class="container mx-auto px-6 py-8">
                    <div class="text-center">
                        <div class="flex items-center justify-center space-x-3 mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center border border-blue-200">
                                <span class="text-white font-bold text-sm">IM</span>
                            </div>
                            <span class="text-lg font-semibold text-slate-900">Internship Management System</span>
                        </div>
                        <p class="text-blue-900/60 text-sm">
                            Professional platform for managing internships and facilitating communication.
                        </p>
                        <div class="mt-4 text-xs text-blue-900/50">
                            &copy; {{ date('Y') }} Internship Management System. Built with Laravel & Professional Excellence.
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>