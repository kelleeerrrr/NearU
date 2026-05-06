@extends('layouts.owner')

@section('title', 'Edit Listing — NearU')

@push('styles')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

  :root {
    --bg: #F3F8F5;
    --card: #ffffff;
    --border: #D4E8DB;
    --border-focus: #2D7D4F;
    --green: #2D7D4F;
    --green-dark: #236040;
    --green-light: #EAF4EE;
    --text-primary: #1A2E22;
    --text-secondary: #5A7A66;
    --text-muted: #8FA898;
    --red: #E53E3E;
    --shadow-sm: 0 1px 3px rgba(45,125,79,0.08);
    --shadow-md: 0 4px 20px rgba(45,125,79,0.10);
    --shadow-lg: 0 8px 40px rgba(45,125,79,0.13);
    --radius: 14px;
    --radius-sm: 8px;
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg);
    color: var(--text-primary);
    min-height: 100vh;
  }

  /* ── Page wrapper ── */
  .edit-page {
    max-width: 680px;
    margin: 0 auto;
    padding: 2rem 1.25rem 3rem;
    animation: fadeUp .45s ease both;
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ── Back button ── */
  .back-btn {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    font-size: .85rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-decoration: none;
    padding: .45rem .85rem .45rem .6rem;
    border-radius: 999px;
    background: transparent;
    border: 1.5px solid var(--border);
    transition: background .18s, color .18s, border-color .18s, box-shadow .18s;
    margin-bottom: 1.75rem;
  }
  .back-btn:hover {
    background: var(--green-light);
    color: var(--green);
    border-color: var(--green);
    box-shadow: 0 2px 8px rgba(45,125,79,.12);
  }
  .back-btn svg { flex-shrink: 0; }

  /* ── Page header ── */
  .page-header {
    margin-bottom: 1.75rem;
  }
  .page-header .badge {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-size: .75rem;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: var(--green);
    background: var(--green-light);
    border: 1px solid #C0DFC9;
    padding: .3rem .75rem;
    border-radius: 999px;
    margin-bottom: .85rem;
  }
  .page-header h1 {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1.2;
  }
  .page-header p {
    margin-top: .4rem;
    font-size: .9rem;
    color: var(--text-secondary);
  }

  /* ── Card ── */
  .card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
  }

  .card-section {
    padding: 1.5rem 1.75rem;
  }
  .card-section + .card-section {
    border-top: 1px solid var(--border);
  }
  .section-label {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 1.1rem;
  }

  /* ── Field grid ── */
  .fields-grid {
    display: grid;
    gap: 1.1rem;
  }
  .fields-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }

  /* ── Form field ── */
  .field {
    display: flex;
    flex-direction: column;
    gap: .45rem;
  }
  .field label {
    font-size: .82rem;
    font-weight: 600;
    color: var(--text-secondary);
  }
  .field label span.req {
    color: var(--red);
    margin-left: 2px;
  }
  .field input,
  .field select {
    width: 100%;
    padding: .7rem .9rem;
    font-family: inherit;
    font-size: .93rem;
    font-weight: 500;
    color: var(--text-primary);
    background: #FAFCFB;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    outline: none;
    transition: border-color .18s, box-shadow .18s, background .18s;
    -webkit-appearance: none;
    appearance: none;
  }
  .field input:focus,
  .field select:focus {
    border-color: var(--green);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(45,125,79,.12);
  }
  .field input::placeholder { color: var(--text-muted); font-weight: 400; }

  /* Custom select arrow */
  .select-wrap {
    position: relative;
  }
  .select-wrap select { padding-right: 2.5rem; cursor: pointer; }
  .select-wrap::after {
    content: '';
    pointer-events: none;
    position: absolute;
    right: .85rem;
    top: 50%;
    transform: translateY(-50%);
    width: 0; height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 6px solid var(--text-muted);
  }

  /* Price prefix */
  .input-prefix {
    position: relative;
  }
  .input-prefix .prefix {
    position: absolute;
    left: .9rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: .88rem;
    font-weight: 600;
    color: var(--text-muted);
    pointer-events: none;
  }
  .input-prefix input { padding-left: 2rem; }

  /* ── Footer (actions) ── */
  .card-footer {
    padding: 1.25rem 1.75rem;
    background: #FAFCFB;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: .75rem;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .7rem 1.4rem;
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-size: .9rem;
    font-weight: 700;
    cursor: pointer;
    border: none;
    transition: background .18s, box-shadow .18s, transform .12s;
    text-decoration: none;
  }
  .btn:active { transform: scale(.97); }

  .btn-ghost {
    background: transparent;
    color: var(--text-secondary);
    border: 1.5px solid var(--border);
  }
  .btn-ghost:hover { background: var(--green-light); color: var(--green); border-color: var(--green); }

  .btn-primary {
    background: var(--green);
    color: #fff;
    box-shadow: 0 2px 10px rgba(45,125,79,.25);
  }
  .btn-primary:hover {
    background: var(--green-dark);
    box-shadow: 0 4px 16px rgba(45,125,79,.35);
  }

  /* ── Validation errors ── */
  @if($errors->any())
  .alert-error {
    background: #FFF5F5;
    border: 1px solid #FED7D7;
    border-radius: var(--radius-sm);
    padding: .85rem 1rem;
    margin-bottom: 1.25rem;
    font-size: .85rem;
    color: var(--red);
  }
  .alert-error ul { padding-left: 1.1rem; margin-top: .3rem; }
  @endif

  /* ── Responsive ── */
  @media (max-width: 480px) {
    .fields-row { grid-template-columns: 1fr; }
    .card-section { padding: 1.25rem; }
    .card-footer { flex-direction: column-reverse; }
    .card-footer .btn { width: 100%; justify-content: center; }
    .page-header h1 { font-size: 1.4rem; }
  }
