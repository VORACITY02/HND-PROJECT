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

        <h2 class="text-2xl font-bold text-center mb-1 text-gray-800">Create Your Account</h2>
        <p class="text-center text-gray-600 mb-6">Register to get started</p>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-300 rounded text-red-700">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Full Name</label>
                <input type="text" name="name"
                       class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email"
                       class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password"
                       class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <button class="w-full py-3 bg-blue-600 text-white rounded hover:bg-blue-700 font-medium">
                Register
            </button>
        </form>

        <p class="text-center text-gray-600 mt-5 text-sm">
            Already have an account?
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium hover:underline">
                Login
            </a>
        </p>

    </div>

</div>
@endsection