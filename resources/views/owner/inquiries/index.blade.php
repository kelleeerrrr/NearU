@extends('layouts.owner')

@section('title', 'Owner Inquiries')

@push('styles')
<style>
/* PAGE WRAPPER */
.page{
    padding:1rem;
}

/* HEADER */
.page-title{
    font-family:'Syne';
    font-size:1.1rem;
    font-weight:800;
    margin-bottom:.8rem;
}

/* CHIPS FILTER */
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

/* LISTING CARD */
.listing-card{
    background:#fff;
    border:1.5px solid #D6E8DC;
    border-radius:16px;
    padding:.9rem;
    margin-bottom:.8rem;
    box-shadow:0 2px 10px rgba(45,125,79,.06);
    cursor:pointer;
    transition:.2s;
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

/* EMPTY */
.empty{
    text-align:center;
    padding:2rem 1rem;
    color:#5E6E5E;
}
</style>
@endpush

@section('content')

<div class="page">

    <div class="page-title">💬 Listing Inquiries</div>

    <!-- FILTER CHIPS -->
    <div class="chip-bar">

        <div class="chip active" onclick="filterListing('all')">
            All
        </div>

        @foreach($grouped as $listingId => $messages)
            @php
                $listing = $messages->first()->listing;
                $unread = $messages->where('is_read', false)->count();
            @endphp

            <div class="chip" onclick="filterListing('{{ $listingId }}')">
                {{ $listing->title ?? 'Listing '.$listingId }}
                @if($unread > 0)
                    ({{ $unread }})
                @endif
            </div>
        @endforeach

    </div>

    <!-- LISTINGS -->
    <div id="listingContainer">

        @foreach($grouped as $listingId => $messages)

            @php
                $listing = $messages->first()->listing;
                $unread = $messages->where('is_read', false)->count();
                $lastMessage = $messages->first();
            @endphp

            <a href="/owner/inquiries/{{ $listingId }}"
               class="listing-card"
               data-listing="{{ $listingId }}">

                <div class="listing-title">
                    📍 {{ $listing->title ?? 'Listing #'.$listingId }}
                </div>

                <div class="listing-meta">
                    {{ $messages->count() }} messages
                </div>

                @if($unread > 0)
                    <div class="badge unread">
                        {{ $unread }} unread
                    </div>
                @else
                    <div class="badge read">
                        Read
                    </div>
                @endif

            </a>

        @endforeach

        @if($grouped->isEmpty())
            <div class="empty">
                <div style="font-size:2rem;">📭</div>
                <p>No inquiries yet</p>
            </div>
        @endif

    </div>

</div>

<script>
function filterListing(id){

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