@extends('layouts.app')

@section('content')
<!-- Back to Dashboard Button -->
<div class="mb-8">
    <a href="@if(auth()->user()->role === 'admin'){{ route('admin.dashboard') }}@elseif(auth()->user()->role === 'staff'){{ route('staff.dashboard') }}@else{{ route('user.dashboard') }}@endif" 
       class="inline-flex items-center text-green-900 hover:text-green-950 font-medium transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Dashboard
    </a>
</div>

<!-- Header Section -->
<div class="bg-slate-800 text-white rounded-xl p-8 mb-8 shadow-xl">
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold mb-1">Message Center</h1>
                <p class="text-blue-300">Professional communication hub for all messages</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('messages.sent') }}" class="bg-green-900/40 hover:bg-green-900/55 px-6 py-3 rounded-lg font-medium transition-all border border-lime-300/20">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Sent Messages
            </a>
            <a href="{{ route('messages.create') }}" class="bg-lime-400 hover:bg-lime-300 text-green-950 px-6 py-3 rounded-lg font-medium transition-all shadow-lg border border-lime-200">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Compose Message
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-300 rounded-lg text-green-700">
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

<!-- Stats Section -->
<div class="grid md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-blue-600/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-bold text-slate-800">{{ $messages->total() }}</div>
                <div class="text-sm text-slate-600">Total Messages</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-red-600/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-bold text-slate-800">{{ $unreadCount }}</div>
                <div class="text-sm text-slate-600">Unread Messages</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-lime-300/15 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>
            <div>
                @php
                    $broadcastCount = $messages->where('is_broadcast', true)->count();
                @endphp
                <div class="text-2xl font-bold text-slate-800">{{ $broadcastCount }}</div>
                <div class="text-sm text-slate-600">Broadcasts</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-emerald-600/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                @php
                    $onlineUsers = \App\Models\User::online()->count();
                @endphp
                <div class="text-2xl font-bold text-slate-800">{{ $onlineUsers }}</div>
                <div class="text-sm text-slate-600">Users Online</div>
            </div>
        </div>
    </div>
</div>


<!-- Inbox -->
<div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-10 h-10 bg-blue-600/10 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </div>
        <div class="flex-1">
            <h2 class="text-2xl font-bold text-slate-800">
                Message Inbox
                @if($unreadCount > 0)
                    <span class="ml-3 px-3 py-1 text-sm bg-red-500 text-white rounded-full">{{ $unreadCount }} New</span>
                @endif
            </h2>
            <p class="text-slate-600">All your received messages and broadcasts</p>
        </div>
    </div>

    @if($messages->count() > 0)
        <form method="POST" action="{{ route('messages.bulkDestroy') }}" onsubmit="return confirm('Delete selected messages? This cannot be undone.');">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="select_all" class="w-5 h-5" onclick="toggleSelectAll(this)">
                    <label for="select_all" class="text-slate-700">Select all deletable (read direct) messages</label>
                </div>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50" id="bulk_delete_btn" disabled>
                    Delete Selected
                </button>
            </div>
            <div class="space-y-4">
                @foreach($messages as $message)
                    <div class="relative">
                        @if(!$message->is_broadcast && $message->receiver_id === auth()->id() && $message->is_read)
                            <div class="absolute -left-2 top-1/2 -translate-y-1/2">
                                <input type="checkbox" name="message_ids[]" value="{{ $message->id }}" class="w-5 h-5 message-checkbox" onchange="updateBulkDeleteState()">
                            </div>
                        @endif
                        <a href="{{ route('messages.show', $message->id) }}" 
                           class="block p-6 border rounded-xl hover:shadow-md transition-all {{ !$message->isReadByUser(auth()->id()) ? 'bg-blue-50 border-blue-200 shadow-sm' : 'border-slate-200' }}">
                            <div class="flex justify-between items-start">
                                <div class="flex items-start space-x-4 flex-1">
                                    <!-- Sender Avatar -->
                                    <div class="w-12 h-12 bg-slate-600 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-bold">{{ substr($message->sender->name, 0, 1) }}</span>
                                    </div>
                                    
                                    <!-- Message Content -->
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="font-semibold text-slate-800 text-lg">{{ $message->sender->name }}</span>
                                            <span class="text-sm text-slate-500">{{ ucfirst($message->sender->role) }}</span>
                                            
                                            @if($message->is_broadcast)
                                                <span class="px-3 py-1 text-xs bg-lime-100 text-green-950 rounded-full font-medium border border-lime-200">
                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                                    </svg>
                                                    Broadcast
                                                </span>
                                            @endif
                                            
                                            @if(!$message->isReadByUser(auth()->id()))
                                                <span class="px-3 py-1 text-xs bg-red-100 text-red-800 rounded-full font-medium border border-red-200 animate-pulse">
                                                    <span class="inline-block w-2 h-2 bg-red-500 rounded-full mr-1"></span>
                                                    New
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <h3 class="font-bold text-slate-800 text-lg mb-2">{{ $message->subject }}</h3>
                                        <p class="text-slate-600 leading-relaxed">{{ Str::limit($message->message, 120) }}</p>
                                        
                                        @if($message->is_broadcast)
                                            <div class="mt-3 text-sm text-green-900">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Sent to: {{ str_replace('all_', '', $message->recipient_type) }} 
                                                {{ $message->recipient_type === 'all_users' ? '(all users)' : ($message->recipient_type === 'all_staff' ? '(all staff)' : '(all admins)') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Message Meta -->
                                <div class="text-right ml-4 flex-shrink-0">
                                    <p class="text-sm text-slate-500 mb-1">{{ $message->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-slate-400">{{ $message->created_at->diffForHumans() }}</p>
                                    
                                    @if(!$message->isReadByUser(auth()->id()))
                                        <div class="mt-2">
                                            <span class="inline-block w-3 h-3 bg-blue-500 rounded-full"></span>
                                        </div>
                                    @endif
                                    @if(!$message->is_broadcast && $message->receiver_id === auth()->id() && $message->is_read)
                                        <div class="mt-3">
                                            <form method="POST" action="{{ route('messages.destroy', $message->id) }}" onsubmit="return confirm('Delete this message?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-green-950 hover:text-green-900 text-sm">Delete</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $messages->links() }}
            </div>
        </form>
    @else
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-slate-800 mb-2">No Messages Yet</h3>
            <p class="text-slate-600 mb-6">Your inbox is empty. Start by sending a message or wait for someone to contact you.</p>
            <a href="{{ route('messages.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all shadow-lg">
                Send Your First Message
            </a>
        </div>
    @endif
</div>

<script>
function toggleSelectAll(master){
    const boxes = document.querySelectorAll('.message-checkbox');
    boxes.forEach(b=>{ b.checked = master.checked; });
    updateBulkDeleteState();
}
function updateBulkDeleteState(){
    const boxes = document.querySelectorAll('.message-checkbox');
    const anyChecked = Array.from(boxes).some(b=>b.checked);
    const btn = document.getElementById('bulk_delete_btn');
    if(btn){ btn.disabled = !anyChecked; }
}
</script>
@endsection
