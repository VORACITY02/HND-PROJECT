@extends('layouts.app')

@section('content')
@php
    $fmt = fn(int $minor) => \App\Support\Money::format($minor, $currency);
@endphp

<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Student payments</h1>
            <p class="text-slate-700">One-time fee per student: <span class="font-semibold">{{ $fmt($studentFeeCents) }} {{ $currency }}</span></p>
        </div>
        <div class="flex gap-2">
            <a class="bg-white/80 hover:bg-white border border-slate-200 px-4 py-2 rounded-lg font-semibold" href="{{ route('admin.payments.dashboard') }}">Overview</a>
            <a class="bg-white/80 hover:bg-white border border-slate-200 px-4 py-2 rounded-lg font-semibold" href="{{ route('admin.payments.settings') }}">Settings</a>
        </div>
    </div>

    <div class="bg-white/80 border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50">
                <tr class="text-slate-700 text-sm">
                    <th class="p-3">Student</th>
                    <th class="p-3">Account</th>
                    <th class="p-3">Paid</th>
                    <th class="p-3">Remaining</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    @php($student = $row['student'])
                    <tr class="border-t border-slate-200">
                        <td class="p-3">
                            <div class="font-semibold text-slate-900">{{ $student->name }}</div>
                            <div class="text-xs text-slate-600">{{ $student->email }}</div>
                        </td>
                        <td class="p-3 text-sm">
                            @if($student->paymentAccount)
                                <div class="text-slate-900 font-medium">Yes</div>
                                <div class="text-xs text-slate-600 break-all">{{ $student->paymentAccount->external_account_id }}</div>
                            @else
                                <div class="text-amber-700 font-semibold">No</div>
                            @endif
                        </td>
                        <td class="p-3 text-sm text-slate-900">{{ $fmt($row['paid_cents']) }} {{ $currency }}</td>
                        <td class="p-3 text-sm">
                            @if($row['remaining_cents'] > 0)
                                <span class="font-semibold text-amber-700">{{ $fmt($row['remaining_cents']) }} {{ $currency }}</span>
                            @else
                                <span class="font-semibold text-emerald-700">0.00 {{ $currency }}</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <div class="flex flex-wrap gap-2">
                                <form method="POST" action="{{ route('admin.payments.users.account.create', $student) }}">
                                    @csrf
                                    <button class="bg-white hover:bg-slate-50 border border-slate-200 px-3 py-2 rounded-lg text-sm font-semibold">Create account</button>
                                </form>

                                <form method="POST" action="{{ route('admin.payments.students.charge', $student) }}">
                                    @csrf
                                    <button class="bg-sky-600 hover:bg-sky-700 text-white px-3 py-2 rounded-lg text-sm font-semibold" @disabled(!$student->paymentAccount || $row['remaining_cents'] <= 0)>
                                        Charge remaining
                                    </button>
                                </form>
                            </div>
                            @if(!$student->paymentAccount)
                                <div class="text-xs text-slate-600 mt-2">Create the payment account first.</div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
