@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8 border border-gray-200">
        <h2 class="text-2xl font-bold text-center text-blue-700 mb-4">Verify Your Email</h2>
        
        <p class="text-gray-600 mb-6 text-center">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
        </p>

        @if (session('message'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button class="w-full py-3 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition font-semibold">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button class="w-full py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-semibold">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection
