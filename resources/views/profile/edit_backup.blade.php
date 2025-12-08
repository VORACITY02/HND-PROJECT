@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Profile</h1>

    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Information -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Profile Information</h2>
        
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Role</label>
                <input type="text" value="{{ ucfirst($user->role) }}"
                    class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100"
                    disabled>
            </div>

            <button type="submit" class="px-6 py-3 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition font-semibold">
                Update Profile
            </button>
        </form>
    </div>

    <!-- Update Password -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Update Password</h2>
        
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Current Password</label>
                <input type="password" name="current_password"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">New Password</label>
                <input type="password" name="password"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Confirm New Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600">
            </div>

            <button type="submit" class="px-6 py-3 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition font-semibold">
                Update Password
            </button>
        </form>
    </div>

    <!-- Delete Account -->
    <div class="bg-white shadow rounded-lg p-6 border-2 border-red-200">
        <h2 class="text-xl font-semibold text-red-700 mb-4">Delete Account</h2>
        <p class="text-gray-600 mb-4">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
        
        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
            @csrf
            @method('DELETE')

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Confirm Password</label>
                <input type="password" name="password"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-red-600 focus:border-red-600"
                    required>
            </div>

            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                Delete Account
            </button>
        </form>
    </div>
</div>
@endsection
