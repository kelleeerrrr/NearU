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
</style>
@endpush

@section('content')

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
                $listing = $first?->resolvedListing;
                $unread = $messages->where('is_read', false)->count();
            @endphp

            @if($listing)
                <div class="chip" onclick="filterListing('{{ $listing->id }}', event)">
                    {{ $listing->title ?? 'Listing '.$listing->id }}
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

                if (!$first || !$first->resolvedListing) continue;

                $listing = $first->resolvedListing;
                $student = $first->sender_id === auth()->id() ? $first->receiver : $first->sender;

                if (!$student) continue;

                $unread = $messages->where('is_read', false)->count();

                $lastMessage = $messages->sortByDesc('created_at')->first();
            @endphp

            <a href="{{ route('owner.inquiries.show', [
                    'listingId' => $listing->id,
                    'userId' => $student->id
                ]) }}"
               class="listing-card"
               data-listing="{{ $listing->id }}">

                <div class="listing-title">
                    👤 {{ $student->name ?? 'Unknown User' }}
                </div>

                <div class="listing-meta">
                    📍 {{ $listing->title ?? 'Listing #'.$listing->id }}
                </div>

                <div class="listing-meta">
                    {{ \Illuminate\Support\Str::limit($lastMessage->message ?? '', 60) }}
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

        @empty

            <div class="empty">
                <div style="font-size:2rem;">📭</div>
                <p>No inquiries yet</p>
            </div>

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