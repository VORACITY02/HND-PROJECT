@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('messages.index') }}" class="text-primary-600 hover:text-primary-700">
            ← Back to Inbox
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg p-6">
    <!-- Message Header -->
    <div class="border-b border-slate-200 pb-4 mb-6">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">{{ $message->subject }}</h1>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                            <span class="text-primary-600 font-bold">{{ substr($message->sender->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">{{ $message->sender->name }}</p>
                            <p class="text-xs text-slate-500">{{ $message->sender->email }}</p>
                        </div>
                    </div>
                    @if($message->is_broadcast)
                        <span class="px-3 py-1 text-sm bg-purple-100 text-purple-800 rounded-full">Broadcast Message</span>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-slate-500">{{ $message->created_at->format('M d, Y') }}</p>
                <p class="text-xs text-slate-400">{{ $message->created_at->format('h:i A') }}</p>
            </div>
        </div>

        @if($message->is_broadcast)
            <div class="mt-3 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                <p class="text-sm text-purple-800">
                    <strong>Broadcast to:</strong> 
                    @if($message->recipient_type === 'all_users')
                        All Users/Students
                    @elseif($message->recipient_type === 'all_staff')
                        All Staff Members
                    @elseif($message->recipient_type === 'all_admins')
                        All Administrators
                    @endif
                </p>
            </div>
        @elseif($message->receiver)
            <div class="mt-3">
                <p class="text-sm text-slate-600">
                    <strong>To:</strong> {{ $message->receiver->name }} ({{ $message->receiver->email }})
                </p>
            </div>
        @endif
    </div>

    <!-- Message Body -->
    <div class="prose max-w-none">
        <div class="text-slate-700 whitespace-pre-wrap">{{ $message->message }}</div>
    </div>

    <!-- Reply Button (if not a broadcast and not sent by current user) -->
    @if(!$message->is_broadcast && $message->sender_id !== auth()->id())
        <div class="mt-8 pt-6 border-t border-slate-200">
            <div class="flex items-center space-x-4">
                <a href="{{ route('messages.create') }}?reply_to={{ $message->sender_id }}&subject={{ urlencode('Re: ' . $message->subject) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all shadow-lg">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                    Reply to {{ $message->sender->name }}
                </a>
                <a href="{{ route('messages.index') }}" 
                   class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition-all">
                    Back to Inbox
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
