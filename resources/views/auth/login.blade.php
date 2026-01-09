@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8 border">
        
        <!-- Back to Welcome Button -->
        <div class="mb-4">
            <a href="/" class="inline-flex items-center text-gray-600 hover:text-gray-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
            </a>
        </div>

        <h2 class="text-2xl font-bold text-center mb-1 text-gray-800">Welcome Back</h2>
        <p class="text-center text-gray-600 mb-6">Login to access your account</p>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-300 rounded text-red-700">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email"
                    class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required autofocus>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>

            <div class="flex justify-between items-center mb-4 text-sm">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="rounded">
                    <span class="text-gray-700">Remember me</span>
                </label>

                <a href="{{ route('password.request') }}" class="text-green-900 hover:text-green-950 hover:underline">
                    Forgot password?
                </a>
            </div>

            <button class="w-full py-3 bg-lime-400 text-green-950 rounded hover:bg-lime-300 font-medium border border-lime-200">
                Login
            </button>
        </form>

        <p class="text-center text-gray-600 mt-5 text-sm">
            Not registered?
            <a href="{{ route('register') }}" class="text-green-900 hover:text-green-950 font-medium hover:underline">Create account</a>
        </p>

    </div>

</div>
@endsection