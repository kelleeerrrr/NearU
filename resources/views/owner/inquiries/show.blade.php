@extends('layouts.owner')

@section('title', 'Conversation with ' . $student->name . ' - ' . $listing->street)

@push('styles')
<style>
.page { padding: 1rem; }

.header {
    font-weight: 800;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    border-radius: 12px;
    padding: 1rem;
    border: 1px solid #fbbf24;
    color: white;
    position: relative;
    overflow: hidden;
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
    color: white;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.header-details p {
    margin: 0.2rem 0;
    color: rgba(255,255,255,0.9);
    font-size: 0.9rem;
}

.listing-info {
    color: rgba(255,255,255,0.95) !important;
    font-weight: 500;
}

.listing-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-top: 8px;
    position: relative;
    height: 50px;
}

.listing-cover-small {
    width: 160px;
    height: 110px;
    border-radius: 6px;
    object-fit: cover;
    border: 2px solid #40dc1021;
    position: absolute;
    right: -250px;
    transform: translateY(-50%);
    top: 0.05%;
}

.profile-indicator {
    color: #666;
    font-size: 12px;
    font-style: italic;
    margin-top: 4px;
    opacity: 0.8;
}

.chat {
    display: flex;
    flex-direction: column;
    gap: .6rem;
    max-height: 65vh;
    overflow-y: auto;
    padding-bottom: 1rem;
    background: linear-gradient(135deg, 
        rgba(255,255,255,0.95) 0%, 
        rgba(251,191,36,0.05) 25%, 
        rgba(59,130,246,0.05) 50%, 
        rgba(45,125,79,0.05) 75%, 
        rgba(255,255,255,0.95) 100%);
    border-radius: 12px;
    padding: 1rem;
    border: 1px solid rgba(222,226,230,0.8);
    margin-bottom: 1rem;
    position: relative;
    backdrop-filter: blur(10px);
}

.msg {
    max-width: 70%;
    padding: .6rem .8rem;
    border-radius: 12px;
    font-size: .85rem;
    position: relative;
    transition: all 0.2s ease;
}

.left {
    background: linear-gradient(135deg, var(--green) 0%, #1e5a3a 100%);
    color: white;
    align-self: flex-start;
    box-shadow: 0 2px 8px rgba(45,125,79,0.2);
}

.right {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    align-self: flex-end;
    box-shadow: 0 2px 8px rgba(59,130,246,0.2);
}

.form {
    display: flex;
    gap: .5rem;
    margin-top: 1rem;
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    border-radius: 12px;
    padding: 1rem;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

input {
    flex: 1;
    padding: .6rem;
    border-radius: 10px;
    border: 1px solid #dee2e6;
    background: white;
    transition: all 0.2s ease;
}

input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}

button {
    background: linear-gradient(135deg, var(--green) 0%, #1e5a3a 100%);
    color: white;
    border: none;
    padding: .6rem 1rem;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(45,125,79,0.2);
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(45,125,79,0.3);
}
</style>
@endpush

@section('content')

<div class="page">

    <div style="text-align: left; margin-bottom: 10px; color: #666; font-size: 12px;">
        <a href="{{ route('owner.inquiries.index') }}" class="back-btn" style="background: #2D7D4F; color: white; padding: 8px 10px; border-radius: 8px; text-decoration: none; display: inline-block; font-weight: 600; transition: background 0.3s ease;">
            ← Back
        </a>
    </div>

    <div class="header">
        <div class="header-info">
            <div class="header-avatar">👤</div>
            <div class="header-details">
                <h3>{{ $student->name }}</h3>
                <p>Student Inquiry</p>
                <div class="listing-header">
                    <p class="listing-info">📍 {{ $listing->street }}</p>
                    @if($listing->coverImage)
                        <img src="{{ asset('storage/' . $listing->coverImage->path) }}" alt="Listing Cover" class="listing-cover-small">
                    @endif
                </div>
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
            <div style="text-align: center; margin-bottom: 10px; color: #666; font-size: 12px;">
                No messages yet.
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