</style>
@endpush

@section('content')

<div class="edit-page">

  {{-- Back button --}}
  <a href="{{ route('owner.listings.index') }}" class="back-btn">
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
      <path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    Back to Listings
  </a>

  {{-- Page header --}}
  <div class="page-header">
    <div class="badge">
      <svg width="12" height="12" viewBox="0 0 16 16" fill="none">
        <path d="M11.5 2.5a2.121 2.121 0 013 3L5 15H2v-3L11.5 2.5z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
      </svg>
      Edit Mode
    </div>
    <h1>Edit Listing</h1>
    <p>Update your listing details. Changes will reflect immediately.</p>
  </div>

  {{-- Validation errors --}}
  @if($errors->any())
  <div class="alert-error">
    <strong>Please fix the following errors:</strong>
    <ul>
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  {{-- Form card --}}
  <div class="card">

    <form method="POST" action="{{ route('owner.listings.update', $listing->id) }}">
      @csrf
      @method('PUT')

      {{-- Location & Pricing --}}
      <div class="card-section">
        <div class="section-label">Location &amp; Pricing</div>

        <div class="fields-grid">
          <div class="field">
            <label for="street">Street Address <span class="req">*</span></label>
            <input id="street" type="text" name="street"
                   value="{{ old('street', $listing->street) }}"
                   placeholder="e.g. 123 Rizal Ave."
                   required>
          </div>

          <div class="field">
            <label for="price">Monthly Price <span class="req">*</span></label>
            <div class="input-prefix">
              <span class="prefix">₱</span>
              <input id="price" type="number" name="price"
                     value="{{ old('price', $listing->price) }}"
                     placeholder="0"
                     min="0"
                     required>
            </div>
          </div>
        </div>
      </div>

      {{-- Listing Details --}}
      <div class="card-section">
        <div class="section-label">Listing Details</div>

        <div class="fields-row">
          <div class="field">
            <label for="type">Type</label>
            <div class="select-wrap">
              <select id="type" name="type">
                <option value="Bedspace"  {{ old('type', $listing->type) == 'Bedspace'  ? 'selected' : '' }}>Bedspace</option>
                <option value="Room"      {{ old('type', $listing->type) == 'Room'      ? 'selected' : '' }}>Room</option>
                <option value="Studio"    {{ old('type', $listing->type) == 'Studio'    ? 'selected' : '' }}>Studio</option>
              </select>
            </div>
          </div>

          <div class="field">
            <label for="status">Status</label>
            <div class="select-wrap">
              <select id="status" name="status">
                <option value="available"   {{ old('status', $listing->status) == 'available'   ? 'selected' : '' }}>Available</option>
                <option value="unavailable" {{ old('status', $listing->status) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="card-footer">
        <a href="{{ route('owner.listings.index') }}" class="btn btn-ghost">
          Cancel
        </a>
        <button type="submit" class="btn btn-primary">
          <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
            <path d="M13.5 4.5l-7 7L3 8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Save Changes
        </button>
      </div>

    </form>
  </div>

</div>

@endsection