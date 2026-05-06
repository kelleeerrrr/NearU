@extends('layouts.owner')

@section('title', 'My Listings — NearU')

@push('styles')
<style>
:root{
  --bg:#F0F7F2;--card:#fff;--border:#D6E8DC;
  --green:#2D7D4F;--blue:#3B82F6;--red:#C8102E;
}

.header-actions{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:1rem 1.2rem;
}

.create-btn{
  background:var(--green);
  color:#fff;
  border:none;
  padding:.6rem 1rem;
  border-radius:50px;
  font-weight:800;
  cursor:pointer;
}

.filter-bar{
  display:flex;
  gap:.5rem;
  padding:0 1.2rem 1rem;
}

.filter-bar a{
  padding:.4rem .8rem;
  border-radius:20px;
  text-decoration:none;
  font-size:.8rem;
  border:1px solid var(--border);
  color:#333;
}

.filter-bar a.active{
  background:var(--green);
  color:#fff;
  border:none;
}

.listing-card{
  background:var(--card);
  margin:0 1.2rem .8rem;
  padding:1rem;
  border-radius:14px;
  border:1px solid var(--border);
}
</style>
@endpush

@section('content')

@php
    $statusFilter = request('status'); // available / unavailable / null
@endphp

{{-- HEADER --}}
<div class="header-actions">
  <div style="font-weight:800;">🏠 My Listings</div>

  <button class="create-btn"
          onclick="location.href='{{ route('owner.listings.create') }}'">
    + Create Listing
  </button>
</div>

{{-- FILTER --}}
<div class="filter-bar">

    <a href="{{ route('owner.listings.index') }}"
       class="{{ !$statusFilter ? 'active' : '' }}">
        All
    </a>

    <a href="{{ route('owner.listings.index', ['status' => 'available']) }}"
       class="{{ $statusFilter === 'available' ? 'active' : '' }}">
        Available
    </a>

    <a href="{{ route('owner.listings.index', ['status' => 'unavailable']) }}"
       class="{{ $statusFilter === 'unavailable' ? 'active' : '' }}">
        Unavailable
    </a>

</div>

{{-- LISTINGS --}}
@forelse($dormListings as $listing)

<div class="listing-card">

    <div style="font-weight:800;">
        {{ $listing->street }}
    </div>

    <div style="color:var(--green);font-weight:800;">
        ₱{{ number_format($listing->price) }}/mo
    </div>

    <div style="font-size:.8rem;color:#666;">
        Status: {{ ucfirst($listing->status) }}
    </div>

    {{-- ACTIONS --}}
    <div style="display:flex;gap:.5rem;margin-top:.8rem;flex-wrap:wrap;">

        {{-- EDIT --}}
        <a href="{{ route('owner.listings.edit', $listing->id) }}"
           style="padding:.4rem .7rem;background:var(--gold);color:#fff;border-radius:8px;font-size:.75rem;text-decoration:none;">
            Edit
        </a>

        {{-- DELETE --}}
        <form method="POST"
              action="{{ route('owner.listings.delete', $listing->id) }}"
              onsubmit="return confirm('Are you sure you want to delete this listing?')">
            @csrf
            @method('DELETE')

            <button type="submit"
                    style="padding:.4rem .7rem;background:var(--red);color:#fff;border:none;border-radius:8px;font-size:.75rem;">
                Delete
            </button>
        </form>

        {{-- STATUS TOGGLE (2 STATES ONLY) --}}
        @if($listing->status === 'available')

            <form method="POST"
                  action="{{ route('owner.listings.unavailable', $listing->id) }}">
                @csrf
                <button type="submit"
                        style="padding:.4rem .7rem;background:#F59E0B;color:#fff;border:none;border-radius:8px;font-size:.75rem;">
                    Mark Unavailable
                </button>
            </form>

        @else

            <form method="POST"
                  action="{{ route('owner.listings.available', $listing->id) }}">
                @csrf
                <button type="submit"
                        style="padding:.4rem .7rem;background:var(--green);color:#fff;border:none;border-radius:8px;font-size:.75rem;">
                    Mark Available
                </button>
            </form>

        @endif

    </div>

</div>

@empty
<div style="padding:1rem;color:#666;">
    No listings found.
</div>
@endforelse

@endsection