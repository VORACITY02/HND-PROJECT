@extends('layouts.app')

@section('content')
<!-- Back to Messages Button -->
<div class="mb-6">
    <a href="{{ route('messages.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Inbox
    </a>
</div>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Compose Message</h1>
    <p class="text-gray-600">Send a message or broadcast</p>
</div>

@if ($errors->any())
    <div class="mb-6 p-4 bg-red-100 border border-red-300 rounded text-red-700">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-lg shadow border p-6">
    <form method="POST" action="{{ route('messages.store') }}">
        @csrf

        <!-- Recipient Type Selection -->
        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Message Type</label>
            <select name="recipient_type" id="recipient_type" 
                    class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required onchange="toggleRecipientField()">
                <option value="">Select recipient type...</option>
                <option value="individual">Individual User</option>
                
                @if(auth()->user()->role === 'staff')
                    <option value="all_users">Broadcast to All Users</option>
                @endif
                
                @if(auth()->user()->role === 'admin')
                    <option value="all_users">Broadcast to All Users</option>
                    <option value="all_staff">Broadcast to All Staff</option>
                    <option value="all_admins">Broadcast to All Admins</option>
                @endif
            </select>
        </div>

        <!-- Individual Recipient Selection -->
        <div class="mb-6" id="recipient_field" style="{{ request('reply_to') ? 'display: block;' : 'display: none;' }}">
            <label class="block text-gray-700 font-medium mb-2">Select Recipient</label>
            <select name="receiver_id" id="receiver_id"
                    class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select a recipient...</option>
                @foreach($recipients as $recipient)
                    <option value="{{ $recipient->id }}" {{ request('reply_to') == $recipient->id ? 'selected' : '' }}>
                        {{ $recipient->name }} ({{ ucfirst($recipient->role) }}) - {{ $recipient->email }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Subject -->
        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Subject</label>
            <input type="text" name="subject" value="{{ old('subject', request('subject')) }}"
                   class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   required maxlength="255" 
                   placeholder="e.g., Meeting Request, Assignment Help, Internship Question">
            <p class="mt-1 text-sm text-gray-500">
                💡 <strong>What is Subject?</strong> A brief title for your message (like an email subject line). 
                Example: "Question about Internship" or "Weekly Update"
            </p>
        </div>

        <!-- Message -->
        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Message</label>
            <textarea name="message" rows="8"
                      class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      required>{{ old('message') }}</textarea>
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 font-medium">
                Send Message
            </button>
            <a href="{{ route('messages.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-medium">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    function toggleRecipientField() {
        const recipientType = document.getElementById('recipient_type').value;
        const recipientField = document.getElementById('recipient_field');
        const receiverIdField = document.getElementById('receiver_id');
        
        if (recipientType === 'individual') {
            recipientField.style.display = 'block';
            receiverIdField.required = true;
        } else {
            recipientField.style.display = 'none';
            receiverIdField.required = false;
            receiverIdField.value = '';
        }
    }

    // Initialize on page load
    toggleRecipientField();
</script>
@endsection
