@extends('layouts.app')

@section('title', 'Messages - NearU')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <h2>💬 Messages</h2>

      @if($conversations->isEmpty())
      <div class="empty" style="text-align:center; padding:2rem;">
        <div class="empty-ic" style="font-size:3rem;">💬</div>
        <p>No messages yet</p>
        <p>Start a conversation by contacting dorm owners!</p>
      </div>
      @else
        @foreach($conversations as $key => $messages)
        @php
          $first = $messages->first();
          $otherUser = $first->sender_id == auth()->id() ? $first->receiver : $first->sender;
          $listing = $first->resolvedListing;
          $lastMessage = $messages->last();
          $unread = $messages->where('is_read', false)->count();
        @endphp

        @if(!$listing)
            @continue
        @endif
        <div class="conv-i" onclick="window.location.href='{{ route('messages.show', [$listing->id, $otherUser->id]) }}'" style="display:flex; align-items:center; padding:1rem; cursor:pointer; border-radius:8px; transition: background-color 0.2s;"
             onmouseover="this.style.backgroundColor='#f0f0f0'" onmouseout="this.style.backgroundColor='transparent'">
          
          <!-- Avatar: listing image -->
          <div class="conv-av" style="width:50px; height:50px; border-radius:8px; background-color: #ccc; display:flex; align-items:center; justify-content:center; font-size:1.2rem; margin-right:1rem; overflow:hidden;">
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

          <div class="conv-info" style="flex:1;">
            <div class="conv-n" style="font-weight:600; display:flex; align-items:center; gap:0.5rem;">
              {{ $listing ? $listing->street : 'Unknown Listing' }}
              @if($unread > 0)
                <span style="background:#2D7D4F; color:white; padding:0.2rem 0.5rem; border-radius:10px; font-size:0.7rem; font-weight:600;">{{ $unread }}</span>
              @endif
            </div>
            <div class="conv-p" style="color: #555; font-size:0.9rem;">{{ $otherUser->name }} • {{ Str::limit($lastMessage->message, 40) }}</div>
          </div>

          <div style="font-size:.7rem; color:var(--t2); text-align:right;">
            {{ $lastMessage->created_at->diffForHumans() }}
          </div>
        </div>
        @endforeach
      @endif
    </div>
  </div>

  @include('partials.footer')
</div>
@endsection