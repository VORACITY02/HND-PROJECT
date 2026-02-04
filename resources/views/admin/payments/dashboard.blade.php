@extends('layouts.app')

@section('content')
@php
    $fmt = fn(int $minor) => \App\Support\Money::format($minor, $currency);
@endphp

<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Payments</h1>
        <div class="flex gap-2">
            <a class="bg-white/80 hover:bg-white border border-slate-200 px-4 py-2 rounded-lg font-semibold" href="{{ route('admin.payments.students') }}">Students</a>
            <a class="bg-white/80 hover:bg-white border border-slate-200 px-4 py-2 rounded-lg font-semibold" href="{{ route('admin.payments.staff') }}">Staff payouts</a>
            <a class="bg-white/80 hover:bg-white border border-slate-200 px-4 py-2 rounded-lg font-semibold" href="{{ route('admin.payments.settings') }}">Settings</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white/80 border border-slate-200 rounded-xl p-6 shadow-sm">
            <h2 class="font-semibold text-slate-900 mb-2">System account</h2>
            <div class="text-sm text-slate-700">
                <div><span class="font-medium">External account id:</span> {{ $systemExternalAccountId ?: 'Not configured' }}</div>
                @if(is_array($systemBalance))
                    <div class="mt-2">
                        <span class="font-medium">Balance:</span>
                        {{ $fmt($systemBalance['balance_cents'] ?? 0) }} {{ $systemBalance['currency'] ?? $currency }}
                    </div>
                    @if(isset($systemBalance['error']))
                        <div class="mt-2 text-amber-700">Simulator error: {{ $systemBalance['error'] }}</div>
                    @endif
                @endif
            </div>
        </div>

        <div class="bg-white/80 border border-slate-200 rounded-xl p-6 shadow-sm">
            <h2 class="font-semibold text-slate-900 mb-2">Configuration</h2>
            <div class="text-sm text-slate-700 space-y-1">
                <div><span class="font-medium">Currency:</span> {{ $currency }}</div>
                <div><span class="font-medium">Student one-time fee:</span> {{ $fmt($studentFeeCents) }} {{ $currency }}</div>
            </div>
        </div>

        <div class="bg-white/80 border border-slate-200 rounded-xl p-6 shadow-sm">
            <h2 class="font-semibold text-slate-900 mb-2">Totals</h2>
            <div class="text-sm text-slate-700 space-y-1">
                <div><span class="font-medium">Collected from students:</span> {{ $fmt($totalStudentCollectedCents) }} {{ $currency }}</div>
                <div><span class="font-medium">Paid to staff:</span> {{ $fmt($totalStaffPaidCents) }} {{ $currency }}</div>
            </div>
        </div>

        <div class="bg-white/80 border border-slate-200 rounded-xl p-6 shadow-sm">
            <h2 class="font-semibold text-slate-900 mb-2">Accounts coverage</h2>
            <div class="text-sm text-slate-700 space-y-1">
                <div><span class="font-medium">Students with accounts:</span> {{ $studentsWithAccounts }}</div>
                <div><span class="font-medium">Staff with accounts:</span> {{ $staffWithAccounts }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
