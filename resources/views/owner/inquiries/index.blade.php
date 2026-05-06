@extends('layouts.owner')

@section('title', 'Owner Inquiries')

@push('styles')
<style>
.page{
    padding:1rem;
}

.page-title{
    font-family:'Syne';
    font-size:1.1rem;
    font-weight:800;
    margin-bottom:.8rem;
}

.chip-bar{
    display:flex;
    gap:.5rem;
    overflow-x:auto;
    padding-bottom:.8rem;
}

.chip-bar::-webkit-scrollbar{display:none;}

.chip{
    padding:.45rem .9rem;
    border-radius:50px;
    border:1.5px solid #D6E8DC;
    background:#fff;
    font-size:.75rem;
    font-weight:700;
    cursor:pointer;
    white-space:nowrap;
    color:#141F14;
}

.chip.active{
    background:#2D7D4F;
    color:#fff;
    border-color:#2D7D4F;
}

.listing-card{
    background:#fff;
    border:1.5px solid #D6E8DC;
    border-radius:16px;
    padding:.9rem;
    margin-bottom:.8rem;
    box-shadow:0 2px 10px rgba(45,125,79,.06);
    cursor:pointer;
    transition:.2s;
    display:block;
    text-decoration:none;
    color:inherit;
}

.listing-card:hover{
    transform:translateY(-2px);
}

.listing-title{
    font-weight:800;
    font-size:.95rem;
    color:#141F14;
}

.listing-meta{
    font-size:.75rem;
    color:#5E6E5E;
    margin-top:.2rem;
}

.badge{
    display:inline-block;
    margin-top:.4rem;
    padding:.2rem .6rem;
    border-radius:50px;
    font-size:.7rem;
    font-weight:800;
}

.badge.unread{
    background:#FFF0F2;
    color:#C8102E;
}

.badge.read{
    background:#E8F7EE;
    color:#2D7D4F;
}

.empty{
    text-align:center;
    padding:2rem 1rem;
    color:#5E6E5E;
}

/* VERIFICATION BANNER */
.verification-banner {
  background: linear-gradient(135deg, #FFF3CD, #FFE089);
  border: 2px solid #F2B705;
  border-radius: 12px;
  padding: 15px;
  margin-bottom: 1rem;
  text-align: center;
  box-shadow: 0 4px 15px rgba(242,183,5,0.2);
}

.verification-banner h4 {
  color: #856404;
  font-size: 16px;
  font-weight: 800;
  margin-bottom: 8px;
}

.verification-banner p {
  color: #856404;
  font-size: 14px;
  margin-bottom: 10px;
}

.verification-link {
  background: linear-gradient(135deg, #2D7D4F, #1e5a3a);
  color: white;
  text-decoration: none;
  padding: 8px 20px;
  border-radius: 6px;
  font-weight: 700;
  font-size: 13px;
  display: inline-block;
  transition: all 0.3s ease;
}

.verification-link:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(45,125,79,0.4);
}

.listing-card.disabled {
  opacity: 0.6;
  pointer-events: none;
  cursor: not-allowed;
}

.inquiry-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 12px;
}

.student-info {
  flex: 1;
  margin-right: 15px;
}

.listing-cover {
  flex-shrink: 0;
}

.cover-photo {
  width: 60px;
  height: 60px;
  border-radius: 8px;
  object-fit: cover;
  border: 2px solid #e9ecef;
}

.bold-text {
  font-weight: 700;
  color: #2D7D4F;
}

.listing-message {
  margin-top: 8px;
  padding: 8px 12px;
  background: #f8f9fa;
  border-radius: 8px;
  border-left: 3px solid #2D7D4F;
}

.needs-reply-flag {
  background: linear-gradient(135deg, #fee, #fca5a5);
  color: #d32f2f;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 700;
  margin-top: 8px;
  display: inline-block;
  border: 1px solid #fca5a5;
}

.you-indicator {
  color: #2D7D4F;
  font-weight: 600;
  font-size: 11px;
  margin-left: 8px;
  opacity: 0.8;
}
</style>
@endpush

@section('content')

@php
    $verificationStatus = auth()->user()->verification_status ?? 'not_verified';
@endphp

<div class="page">

    <div class="page-title">💬 Listing Inquiries</div>

    <!-- FILTER CHIPS -->
    <div class="chip-bar">

        <div class="chip active" onclick="filterListing('all', event)">
            All
        </div>

        @foreach($grouped as $key => $messages)

            @php
                $first = $messages->first();
                $listing = $first?->listing ?? $first?->legacyListing;
                $unread = $messages->where('is_read', false)->count();
            @endphp

            @if($listing)
                <div class="chip" onclick="filterListing('{{ $listing->id }}', event)">
                    {{ $listing->street ?? 'Listing '.$listing->id }}
                    @if($unread > 0)
                        ({{ $unread }})
                    @endif
                </div>
            @endif

        @endforeach

    </div>

    <!-- LISTINGS -->
    <div id="listingContainer">

        @forelse($grouped as $key => $messages)

            @php
                $first = $messages->first();

                $listing = $first?->listing ?? $first?->legacyListing;
                if (!$listing) continue;

                $student = $first->sender_id === auth()->id() ? $first->receiver : $first->sender;
                if (!$student) continue;

                $lastMessage = $messages->sortByDesc('created_at')->first();
                $ownerReplied = $lastMessage->sender_id === auth()->id();
                $ownerLastMessage = $ownerReplied ? $lastMessage->message : null;
            @endphp

            <a href="{{ route('owner.inquiries.show', [
                    'listingId' => $listing->id,
                    'userId' => $student->id
                ]) }}"
               class="listing-card"
               data-listing="{{ $listing->id }}">

                <div class="inquiry-header">
                    <div class="student-info">
                        <div class="listing-title {{ $ownerReplied ? '' : 'bold-text' }}">
                            👤 {{ $student->name ?? 'Unknown User' }}
                        </div>
                        <div class="listing-meta">
                            📍 {{ $listing->street ?? 'Listing #'.$listing->id }}
                        </div>
                    </div>
                </div>

                <div class="listing-message {{ $ownerReplied ? '' : 'bold-text' }}">
                    @if($ownerReplied)
                        <span class="you-indicator">You: {{ \Illuminate\Support\Str::limit($ownerLastMessage, 30) }}</span>
                    @else
                        {{ \Illuminate\Support\Str::limit($lastMessage->message ?? '', 60) }}
                    @endif
                </div>

                @if(!$ownerReplied)
                    <div class="needs-reply-flag">
                        ⏳ Needs Reply
                    </div>
                @endif

            </a>

        @empty

            @if($verificationStatus === 'not_verified' || $verificationStatus === 'under_review')
                <div class="verification-banner">
                  <h4>🔐 Verification Required</h4>
                  <p>You need to complete verification to access inquiries and manage your listing messages.</p>
                  <a href="{{ route('owner.verification.form') }}" class="verification-link">
                    Complete Verification
                  </a>
                </div>
            @else
                <div class="empty">
                    <div style="font-size:2rem;">📭</div>
                    <p>No inquiries yet</p>
                </div>
            @endif

        @endforelse

    </div>

</div>

<script>
function filterListing(id, event){

    document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
    event.target.classList.add('active');

    document.querySelectorAll('.listing-card').forEach(card => {

        if(id === 'all'){
            card.style.display = 'block';
        } else {
            card.style.display = (card.dataset.listing == id) ? 'block' : 'none';
        }

    });
}
</script>

@endsection