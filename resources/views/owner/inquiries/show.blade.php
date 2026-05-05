@extends('layouts.owner')

@section('title', 'Conversation with ' . $student->name . ' - ' . $listing->street)

@push('styles')
<style>
.page { padding: 1rem; }

.header {
    font-weight: 800;
    margin-bottom: 1rem;
    background: #fff;
    border-radius: 12px;
    padding: 1rem;
    border: 1px solid #ddd;
}

.header-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.header-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.header-details h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.header-details p {
    margin: 0.2rem 0;
    color: #666;
    font-size: 0.9rem;
}

.listing-info {
    color: #2D7D4F !important;
    font-weight: 500;
}

.chat {
    display: flex;
    flex-direction: column;
    gap: .6rem;
    max-height: 65vh;
    overflow-y: auto;
    padding-bottom: 1rem;
    background: #fff;
    border-radius: 12px;
    padding: 1rem;
    border: 1px solid #ddd;
    margin-bottom: 1rem;
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
    background: #2D7D4F;
    color: white;
    align-self: flex-end;
}

.form {
    display: flex;
    gap: .5rem;
    margin-top: 1rem;
    background: #fff;
    border-radius: 12px;
    padding: 1rem;
    border: 1px solid #ddd;
}

input {
    flex: 1;
    padding: .6rem;
    border-radius: 10px;
    border: 1px solid #ccc;
}

button {
    background: #2D7D4F;
    color: white;
    border: none;
    padding: .6rem 1rem;
    border-radius: 10px;
    cursor: pointer;
}
</style>
@endpush

@section('content')

<div class="page">

    <div class="header">
        <div class="header-info">
            <div class="header-avatar">👤</div>
            <div class="header-details">
                <h3>{{ $student->name }}</h3>
                <p>Student Inquiry</p>
                <p class="listing-info">📍 {{ $listing->street }}</p>
            </div>
        </div>
    </div>

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

    <form method="POST" class="form"
          action="{{ route('owner.inquiries.reply', ['listingId' => $listing->id, 'userId' => $student->id]) }}">

        @csrf

        <input type="text" name="message" placeholder="Type your reply..." required maxlength="500">

        <button type="submit">Send Reply</button>

    </form>

</div>

@endsection