@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->name . ' - NearU')

@push('styles')
<style>
.page { padding: 1rem; }

.header {
    position: fixed;
    top: 100px;
    left: 50%;
    transform: translateX(-50%);
    width: calc(100% - 32px);
    max-width: 398px;
    font-weight: 800;
    background: var(--card);
    border-radius: 18px;
    padding: 0.75rem;
    border: 1.5px solid var(--border);
    z-index: 100;
    box-shadow: var(--sh);
}

.header-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.header-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--green);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: white;
}

.header-details h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
}

.header-details p {
    margin: 0.1rem 0;
    color: #666;
    font-size: 0.85rem;
}

.listing-info {
    color: var(--green);
    font-weight: 500;
}

.chat-page {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 140px);
    padding-bottom: 0;
}

.chat-container {
    position: fixed;
    top: 190px;
    left: 50%;
    transform: translateX(-50%);
    width: calc(100% - 32px);
    max-width: 398px;
    bottom: 180px;
    display: flex;
    flex-direction: column;
}

.chat {
    flex: 1;
    overflow-y: auto;
    padding: 20px 14px 90px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    scroll-behavior: smooth;
}

.msg {
    max-width: 70%;
    padding: .6rem .8rem;
    border-radius: 12px;
    font-size: .85rem;
}

.left {
    background: #E8F7EE;
    align-self: flex-start;
}

.right {
    background: var(--green);
    color: white;
    align-self: flex-end;
}

input {
    flex: 1;
    padding: .6rem;
    border-radius: 10px;
    border: 1px solid #ccc;
}

button {
    background: var(--green);
    color: white;
    border: none;
    padding: .6rem 1rem;
    border-radius: 10px;
    cursor: pointer;
}

button:hover {
    background: var(--green-dk);
}

.message-input-container {
    position: fixed;
    bottom: 105px;

    left: 50%;
    transform: translateX(-50%);
    width: calc(100% - 32px);
    max-width: 398px;
    box-sizing: border-box;

    display: flex;
    gap: 10px;

    background: var(--card);
    border-radius: 18px;
    padding: 12px 16px;
    border: 1.5px solid var(--border);
    box-shadow: var(--sh);

    z-index: 1500;
}

.message-input-wrapper {
    display: flex;
    gap: 10px;
    flex: 1;
}

.message-input-field {
    flex: 1;
    padding: .6rem .8rem;
    border-radius: 12px;
    border: 1.5px solid var(--border);
    background: var(--surface);
    font-family: 'DM Sans', sans-serif;
    font-size: .85rem;
    outline: none;
    transition: border-color var(--transition), box-shadow var(--transition);
}

.message-input-field:focus {
    border-color: var(--green);
    box-shadow: 0 0 0 3px rgba(45, 125, 79, .12);
}

.send-message-btn {
    background: var(--green);
    color: white;
    border: none;
    padding: .6rem 1rem;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
}

.send-message-btn:hover {
    background: var(--green-dk);
}
</style>
@endpush

@section('content')
<div class="wrap">
    @include('partials.navbar')

    <div class="screen active">

        <div class="page">
            <div class="header">
                <div class="header-info">
                    <div class="header-avatar">
    @if($otherUser->profile_photo_path)
        <img src="{{ asset('storage/' . $otherUser->profile_photo_path) }}" alt="{{ $otherUser->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
    @else
        {{ strtoupper(substr($otherUser->name, 0, 1)) }}
    @endif
</div>
                    <div class="header-details">
                        <h3>{{ $otherUser->name }}</h3>
                        <p>{{ $otherUser->user_type === 'owner' ? 'Property Owner' : 'Student' }}</p>
                        <p class="listing-info">📍 {{ $listing->street }}</p>
                    </div>
                </div>
            </div>

            <div class="chat-container">
                <div class="chat">

                    @forelse($messages as $msg)
                        <div class="msg {{ $msg->sender_id == auth()->id() ? 'right' : 'left' }}">
                            {{ $msg->message }}
                            <div style="font-size: 0.7rem; opacity: 0.7; margin-top: 0.3rem;">
                                {{ $msg->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 2rem; color: #666;">
                            No messages yet. Start the conversation!
                        </div>
                    @endforelse

                </div>
            </div>

        </div>
    </div>
    </div>

    <form id="messageForm" method="POST" action="{{ route('messages.send', [$listing->id, $otherUser->id]) }}">
        @csrf
        <div class="message-input-container">
            <div class="message-input-wrapper">
                <input type="text" id="messageInput" name="message" placeholder="Type a message..." required maxlength="500">
                <button type="submit" class="send-message-btn">Send Message</button>
            </div>
        </div>
    </form>

    @include('partials.footer')
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatMsgs = document.querySelector('.chat');
    if (chatMsgs) {
        chatMsgs.scrollTop = chatMsgs.scrollHeight;
    }

    const input = document.getElementById('messageInput');
    const form = document.getElementById('messageForm');
    
    if(input){
        input.focus();
    }

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        formData.append('listing_id', '{{ $listing->id }}');
        formData.append('receiver_id', '{{ $otherUser->id }}');
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`Server responded with ${response.status}: ${text}`);
                });
            }
            return response.text();
        })
        .then(html => {
            // Store message value before clearing input
            const messageText = input.value;
            input.value = '';
            
            // Add new message to chat dynamically
            const chatMsgs = document.querySelector('.chat');
            
            // Remove "No messages yet" text if it exists
            const noMessagesElement = chatMsgs.querySelector('div[style*="text-align: center"]');
            if (noMessagesElement && noMessagesElement.textContent.includes('No messages yet')) {
                noMessagesElement.remove();
            }
            
            const newMessage = document.createElement('div');
            newMessage.className = 'msg right';
            newMessage.innerHTML = `
                ${messageText}
                <div style="font-size: 0.7rem; opacity: 0.7; margin-top: 0.3rem;">
                    just now
                </div>
            `;
            chatMsgs.appendChild(newMessage);
            
            // Scroll to bottom
            chatMsgs.scrollTop = chatMsgs.scrollHeight;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to send message: ' + error.message);
        });
        return false;
    });
});
</script>
@endpush