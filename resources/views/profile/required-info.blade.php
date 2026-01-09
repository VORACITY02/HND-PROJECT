@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
  <h1 class="text-2xl font-bold mb-2">Complete Required Information</h1>
  <p class="text-slate-600 mb-6">Please provide your department, phone and address to continue.</p>

  @if(session('status'))
    <div class="bg-emerald-100 text-emerald-800 p-3 rounded mb-4">{{ session('status') }}</div>
  @endif

  @if($errors->any())
    <div class="bg-rose-100 text-rose-800 p-3 rounded mb-4">
      <ul class="list-disc list-inside">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('profile.required.update') }}">
    @csrf
    @method('PUT')

    <div class="mb-4">
      <label class="block font-semibold">Department <span class="text-red-600">*</span></label>
      <input name="department" value="{{ old('department', $department) }}" class="w-full border p-2 rounded" required />
    </div>

    <div class="mb-4">
      <label class="block font-semibold">Phone <span class="text-red-600">*</span></label>
      <input name="phone" value="{{ old('phone', $phone) }}" class="w-full border p-2 rounded" required />
    </div>

    <div class="mb-4">
      <label class="block font-semibold">Address <span class="text-red-600">*</span></label>
      <input name="address" value="{{ old('address', $address) }}" class="w-full border p-2 rounded" required />
    </div>

    <button class="bg-lime-400 text-green-950 px-4 py-2 rounded border border-lime-200">Save and Continue</button>

    @if($redirectTo)
      <input type="hidden" name="redirect_to" value="{{ $redirectTo }}" />
    @endif
  </form>
</div>
@endsection
