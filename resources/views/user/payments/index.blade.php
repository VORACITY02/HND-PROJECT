@extends('layouts.app')

@section('content')
@php
    $fmt = fn(int $minor) => \App\Support\Money::format($minor, $currency);
@endphp

<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">My Payments</h1>
            <p class="text-slate-700">One-time internship fee: <span class="font-semibold">{{ $fmt($feeMinor) }} {{ $currency }}</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white/80 border border-slate-200 rounded-xl p-6 shadow-sm">
            <h2 class="font-semibold text-slate-900 mb-2">Status</h2>
            <div class="text-sm text-slate-700 space-y-1">
                <div><span class="font-medium">Paid:</span> {{ $fmt($paidMinor) }} {{ $currency }}</div>
                <div>
                    <span class="font-medium">Remaining:</span>
                    @if($remainingMinor > 0)
                        <span class="font-semibold text-amber-700">{{ $fmt($remainingMinor) }} {{ $currency }}</span>
                    @else
                        <span class="font-semibold text-emerald-700">0 {{ $currency }}</span>
                    @endif
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
                        <form method="POST" action="{{ route('user.payments.account.create') }}" class="flex flex-wrap items-center gap-2">
                            @csrf
                            <button class="bg-white hover:bg-slate-50 border border-slate-200 px-3 py-2 rounded-lg text-sm font-semibold">
                                {{ app(\App\Services\Payment\PaymentConfig::class)->paymentDriver() === 'rabbitmaid' ? 'Activate wallet' : 'Create payment account' }}
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('user.payments.pay') }}">
                        @csrf
                        <button class="bg-sky-600 hover:bg-sky-700 text-white px-3 py-2 rounded-lg text-sm font-semibold" @disabled(!$hasAccount || $remainingMinor <= 0)>
                            Pay remaining
                        </button>
                    </form>
                </div>

                @if(!$hasAccount)
                    <div class="text-xs text-slate-600 mt-2">Create your payment account before paying.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white/80 border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200">
            <h2 class="font-semibold text-slate-900">Recent payments</h2>
            <p class="text-xs text-slate-600">Shows up to the last 20 payment attempts.</p>
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
                @forelse($payments as $p)
                    <tr class="border-t border-slate-200">
                        <td class="p-3 text-sm text-slate-700">{{ optional($p->paid_at ?: $p->created_at)->format('Y-m-d H:i') }}</td>
                        <td class="p-3 text-sm text-slate-900">{{ $fmt((int)$p->amount_cents) }} {{ $p->currency }}</td>
                        <td class="p-3 text-sm">
                            @if($p->status === 'completed')
                                <span class="font-semibold text-emerald-700">Completed</span>
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
                        <td class="p-3 text-sm text-slate-700" colspan="4">No payments yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
