@extends('layouts.app')

@section('content')
@php
    $fmt = fn(int $minor) => \App\Support\Money::format($minor, $currency);
@endphp

<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Staff payouts</h1>
            <p class="text-slate-700">Payouts are calculated from base pay + supervisor bonuses + supervisee count.</p>
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
                    <th class="p-3">Staff</th>
                    <th class="p-3">Account</th>
                    <th class="p-3">Supervisor?</th>
                    <th class="p-3">Supervisees</th>
                    <th class="p-3">Computed payout</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    @php($staff = $row['staff'])
                    @php($calc = $row['calc'])
                    <tr class="border-t border-slate-200">
                        <td class="p-3">
                            <div class="font-semibold text-slate-900">{{ $staff->name }}</div>
                            <div class="text-xs text-slate-600">{{ $staff->email }}</div>
                            <div class="text-xs text-slate-600">Total paid: {{ $fmt($row['paid_total_cents']) }} {{ $currency }}</div>
                        </td>
                        <td class="p-3 text-sm">
                            @if($staff->paymentAccount)
                                <div class="text-slate-900 font-medium">Yes</div>
                                <div class="text-xs text-slate-600 break-all">{{ $staff->paymentAccount->external_account_id }}</div>
                            @else
                                <div class="text-amber-700 font-semibold">No</div>
                            @endif
                        </td>
                        <td class="p-3 text-sm">
                            @if($row['is_supervisor'])
                                <span class="font-semibold text-emerald-700">Yes</span>
                            @else
                                <span class="font-semibold text-slate-700">No</span>
                            @endif
                        </td>
                        <td class="p-3 text-sm text-slate-900">{{ $row['supervisee_count'] }}</td>
                        <td class="p-3 text-sm">
                            <div class="font-semibold text-slate-900">{{ $fmt($calc['amount_cents']) }} {{ $currency }}</div>
                            <div class="text-xs text-slate-600">
                                base {{ $fmt($calc['base_pay_cents']) }} + fixed {{ $fmt($calc['supervisor_fixed_bonus_cents']) }} + per {{ $fmt($calc['per_supervisee_bonus_cents']) }} × {{ $row['supervisee_count'] }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="flex flex-wrap gap-2">
                                <form method="POST" action="{{ route('admin.payments.users.account.create', $staff) }}">
                                    @csrf
                                    <button class="bg-white hover:bg-slate-50 border border-slate-200 px-3 py-2 rounded-lg text-sm font-semibold">Create account</button>
                                </form>

                                <form method="POST" action="{{ route('admin.payments.staff.pay', $staff) }}">
                                    @csrf
                                    <button class="bg-sky-600 hover:bg-sky-700 text-white px-3 py-2 rounded-lg text-sm font-semibold" @disabled(!$staff->paymentAccount)>
                                        Pay now
                                    </button>
                                </form>
                            </div>
                            @if(!$staff->paymentAccount)
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
