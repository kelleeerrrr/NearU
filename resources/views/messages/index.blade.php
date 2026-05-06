@extends('layouts.app')

@section('title', 'Messages - NearU')

@push('styles')
<style>
.filter-chips {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
}

.filter-chips::-webkit-scrollbar {
    display: none;
}

.chip {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    border: 1.5px solid var(--border);
    background: var(--card);
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s ease;
}

.chip.active {
    background: var(--green);
    color: white;
    border-color: var(--green);
}

.chip:hover:not(.active) {
    border-color: var(--green);
    transform: translateY(-1px);
}

.conv-i {
    display: flex;
    align-items: center;
    padding: 1rem;
    cursor: pointer;
    border-radius: 12px;
    transition: all 0.2s ease;
    border: 1px solid #e5e7eb;
    margin-bottom: 0.5rem;
    background: #fff;
}

.conv-i:hover {
    background: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border-color: var(--green);
}

.conv-i.unread {
    border-left: 4px solid var(--green);
    background: linear-gradient(to right, rgba(45,125,79,0.05), transparent);
    border-color: var(--green);
}

.conv-av {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-right: 1rem;
    overflow: hidden;
    flex-shrink: 0;
}

.conv-info {
    flex: 1;
    min-width: 0;
}

.conv-n {
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
}

.conv-p {
    color: var(--t2);
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.conv-time {
    font-size: 0.75rem;
    color: var(--t2);
    text-align: right;
    flex-shrink: 0;
    margin-left: 0.5rem;
}

.unread-badge {
    background: var(--green);
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 700;
    min-width: 20px;
    text-align: center;
}

.message-preview {
    font-weight: 400;
    color: var(--t2);
}

.sender-name {
    font-weight: 500;
    color: var(--t2);
}

.message-preview.unread {
    font-weight: 600;
    color: var(--t1);
}

.sender-name.unread {
    font-weight: 700;
    color: var(--t1);
}
</style>
@endpush

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <h2>💬 Messages</h2>

      @if($conversations->isEmpty())
      <div class="empty" style="text-align:center; padding:3rem 1rem;">
        <div class="empty-ic" style="font-size:4rem; margin-bottom:1rem;">💬</div>
        <h3 style="color: var(--t1); margin-bottom:0.5rem;">No messages yet</h3>
        <p style="color: var(--t2); margin-bottom:1.5rem;">Start a conversation by contacting dorm owners!</p>
        <a href="{{ route('student.home') }}" style="display: inline-block; background: var(--green); color: white; padding: 0.8rem 1.5rem; border-radius: 25px; text-decoration: none; font-weight: 600; transition: all 0.2s ease;">
          Browse Dorms →
        </a>
      </div>
      @else
        <!-- Filter Chips -->
        <div class="filter-chips">
            <div class="chip active" onclick="filterMessages('all', event)">
                All
            </div>
            <div class="chip" onclick="filterMessages('unread', event)">
                Unread
            </div>
            <div class="chip" onclick="filterMessages('read', event)">
                Read
            </div>
        </div>

        <!-- Conversations -->
        <div id="conversationsContainer">
          @foreach($conversations as $key => $messages)
          @php
            $first = $messages->first();
            $otherUser = $first->sender_id == auth()->id() ? $first->receiver : $first->sender;
            $listing = $first->listing ?? $first->legacyListing ?? $first->resolvedListing;
            $lastMessage = $messages->sortByDesc('created_at')->first();
            $unread = $messages->where('is_read', false)->where('receiver_id', auth()->id())->count();
            $isUnread = $lastMessage && !$lastMessage->is_read && $lastMessage->receiver_id == auth()->id();
          @endphp

          @if(!$listing || !$otherUser)
              @continue
          @endif
          <div class="conv-i {{ $isUnread ? 'unread' : '' }}" 
               data-read-status="{{ $isUnread ? 'unread' : 'read' }}"
               onclick="window.location.href='{{ route('messages.show', [$listing->id, $otherUser->id]) }}'">
            
            <!-- Avatar: listing image -->
            <div class="conv-av">
              @if($listing && $listing->photos)
                @php $photos = is_array($listing->photos) ? $listing->photos : json_decode($listing->photos, true); @endphp
                @if(count($photos ?? []))
                  <img src="{{ asset('storage/' . $photos[0]) }}" alt="Listing" style="width:100%; height:100%; object-fit:cover;">
                @else
                  📍
                @endif
              @else
                📍
              @endif
            </div>

            <div class="conv-info">
              <div class="conv-n">
                {{ $listing ? $listing->street : 'Unknown Listing' }}
                @if($unread > 0)
                  <span class="unread-badge">{{ $unread }}</span>
                @endif
              </div>
              <div class="conv-p">
                @php
                  $isLastMessageFromMe = $lastMessage && $lastMessage->sender_id == auth()->id();
                  $senderName = $isLastMessageFromMe ? 'You' : $otherUser->name;
                @endphp
                <span class="sender-name {{ $isUnread ? 'unread' : '' }}">{{ $senderName }}:</span>
                <span class="message-preview {{ $isUnread ? 'unread' : '' }}"> {{ Str::limit($lastMessage->message ?? 'No message', 45) }}</span>
              </div>
            </div>

            <div class="conv-time">
              {{ $lastMessage->created_at->diffForHumans() }}
            </div>
          </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  @include('partials.footer')
</div>

<script>
function filterMessages(status, event) {
    // Update active chip
    document.querySelectorAll('.chip').forEach(chip => chip.classList.remove('active'));
    event.target.classList.add('active');

    // Filter conversations
    document.querySelectorAll('.conv-i').forEach(conv => {
        const readStatus = conv.dataset.readStatus;
        
        if (status === 'all') {
            conv.style.display = 'flex';
        } else if (status === 'unread' && readStatus === 'unread') {
            conv.style.display = 'flex';
        } else if (status === 'read' && readStatus === 'read') {
            conv.style.display = 'flex';
        } else {
            conv.style.display = 'none';
        }
    });
}
</script>
@endsection
