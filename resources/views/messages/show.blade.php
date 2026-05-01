@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->name . ' - NearU')

@section('content')
<div class="wrap">
    @include('partials.navbar')

    <div class="screen active">

        <!-- Chat Area -->
        <div class="chat-page">

            <!-- Header -->
            <header class="chat-header" aria-label="Conversation Header">
                <div class="chat-user">
                    <div class="chat-avatar" aria-hidden="true">
                        {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                    </div>

                    <div class="chat-user-info">
                        <h2 class="chat-name">{{ $otherUser->name }}</h2>
                        <p class="chat-role">
                            {{ $otherUser->user_type === 'owner' ? 'Property Owner' : 'Student' }}
                        </p>
                    </div>
                </div>
            </header>

            <!-- Messages -->
            <main id="chatMsgs" class="chat-messages" aria-live="polite">
                @forelse($messages as $message)
                    <div class="message-row {{ $message->sender_id === auth()->id() ? 'mine' : 'theirs' }}">
                        <div class="chat-bubble">
                            {{ $message->message }}
                        </div>
                    </div>
                @empty
                    <div class="empty-chat">
                        No messages yet. Start the conversation 👋
                    </div>
                @endforelse
            </main>

            <!-- Input -->
            <footer class="chat-input-wrap">
                <form method="POST"
                      action="{{ route('messages.send', $otherUser->id) }}"
                      class="chat-form">
                    @csrf

                    <label for="message" class="sr-only">Type your message</label>

                    <input
                        id="message"
                        name="message"
                        type="text"
                        class="chat-input"
                        placeholder="Type a message..."
                        maxlength="500"
                        autocomplete="off"
                        required
                    >

                    <button type="submit" class="send-btn" aria-label="Send message">
                        Send
                    </button>
                </form>
            </footer>

        </div>
    </div>

    @include('partials.footer')
</div>

<style>
/* ---------- Layout ---------- */
.chat-page{
    display:flex;
    flex-direction:column;
    height:calc(100vh - 130px);
    background:#f8fafc;
}

/* ---------- Header ---------- */
.chat-header{
    position:sticky;
    top:0;
    z-index:10;
    background:#ffffff;
    border-bottom:1px solid #e5e7eb;
    padding:14px 18px;
}

.chat-user{
    display:flex;
    align-items:center;
    gap:12px;
}

.chat-avatar{
    width:46px;
    height:46px;
    border-radius:50%;
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    font-size:1rem;
    flex-shrink:0;
}

.chat-name{
    margin:0;
    font-size:1rem;
    font-weight:700;
    color:#111827;
}

.chat-role{
    margin:2px 0 0;
    font-size:.82rem;
    color:#6b7280;
}

/* ---------- Messages ---------- */
.chat-messages{
    flex:1;
    overflow-y:auto;
    padding:18px 14px 22px;
    display:flex;
    flex-direction:column;
    gap:10px;
    scroll-behavior:smooth;
}

.message-row{
    display:flex;
}

.message-row.mine{
    justify-content:flex-end;
}

.message-row.theirs{
    justify-content:flex-start;
}

.chat-bubble{
    max-width:75%;
    padding:11px 14px;
    border-radius:18px;
    font-size:.95rem;
    line-height:1.45;
    word-wrap:break-word;
    box-shadow:0 2px 6px rgba(0,0,0,.05);
}

/* My message */
.mine .chat-bubble{
    background:#2563eb;
    color:#fff;
    border-bottom-right-radius:6px;
}

/* Other message */
.theirs .chat-bubble{
    background:#ffffff;
    color:#111827;
    border:1px solid #e5e7eb;
    border-bottom-left-radius:6px;
}

/* Empty State */
.empty-chat{
    margin:auto;
    text-align:center;
    color:#6b7280;
    font-size:.95rem;
}

/* ---------- Input ---------- */
.chat-input-wrap{
    position:sticky;
    bottom:0;
    background:#fff;
    border-top:1px solid #e5e7eb;
    padding:12px;
}

.chat-form{
    display:flex;
    gap:10px;
    align-items:center;
}

.chat-input{
    flex:1;
    height:46px;
    border:1px solid #d1d5db;
    border-radius:24px;
    padding:0 16px;
    font-size:.95rem;
    outline:none;
    transition:.2s;
}

.chat-input:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.12);
}

.send-btn{
    border:none;
    background:#2563eb;
    color:#fff;
    padding:0 18px;
    height:46px;
    border-radius:24px;
    font-weight:600;
    cursor:pointer;
    transition:.2s;
}

.send-btn:hover{
    background:#1d4ed8;
}

.send-btn:active{
    transform:scale(.97);
}

/* Accessibility */
.sr-only{
    position:absolute;
    width:1px;
    height:1px;
    padding:0;
    margin:-1px;
    overflow:hidden;
    clip:rect(0,0,0,0);
    border:0;
}

/* Mobile */
@media (max-width:768px){
    .chat-bubble{
        max-width:85%;
    }

    .chat-page{
        height:calc(100vh - 110px);
    }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatMsgs = document.getElementById('chatMsgs');
    chatMsgs.scrollTop = chatMsgs.scrollHeight;

    const input = document.getElementById('message');
    if(input){
        input.focus();
    }
});
</script>
@endpush