@extends('layouts.app')

@section('content')
@php
    $fmt = fn(int $minor) => \App\Support\Money::format($minor, $currency);
@endphp

<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">My Payouts</h1>
            <p class="text-slate-700">Request your payout and track your payment history.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white/80 border border-slate-200 rounded-xl p-6 shadow-sm">
            <h2 class="font-semibold text-slate-900 mb-2">Computed payout</h2>
            <div class="text-sm text-slate-700 space-y-1">
                <div><span class="font-medium">Supervisor:</span> {{ $isSupervisor ? 'Yes' : 'No' }}</div>
                <div><span class="font-medium">Supervisees:</span> {{ $superviseeCount }}</div>
                <div class="pt-1">
                    <span class="font-medium">Total:</span>
                    <span class="font-semibold text-slate-900">{{ $fmt((int)($calc['amount_cents'] ?? 0)) }} {{ $currency }}</span>
                </div>
                <div class="text-xs text-slate-600">
                    base {{ $fmt((int)($calc['base_pay_cents'] ?? 0)) }} + fixed {{ $fmt((int)($calc['supervisor_fixed_bonus_cents'] ?? 0)) }} + per {{ $fmt((int)($calc['per_supervisee_bonus_cents'] ?? 0)) }} × {{ $superviseeCount }}
                </div>
            </div>
        </div>

        <div class="bg-white/80 border border-slate-200 rounded-xl p-6 shadow-sm">
            <h2 class="font-semibold text-slate-900 mb-2">Account summary</h2>
            <div class="text-sm text-slate-700 space-y-1">
                <div><span class="font-medium">Paid total:</span> {{ $fmt($paidTotalMinor) }} {{ $currency }}</div>
                <div>
                    <span class="font-medium">Remaining:</span>
                    <span class="font-semibold {{ $remainingMinor > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                        {{ $fmt($remainingMinor) }} {{ $currency }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white/80 border border-slate-200 rounded-xl p-6 shadow-sm">
            <h2 class="font-semibold text-slate-900 mb-2">My wallet</h2>
            <div class="text-sm text-slate-700">
                <div><span class="font-medium">Account:</span> {{ $hasAccount ? 'Yes' : 'No' }}</div>
                @if($hasAccount)
                    <div class="text-xs text-slate-600 break-all mt-1">{{ $externalAccountId }}</div>
                @endif

                @if(is_array($balance))
                    <div class="mt-2">
                        <span class="font-medium">Balance:</span>
                        {{ $fmt((int)($balance['balance_cents'] ?? 0)) }} {{ $balance['currency'] ?? $currency }}
                    </div>
                    @if(isset($balance['error']))
                        <div class="mt-2 text-amber-700">Simulator error: {{ $balance['error'] }}</div>
                    @endif
                @endif

                <div class="mt-4 flex flex-wrap gap-2">
                    @if(!$hasAccount)
                        <form method="POST" action="{{ route('staff.payments.account.create') }}" class="flex flex-wrap items-center gap-2">
                            @csrf
                            <button class="bg-white hover:bg-slate-50 border border-slate-200 px-3 py-2 rounded-lg text-sm font-semibold">Activate wallet</button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('staff.payments.request') }}">
                        @csrf
                        <button class="bg-sky-600 hover:bg-sky-700 text-white px-3 py-2 rounded-lg text-sm font-semibold" @disabled(!$hasAccount)>
                            Request payout
                        </button>
                    </form>
                </div>

                @if(!$hasAccount)
                    <div class="text-xs text-slate-600 mt-2">Create your payment account before requesting payout.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white/80 border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200">
            <h2 class="font-semibold text-slate-900">Recent payout history</h2>
            <p class="text-xs text-slate-600">Shows up to the last 20 payout attempts.</p>
        </div>
        <table class="w-full text-left">
            <thead class="bg-slate-50">
                <tr class="text-slate-700 text-sm">
                    <th class="p-3">Date</th>
                    <th class="p-3">Amount</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Reference</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payouts as $p)
                    <tr class="border-t border-slate-200">
                        <td class="p-3 text-sm text-slate-700">{{ optional($p->paid_at ?: $p->created_at)->format('Y-m-d H:i') }}</td>
                        <td class="p-3 text-sm text-slate-900">{{ $fmt((int)$p->amount_cents) }} {{ $p->currency }}</td>
                        <td class="p-3 text-sm">
                            @if($p->status === 'paid')
                                <span class="font-semibold text-emerald-700">Paid</span>
                            @elseif($p->status === 'failed')
                                <span class="font-semibold text-rose-700">Failed</span>
                            @else
                                <span class="font-semibold text-amber-700">Pending</span>
                            @endif
                        </td>
                        <td class="p-3 text-xs text-slate-600 break-all">{{ $p->reference }}</td>
                    </tr>
                @empty
                    <tr class="border-t border-slate-200">
                        <td class="p-3 text-sm text-slate-700" colspan="4">No payouts yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
