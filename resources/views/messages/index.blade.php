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
        @foreach($conversations as $userId => $messages)
        @php
          $otherUser = $messages->first()->sender_id == auth()->id()
            ? $messages->first()->receiver
            : $messages->first()->sender;
          $lastMessage = $messages->last();
        @endphp
        <div class="conv-i" onclick="window.location.href='{{ route('messages.show', $otherUser->id) }}'" style="display:flex; align-items:center; padding:1rem; cursor:pointer; border-radius:8px; transition: background-color 0.2s;"
             onmouseover="this.style.backgroundColor='#f0f0f0'" onmouseout="this.style.backgroundColor='transparent'">
          
          <!-- Avatar: replace with user profile photo if available -->
          <div class="conv-av" style="width:50px; height:50px; border-radius:50%; background-color: #ccc; display:flex; align-items:center; justify-content:center; font-size:1.2rem; margin-right:1rem;">
            {{ substr($otherUser->name, 0, 1) }}
          </div>

          <div class="conv-info" style="flex:1;">
            <div class="conv-n" style="font-weight:600;">{{ $otherUser->name }}</div>
            <div class="conv-p" style="color: #555;">{{ Str::limit($lastMessage->message, 50) }}</div>
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