@extends('layouts.owner')

@section('title', 'Edit Listing — NearU')

@push('styles')
<style>

.container{
  padding:1rem;
}

.card{
  background:#fff;
  border:1px solid var(--border);
  border-radius:14px;
  padding:1rem;
}

input, select{
  width:100%;
  padding:.7rem;
  margin-top:.4rem;
  margin-bottom:1rem;
  border:1px solid var(--border);
  border-radius:8px;
  outline:none;
}

button{
  background:var(--green);
  color:#fff;
  border:none;
  padding:.7rem 1rem;
  border-radius:8px;
  font-weight:700;
  cursor:pointer;
}
</style>
@endpush

@section('content')

<div class="container">

    <h2 style="font-weight:800;">✏️ Edit Listing</h2>

    <div class="card">

        <form method="POST"
              action="{{ route('owner.listings.update', $listing->id) }}">

            @csrf
            @method('PUT')

            {{-- STREET --}}
            <label>Street</label>
            <input type="text"
                   name="street"
                   value="{{ $listing->street }}"
                   required>

            {{-- PRICE --}}
            <label>Price</label>
            <input type="number"
                   name="price"
                   value="{{ $listing->price }}"
                   required>

            {{-- TYPE --}}
            <label>Type</label>
            <select name="type">
                <option value="Bedspace" {{ $listing->type == 'Bedspace' ? 'selected' : '' }}>Bedspace</option>
                <option value="Room" {{ $listing->type == 'Room' ? 'selected' : '' }}>Room</option>
                <option value="Studio" {{ $listing->type == 'Studio' ? 'selected' : '' }}>Studio</option>
            </select>

            {{-- STATUS (optional edit) --}}
            <label>Status</label>
            <select name="status">
                <option value="available" {{ $listing->status == 'available' ? 'selected' : '' }}>
                    Available
                </option>
                <option value="unavailable" {{ $listing->status == 'unavailable' ? 'selected' : '' }}>
                    Unavailable
                </option>
            </select>

            <button type="submit">
                Save Changes
            </button>

        </form>

    </div>

</div>

@endsection