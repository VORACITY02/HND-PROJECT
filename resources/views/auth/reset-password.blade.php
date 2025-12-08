@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8 border border-gray-200">
        <h2 class="text-2xl font-bold text-center text-blue-700 mb-2">Reset Password</h2>
        <p class="text-center text-gray-600 mb-6">Enter your new password</p>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', request()->email) }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600"
                    required autofocus>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">New Password</label>
                <input type="password" name="password"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600"
                    required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600"
                    required>
            </div>

            <button class="w-full py-3 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition font-semibold">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection
