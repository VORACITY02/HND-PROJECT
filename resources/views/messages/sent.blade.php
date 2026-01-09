@extends('layouts.app')

@section('content')
<!-- Back to Messages Button -->
<div class="mb-6">
    <a href="{{ route('messages.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Inbox
    </a>
</div>

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Sent Messages</h1>
            <p class="text-slate-600">Messages you have sent</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('messages.index') }}" class="px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition">
                Back to Inbox
            </a>
            <a href="{{ route('messages.create') }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                Compose Message
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-xl font-semibold text-slate-800 mb-4">Sent Messages</h2>

    @if($messages->count() > 0)
        <div class="space-y-2">
            @foreach($messages as $message)
                <a href="{{ route('messages.show', $message->id) }}" 
                   class="block p-4 border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                @if($message->is_broadcast)
                                    <span class="font-semibold text-slate-800">Broadcast Message</span>
                                    <span class="px-2 py-1 text-xs bg-lime-100 text-green-950 rounded-full">
                                        @if($message->recipient_type === 'all_users')
                                            To: All Users
                                        @elseif($message->recipient_type === 'all_staff')
                                            To: All Staff
                                        @elseif($message->recipient_type === 'all_admins')
                                            To: All Admins
                                        @endif
                                    </span>
                                @else
                                    <span class="font-semibold text-slate-800">To: {{ $message->receiver->name ?? 'Unknown' }}</span>
                                @endif
                            </div>
                            <p class="font-medium text-slate-700">{{ $message->subject }}</p>
                            <p class="text-sm text-slate-500 mt-1">{{ Str::limit($message->message, 100) }}</p>
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-xs text-slate-500">{{ $message->created_at->diffForHumans() }}</p>
                            @if($message->is_read)
                                <span class="text-xs text-green-600">✓ Read</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    @else
        <p class="text-slate-600 text-center py-8">You haven't sent any messages yet.</p>
    @endif
</div>
@endsection
