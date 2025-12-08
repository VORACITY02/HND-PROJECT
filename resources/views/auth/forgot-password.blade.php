@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8 border border-gray-200">
        <h2 class="text-2xl font-bold text-center text-blue-700 mb-2">Forgot Password?</h2>
        <p class="text-center text-gray-600 mb-6">Enter your email to receive a password reset link</p>

        @if (session('status'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600"
                    required autofocus>
            </div>

            <button class="w-full py-3 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition font-semibold">
                Send Reset Link
            </button>
        </form>

        <p class="text-center text-gray-600 mt-5 text-sm">
            Remember your password?
            <a href="{{ route('login') }}" class="text-blue-700 hover:underline">Login</a>
        </p>
    </div>
</div>
@endsection
