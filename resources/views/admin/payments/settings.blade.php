@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Payment settings</h1>
            <p class="text-slate-700">These values control the one-time student fee and staff payout calculations.</p>
        </div>
        <div class="flex gap-2">
            <a class="bg-white/80 hover:bg-white border border-slate-200 px-4 py-2 rounded-lg font-semibold" href="{{ route('admin.payments.dashboard') }}">Overview</a>
        </div>
    </div>

    <div class="bg-white/80 border border-slate-200 rounded-xl p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.payments.settings.update') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-900">Currency</label>
                <input name="currency" value="XOF" readonly class="w-full mt-1 border border-slate-200 rounded-lg px-3 py-2 bg-slate-50" />
                <p class="text-xs text-slate-600 mt-1">Currency is fixed to XOF (FCFA).</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900">Student fee (XOF)</label>
                <input type="number" min="0" step="1" name="student_fee_cents" value="{{ old('student_fee_cents', $student_fee_cents) }}" class="w-full mt-1 border border-slate-200 rounded-lg px-3 py-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-900">Staff base pay (XOF)</label>
                    <input type="number" min="0" step="1" name="staff_base_pay_cents" value="{{ old('staff_base_pay_cents', $staff_base_pay_cents) }}" class="w-full mt-1 border border-slate-200 rounded-lg px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900">Supervisor fixed bonus (XOF)</label>
                    <input type="number" min="0" step="1" name="supervisor_fixed_bonus_cents" value="{{ old('supervisor_fixed_bonus_cents', $supervisor_fixed_bonus_cents) }}" class="w-full mt-1 border border-slate-200 rounded-lg px-3 py-2" />
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900">Per supervisee bonus (XOF)</label>
                <input type="number" min="0" step="1" name="per_supervisee_bonus_cents" value="{{ old('per_supervisee_bonus_cents', $per_supervisee_bonus_cents) }}" class="w-full mt-1 border border-slate-200 rounded-lg px-3 py-2" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-900">System external account id</label>
                <input name="system_external_account_id" value="{{ old('system_external_account_id', $system_external_account_id) }}" class="w-full mt-1 border border-slate-200 rounded-lg px-3 py-2" />
                <div class="flex items-center gap-2 mt-2">
                    <form method="POST" action="{{ route('admin.payments.settings.system-account') }}">
                        @csrf
                        <button type="submit" class="bg-white hover:bg-slate-50 border border-slate-200 px-3 py-2 rounded-lg text-sm font-semibold">
                            Create system account (simulator)
                        </button>
                    </form>
                    <div class="text-xs text-slate-600">Creates a wallet on the simulator and saves its id here.</div>
                </div>
                <p class="text-xs text-slate-600 mt-2">This is the simulator account that collects student fees and pays staff.</p>
            </div>

            <div class="pt-2">
                <button class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-lg font-semibold">Save settings</button>
            </div>

            <div class="mt-6 text-xs text-slate-600">
                Simulator base URL is read from <code>services.payment_simulator.base_url</code>: <span class="font-mono">{{ $simulator_base_url ?: '(not set)' }}</span>
            </div>
        </form>
    </div>
</div>
@endsection
