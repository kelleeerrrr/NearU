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
    border-radius:16px;
    padding:.9rem;
    margin-bottom:.8rem;
    cursor:pointer;
    transition:.2s;
    display:block;
    text-decoration:none;
    color:inherit;
    position:relative;
    overflow:hidden;
}

/* Color variations based on reply status */
.listing-card.needs-reply {
    background: linear-gradient(135deg, var(--green) 0%, #1e5a3a 100%);
    border:1px solid var(--green);
}

.listing-card.no-reply-needed {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border:1px solid #3b82f6;
}

.listing-card:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 24px rgba(0,0,0,0.15);
}

/* Text styling for colored cards */
.listing-card .listing-title{
    color: #fff !important;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.listing-card .listing-meta{
    color: rgba(255,255,255,0.9) !important;
}

.listing-card .listing-message{
    background: rgba(255,255,255,0.15);
    border-left: 3px solid rgba(255,255,255,0.5);
    color: rgba(255,255,255,0.95);
}

.listing-card .bold-text{
    color: #fff !important;
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
  background: linear-gradient(135deg, #fef3c7 0%, #fefce8 50%, #fff9e6 100%);
  border: 2px solid var(--gold);
  border-radius: 20px;
  padding: 1.5rem;
  margin: 0 1.2rem 0.5rem;
  text-align: center;
  box-shadow: 0 8px 25px rgba(242,183,5,0.15);
  position: relative;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.verification-banner::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200px;
  height: 200px;
  background: radial-gradient(circle, rgba(242,183,5,0.1) 0%, transparent 70%);
  animation: float 6s ease-in-out infinite;
}

.verification-banner::after {
  content: '';
  position: absolute;
  bottom: -30%;
  left: -30%;
  width: 150px;
  height: 150px;
  background: radial-gradient(circle, rgba(45,125,79,0.05) 0%, transparent 70%);
  animation: float 8s ease-in-out infinite reverse;
}

.verification-banner:hover {
  transform: translateY(-4px) scale(1.02);
  box-shadow: 0 12px 35px rgba(242,183,5,0.25);
  border-color: #f59e0b;
}

@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(180deg); }
}

.verification-icon {
  font-size: 2.5rem;
  margin-bottom: 0.5rem;
  display: block;
  animation: bounce 2s infinite;
  position: relative;
  z-index: 2;
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
  40% { transform: translateY(-10px); }
  60% { transform: translateY(-5px); }
}

.verification-banner h4 {
  color: #5a4300;
  font-size: 1.2rem;
  font-weight: 800;
  margin-bottom: 0.5rem;
  font-family: 'Syne', sans-serif;
  position: relative;
  z-index: 2;
}

.verification-banner p {
  color: #6b5900;
  font-size: 0.9rem;
  margin-bottom: 1rem;
  line-height: 1.5;
  position: relative;
  z-index: 2;
}

.verification-link {
  background: linear-gradient(135deg, var(--gold), #f59e0b);
  color: #5a4300;
  text-decoration: none;
  padding: 0.75rem 2rem;
  border-radius: 15px;
  font-weight: 800;
  font-size: 0.9rem;
  display: inline-block;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(242,183,5,0.2);
  position: relative;
  z-index: 2;
  font-family: 'DM Sans', sans-serif;
}

.verification-link:hover {
  transform: translateY(-2px) scale(1.05);
  box-shadow: 0 6px 20px rgba(242,183,5,0.3);
  background: linear-gradient(135deg, #f59e0b, #d4a200);
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
  background: rgba(255,255,255,0.9);
  color: #d32f2f;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 700;
  margin-top: 8px;
  display: inline-block;
  border: 1px solid rgba(255,255,255,0.5);
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.you-indicator {
  color: rgba(255,255,255,0.9);
  font-weight: 600;
  font-size: 11px;
  margin-left: 8px;
  opacity: 0.9;
}

.unread-inquiry {
  border-left: 4px solid #fff;
  background: linear-gradient(to right, rgba(255,255,255,0.1), transparent);
}

.unread-indicator {
  color: #fff;
  font-size: 8px;
  margin-left: 8px;
  animation: pulse 2s infinite;
  text-shadow: 0 0 4px rgba(0,0,0,0.3);
}

.unread-badge {
  background: rgba(255,255,255,0.9);
  color: #141F14;
  padding: 0.3rem 0.6rem;
  border-radius: 12px;
  font-size: 0.7rem;
  font-weight: 700;
  min-width: 24px;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.unread-preview {
  font-weight: 600;
  color: rgba(255,255,255,0.95);
}

.read-preview {
  font-weight: 400;
  color: rgba(255,255,255,0.85);
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
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

        <div class="chip active" onclick="filterInquiries('all', event)">
            All
        </div>

        <div class="chip" onclick="filterInquiries('unread', event)">
            Unread
        </div>

        <div class="chip" onclick="filterInquiries('read', event)">
            Read
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

            @php
            $hasUnread = $messages->where('is_read', false)->where('receiver_id', auth()->id())->count() > 0;
        @endphp
            <a href="{{ route('owner.inquiries.show', [
                    'listingId' => $listing->id,
                    'userId' => $student->id
                ]) }}"
               class="listing-card {{ $hasUnread ? 'unread-inquiry' : '' }} {{ !$ownerReplied ? 'needs-reply' : 'no-reply-needed' }}"
               data-listing="{{ $listing->id }}"
               data-read-status="{{ $hasUnread ? 'unread' : 'read' }}">

                <div class="inquiry-header">
                    <div class="student-info">
                        <div class="listing-title {{ $ownerReplied ? '' : 'bold-text' }}">
                            👤 {{ $student->name ?? 'Unknown User' }}
                            @if($hasUnread)
                                <span class="unread-indicator">●</span>
                            @endif
                        </div>
                        <div class="listing-meta">
                            📍 {{ $listing->street ?? 'Listing #'.$listing->id }}
                        </div>
                    </div>
                    @if($hasUnread)
                        <div class="unread-badge">
                            {{ $messages->where('is_read', false)->where('receiver_id', auth()->id())->count() }}
                        </div>
                    @endif
                </div>

                <div class="listing-message {{ $ownerReplied ? '' : 'bold-text' }}">
                    @if($ownerReplied)
                        <span class="you-indicator">You: {{ \Illuminate\Support\Str::limit($ownerLastMessage, 40) }}</span>
                    @else
                        <span class="{{ $hasUnread ? 'unread-preview' : 'read-preview' }}">
                            {{ \Illuminate\Support\Str::limit($lastMessage->message ?? '', 70) }}
                        </span>
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
                  <span class="verification-icon">🔐</span>
                  <h4>Verification Required</h4>
                  <p>You need to complete verification to access inquiries and manage your listing messages.</p>
                  <a href="{{ route('owner.verification.form') }}" class="verification-link">
                    🎯 Complete Verification Now
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
function filterInquiries(status, event) {
    // Update active chip
    document.querySelectorAll('.chip').forEach(chip => chip.classList.remove('active'));
    event.target.classList.add('active');

    // Filter inquiries by read status
    document.querySelectorAll('.listing-card').forEach(card => {
        const readStatus = card.dataset.readStatus;
        
        if (status === 'all') {
            card.style.display = 'block';
        } else if (status === 'unread' && readStatus === 'unread') {
            card.style.display = 'block';
        } else if (status === 'read' && readStatus === 'read') {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function filterListing(id, event){
    // Update active chip
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